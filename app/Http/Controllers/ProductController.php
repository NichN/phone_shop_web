<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Productdetail;
use App\Models\Product;

class ProductController extends Controller
{
    // public function index(Request $request)
    // {
    //     $products = DB::table('product')
    //         ->join('product_item', function ($join) {
    //             $join->on('product.id', '=', 'product_item.pro_id')
    //                 ->whereRaw('product_item.id = (
    //                     select min(id) from product_item as pi2 
    //                     where pi2.pro_id = product.id and pi2.stock > 0
    //                 )')
    //                 ->where('product_item.stock', '>', 0);
    //         })
    //         ->select('product.*', 'product_item.price', 'product_item.images', 'product_item.color_code')
    //         ->get();
    //     $phone = DB::table('product')
    //         ->join('category', 'product.cat_id', '=', 'category.id')
    //         ->join('product_item', function ($join) {
    //             $join->on('product.id', '=', 'product_item.pro_id')
    //                 ->whereRaw('product_item.id = (
    //                     select min(id) from product_item as pi2 
    //                     where pi2.pro_id = product.id and pi2.stock > 0
    //                 )');
    //         })
    //         ->where('category.name', 'Phone')
    //         ->select('product.*', 'category.name as category_name', 'product_item.price', 'product_item.images', 'product_item.color_code')
    //         ->get();
    //     $brands = DB::table('brand')
    //         ->join('product', 'brand.id', '=', 'product.brand_id')
    //         ->join('category', 'product.cat_id', '=', 'category.id')
    //         ->where('category.name', 'Phone')
    //         ->select('brand.id', 'brand.name')
    //         ->distinct()
    //         ->get();

