<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
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
        return view('customer.homepage2', compact('products'));
    }
}
