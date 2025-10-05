<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Color;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Size;
use App\Models\suppiler;
use App\Models\productdetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class productAdminController extends Controller
{
  

public function productindex(Request $request)
{
    if ($request->ajax()) {
        $query = DB::table('product')
            ->join('brand', 'brand.id', '=', 'product.brand_id')
            ->join('category', 'category.id', '=', 'product.cat_id')
            ->leftJoin('product_item', 'product_item.pro_id', '=', 'product.id')
            ->select(
                'product.name',
                DB::raw('MAX(product.id) as id'),
                DB::raw('MAX(product.description) as description'),
                DB::raw('MAX(brand.name) as brandname'),
                DB::raw('MAX(category.name) as categoryname'),
                DB::raw('MAX(product_item.images) as images'),
                DB::raw('GROUP_CONCAT(DISTINCT product_item.color_code) as colors_code'),
                DB::raw('GROUP_CONCAT(DISTINCT product_item.size) as sizes'),
                DB::raw('SUM(product_item.stock) as stock')
            )
            ->groupBy('product.name')
            ->orderBy('id', 'desc');

        return DataTables::of($query)
            ->editColumn('colors_code', function ($row) {
                return $row->colors_code ? explode(',', $row->colors_code) : [];
            })
            ->editColumn('sizes', function ($row) {
                return $row->sizes ? explode(',', $row->sizes) : [];
            })
            ->addColumn('action', function ($row) {
                $invoiceUrl = route('products.productshow', $row->id);
                $editUrl = route('products.product_edit', $row->id);
                return '
                    <div>
                        <button class="btn btn-light btn-sm viewProduct" data-url="' . $invoiceUrl . '" title="View">
                            <i class="fas fa-eye" style="color: #17a2b8;"></i>
                        </button>
                        <button class="btn btn-light btn-sm editProduct" data-url="' . $editUrl . '" title="Edit">
                            <i class="fas fa-edit" style="color: #ffc107;"></i>
                        </button>
                        <button class="btn btn-light btn-sm deleteProduct" data-id="' . $row->id . '" title="Delete">
                            <i class="fas fa-trash-alt" style="color: #dc3545;"></i>
                        </button>
                    </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    $brands = Brand::all();
    $category = Category::all();
    return view('Admin.product.listproduct', compact('brands', 'category'));
}
    // public function productstore(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //     ]);

    //     $data = [
    //         'name' => $request->name,
    //         'brand_id' => $request->brand_id,
    //         'cat_id' => $request->cat_id,
    //         'description' => $request->description,
    //         'stock' => 0
    //     ];

    //     Product::create($data);
    //     return response()->json(['success' => true, 'message' => 'Product added successfully!']);
    // }
   public function productstore(Request $request)
{
    DB::beginTransaction();

    try {
        $request->validate([
            'name'        => 'required|string|max:255',
            'brand_id'    => 'required|exists:brand,id',
            'cat_id'      => 'required|exists:category,id',
            'description' => 'nullable|string',
            'variants'    => 'required|array|min:1',
            'variants.*.cost_price' => 'required|numeric',
            'variants.*.price'      => 'required|numeric',
            'variants.*.size_id'    => 'required|exists:size,id',
            'variants.*.color'     => 'required|string|exists:color,name',
            'variants.*.type'       => 'nullable|string|in:new,refurbished',
            'variants.*.warranty'   => 'nullable|integer|min:0',
           'variants.*.is_featured' => 'nullable|boolean',
            'images.*'       => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'variants.*.images.*'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        $product = Product::create([
            'name'        => $request->name,
            'brand_id'    => $request->brand_id,
            'cat_id'      => $request->cat_id,
            'description' => $request->description,
            'stock'       => 0,
        ]);

        $totalStock = 0;
        foreach ($request->variants as $variant) {
            $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('public/product_images');
                $imagePaths[] = str_replace('public/', '', $path);
            }
        }

           $color = \App\Models\Color::where('name', $variant['color'])->first();
            $size = Size::find($variant['size_id']);

            $variantData = [
                'pro_id'        => $product->id,
                'cost_price'    => $variant['cost_price'],
                'price'         => $variant['price'],
                'size_id'       => $variant['size_id'],
                'color_id' => $color ? $color->id : null,
                'stock'         => $variant['stock'] ?? 0,
                'product_name'  => $product->name,
                'color_code'    => $color->code,
                'size'          => $size->size,
                'images'        => $imagePaths,
                'type'          => $variant['type'] ?? null,
                'warranty'      => $variant['warranty'] ?? null,
                'is_featured' => isset($variant['is_featured']) && $variant['is_featured'] ? 1 : 0,

            ];

            ProductDetail::create($variantData);

            $totalStock += $variantData['stock'];
        }

        $product->update(['stock' => $totalStock]);

        DB::commit();

        return redirect()->route('products.product_index')
                 ->with('success', 'Product created successfully!');

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Failed to store product: ' . $e->getMessage()
        ], 500);
    }
}

   public function edit($id)
{
    $product = Product::with('items')->findOrFail($id);
    // dd($product);
    $categories = Category::all();
     $variantCount = $product->items->count();
    $brands = Brand::all();
    $colors = Color::all();
    $sizes = Size::all();

    return view('Admin.product.edit', [
        'product' => $product,
        'categories' => $categories,
        'brands' => $brands,
        'colors' => $colors,
        'sizes' => $sizes,
        'variantCount' => $variantCount,
    ]);
}
   public function updateproduct(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'cat_id' => 'required|exists:category,id',
        'brand_id' => 'required|exists:brand,id',
        'is_featured' => 'required|boolean',
        'items' => 'required|array|min:1',
        'items.*.id' => 'nullable|exists:product_item,id',
        'items.*.color_id_select' => 'required|exists:color,id',
        'items.*.size_id' => 'required|exists:size,id',
        'items.*.type' => 'required|in:new,refurbished',
        'items.*.warranty' => 'nullable|integer|min:0',
        'items.*.cost_price' => 'required|numeric|min:0',
        'items.*.price' => 'required|numeric|min:0',
        'items.*.stock' => 'nullable|integer|min:0',
        'items.*.images.*' => [
            'nullable',
            'image',
            'mimes:jpeg,png,jpg',
            'max:2048',
            function ($attribute, $value, $fail) use ($request) {
                $index = explode('.', $attribute)[1];
                $hasExistingImages = !empty($request->input("items.$index.existing_images"));
                $hasNewImages = !empty($request->file("items.$index.images"));
                $deleteImages = $request->input("items.$index.delete_images", []);
                $existingImages = $request->input("items.$index.existing_images", []);
                $allDeleted = count(array_filter($deleteImages)) >= count($existingImages);
                if (!$hasExistingImages && !$hasNewImages || ($hasExistingImages && $allDeleted && !$hasNewImages)) {
                    $fail("At least one image is required for variant $index.");
                }
            }
        ],
        'items.*.delete_images.*' => 'nullable|string',
        'items.*.existing_images.*' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to update product',
            'errors' => $validator->errors()->all()
        ], 422);
    }

    $product = Product::findOrFail($id);
    $product->update([
        'name' => $request->name,
        'cat_id' => $request->cat_id,
        'brand_id' => $request->brand_id,
        'is_featured' => $request->is_featured,
        'description' => $request->description,
    ]);
    $submittedVariantIds = array_filter(array_column($request->items, 'id'));

    $product->items()->whereNotIn('id', $submittedVariantIds)->delete();

    foreach ($request->items as $index => $item) {
        $variant = $product->items()->updateOrCreate(
            ['id' => $item['id'] ?? null],
            [
                'color_id' => $item['color_id_select'],
                'size_id' => $item['size_id'],
                'type' => $item['type'],
                'warranty' => $item['warranty'] ?? null,
                'cost_price' => $item['cost_price'],
                'price' => $item['price'],
                'stock' => $item['stock'] ?? 0,
            ]
        );

    
        $currentImages = $variant->images ?? [];

        // Handle new image uploads
        if (!empty($item['images'])) {
            foreach ($item['images'] as $image) {
                $path = $image->store('products', 'public');
                $currentImages[] = $path;
            }
        }

        // Handle image deletions
        if (!empty($item['delete_images'])) {
            $deleteImages = is_array($item['delete_images']) ? $item['delete_images'] : explode(',', $item['delete_images']);
            foreach ($deleteImages as $path) {
                if ($path && in_array($path, $currentImages)) {
                    Storage::disk('public')->delete($path);
                    $currentImages = array_filter($currentImages, fn($img) => $img !== $path);
                }
            }
        }

        // Save updated image list
        $variant->images = array_values($currentImages);
        $variant->save();
    }

    return redirect()->route('products.product_index')->with('success', 'Product updated successfully');
}

    public function deleteproduct($id){
        $product = Product::find($id);
        productdetail::where("pro_id",$id)->delete();
        $product->delete();
        return response()->json(['success' => true, 'message' => 'Size deleted successfully!']);
    }
   public function getproduct($id)
    {
        $product = Product::findOrFail($id);
        $brandName = Brand::find($product->brand_id)->name ?? 'No Brand';
        $categoryName = Category::find($product->cat_id)->name ?? 'No Category';

        return response()->json([
            'brand' => $brandName,
            'category' => $categoryName
        ]);
    }
    public function productshow($id)
{
    $products = Product::findOrFail($id);
    $brandName = Brand::find($products->brand_id)->name ?? 'No Brand';
    $categoryName = Category::find($products->cat_id)->name ?? 'No Category';
    $colors = Color::all();
    $productDetails = ProductDetail::where('pro_id', $id)->first();
    $total_stock = ProductDetail::where('pro_id', $id)->sum('stock');
    $variants_count = ProductDetail::where('pro_id', $id)->count();
    $variants = ProductDetail::where('pro_id', $id)->get();
    $min_price = $variants->min('cost_price');
    $max_price = $variants->max('cost_price');
  
    
    $images = $variants->flatMap(function($variant) {
    if (is_string($variant->images)) {
        return json_decode($variant->images, true) ?? [];
    } elseif (is_array($variant->images)) {
        return $variant->images;
    }
    return [];
});

    

    return view('Admin.product.show', compact('products', 'brandName', 'categoryName', 'colors', 'productDetails', 'images', 'total_stock', 'variants_count','variants','min_price','max_price'));
}
public function add()
{
    $brands = Brand::all();
    $categories = Category::all();
    $colors = Color::all();
    $product = Product::all();
    $suppliers = suppiler::all();
    $size = Size::all();
    return view('Admin.product.addproduct', compact('brands', 'categories', 'colors','size','product','suppliers',));
}
}
