<?php

namespace App\Http\Controllers;

use App\Models\purchase;
use App\Models\refund;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\productdetail;
use Yajra\DataTables\Facades\DataTables;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Termwind\Components\Raw;

use function Laravel\Prompts\select;

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
            ->leftJoin('payment', 'payment.order_id', '=', 'orders.id')
            ->select(
                'orders.id',
                'orders.order_num',
                'orders.created_at',
                'orders.guest_name',
                'orders.total_amount',
                'payment.payment_type',
                'orders.status',
                'orders.phone_guest',
                'orders.guest_address'
            );

        if ($request->filled('order_date')) {
            $query->whereDate('orders.created_at','>=', $request->order_date);
        }

        if ($request->filled('guest_name')) {
            $query->where('orders.guest_name', 'like', '%' . $request->guest_name . '%');
        }

        if ($request->filled('payment_type')) {
            $query->where('payment.payment_type', $request->payment_type);
        }

        if ($request->filled('order_num')) {
            $query->where('orders.order_num', 'like', '%' . $request->order_num . '%');
        }
        if ($request->end_date) {
            $query->whereDate('orders.created_at', '<=', $request->end_date);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        return DataTables::of($query)->make(true);
    }

    return view('Admin.report.daily_sale');
}
public function supplier(Request $request)
{
    if ($request->ajax()) {
    $query = DB::table('supplier')
        ->leftJoin('purchase', 'purchase.supplier_id', '=', 'supplier.id')
        ->select(
            'supplier.id',
            'supplier.name',
            'supplier.phone',
            'supplier.email',
            'supplier.address',
            DB::raw('COUNT(purchase.id) as total_purchases'),
            DB::raw('SUM(purchase.Grand_total) as total_Grand_total'),
            DB::raw('SUM(purchase.paid) as total_paid'),
            DB::raw('SUM(purchase.balance) as total_balance')
        )
        ->groupBy(
            'supplier.id',
            'supplier.name',
            'supplier.phone',
            'supplier.email',
            'supplier.address'
        );
    return DataTables::of($query)
        ->addColumn('action', function ($row) {
            $url = route('report.supplier_view', $row->id);
    return '<a href="'.$url.'" class="btn btn-sm btn-outline-success">
                 Detail
            </a>';
    })
        ->rawColumns(['action'])
        ->make(true);
}
    return view('Admin.report.supplier');
}
    public function supplier_view(Request $request, $id)
{
    $supplier = DB::table('supplier')
        ->where('id', $id)
        ->first();
    if ($request->ajax()) {
        $query = DB::table('supplier')
    ->leftJoin('purchase', 'purchase.supplier_id', '=', 'supplier.id')
    ->join('purchase_item', 'purchase.id', '=', 'purchase_item.purchase_id')
    ->join('product_item', 'purchase_item.pr_item_id', '=', 'product_item.id')
    ->where('supplier.id', $id)
    ->groupBy('purchase.reference_no', 'purchase.created_at', 'purchase.payment_statuse','supplier.name')
    ->select([
        'supplier.name as name',
        'purchase.reference_no',
        'purchase.created_at as purchase_date',
        'purchase.payment_statuse',
        DB::raw('GROUP_CONCAT(product_item.product_name SEPARATOR ", ") as products'),
        DB::raw('SUM(purchase.Grand_total) as Grand_total'),
        DB::raw('SUM(purchase.paid) as paid'),
        DB::raw('SUM(purchase.balance) as balance'),
    ]);
        return DataTables::of($query)->make(true);
    }
    return view('Admin.report.supplier_detail', compact('supplier'));
}
public function sale_completed(Request $request){
    if ($request->ajax()) {
        $query = DB::table('orders')
            ->join('payment', 'payment.order_id', '=', 'orders.id')
            ->select(
                'orders.id',
                'orders.order_num',
                'orders.created_at',
                'orders.guest_name',
                'orders.total_amount',
                'payment.payment_type',
                'orders.status',
                'orders.phone_guest',
                'orders.guest_address'
            )
             ->where('orders.status', '=', 'completed');

        if ($request->filled('order_date')) {
            $query->whereDate('orders.created_at','>=', $request->order_date);
        }

        if ($request->filled('guest_name')) {
            $query->where('orders.guest_name', 'like', '%' . $request->guest_name . '%');
        }

        if ($request->filled('payment_type')) {
            $query->where('payment.payment_type', $request->payment_type);
        }

        if ($request->filled('order_num')) {
            $query->where('orders.order_num', 'like', '%' . $request->order_num . '%');
        }
        if ($request->end_date) {
            $query->whereDate('orders.created_at', '<=', $request->end_date);
        }

        return DataTables::of($query)->make(true);
    }
    return view('Admin.report.sale_completed');
}
public function profit(Request $request)
{
    $year = $request->get('year', date('Y'));

    // Query data
    $data = DB::table('orders')
        ->selectRaw('
            MONTH(created_at) as month,
            SUM(subtotal) as subtotal
        ')
        ->whereYear('created_at', $year)
        ->where('orders.status','=','completed')
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->orderBy('month')
        ->get();

    // Fill empty months
    $months = collect(range(1, 12))->map(function($m) use ($data) {
        $found = $data->firstWhere('month', $m);
        return [
            'month' => $m,
            'subtotal' => $found->subtotal ?? 0,
        ];
    });
    if ($request->ajax()) {
        return response()->json([
            'year' => $year,
            'data' => $months
        ]);
    }
    return view('Admin.report.profit');
}

public function product_chart(Request $request)
{
    $productVariants = DB::table('order_item as si')
        ->join('product_item as pi', 'si.product_item_id', '=', 'pi.id')
        ->join('color', 'color.id', '=', 'pi.color_id')
        ->select(
            'pi.product_name',
            'pi.size',
            'pi.type',
            'color.name',
            DB::raw('COUNT(si.product_item_id) as sales_count'),
            DB::raw('SUM(si.quantity) as total_quantity')
        )
        ->groupBy(
            'pi.product_name',
            'pi.size',
            'pi.type',
            'color.name'
        )
        ->orderByDesc('total_quantity')
        ->get();

    return view('Admin.report.productCom', compact('productVariants'));
}
public function income_expense(Request $request)
{
    $totalProduct = DB::table('product_item')
        ->select(DB::raw('COUNT(id) as total_product'))
        ->first();

    $totalSupplier = DB::table('supplier')
        ->select(DB::raw('COUNT(id) as total_supplier'))
        ->first();

    $totalInvoice = DB::table('orders')
        ->where('status', '!=', 'pending')
        ->select(DB::raw('COUNT(id) as total_invoice'))
        ->first();

    $totalBill = DB::table('purchase')
        ->select(DB::raw('COUNT(id) as total_bill'))
        ->first();
    $totalIncome = DB::table('orders')
    ->select(
        DB::raw('DATE(created_at) as date'),
        DB::raw('SUM(total_amount) as total_income')
    )
    ->whereIn('status', ['accepted', 'processing', 'paid', 'completed'])
    ->whereDate('created_at', today())
    ->groupBy(DB::raw('DATE(created_at)'))
    ->get();
    if ($totalIncome->isNotEmpty()) {
        $income = $totalIncome->first();
        $output = number_format($income->total_income, 2);
    } else {
        $output = 0;
    }
    $monthlyIncome = DB::table('orders')
    ->select(DB::raw('SUM(total_amount) as total_income'))
    ->whereIn('status', ['accepted', 'processing', 'paid', 'completed'])
    ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
    ->first();

        if ($monthlyIncome->total_income) {
            $monthlyOutput = number_format($monthlyIncome->total_income, 2);
        } else {
            $monthlyOutput = 0;
        }
    $weeklyIncome = DB::table('orders')
        ->select(
            DB::raw('WEEK(created_at, 1) as week_number'),
            DB::raw('SUM(total_amount) as total_income')
        )
        ->whereIn('status', ['accepted', 'processing', 'paid', 'completed'])
        ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
        ->groupBy(DB::raw('WEEK(created_at, 1)'))
        ->orderBy('week_number', 'asc')
        ->get();
        if ($weeklyIncome->isNotEmpty()) {
            $weeklyOutput = $weeklyIncome->map(function($item) {
                return [
                    'week' => $item->week_number,
                    'total_income' => number_format($item->total_income, 2)
                ];
            });
        } else {
            $weeklyOutput = 0;
        }
    $recentOrders = DB::table('orders')
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();
    $recentBill = DB::table('purchase')
        ->join('supplier', 'purchase.supplier_id', '=', 'supplier.id')
        ->select('purchase.*', 'supplier.name as supplier_name')
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

    // Prepare cards array dynamically
    $cards = [
        [
            'title' => 'Products',
            'count' => $totalProduct->total_product,
            'icon'  => 'bi-box-seam',
            'color' => 'success',
        ],
        [
            'title' => 'Suppliers',
            'count' => $totalSupplier->total_supplier,
            'icon'  => 'bi-person',
            'color' => 'primary',
        ],
        [
            'title' => 'Invoices',
            'count' => $totalInvoice->total_invoice,
            'icon'  => 'bi-file-earmark-text',
            'color' => 'warning',
        ],
        [
            'title' => 'Bills',
            'count' => $totalBill->total_bill,
            'icon'  => 'bi-file-earmark',
            'color' => 'danger',
        ],
    ];

    return view('Admin.report.income-expense', compact('cards','output','monthlyOutput','recentOrders','recentBill','weeklyOutput'));
}

}