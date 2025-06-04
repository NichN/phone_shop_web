<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Productdetail;

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

    return view('customer.homepage2', compact('products','accessoryProducts','phone'));
}
}

