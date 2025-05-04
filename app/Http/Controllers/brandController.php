<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use Yajra\DataTables\Facades\DataTables;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Brand::select(['id', 'name', 'description', 'created_at']);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return ' <button class="btn btn-primary btn-sm editBrand" data-id="' . $row->id . '" data-toggle="tooltip" title="Edit">
                                Edit
                            </button>
                            <button data-id="'.$row->id.'" class="btn btn-sm btn-danger deleteBrand">Delete</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('Admin.brand.index');
    }

    public function store(Request $request)
        {
            try {
                $validated = $request->validate([
                    'name' => 'required|string|max:255|',
                    'description' => 'nullable|string',
                ]);
                $data = [
                    'name' => $validated['name'],
                    'description' => $validated['description'] ?? null,
                ];
                Brand::create($data);
        
                return response()->json([
                    'success' => true,
                    'message' => 'Category created successfully',
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
    public function show()
    {
        return view('Admin.brand.newbrand');
    }
    public function delete(Request $request)
    {
        $brand = Brand::findOrFail($request->id);
        $brand->delete();
        return response()->json(['success' => true, 'message' => 'Brand deleted successfully!']);
    }
    public function edit($id){
        $brand= Brand::findOrFail($id);
        return response()->json($brand);
    }
    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);
        $brand->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return response()->json(['success' => true]);
    }
}
