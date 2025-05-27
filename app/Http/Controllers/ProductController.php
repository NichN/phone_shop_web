<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

// class ProductController extends Controller
// {
//     public function index(Request $request)
//     {
//         $products = [
//             [
//                 'id' => 1,
//                 'name' => 'IPhone 15',
//                 'category' => 'Smartphone, IPhone',
//                 'price' => '$1059.00',
//                 'image' => asset('image/Iphone16.jpg'),
//             ],
//             [
//                 'id' => 2,
//                 'name' => 'IPhone 16',
//                 'category' => 'Smartphone, IPhone',
//                 'price' => '$1059.00',
//                 'image' => asset('image/Iphone16.jpg'),
//             ],
//             [
//                 'id' => 3,
//                 'name' => 'IPhone 16',
//                 'category' => 'Smartphone, IPhone',
//                 'price' => '$1059.00',
//                 'image' => asset('image/Iphone16.jpg'),
//             ],
//             [
//                 'id' => 4,
//                 'name' => 'IPhone 16',
//                 'category' => 'Smartphone, IPhone',
//                 'price' => '$1059.00',
//                 'image' => asset('image/Iphone16.jpg'),
//             ],
//             [
//                 'id' => 5,
//                 'name' => 'IPhone 16',
//                 'category' => 'Smartphone, IPhone',
//                 'price' => '$1059.00',
//                 'image' => asset('image/Iphone16.jpg'),
//             ],
//             [
//                 'id' => 6,
//                 'name' => 'IPhone 16',
//                 'category' => 'Smartphone, IPhone',
//                 'price' => '$1059.00',
//                 'image' => asset('image/Iphone16.jpg'),
//             ],
//             [
//                 'id' => 7,
//                 'name' => 'IPhone 16',
//                 'category' => 'Smartphone, IPhone',
//                 'price' => '$1059.00',
//                 'image' => asset('image/Iphone16.jpg'),
//             ],
//             [
//                 'id' => 8,
//                 'name' => 'IPhone 16',
//                 'category' => 'Smartphone, IPhone',
//                 'price' => '$1059.00',
//                 'image' => asset('image/Iphone16.jpg'),
//             ],
//             [
//                 'id' => 9,
//                 'name' => 'IPhone 16',
//                 'category' => 'Smartphone, IPhone',
//                 'price' => '$1059.00',
//                 'image' => asset('image/Iphone16.jpg'),
//             ],
//             [
//                 'id' => 10,
//                 'name' => 'IPhone 16',
//                 'category' => 'Smartphone, IPhone',
//                 'price' => '$1059.00',
//                 'image' => asset('image/Iphone16.jpg'),
//             ],
//             [
//                 'id' => 11,
//                 'name' => 'IPhone 16',
//                 'category' => 'Smartphone, IPhone',
//                 'price' => '$1059.00',
//                 'image' => asset('image/Iphone16.jpg'),
//             ],
//             [
//                 'id' => 12,
//                 'name' => 'IPhone 16',
//                 'category' => 'Smartphone, IPhone',
//                 'price' => '$1059.00',
//                 'image' => asset('image/Iphone16.jpg'),
//             ],
//             [
//                 'id' => 13,
//                 'name' => 'IPhone 16',
//                 'category' => 'Smartphone, IPhone',
//                 'price' => '$1059.00',
//                 'image' => asset('image/Iphone16.jpg'),
//             ],
//             [
//                 'id' => 14,
//                 'name' => 'IPhone 16',
//                 'category' => 'Smartphone, IPhone',
//                 'price' => '$1059.00',
//                 'image' => asset('image/Iphone16.jpg'),
//             ],
//             [
//                 'id' => 15,
//                 'name' => 'IPhone 16',
//                 'category' => 'Smartphone, IPhone',
//                 'price' => '$1059.00',
//                 'image' => asset('image/Iphone16.jpg'),
//             ],
//             [
//                 'id' => 16,
//                 'name' => 'IPhone 16',
//                 'category' => 'Smartphone, IPhone',
//                 'price' => '$1059.00',
//                 'image' => asset('image/Iphone16.jpg'),
//             ],
//         ];

//         // Convert the array into a Laravel Collection
//         $productsCollection = collect($products);

//         // Handle search (filtering)
//         if ($request->has('search') && !empty($request->search)) {
//             $searchTerm = strtolower($request->search);
//             $productsCollection = $productsCollection->filter(function ($product) use ($searchTerm) {
//                 return str_contains(strtolower($product['name']), $searchTerm) ||
//                     str_contains(strtolower($product['category']), $searchTerm);
//             });
//         }

//         // Get the current page from the request, default is 1
//         $currentPage = LengthAwarePaginator::resolveCurrentPage();

//         // Define how many items you want per page
//         $perPage = 12;

//         // Slice the collection to get the items to display in the current page
//         $currentPageItems = $productsCollection->slice(($currentPage - 1) * $perPage, $perPage)->all();

//         // Create a LengthAwarePaginator instance
//         $paginatedProducts = new LengthAwarePaginator(
//             $currentPageItems,
//             $productsCollection->count(),
//             $perPage,
//             $currentPage,
//             ['path' => $request->url(), 'query' => $request->query()]
//         );
//         return view('customer.product', compact('paginatedProducts'));
//     }

//     public function show($id)
//     {
//         $product = collect($this->getAllProducts())->firstWhere('id', $id);

//         if (!$product) {
//             abort(404);
//         }

//         $similarProducts = collect($this->getAllProducts())
//             ->where('category', $product['category'])
//             ->where('id', '!=', $id)
//             ->take(4); // show 4 similar items

//         return view('productdetail', [
//             'product' => $product,
//             'products' => $similarProducts,
//         ]);
//     }
// }

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $productsCollection = collect($this->getAllProducts());

        // Handle search
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

    public function show($id)
    {
        $products = collect($this->getAllProducts());

        $product = $products->firstWhere('id', $id);
        if (!$product) {
            abort(404);
        }

        $similarProducts = $products->where('category', $product['category'])
                                    ->where('id', '!=', $id)
                                    ->take(4);

        return view('customer.productdetail', [
            'product' => $product,
            'products' => $similarProducts,
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
    }
}
