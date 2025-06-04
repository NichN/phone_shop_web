<?php

namespace App\Http\Controllers;
use App\Models\productdetail;
use App\Models\suppiler;
use App\Models\Product;
use App\Models\purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Models\purchses_item;
// use App\Models\
use App\Models\purchse;
use Illuminate\Http\JsonResponse;
use Haruncpi\LaravelIdGenerator\IdGenerator;
class purchaseController extends Controller
{
    public function index(Request $request){
    if ($request->ajax()) {
        $data = DB::table('purchase_item')
            ->join('product_item', 'product_item.id', '=', 'purchase_item.pr_item_id')
            ->select(
                'purchase_item.*',
                'product_item.product_name as name',
                'product_item.size as size',
                'product_item.color as color',
                'product_item.cost_price as cost_price'
            )->whereNull('purchase_id')
            ->get();

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-warning btn-sm delete" data-id="' . $row->id . '"><i class="fa-solid fa-xmark"></i></button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    $suppleir = suppiler::all();
    $product_item = productdetail::all();
    $product = Product::all();

    return view('Admin.purchase.add', compact('suppleir', 'product_item', 'product'));
}
    public function show(){
        return view('Admin.purchase.index');
    }
    public function search(Request $request)
    {
        $search = $request->input('search');
        $product_item = DB::table('product_item')
            ->where('product_name', 'LIKE', "%{$search}%")
            ->select('id','color', 'product_name','size')
            ->get();
        return response()->json($product_item);
    }
    public function store(Request $request)
    {
        try {
            $purchaseItem = purchses_item::create([
                'pr_item_id' => $request->product_name,
                'purchase_id' => $request->purchase_id,
                'quantity' => $request->quantity,
                'subtotal' => $request->subtotal,
                'cost_price' => $request->cost_price
            ]);
            DB::update("
                UPDATE product_item
                SET stock = stock + ?
                WHERE id = ?
            ", [$request->quantity, $request->product_name]);
            
            $item = DB::table('purchase_item')
                ->join('product_item', 'purchase_item.pr_item_id', '=', 'product_item.id')
                ->select(
                    'purchase_item.id',
                    'purchase_item.quantity',
                    'purchase_item.subtotal',
                    'product_item.product_name',
                    'product_item.color',
                    'product_item.size',
                    'product_item.cost_price',
                    'product_item.stock',
                    'purchase_item.created_at'
                )->where('purchase_item.id', $purchaseItem->id)
                ->get();

            return response()->json([
                'message' => 'Purchase item created and stock updated.',
                'data' => $item,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    public function destroy(Request $request){
        $purchaseItem = purchses_item::findOrFail($request->id);
        $purchaseItem->delete();
        return response()->json(['success' => true, 'message' => 'Size deleted successfully!']);
    }
    public function payment(Request $request){
        if ($request->ajax()) {
        $data = DB::table('purchase')->get()
        ->map(function ($item) {
        if ($item->paid >= $item->Grand_total) {
            $item->payment_statuse = 'Paid';
        } elseif ($item->paid > 0) {
            $item->payment_statuse = 'Partially';
        } else {
            $item->payment_statuse = 'Unpaid';
        }
        return $item;
    });
        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $addPaymentUrl = route('purchase.addpayment', $row->id);
                
                return '
                    <button class="btn btn-info btn-sm showpurachse" data-id="' . $row->id . '">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                    <button class="btn btn-success btn-sm addpayment" data-url="' . $addPaymentUrl . '">
                        <i class="fa-solid fa-money-bill-wave"></i>
                    </button>
                    <button class="btn btn-danger btn-sm delete" data-id="' . $row->id . '">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
        $suppleir = suppiler::all();
        return view('Admin.purchase.payment',compact('suppleir'));
    }
    public function showinvoice($id)
{
    $purchase = DB::table('purchase')
        ->join('supplier', 'purchase.supplier_id', '=', 'supplier.id')
        ->select(
            'purchase.*',
            'supplier.name as supplier_name',
            'purchase.reference_no as reference_no',
            'purchase.created_at as created_at'
        )
        ->where('purchase.id', $id)
        ->first();

    $items = DB::table('purchase_item')
        ->join('product_item', 'purchase_item.pr_item_id', '=', 'product_item.id')
        ->join('product','product.id','=','product_item.pro_id')
        ->select(
            'product_item.pro_id as product_name',
            'product_item.color',
            'product.name as name',
            'product_item.size',
            'purchase_item.quantity',
            'product_item.cost_price as unit_price',
            'purchase_item.subtotal'
        )
        ->where('purchase_item.purchase_id', $id)
        ->get();
        $grandTotal = $items->sum('subtotal');
        return response()->json([
            'success' => true,
            'purchase' => $purchase,
            'items' => $items,
            'grand_total' => $grandTotal
        ]);
        return view('Admin.purchase.index', compact('purchase', 'items'));
    }
    public function storepayment(Request $request)
{
    DB::beginTransaction();

    try {
        $purchase = purchase::create([
            'reference_no' => IdGenerator::generate([
                'table' => 'purchase',
                'field' => 'reference_no',
                'length' => 9,
                'prefix' => 'REF-',
                'reset_on_prefix_change' => true
            ]),
            'Grand_total' => $request->grand_total,
            'paid' => $request->paid,
            'balance' => $request->balance,
            'payment_statuse' => $request->payment_st,
            'supplier_id' => $request->supplier_id
        ]);
        purchses_item::whereNull('purchase_id')->update(['purchase_id' => $purchase->id]);
        DB::commit(); 
        $supplier = DB::table('purchase')
            ->join('supplier', 'purchase.supplier_id', '=', 'supplier.id')
            ->select('supplier.id', 'supplier.name')
            ->where('purchase.id', $purchase->id)
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Purchase stored successfully!',
            'purchase_id' => $purchase->id,
            'data' => $supplier
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Failed to store purchase: ' . $e->getMessage()
        ], 500);
    }
}
public function delete_purchases(Request $request)
{
    DB::transaction(function () use ($request) {
        $purchase =purchase::findOrFail($request->id);
        $purchaseItems = purchses_item::where('purchase_id', $purchase->id)->get();
        foreach ($purchaseItems as $item) {
            $productItem = productdetail::find($item->pr_item_id);
            if ($productItem) {
                $productItem->stock = 0;
                $productItem->save();
            }
            $item->delete();
        }
        $purchase->delete();
    });
    return response()->json(['message' => 'Purchase deleted']);
}
public function addpayment($id){
    $purchase = DB::table('purchase')
        ->join('supplier', 'purchase.supplier_id', '=', 'supplier.id')
        ->select(
            'purchase.id',
            'purchase.reference_no',
            'purchase.supplier_id',
            'supplier.name as supplier_name',
            'purchase.Grand_total',
            'purchase.balance',
            'supplier.email',
            'purchase.paid'
        )
        ->where('purchase.id', $id)
        ->first();
        $suppliers = DB::table('supplier')->select('id', 'name')->get();

    return view('Admin.purchase.addpayment', compact('purchase','suppliers'));
}
public function updatepayment(Request $request, $id)
{
    $request->validate([
        'paid' => 'required|numeric|min:0',
    ]);

    $purchase = Purchase::findOrFail($id);
    $newPaid = $purchase->paid + $request->paid;
    $newBalance = $purchase->balance - $request->paid;

    if ($newBalance < 0) {
        return response()->json([
            'success' => false,
            'message' => 'Paid amount exceeds the total.',
        ], 422);
    }
    $purchase->update([
        'paid' => $newPaid,
        'balance' => $newBalance,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Payment updated successfully',
        'data' => $purchase
    ]);
}


}