    //     foreach ($phone as $product) {
    //         $product->colors = DB::table('product_item')
    //             ->where('pro_id', $product->id)
    //             ->pluck('color_code')
    //             ->unique()
    //             ->values();
    //     }
    //     return view('customer.product', compact('products', 'phone', 'brands'));
    // }
    public function index(Request $request)
    {
        $query = DB::table('product')
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
                'product_item.id as product_item_id',
                'product_item.price',
                'product_item.images',
                'product_item.color_code'
            );

        
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('product.name', 'like', '%' . $search . '%')
                    ->orWhere('product.description', 'like', '%' . $search . '%');
            });
        }

    
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('product.brand_id', $request->category);
        }

        $phone = $query->get();

        // Load brands for filter dropdown
        $brands = DB::table('brand')
            ->join('product', 'brand.id', '=', 'product.brand_id')
            ->join('category', 'product.cat_id', '=', 'category.id')
            ->where('category.name', 'Phone')
            ->select('brand.id', 'brand.name')
            ->distinct()
            ->get();

        // Get available colors for each product
        foreach ($phone as $product) {
            $product->colors = DB::table('product_item')
                ->where('pro_id', $product->id)
                ->pluck('color_code')
                ->unique()
                ->values();
        }

        return view('customer.product', compact('phone', 'brands'));
    }

    public function getByBrand($brandId)
    {
        $brand = DB::table('brand')->where('id', $brandId)->first();
        
        $products = DB::table('product')
            ->join('product_item', function ($join) {
                $join->on('product.id', '=', 'product_item.pro_id')
                    ->whereRaw('product_item.id = (
                        select min(id) from product_item as pi2 
                        where pi2.pro_id = product.id and pi2.stock > 0
                    )')
                    ->where('product_item.stock', '>', 0);
            })
            ->where('product.brand_id', $brandId)
            ->select('product.*', 'product_item.id as product_item_id', 'product_item.price', 'product_item.images', 'product_item.color_code')
            ->get();

        // Get available colors for each product
        foreach ($products as $product) {
            $product->colors = DB::table('product_item')
                ->where('pro_id', $product->id)
                ->pluck('color_code')
                ->unique()
                ->values();
        }

        // Get all brands for the filter dropdown
        $brands = DB::table('brand')
            ->join('product', 'brand.id', '=', 'product.brand_id')
            ->select('brand.id', 'brand.name')
            ->distinct()
            ->get();

        return view('customer.product', compact('products', 'brand', 'brands'));
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
            ->where('category.name', 'Accesories')
            ->select('product.*', 'category.name as category_name', 'product_item.id as product_item_id', 'product_item.price', 'product_item.images', 'product_item.color_code')
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
    // public function show($pro_id)
    // {
    //     $product_item = Productdetail::where('pro_id', $pro_id)->firstOrFail();
    //     $related_items = Productdetail::where('pro_id', $pro_id)->get();

    //     $colors = $related_items->pluck('color_code')->unique()->values();
    //     $sizes = $related_items->pluck('size')->unique()->values();
    //     $stock = $related_items->pluck('stock')->unique()->values();
    //     $type = $related_items->pluck('type')->unique()->values();

    //     $variants = $related_items->map(function ($item) {
    //         return [
    //             'id'         => $item->id,
    //             'color_code' => $item->color_code,
    //             'size'       => $item->size,
    //             'price'      => $item->price,
    //             'stock'      => $item->stock,
    //             'type'       => $item->type,
    //             'images'     => $item->images,
    //         ];
    //     });

    //     $allImages = $related_items->pluck('images')->flatten()->unique()->values();

    //     $products = Productdetail::where('product_name', '!=', $product_item->product_name)
    //         ->inRandomOrder()
    //         ->take(4)
    //         ->get()
    //         ->map(function ($item) {
    //             return [
    //                 'id'          => $item->id,
    //                 'name'        => $item->product_name,
    //                 'price'       => $item->price,
    //                 'category'    => $item->category,
    //                 'created_at'  => $item->created_at,
    //                 'description' => $item->description,
    //                 'size'        => $item->size,
    //                 'type'        => $item->type,
    //                 'images'      => $item->images ?? null,
    //                 'warranty'    => $item->warranty,
    //             ];
    //         });

    //     return view('customer.productdetail', [
    //         'product'     => [
    //             'name'        => $product_item->product_name,
    //             'image'       => $product_item->images,
    //             'price'       => $product_item->price,
    //             'created_at'  => $product_item->created_at,
    //             'description' => $product_item->description,
    //             'size'        => $product_item->size,
    //             'type'        => $product_item->type,
    //             'warranty'    => $product_item->warranty
    //         ],
    //         'color_code' => $colors,
    //         'sizes'      => $sizes,
    //         'stock'      => $stock,
    //         'variants'   => $variants,
    //         'products'   => $products,
    //         'images'     => $allImages,
    //     ]);
    // }
    public function show($pro_id)
{
    $product_item = Productdetail::where('pro_id', $pro_id)
        ->where('is_featured', 1)
        ->where('stock', '>', 0)
        ->firstOrFail();

    $related_items = Productdetail::where('pro_id', $pro_id)
        ->where('is_featured', 1)
        ->where('stock', '>', 0)
        ->get();

    $colors = $related_items->pluck('color_code')->unique()->values();
    $sizes  = $related_items->pluck('size')->unique()->values();
    $stock  = $related_items->pluck('stock')->unique()->values();
    $type   = $related_items->pluck('type')->unique()->values();

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

    // Suggested products but only featured
    // $product_des = Product::with('Productdetail')->get();
    // dd($product_des);
    $products = Productdetail::where('product_name', '!=', $product_item->product_name)
        ->where('is_featured', 1)
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
        $productDescription = Product::where('name', $product_item->product_name)->value('description');

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
        'productDescription' => $productDescription
    ]);
}

    // API method to get product details for modal
    public function getProductDetailApi($productItemId)
    {
        try {
            \Log::info('API called with productItemId: ' . $productItemId);
            
            $productItem = Productdetail::find($productItemId);
            
            if (!$productItem) {
                \Log::warning('Product not found for ID: ' . $productItemId);
                return response()->json(['error' => 'Product not found'], 404);
            }

            // Get all variants of this product
            $variants = Productdetail::where('pro_id', $productItem->pro_id)->get();
            
            // Get all images for this product
            $allImages = [];
            foreach ($variants as $variant) {
                if ($variant->images && is_array($variant->images)) {
                    $allImages = array_merge($allImages, $variant->images);
                }
            }
            $allImages = array_unique($allImages);
            
            // Get unique colors and sizes
            $colors = $variants->pluck('color_code')->unique()->values();
            $sizes = $variants->pluck('size')->unique()->values();
            
            // Get stock for current variant
            $stock = $productItem->stock;
            
            // Get product description
            $productDescription = Product::where('name', $productItem->product_name)->value('description');
            
            // Prepare variants data
            $variantsData = $variants->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'color_code' => $variant->color_code,
                    'size' => $variant->size,
                    'price' => $variant->price,
                    'stock' => $variant->stock,
                    'type' => $variant->type
                ];
            });

            $response = [
                'name' => $productItem->product_name,
                'mainImage' => asset('storage/' . ($productItem->images[0] ?? '')),
                'images' => array_map(function($img) {
                    return asset('storage/' . $img);
                }, $allImages),
                'price' => $productItem->price,
                'type' => $productItem->type,
                'warranty' => $productItem->warranty,
                'description' => $productDescription,
                'colors' => $colors,
                'sizes' => $sizes,
                'stock' => $stock,
                'productItemId' => $productItem->id,
                'variants' => $variantsData
            ];

            return response()->json($response);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load product details'], 500);
        }
    }
}
