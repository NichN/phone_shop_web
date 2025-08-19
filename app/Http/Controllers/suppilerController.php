<?php

namespace App\Http\Controllers;

use App\Models\suppiler;
use Illuminate\Http\Request;    
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class suppilerController extends Controller
{
   public function index()
   {
    //    return view('Admin.supplier.index');
    if (request()->ajax()){
        $data = suppiler:: select(['id', 'name','address','phone','email']);
        return DataTables::of($data)
        ->addColumn('action', function($row){
            return ' <button class="btn btn-sm editSupplier" data-id="' . $row->id . '" data-toggle="tooltip" title="Edit" style="background-color: #fffde7; border: 1px solid #ffe082; color: #fbc02d; padding: 0.25rem 0.5rem; font-size: 0.875rem; border-radius: 0.2rem;">
                        Edit
                    </button>
                    <button data-id="'.$row->id.'" class="btn btn-sm deleteSupplier" data-toggle="tooltip" title="Delete" style="background-color: #ffebee; border: 1px solid #ef9a9a; color: #c62828; padding: 0.25rem 0.5rem; font-size: 0.875rem; border-radius: 0.2rem;">Delete</button>';
        })
        ->rawColumns(['action'])
        ->make(true);
    }
    return view('Admin.supplier.index');
   }
   public function store(Request $request){
    try {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|max:255',
        ]);
        $data = [
            'name' => $validated['name'],
            'address' => $validated['address'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
        ];
        suppiler::create($data);
        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => $data
        ]);
    }catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Operation failed: ' . $e->getMessage(),
            'error_details' => $e->getTraceAsString()
        ], 500);
    }
   }
   public function edit($id){
        $supplier = suppiler::find($id);
        return response()->json($supplier);
   }
   public function update(Request $request, $id){
        $supplier = suppiler::findOrFail($id);
        $supplier->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);
        return response()->json(['success' => true]);
    }

    public function delete(Request $request){
        $supplier = suppiler::findOrFail($request->id);
        $supplier->delete();
        return response()->json(['success' => true, 'message' => 'Supplier deleted successfully!']);
    }
}
