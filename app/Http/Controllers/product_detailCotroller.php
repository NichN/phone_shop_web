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
                        $btn = '<div>
                                    <button class="btn btn-warning btn-sm editProduct_dt" data-id="' . $row->id . '" data-toggle="tooltip" title="Edit">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm delete" data-id="' . $row->id . '" data-toggle="tooltip" title="Delete">
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
        ]);
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('public/product_images');
                $imagePaths[] = str_replace('public/', '', $path);
            }
        }
        $product = Product::find($validated['product_name']);
        $color   = Color::find($validated['color']);
        $size    = Size::find($validated['size']);

        $data = [
            'pro_id'       => $product->id,
            'cost_price'   => $validated['cost_price'],
            'price'        => $validated['price'],
            'size_id'      => $size->id,
            'color_id'     => $color->id,
            'stock'        => $request->stock ?? 0,
            'product_name' => $product->name,
            'color_code'   => $color->code,
            'size'         => $size->size,
            'images'       => $imagePaths,
            'type'         => $request->type
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
            ->take(10)
            ->get();
        return response()->json($product);
    }
    public function edit($id){
        $productz_dt = productdetail::findOrFail($id);
        return response()->json($productz_dt);
    }
    public function update(Request $request, $id){
       $productz_dt = productdetail::findOrFail($id);
        $productz_dt->update([
            'name' => $request->name,
            'color_id'=>$request->color_id,
            'size_id' =>$request->size_id,
            'price'=>$request->price,
            'brand_id'=>$request->brand_id,
            'cost_price'=>$request->cost_price,
            'cat_id'=>$request->cat_id
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
        return response()->json([
        'cost_price' => $product_item->cost_price,
         ]);
    }
}
