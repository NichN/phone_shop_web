<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Color;
use Yajra\DataTables\Facades\DataTables;

class productAdminController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Color::select(['id', 'name', 'code', 'created_at']);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return ' <button class="btn btn-primary btn-sm editColor" data-id="'. $row->id .'" data-toggle="tooltip" title="Edit">
                                Edit
                            </button>
                            <button data-id="'.$row->id.'" class="btn btn-sm btn-danger deleteColor">Delete</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('Admin.color.index');
    }
    public function storecolor(Request $request)
        {
            try {
                $validated = $request->validate([
                    'name' => 'required|string|max:255|',
                    'code' => 'nullable|string|regex:/^#([a-fA-F0-9]{6})$/',
                ]);
                $data = [
                    'name' => $validated['name'],
                    'code' => $validated['code'] ?? null,
                ];
                color::create($data);
        
                return response()->json([
                    'success' => true,
                    'message' => 'color created successfully',
                    'data' => $data
                ]);
        
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Operation failed: ' . $e->getMessage(),
                    'error_details' => $e->getTraceAsString()
                ], 500);
            }
    }
    public function show(){
        return view('Admin.color.index');
    }
    public function delete(Request $request){
        $color = Color:: findOrFail($request->id);
        $color->delete();
        return response()->json(['success' => true, 'message' => 'color deleted successfully!']);
    }
    public function editcolor($id)
    {
        $color= Color::findOrFail($id);
        return response()->json($color);
    }
    public function updatecolor(Request $request, $id)
    {
        $color = Color::findOrFail($id);
        $color->update([
            'name' => $request->name,
            'code' => $request->code
        ]);
        return response()->json(['success' => true]);
    }
}
