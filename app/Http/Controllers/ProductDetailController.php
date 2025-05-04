<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductDetailController extends Controller
{
    public function index()
    {
        $products = [
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
            ]
        ];

        return view('customer.productdetail', ['products' => $products]);
    }
}