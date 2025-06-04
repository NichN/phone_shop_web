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
            $data = DB::table('product')
                ->join('brand', 'brand.id', '=', 'product.brand_id')
                ->join('category', 'category.id', '=', 'product.cat_id')
                ->select(
                    'product.*',
                    'brand.name as brandname',
                    'category.name as categoryname'
                )
                ->orderBy('product.id', 'desc')
                ->get();

            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    $btn = '<div>
                                <button class="btn btn-primary btn-sm viewProduct" data-id="' . $row->id . '" data-bs-toggle="tooltip" title="View">
                                        <i class="fa fa-eye"></i>
                                </button>
                                <button class="btn btn-warning btn-sm editProduct" data-id="' . $row->id . '" data-toggle="tooltip" title="Edit">
                                    Edit
                                </button>
                                <button class="btn btn-danger btn-sm deleteProduct" data-id="' . $row->id . '" data-toggle="tooltip" title="Delete">
                                    Delete
                                </button>
                            </div>';
                    return $btn;
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
            'pro_id' => $request->name,
            'color_id'=>$request->description,
            'brand_id'=>$request->brand_id,
            'cat_id'=>$request->cat_id
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
    public function show_product($pro_id)
    {
        $product_item = Productdetail::where('pro_id', $pro_id)->firstOrFail();
        $related_items = Productdetail::where('pro_id', $pro_id)->get();
        $colors = $related_items->pluck('color')->unique()->values();
        $sizes = $related_items->pluck('size')->unique()->values();
        $stock = $related_items->pluck('stock')->unique()->values();
        $variants = $related_items->map(function ($item) {
            return [
                'color'  => $item->color,
                'size'   => $item->size,
                'price'  => $item->price,
                'images' => $item->images,
                'stock' => $item->stock,
            ];
        });
        return response()->json([
            'product_name' => $product_item->product_name,
            'colors'       => $colors,
            'sizes'        => $sizes,
            'stock'        => $stock,
            'variants'     => $variants,
        ]);
    }
}
