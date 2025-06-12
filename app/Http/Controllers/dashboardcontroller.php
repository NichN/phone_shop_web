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
            $totalPurchase = DB::table('purchase')
                ->select(DB::raw('SUM(Grand_total) as Grand_total'))
                ->first();

            return view('Admin.dasboard.index', [
                'total_product' => $totalProduct->total_product ?? 0,
                'Grand_total' => $totalPurchase->Grand_total ?? 0,
            ]);
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
            public function purchase()
            {
                $totalPurchase = DB::table('purchase')
                    ->select(DB::raw('SUM(Grand_total) as Grand_total'))
                    ->first();
                return view('Admin.dasboard.index', [
                    'Grand_total' => $totalPurchase->Grand_total ?? 0,
                ]);
            }
    }
?>
