<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Productdetail;
use App\Models\Image;

use function Laravel\Prompts\select;

class HomeController extends Controller
{
public function index()
{
    $products = DB::table('product')
        ->join('product_item', function ($join) {
            $join->on('product.id', '=', 'product_item.pro_id')
                ->whereRaw('product_item.id = (
                    select min(id) from product_item as pi2 where pi2.pro_id = product.id and pi2.stock > 0
                )')
                ->where('product_item.stock', '>', 0);
        })
        ->select('product.*', 'product_item.price', 'product_item.images','product_item.color_code',)
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
        ->select(
            'product.*',
            'category.name as category_name',
            'product_item.price',
            'product_item.images',
            'product_item.color_code',
        )
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
        ->select(
            'product.*',
            'category.name as category_name',
            'product_item.price',
            'product_item.images',
            'product_item.color_code',
        )
        ->get();
        foreach ($products as $product) {
            $product->colors = DB::table('product_item')
                ->where('pro_id', $product->id)
                ->pluck('color_code')
                ->unique()
                ->values();
        }

        foreach ($accessoryProducts as $product) {
            $product->colors = DB::table('product_item')
                ->where('pro_id', $product->id)
                ->pluck('color_code')
                ->unique()
                ->values();
        }

        foreach ($phone as $product) {
            $product->colors = DB::table('product_item')
                ->where('pro_id', $product->id)
                ->pluck('color_code')
                ->unique()
                ->values();
        }
    return view('customer.homepage2', compact('products','accessoryProducts','phone'));
}
public function search(Request $request)
{
    $query = $request->input('query');
    
    if (empty($query)) {
        return $request->expectsJson() 
            ? response()->json([])
            : redirect()->back()->with('error', 'Please enter a search term');
    }

    $products = Product::where('name', 'LIKE', "%{$query}%")
        ->orWhere('description', 'LIKE', "%{$query}%")
        ->get();

    if ($request->has('json') || $request->expectsJson()) {
        return response()->json($products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'images' => json_decode($product->images, true),
                'colors' => $product->colors,
            ];
        }));
    }

    return view('customer.homepage2', compact('products', 'query'));
}

}

