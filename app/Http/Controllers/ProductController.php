<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Productdetail;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = DB::table('product')
            ->join('product_item', function ($join) {
                $join->on('product.id', '=', 'product_item.pro_id')
                    ->whereRaw('product_item.id = (
                        select min(id) from product_item as pi2 
                        where pi2.pro_id = product.id and pi2.stock > 0
                    )')
                    ->where('product_item.stock', '>', 0);
            })
            ->select('product.*', 'product_item.price', 'product_item.images','product_item.color_code')
            ->get();
        $phone = DB::table('product')
            ->join('category', 'product.cat_id', '=', 'category.id')
            ->join('product_item', function ($join) {
                $join->on('product.id', '=', 'product_item.pro_id')
                    ->whereRaw('product_item.id = (
                        select min(id) from product_item as pi2 
                        where pi2.pro_id = product.id and pi2.stock > 0
                    )');
            })
            ->where('category.name', 'Phone')
            ->select('product.*', 'category.name as category_name', 'product_item.price', 'product_item.images','product_item.color_code')
            ->get();
            $brands = DB::table('brand')
                ->join('product', 'brand.id', '=', 'product.brand_id')
                ->join('category', 'product.cat_id', '=', 'category.id')
                ->where('category.name', 'Phone')
                ->select('brand.id', 'brand.name')
                ->distinct()
                ->get();

                foreach ($phone as $product) {
                $product->colors = DB::table('product_item')
                    ->where('pro_id', $product->id)
                    ->pluck('color_code')
                    ->unique()
                    ->values();
            }
            return view('customer.product', compact('products', 'phone', 'brands'));
        }
        public function product_acessory()
        {
            $products = DB::table('product')
                ->join('product_item', function ($join) {
                    $join->on('product.id', '=', 'product_item.pro_id')
                        ->whereRaw('product_item.id = (
                            select min(id) from product_item as pi2 
                            where pi2.pro_id = product.id and pi2.stock > 0
                        )')
                        ->where('product_item.stock', '>', 0);
                })
                ->select('product.*', 'product_item.price', 'product_item.images','product_item.color_code')
                ->get();

            $accessoryProducts = DB::table('product')
                ->join('category', 'product.cat_id', '=', 'category.id')
                ->join('product_item', function ($join) {
                    $join->on('product.id', '=', 'product_item.pro_id')
                        ->whereRaw('product_item.id = (
                            select min(id) from product_item as pi2 
                            where pi2.pro_id = product.id and pi2.stock > 0
                        )');
                })
                ->where('category.name', 'Accesories')
                ->select('product.*', 'category.name as category_name', 'product_item.price', 'product_item.images','product_item.color_code')
                ->get();

            $brands = DB::table('brand')
                ->join('product', 'brand.id', '=', 'product.brand_id')
                ->join('category', 'product.cat_id', '=', 'category.id')
                ->where('category.name', 'Accesories')
                ->select('brand.id', 'brand.name')
                ->distinct()
                ->get();
                foreach ($accessoryProducts as $product) {
            $product->colors = DB::table('product_item')
                ->where('pro_id', $product->id)
                ->pluck('color_code')
                ->unique()
                ->values();
        
            }
            return view('customer.product_accesory', compact('products', 'accessoryProducts', 'brands'));
        }
    public function show($pro_id)
{
    $product_item = Productdetail::where('pro_id', $pro_id)->firstOrFail();
    $related_items = Productdetail::where('pro_id', $pro_id)->get();

    $colors = $related_items->pluck('color_code')->unique()->values();
    $sizes = $related_items->pluck('size')->unique()->values();
    $stock = $related_items->pluck('stock')->unique()->values();
    $type = $related_items->pluck('type')->unique()->values();

    $variants = $related_items->map(function ($item) {
        return [
            'id'         => $item->id,
            'color_code' => $item->color_code,
            'size'       => $item->size,
            'price'      => $item->price,
            'stock'      => $item->stock,
            'type'       => $item->type,
            'images'     => $item->images,
        ];
    });
    
    $allImages = $related_items->pluck('images')->flatten()->unique()->values();

    $products = Productdetail::where('product_name', '!=', $product_item->product_name)
        ->inRandomOrder()
        ->take(4)
        ->get()
        ->map(function ($item) {
            return [
                'id'          => $item->id,
                'name'        => $item->product_name,
                'price'       => $item->price,
                'category'    => $item->category,
                'created_at'  => $item->created_at,
                'description' => $item->description,
                'size'        => $item->size,
                'type'        => $item->type,
                'images'      => $item->images ?? null,
                'warranty'    => $item->warranty,
            ];
        });

    return view('customer.productdetail', [
        'product'     => [
            'name'        => $product_item->product_name,
            'image'       => $product_item->images,
            'price'       => $product_item->price,
            'created_at'  => $product_item->created_at,
            'description' => $product_item->description,
            'size'        => $product_item->size,
            'type'        => $product_item->type,
            'warranty'    => $product_item->warranty
        ],
        'color_code' => $colors,
        'sizes'      => $sizes,
        'stock'      => $stock,
        'variants'   => $variants,
        'products'   => $products,
        'images'     => $allImages,
    ]);
}

}
