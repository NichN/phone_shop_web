<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $products = DB::table('product_item')
            ->limit(10) // fetch a few more to filter duplicates
            ->get()
            ->unique('product_name') // remove duplicates by product_name
            ->take(4) // keep only 4 unique products
            ->map(function ($item) {

                $images = json_decode($item->images);
                 $firstImage = $images[0] ?? null;
                return [
                    'id' => $item->id,
                    'name' => $item->product_name,
                    'price' => '$' . number_format($item->price),
                    'image' => $firstImage ? asset($firstImage) : asset('images/placeholder.png'),
                    'category' => $item->color . ' / ' . $item->size,
                ];
            });

        return view('customer.homepage2', compact('products'));
    }
}
