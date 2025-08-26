<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Productdetail;
use App\Models\Image;
use App\Models\Category;
use App\Models\Brand;

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
        ->select('product.*', 'product_item.id as product_item_id', 'product_item.price', 'product_item.images', 'product_item.color_code')
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
        ->where('category.name', 'Accessories')
        ->select(
            'product.*',
            'category.name as category_name',
            'product_item.id as product_item_id',
            'product_item.price',
            'product_item.images',
            'product_item.color_code'
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
        ->where('category.name', 'Smartphone')
        ->select(
            'product.*',
            'category.name as category_name',
            'product_item.id as product_item_id',
            'product_item.price',
            'product_item.images',
            'product_item.color_code'
        )
        ->get();

    // Attach colors
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

    $categories = DB::table('category')->get();

    // Test Productdetail query with a valid pro_id
    $someValue = $products->isNotEmpty() ? $products->first()->id : 1; // Use first product.id or a known ID
    $productItems = Productdetail::where('pro_id', $someValue)->get(['id as product_item_id', 'pro_id', 'price', 'images', 'color_code']);
    // dd($productItems); // Should show product_item_id (product_item.id), pro_id, etc.

    return view('customer.homepage2', compact('products', 'accessoryProducts', 'phone', 'categories','productItems'));
}
public function getByCategory($id)
{
    $category = Category::findOrFail($id);
    $brands = Brand::all();

    $products = DB::table('product')
        ->join('product_item', function ($join) {
            $join->on('product.id', '=', 'product_item.pro_id')
                ->whereRaw('product_item.id = (
                    select min(id) from product_item as pi2 where pi2.pro_id = product.id and pi2.stock > 0
                )')
                ->where('product_item.stock', '>', 0);
        })
        ->where('product.cat_id', $id)
        ->select('product.*', 'product_item.price', 'product_item.images', 'product_item.color_code')
        ->get();
    // dd($products);

    // Attach colors array to each product
    foreach ($products as $product) {
        $product->colors = DB::table('product_item')
            ->where('pro_id', $product->id)
            ->pluck('color_code')
            ->unique()
            ->values();
    }

    return view('customer.product_accesory', compact('category', 'products', 'brands'));
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
public function getOptions($productItemId)
{
    // First get the product item
    $productItem = DB::table('product_item')
        ->where('id', $productItemId)
        ->first();

    if (!$productItem) {
        return response()->json(['message' => 'Product item not found'], 404);
    }

    // Now get all product_items with the same pro_id
    $productItems = DB::table('product_item')
        ->where('pro_id', $productItem->pro_id)
        ->get();

    $sizes = $productItems->pluck('size')->unique()->values();
    $colorCodes = $productItems->pluck('color_code')->unique();

   $colors = DB::table('color')
    ->whereIn('code', $colorCodes)
    ->select('code', 'name')  // ✅ now returning id instead of code
    ->get();
    // dd($colors)


    return response()->json([
        'sizes' => $sizes,
        'colors' => $colors,
    ]);
}


public function getProductItemId(Request $request)
{
    $proId = $request->query('pro_id');
    $size = $request->query('size');
    $colorCode = $request->query('color_code');

    \Log::info('getProductItemId Request:', ['pro_id' => $proId, 'size' => $size, 'color_code' => $colorCode]);

    $productItem = ProductDetail::where('pro_id', $proId)
        ->whereRaw('LOWER(size) = ?', [strtolower($size)])
        ->whereRaw('LOWER(color_code) = ?', [strtolower($colorCode)])
        ->first();

    if ($productItem) {
        \Log::info('Product item found:', [
            'id' => $productItem->id,
            'pro_id' => $productItem->pro_id,
            'size' => $productItem->size,
            'color_code' => $productItem->color_code
        ]);
        return response()->json([
            'success' => true,
            'product_item_id' => $productItem->id
        ]);
    }

    \Log::warning('Product variation not found:', [
        'pro_id' => $proId,
        'size' => $size,
        'color_code' => $colorCode
    ]);

    return response()->json([
        'success' => false,
        'message' => 'Product variation not found.'
    ], 404);
}





}

