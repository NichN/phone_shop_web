<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\size;
class sizeController extends Controller
{
    public function index(Request $request)
{
    if ($request->ajax()) {
        $size = DB::table('size')->get();
        return DataTables::of($size)
            ->addColumn('action', function ($row) {
                return '
                    <div>
                        <button class="btn btn-primary btn-sm editsize" data-id="' . $row->id . '" title="Edit">
                            Edit
                        </button>
                        <button class="btn btn-danger btn-sm deletesize" data-id="' . $row->id . '" title="Delete">
                            Delete
                        </button>
                    </div>';
            })
            ->rawColumns(['action']) 
            ->make(true);
    }
    return view('Admin.size.index');
}

    public function store(Request $request){
         try{
            $validated = $request->validate([
                'size' => 'required|string|max:255|',
            ]);
            $data = [
                'size' => $validated['size']
            ];
            size::create($data);
            return response()->json([
                'success' => true, 'message' => 'Data saved successfully','data' => $validated,
            ]);
         }
        catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Operation failed: ' . $e->getMessage(),
                'error_details' => $e->getTraceAsString()
            ], 500);
        }
    }
}
