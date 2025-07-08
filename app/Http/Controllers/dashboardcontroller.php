<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
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
                ->where('role_id', '!=', 4) 
                ->select(DB::raw('COUNT(id) as total_customer'))
                ->first();
            $totalOrder = DB ::table('orders')
                ->select(DB::raw('COUNT(id) as total_order'))->first();
            $recentOrders = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
             ->select('orders.*', 'users.name as user_name', 'users.profile_image')
                ->orderBy('orders.created_at', 'desc')
                ->take(5)
                ->get(); 
           $product_instock = DB::table('product_item')
            ->where('stock', '>', 0)
            ->sum('stock');
            // $soldOutItems = DB::table('product_item')
            $soldOutItems = DB::table('product_item')
                ->join('order_item', 'product_item.id', '=', 'order_item.product_item_id')
                ->select('product_item.id', DB::raw('SUM(order_item.quantity) as total_sold'))
                ->groupBy('product_item.id', 'product_item.product_name')
                ->get()
                ->sum('total_sold');

            $order_monthly = DB::table('orders')
                ->select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('SUM(subtotal) as total_sales')
                )
                ->where('status', 'completed')
                ->groupBy('month')
                ->orderBy('month')
                ->get();
            $order_monthly_pr = DB::table('orders')
                ->select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('SUM(subtotal) as total_sales_pr')
                )
                ->where('status', 'processing')
                ->groupBy('month')
                ->orderBy('month')
                ->get();
                // dd($order_monthly_pr);


                // dd($soldOutItems);
            return view('Admin.dasboard.index', [
                'total_product' => $totalProduct->total_product ?? 0,
                'Grand_total' => $totalPurchase->Grand_total ?? 0,
                'total_customer' => $totalCustomer->total_customer ?? 0,
                'total_order' => $totalOrder->total_order ?? 0,
                'recentOrders' => $recentOrders,
                'product_instock'=>$product_instock,
                'soldOutItems' => $soldOutItems,
                'order_monthly' => $order_monthly,
                'order_monthly_pr' => $order_monthly_pr
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
                    ->where('role_id', '!=', 4) // Exclude customers (role_id = 4)
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
    }
?>
