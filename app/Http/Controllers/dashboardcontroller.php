<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

    class dashboardcontroller extends Controller
    {
        public function index()
        {
            return view('Admin.component.sidebar');
        }
        
        public function show()
        {
            $totalProduct = DB::table('product_item')
                ->select(DB::raw('COUNT(id) as total_product'))
                ->first(); 

            $data['total_product'] = $totalProduct;
            return view('Admin.dasboard.index', $data);
        }

        public function product_count()
        {
            $totalProduct = DB::table('product_item')
                ->select(DB::raw('COUNT(id) as total_product'))
                ->first();

            return response()->json([
                'total_product' => $totalProduct->total_product ?? 0,
            ]);
        }
    }
?>
