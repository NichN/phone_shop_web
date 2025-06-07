<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class reportController extends Controller
{
    public function product_report(Request $request) {
        $productSummary = DB::table('product')
            ->leftJoin('product_item', 'product.id', '=', 'product_item.pro_id')
            ->leftJoin('purchase_item', 'product_item.id', '=', 'purchase_item.pr_item_id')
            ->leftJoin('purchase', 'purchase.id', '=', 'purchase_item.purchase_id')
            ->select(
                'product.id',
                'product.name',
                DB::raw('GROUP_CONCAT(DISTINCT product_item.color) as colors'),
                DB::raw('GROUP_CONCAT(DISTINCT product_item.size) as sizes'),
                DB::raw('SUM(product_item.stock) as stock'),
                DB::raw('SUM(purchase.Grand_total) as Grand_total')
            )
            ->groupBy('product.id', 'product.name')
            ->get()
            ->map(function ($item) {
                $item->colors = explode(',', $item->colors);
                $item->sizes = explode(',', $item->sizes);
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

}
