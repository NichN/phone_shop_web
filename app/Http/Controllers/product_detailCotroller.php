<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Models\Size;
use App\Models\Color;
use App\Models\Product;
use App\Models\productdetail;

    class product_detailCotroller extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('product_item')
                ->join('product', 'product_item.pro_id', '=', 'product.id')
                ->join('brand', 'product.brand_id', '=', 'brand.id')
                ->join('category', 'product.cat_id', '=', 'category.id')
                ->select(
                    'product_item.*',
                    'product.name as product_name',
                    'brand.name as brand',
                    'category.name as category'
                )
                ->get();
               
                return DataTables::of($data)
                    ->addColumn('action', function ($row) {
                        $btn = '
                        <div class="btn-group" role="group">
                            <button 
                                style="background-color: #e3f2fd; border: 1px solid #90caf9; color: #1565c0; padding: 0.25rem 0.5rem; font-size: 0.875rem; border-radius: 0.2rem;" 
                                class="viewProduct" 
                                data-id="' . $row->id . '" 
                                data-bs-toggle="tooltip" 
                                title="View">
                                <i class="fa fa-eye"></i>
                            </button>
                            <button 
                                style="background-color: #fffde7; border: 1px solid #ffe082; color: #fbc02d; padding: 0.25rem 0.5rem; font-size: 0.875rem; border-radius: 0.2rem;" 
                                class="editProduct_dt" 
                                data-id="' . $row->id . '" 
                                data-bs-toggle="tooltip" 
                                title="Edit">
                                <i class="fa fa-pencil"></i>
                            </button>
                            <button 
                                style="background-color: #ffebee; border: 1px solid #ef9a9a; color: #c62828; padding: 0.25rem 0.5rem; font-size: 0.875rem; border-radius: 0.2rem;" 
                                class="delete" 
                                data-id="' . $row->id . '" 
                                data-bs-toggle="tooltip" 
                                title="Delete">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>';
                        return $btn;
                    })

                    ->rawColumns(['action'])
                    ->make(true);
            }
            $size = Size::all();
            $color = Color::all();
            $product = Product::all();
            // dd($product);

        return view('Admin.productdetail.index',compact('size', 'color','product'));
    }

     public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'cost_price'     => 'required|numeric',
            'price'          => 'required|numeric',
            'product_name'   => 'required',
            'size'           => 'required|exists:size,id',
            'color'          => 'required|exists:color,id',
            'images.*'       => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_featured'     => 'boolean',  // Add validation for the is_featured field
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('public/product_images');
                $imagePaths[] = str_replace('public/', '', $path);
            }
        }

        $product = Product::find($validated['product_name']);
        $color = Color::find($validated['color']);
        $size = Size::find($validated['size']);

        $data = [
            'pro_id'        => $product->id,
            'cost_price'    => $validated['cost_price'],
            'price'         => $validated['price'],
            'size_id'       => $size->id,
            'color_id'      => $color->id,
            'stock'         => $request->stock ?? 0,
            'product_name'  => $product->name,
            'color_code'    => $color->code,
            'size'          => $size->size,
            'images'        => $imagePaths,
            'type'          => $request->type,
            'warranty'      => $request->warranty,
            'is_featured'   => $request->is_active ?? false,  // Set the default value if not provided
        ];

        $productDetail = ProductDetail::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Product stored successfully!',
            'data' => $productDetail
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Operation failed: ' . $e->getMessage()
        ], 500);
    }
}


    public function addproduct()
    {
        $size = Size::all();
        $color = Color::all();
        $product = Product::all();
        return view('Admin.productdetail.add_pro', compact('size', 'color','product'));
    }
    public function search(Request $request)
    {
        $search = $request->input('search');
        $product = DB::table('product')
            ->where('name', 'LIKE', "%{$search}%")
            ->select('id', 'name')
            // ->take(10)
            ->get();
        return response()->json($product);
    }
    public function edit($id){
        $productz_dt = productdetail::findOrFail($id);
        return response()->json($productz_dt);
    }
    public function update(Request $request, $id)
{
    $productz_dt = ProductDetail::findOrFail($id);
    $imagePaths = [];
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('public/product_images');
            $imagePaths[] = str_replace('public/', '', $path);
        }
    }

    $productz_dt->update([
        'product_name' => $request->name,
        'color_id'     => $request->color_id,
        'size_id'      => $request->size_id,
        'price'        => $request->price,
        'brand_id'     => $request->brand_id,
        'cost_price'   => $request->cost_price,
        'cat_id'       => $request->cat_id,
        'type'         => $request->type,
        'images'       => $imagePaths,
        'warranty'     => $request->warranty,
        'is_featured'   => $request->is_featured ?? false,  // Update the is_featured field
    ]);

    return response()->json(['success' => true]);
}

     public function delete(Request $request){
        $product_dt = productdetail::findOrFail($request->id);
        $product_dt->delete();
        return response()->json(['success' => true, 'message' => 'Size deleted successfully!']);
    }
    public function get_pr_item($id){
        $product_item = productdetail::findOrFail($id);
        $color = Color::find($product_item->color_id);
        return response()->json([
            'cost_price' => $product_item->cost_price,
            'color_name' => $color ? $color->name : null,
        ]);
    }
   public function show_product($pro_id)
{
    $product_item = Productdetail::find($pro_id);

    if (!$product_item) {
        return response()->json(['message' => 'No product item found.'], 404);
    }
    return response()->json([
        'product_name' => $product_item->product_name,
        'color'        => $product_item->color_code,
        'size'         => $product_item->size,
        'stock'        => $product_item->stock,
        'price'        => $product_item->price,
        'images'       => $product_item->images ?? [],
        'type'         => $product_item->type,
        'warranty'     =>$product_item->warranty,
    ]);
}
public function updateFeaturedStatus(Request $request, $id)
{
    try {
        $validated = $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $product = ProductDetail::findOrFail($id);

        if ($validated['is_active'] == 0) {
            $product->is_featured = 0;
        } else {
            $product->is_featured = 1;
        }

        $product->save();

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}


}
