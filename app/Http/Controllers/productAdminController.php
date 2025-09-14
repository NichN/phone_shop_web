<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Color;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\productdetail;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
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
                return '
                    <div>
                        <button class="btn btn-light btn-sm viewProduct" data-url="' . $invoiceUrl . '" title="View">
                            <i class="fas fa-eye" style="color: #17a2b8;"></i>
                        </button>
                        <button class="btn btn-light btn-sm editProduct" data-id="' . $row->id . '" title="Edit">
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
    public function productstore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $data = [
            'name' => $request->name,
            'brand_id' => $request->brand_id,
            'cat_id' => $request->cat_id,
            'description' => $request->description,
            'stock' => 0
        ];

        Product::create($data);
        return response()->json(['success' => true, 'message' => 'Product added successfully!']);
    }
    public function editproduct($id){
        $product = Product::findOrFail($id);
        return response()->json($product);
    }
    public function updateproduct(Request $request, $id){
        $product = Product::findOrFail($id);
        $product->update([
            'pro_id' => $request->id,
            'name' => $request->name,
            'description' => $request->description,
            'brand_id' => $request->brand_id,
            'cat_id' => $request->cat_id
        ]);
        return response()->json(['success' => true]);
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
}
