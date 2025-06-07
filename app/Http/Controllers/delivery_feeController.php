<?php

namespace App\Http\Controllers;

use App\Models\delivery;
use Illuminate\Http\Request;
use App\Models\Exchange;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class delivery_feeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $delivery = DB::table('delivery')->get();

            return Datatables::of($delivery)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-sm btn-primary edit" data-id="' . $row->id . '">
                            <i class="fas fa-edit"></i> Change
                        </button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Admin.delivery.delivery_fee');
    }

    public function store(Request $request)
    {
        $deliveryfee = new delivery();
        $deliveryfee->fee = $request->fee;
        $deliveryfee->save();

        return redirect()->back()->with('success', 'successfully!');
    }

    public function edit_fee($id)
    {
        $delivery = delivery::find($id);
        if ($delivery) {
            return response()->json([
                'success' => true,
                'data' => $delivery
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Exchange not found.'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        DB::table('orders')->whereNull('delivery_id')->update(['delivery_id' => 1]);

        $delivery = delivery::findOrFail($id);
        $delivery->update([
            'fee' => $request->fee,
        ]);

        return response()->json(['success' => true, 'message' => 'Delivery fee updated successfully!']);
    }
}
