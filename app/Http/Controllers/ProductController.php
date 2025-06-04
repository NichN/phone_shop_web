<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = DB::table('product_item')->get()->map(function ($item) {
            $images = json_decode($item->images, true);
            $firstImage = is_array($images) && count($images) > 0 ? $images[0] : asset('image/default-image.jpg');

            return [
                'id' => $item->id,
                'name' => $item->product_name,
                'price' => '$' . number_format($item->price, 2),
                'image' => $firstImage,
                'category' => $item->color . ' / ' . $item->size,
            ];
        });

        // Apply search filter if needed
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = strtolower($request->search);
            $products = $products->filter(function ($product) use ($searchTerm) {
                return str_contains(strtolower($product['name']), $searchTerm) ||
                    str_contains(strtolower($product['category']), $searchTerm);
            });
        }

        // Pagination setup
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 16;
        $currentPageItems = $products->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedProducts = new LengthAwarePaginator(
            $currentPageItems,
            $products->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('customer.product', ['paginatedProducts' => $paginatedProducts]);
    }
}
