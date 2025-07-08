<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\rate;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class exchangeController extends Controller
{
    public function exchange_index(Request $request){
        if ($request->ajax()) {
            $exchnage = DB::table('exchange_rates')->get();
            return Datatables::of($exchnage)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $btn = '
                            <button class="btn btn-sm btn-primary editExchnage" data-id="'.$row->id.'">
                                <i class="fas fa-edit"></i> Change
                            </button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
                }
        return view('Admin.exchange_rate.index');
        
    }
    public function storerate(Request $request)
    {
        $rateRate = new rate();
        $rateRate->kh_money = $request->kh_money;
        $rateRate->money_usd = $request->money_usd;
        $rateRate->save();
    
        return redirect()->back()->with('success', 'rate rate created successfully!');
    }
    public function edit_rate($id)
    {
        $exchnage = rate::find($id);
        if ($exchnage) {
            return response()->json([
                'success' => true,
                'data' => $exchnage
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Customer group not found.'
            ]);
        }
    }
    public function updateExchange(Request $request, $id)
{
    DB::table('orders')->whereNull('rate_id')->update(['rate_id' => 1]);
    $rateRate = rate::findOrFail($id);
    $rateRate->update([
        'rate' => $request->kh_money, 
    ]);

    return response()->json(['success' => true, 'message' => 'rate rate updated successfully!']);
}
}
