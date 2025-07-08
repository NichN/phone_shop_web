<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use Symfony\Component\HttpFoundation\StreamedResponse;

class reportController extends Controller
{
    public function product_report(Request $request) {
        $productSummary = DB::table('product')
    ->leftJoin('product_item', 'product.id', '=', 'product_item.pro_id')
    ->leftJoin('purchase_item', 'product_item.id', '=', 'purchase_item.pr_item_id')
    ->leftJoin('purchase', 'purchase.id', '=', 'purchase_item.purchase_id')
    ->leftJoin('order_item', 'order_item.product_item_id', '=', 'product_item.id')
    ->select(
        'product.id',
        'product.name',
        DB::raw('GROUP_CONCAT(DISTINCT product_item.color_code) as colors_code'),
        DB::raw('GROUP_CONCAT(DISTINCT product_item.size) as sizes'),
        DB::raw('SUM(product_item.stock) as stock'),
        DB::raw('SUM(purchase.Grand_total) as Grand_total'),
        DB::raw('SUM(order_item.price) as sold')
    )
    ->groupBy('product.id', 'product.name')
    ->get()
    ->map(function ($item) {
        $item->colors_code = explode(',', $item->colors_code);
        $item->sizes = explode(',', $item->sizes);
        $item->sold = $item->sold ?? 0;
        return $item;
    });

if ($request->ajax()) {
    return Datatables::of($productSummary)->make(true);
}
     return view('Admin.report.product');
    }
    public function purchase_report(Request $request) {
        $productSummary = DB::table('product')
            ->Join('product_item', 'product.id', '=', 'product_item.pro_id')
            ->leftJoin('purchase_item', 'product_item.id', '=', 'purchase_item.pr_item_id')
            ->leftJoin('purchase', 'purchase.id', '=', 'purchase_item.purchase_id')
            ->join('supplier','supplier.id','=','purchase.supplier_id')
            ->select(
                'product.*',
                'purchase.reference_no as reference_no',
                'supplier.name as supplier_name',
                'purchase_item.quantity as quantity',
                'purchase.Grand_total as total',
                'purchase.paid as paid',
                'purchase.balance as balance',
                'purchase.created_at as date',
                'purchase.payment_statuse as payment_statuse'
            )
            ->get();
            if ($request->ajax()) {
                return Datatables::of($productSummary)->make(true);
            }
        return view('Admin.report.purchase');
}
public function daily_sale(Request $request)
{
    if ($request->ajax()) {
        $query = DB::table('orders')
            ->select(
                'orders.*',
                'payment.payment_type',
            )
            ->join('payment', 'payment.order_id', '=', 'orders.id');
        if ($request->has('month')) {
            $month = $request->input('month');
            $year = date('Y');
            $query->whereYear('orders.created_by', $year)
                  ->whereMonth('orders.created_by', $month);
        }
        if ($request->has('guest_name')) {
            $query->where('orders.guest_name', 'like', '%' . $request->guest_name . '%');
        }
        if ($request->has('payment_type')) {
            $query->where('payment.payment_type', $request->payment_type);
        }
      
    }
    return view('Admin.report.daily_sale');
}

}
