<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class pick_upController extends Controller
{
    public function index(Request $request)
{
    if ($request->ajax()) {
        $data = DB::table('orders')
            ->where('delivery_type', 'pick up')
            ->get();

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $dropdown = '
                    <div class="dropdown">
                        <a class="text-dark" href="#" role="button" id="dropdownMenu' . $row->id . '" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu' . $row->id . '">
                            <li>
                                <a class="dropdown-item vieworder" href="' . route('delivery_option.show', ['id' => $row->id]) . '" data-id="' . $row->id . '">Order Detail</a>
                            </li>
                            <li>
                                <a class="dropdown-item confirm" href="' . route('delivery_option.confirm', ['id' => $row->id]) . '">Confirm Order</a>
                            </li>
                              <li>
                                <a class="dropdown-item confirm" href="' . route('delivery_option.confirm', ['id' => $row->id]) . '">Confirm Order</a>
                            </li>
                        </ul>
                    </div>';
                return $dropdown;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    return view('Admin.pick_up.index');
}

}
