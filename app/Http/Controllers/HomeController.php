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

    // Get all brands for the brand grid
    $brands = DB::table('brand')
        ->join('product', 'brand.id', '=', 'product.brand_id')
        ->select('brand.id', 'brand.name')
        ->distinct()
        ->get();

    // Test Productdetail query with a valid pro_id
    $someValue = $products->isNotEmpty() ? $products->first()->id : 1; // Use first product.id or a known ID
    $productItems = Productdetail::where('pro_id', $someValue)->get(['id as product_item_id', 'pro_id', 'price', 'images', 'color_code']);
    // dd($productItems); // Should show product_item_id (product_item.id), pro_id, etc.

    return view('customer.homepage2', compact('products', 'accessoryProducts', 'phone', 'categories', 'brands', 'productItems'));
}
public function getByCategory($id)
{
    $category = Category::findOrFail($id);
    
    // Get only brands that have products in this category
    $brands = DB::table('brand')
        ->join('product', 'brand.id', '=', 'product.brand_id')
        ->where('product.cat_id', $id)
        ->select('brand.id', 'brand.name')
        ->distinct()
        ->get();

    $products = DB::table('product')
        ->join('product_item', function ($join) {
            $join->on('product.id', '=', 'product_item.pro_id')
                ->whereRaw('product_item.id = (
                    select min(id) from product_item as pi2 where pi2.pro_id = product.id and pi2.stock > 0
                )')
                ->where('product_item.stock', '>', 0);
        })
        ->where('product.cat_id', $id)
        ->select('product.*', 'product_item.price', 'product_item.images', 'product_item.color_code','product_item.id as product_item_id',)
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
    $query = trim($request->input('query'));
    
    if (empty($query)) {
        return $request->expectsJson() 
            ? response()->json(['success' => false, 'message' => 'Please enter a search term'])
            : redirect()->back()->with('error', 'Please enter a search term');
    }

    try {
        // Search across ALL data using your existing structure
        $products = DB::table('product')
            ->leftJoin('category', 'product.cat_id', '=', 'category.id')
            ->leftJoin('brand', 'product.brand_id', '=', 'brand.id')
            ->leftJoin('product_item', function ($join) {
                $join->on('product.id', '=', 'product_item.pro_id')
                     ->where('product_item.stock', '>', 0);
            })
            ->where(function($q) use ($query) {
                $q->where('product.name', 'LIKE', "%{$query}%")
                  ->orWhere('product.description', 'LIKE', "%{$query}%")
                  ->orWhere('category.name', 'LIKE', "%{$query}%")
                  ->orWhere('brand.name', 'LIKE', "%{$query}%")
                  ->orWhere('product_item.price', 'LIKE', "%{$query}%")
                  ->orWhere('product_item.stock', 'LIKE', "%{$query}%");
            })
            ->select(
                'product.id',
                'product.name',
                'product.description',
                'category.name as category_name',
                'brand.name as brand_name',
                'product_item.price',
                'product_item.images',
                'product_item.color_code',
                'product_item.stock',
                'product_item.id as product_item_id'
            )
            ->orderBy('product.name')
            ->limit(50)
            ->get();

        // Log search results for debugging
        \Log::info('Search query: ' . $query . ', Found products: ' . $products->count());

        if ($request->has('json') || $request->expectsJson()) {
            $formattedProducts = $products->map(function ($product) {
                // Handle images - check if it's JSON or string (matching your existing structure)
                $imageUrl = null;
                if ($product->images) {
                    if (is_string($product->images)) {
                        $images = json_decode($product->images, true);
                        if (is_array($images) && !empty($images)) {
                            $imageUrl = $images[0];
                        } else {
                            $imageUrl = $product->images; // Direct string
                        }
                    }
                }

                // Get colors for this product (matching your existing structure)
                $colors = DB::table('product_item')
                    ->where('pro_id', $product->id)
                    ->where('stock', '>', 0)
                    ->pluck('color_code')
                    ->unique()
                    ->values();

                // Get price range for this product
                $priceRange = DB::table('product_item')
                    ->where('pro_id', $product->id)
                    ->where('stock', '>', 0)
                    ->select('price')
                    ->get();

                $prices = $priceRange->pluck('price')->filter()->values();
                $minPrice = $prices->min();
                $maxPrice = $prices->max();
                $priceDisplay = $minPrice == $maxPrice 
                    ? '$' . number_format($minPrice, 2)
                    : '$' . number_format($minPrice, 2) . ' - $' . number_format($maxPrice, 2);

                // Get total stock
                $totalStock = DB::table('product_item')
                    ->where('pro_id', $product->id)
                    ->where('stock', '>', 0)
                    ->sum('stock');

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description ?: 'No description available',
                    'category' => $product->category_name ?: 'Uncategorized',
                    'brand' => $product->brand_name ?: 'Unknown Brand',
                    'price' => $priceDisplay,
                    'image' => $imageUrl ?: '📱',
                    'stock' => $totalStock,
                    'colors' => $colors,
                    'product_item_id' => $product->product_item_id,
                    'rating' => 4.5, // Default rating
                    'url' => route('product.show', $product->id)
                ];
            });

            return response()->json([
                'success' => true,
                'query' => $query,
                'count' => $formattedProducts->count(),
                'products' => $formattedProducts
            ]);
        }

        return view('customer.search_results', compact('products', 'query'));

    } catch (\Exception $e) {
        \Log::error('Search error: ' . $e->getMessage());
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }

        return redirect()->back()->with('error', 'Search failed. Please try again.');
    }
}

public function getProductSuggestions(Request $request)
{
    $query = trim($request->input('query'));
    
    if (empty($query) || strlen($query) < 2) {
        return response()->json([]);
    }

    try {
        // Comprehensive suggestions from ALL data sources
        $suggestions = collect();

        // Product names and descriptions
        $productData = DB::table('product')
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->pluck('name')
            ->take(8);

        // Categories
        $categories = DB::table('category')
            ->where('name', 'LIKE', "%{$query}%")
            ->pluck('name')
            ->take(5);

        // Brands
        $brands = DB::table('brand')
            ->where('name', 'LIKE', "%{$query}%")
            ->pluck('name')
            ->take(5);

        // Colors (using color_code from product_item)
        $colors = DB::table('product_item')
            ->where('color_code', 'LIKE', "%{$query}%")
            ->pluck('color_code')
            ->unique()
            ->take(3);

        // Price ranges (if query contains numbers)
        $priceSuggestions = [];
        if (is_numeric($query)) {
            $priceSuggestions = [
                "Under \${$query}",
                "Around \${$query}",
                "Over \${$query}"
            ];
        }

        // Stock suggestions
        $stockSuggestions = [];
        if (is_numeric($query)) {
            $stockSuggestions = [
                "In stock ({$query}+ items)",
                "Limited stock ({$query} items)"
            ];
        }

        // Combine all suggestions
        $suggestions = $productData
            ->concat($categories)
            ->concat($brands)
            ->concat($colors)
            ->concat($priceSuggestions)
            ->concat($stockSuggestions)
            ->unique()
            ->values()
            ->take(12);

        // Log suggestions for debugging
        \Log::info('Suggestions for query: ' . $query . ', Found: ' . $suggestions->count());

        return response()->json($suggestions);

    } catch (\Exception $e) {
        \Log::error('Suggestions error: ' . $e->getMessage());
        return response()->json([]);
    }
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
    ->select('code', 'name') 
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

