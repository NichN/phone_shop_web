<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\productdetail;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = DB::table('product')
        ->join('product_item', function ($join) {
            $join->on('product.id', '=', 'product_item.pro_id')
                ->whereRaw('product_item.id = (
                    select min(id) from product_item as pi2 where pi2.pro_id = product.id and pi2.stock > 0
                )')
                ->where('product_item.stock', '>', 0);
        })
        ->select('product.*', 'product_item.price', 'product_item.images')
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
                'product_item.images'
            )
            ->get();
            $brands = DB::table('brand')
            ->join('product', 'brand.id', '=', 'product.brand_id')
            ->join('category', 'product.cat_id', '=', 'category.id')
            ->where('category.name', 'Phone')
            ->select('brand.id', 'brand.name')
            ->distinct()
            ->get();

        return view('customer.product', compact('products','phone','brands'));
}
public function product_acessory(){
     $products = DB::table('product')
        ->join('product_item', function ($join) {
            $join->on('product.id', '=', 'product_item.pro_id')
                ->whereRaw('product_item.id = (
                    select min(id) from product_item as pi2 where pi2.pro_id = product.id and pi2.stock > 0
                )')
                ->where('product_item.stock', '>', 0);
        })
        ->select('product.*', 'product_item.price', 'product_item.images')
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
                'product_item.images'
            )
            ->get();
            $brands = DB::table('brand')
                ->join('product', 'brand.id', '=', 'product.brand_id')
                ->join('category', 'product.cat_id', '=', 'category.id')
                ->where('category.name', 'Accesories')
                ->select('brand.id', 'brand.name')
                ->distinct()
                ->get();
            return view('customer.product_accesory', compact('products','accessoryProducts','brands'));
    }
    public function show($pro_id)
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
                'stock'  => $item->stock,
            ];
        });
        $products = Productdetail::where('product_name', '!=', $product_item->product_name)
                        ->inRandomOrder()
                        ->take(4)
                        ->get()
                        ->map(function ($item) {
                            return [
                                'name'     => $item->product_name,
                                'price'    => $item->price,
                                'category' => $item->category,
                                'created_at' => $item->created_at,
                                'size' => $item->size,
                                'image'    => $item->images[0] ?? null, 
                            ];
                        });

        return view('customer.productdetail', [
            'product'  => [
                'name'  => $product_item->product_name,
                'image' => $product_item->images, 
                'price' => $product_item->price,
                'created_at' =>$product_item->created_at,
                'size' =>$product_item->size
            ],
            'colors'   => $colors,
            'sizes'    => $sizes,
            'stock'    => $stock,
            'variants' => $variants,
            'products' => $products,
        ]);
    }


    private function getAllProducts()
    {
        return [
            [
                'id' => 1,
                'name' => 'IPhone 15',
                'category' => 'Smartphone, IPhone',
                'price' => '$1059.00',
                'image' => asset('image/Iphone16.jpg'),
            ],
            [
                'id' => 2,
                'name' => 'IPhone 16',
                'category' => 'Smartphone, IPhone',
                'price' => '$1059.00',
                'image' => asset('image/Iphone16.jpg'),
            ],
            [
                'id' => 3,
                'name' => 'IPhone 16',
                'category' => 'Smartphone, IPhone',
                'price' => '$1059.00',
                'image' => asset('image/Iphone16.jpg'),
            ],
            [
                'id' => 4,
                'name' => 'IPhone 16',
                'category' => 'Smartphone, IPhone',
                'price' => '$1059.00',
                'image' => asset('image/Iphone16.jpg'),
            ],
            [
                'id' => 5,
                'name' => 'IPhone 16',
                'category' => 'Smartphone, IPhone',
                'price' => '$1059.00',
                'image' => asset('image/Iphone16.jpg'),
            ],
            [
                'id' => 6,
                'name' => 'IPhone 16',
                'category' => 'Smartphone, IPhone',
                'price' => '$1059.00',
                'image' => asset('image/Iphone16.jpg'),
            ],
            [
                'id' => 7,
                'name' => 'IPhone 16',
                'category' => 'Smartphone, IPhone',
                'price' => '$1059.00',
                'image' => asset('image/Iphone16.jpg'),
            ],
            [
                'id' => 8,
                'name' => 'IPhone 16',
                'category' => 'Smartphone, IPhone',
                'price' => '$1059.00',
                'image' => asset('image/Iphone16.jpg'),
            ],
            [
                'id' => 9,
                'name' => 'IPhone 16',
                'category' => 'Smartphone, IPhone',
                'price' => '$1059.00',
                'image' => asset('image/Iphone16.jpg'),
            ],
            [
                'id' => 10,
                'name' => 'IPhone 16',
                'category' => 'Smartphone, IPhone',
                'price' => '$1059.00',
                'image' => asset('image/Iphone16.jpg'),
            ],
            [
                'id' => 11,
                'name' => 'IPhone 16',
                'category' => 'Smartphone, IPhone',
                'price' => '$1059.00',
                'image' => asset('image/Iphone16.jpg'),
            ],
            [
                'id' => 12,
                'name' => 'IPhone 16',
                'category' => 'Smartphone, IPhone',
                'price' => '$1059.00',
                'image' => asset('image/Iphone16.jpg'),
            ],
            [
                'id' => 13,
                'name' => 'IPhone 16',
                'category' => 'Smartphone, IPhone',
                'price' => '$1059.00',
                'image' => asset('image/Iphone16.jpg'),
            ],
            [
                'id' => 14,
                'name' => 'IPhone 16',
                'category' => 'Smartphone, IPhone',
                'price' => '$1059.00',
                'image' => asset('image/Iphone16.jpg'),
            ],
            [
                'id' => 15,
                'name' => 'IPhone 16',
                'category' => 'Smartphone, IPhone',
                'price' => '$1059.00',
                'image' => asset('image/Iphone16.jpg'),
            ],
            [
                'id' => 16,
                'name' => 'IPhone 16',
                'category' => 'Smartphone, IPhone',
                'price' => '$1059.00',
                'image' => asset('image/Iphone16.jpg'),
            ],
        ];

        $productsCollection = collect($products);
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = strtolower($request->search);
            $productsCollection = $productsCollection->filter(function ($product) use ($searchTerm) {
                return str_contains(strtolower($product['name']), $searchTerm) || 
                       str_contains(strtolower($product['category']), $searchTerm);
            });
        }
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 12;
        $currentPageItems = $productsCollection->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedProducts = new LengthAwarePaginator(
            $currentPageItems,
            $productsCollection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        return view('customer.product', compact('paginatedProducts'));
    }
}
