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
            $totalCustomer = DB::table('users')
                ->select(DB::raw('COUNT(id) as total_customer'))
                ->first();
            $totalOrder = DB ::table('orders')
                ->where('status', 'paid')
                ->select(DB::raw('COUNT(id) as total_order'))->first();

            return view('Admin.dasboard.index', [
                'total_product' => $totalProduct->total_product ?? 0,
                'Grand_total' => $totalPurchase->Grand_total ?? 0,
                'total_customer' => $totalCustomer->total_customer ?? 0,
                'total_order' => $totalOrder->total_order ?? 0,
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
            public function customer(){
                $totalCustomer = DB::table('users')
                ->select(DB::raw('COUNT(id) as total_customer'))
                ->first();
                return view('Admin.dasboard.index', [
                    'total_customer' => $totalCustomer->total_customer ?? 0,
                ]);
            }
            public function order(){
                $totalOrder = DB::table('orders')
                ->where('status', 'paid')
                ->select(DB::raw('COUNT(id) as total_order'))->where('status','===','paid')->get();
                return view('Admin.dasboard.index', [
                    'total_customer' => $totalOrder->total_order ?? 0,
                ]);
            }
            public function get_order()
    {
        $totalProduct = DB::table('products')->count();
        $totalCustomer = DB::table('customers')->count();
        $totalOrder = DB::table('orders')->count();
        $grandTotal = DB::table('orders')->sum('total');

        $recentOrders = DB::table('orders')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            dd($recentOrders);

        return view('Admin.dasboard.index', [
            'total_product' => $totalProduct,
            'total_customer' => $totalCustomer,
            'total_order' => $totalOrder,
            'Grand_total' => $grandTotal,
            'recent_orders' => $recentOrders,
        ]);
    }
    }
?>
