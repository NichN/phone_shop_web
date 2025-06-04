<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = DB::table('product')
            ->join('product_item', function ($join) {
                $join->on('product.id', '=', 'product_item.pro_id')
                    ->whereRaw('product_item.id = (
                        select min(id) from product_item as pi2 where pi2.pro_id = product.id
                    )');
            })
            ->select('product.*', 'product_item.price', 'product_item.images')
            ->get();

        $productsCollection = collect($products);

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 12;
        $currentPageItems = $productsCollection->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedProducts = new LengthAwarePaginator(
            $currentPageItems,
            $productsCollection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('customer.product', ['paginatedProducts' => $paginatedProducts]);
    }

    public function show($id)
    {
        $products = DB::table('product')
            ->join('product_item', function ($join) {
                $join->on('product.id', '=', 'product_item.pro_id')
                    ->whereRaw('product_item.id = (
                        select min(id) from product_item as pi2 where pi2.pro_id = product.id
                    )');
            })
            ->select('product.*', 'product_item.price', 'product_item.images')
            ->get();

        $productsCollection = collect($products);

        $product = $productsCollection->firstWhere('id', $id);
        if (!$product) {
            abort(404);
        }

        $similarProducts = $productsCollection
            ->where('category', $products->category)
            ->where('id', '!=', $id)
            ->take(4);

        return view('customer.productdetail', [
            'product' => $product,
            'products' => $similarProducts,
        ]);
    }
    
}
