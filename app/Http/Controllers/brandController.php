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
                    return '<button class="btn btn-sm editBrand" data-id="'. $row->id .'" data-toggle="tooltip" title="Edit" style="background-color: #fffde7; border: 1px solid #ffe082; color: #fbc02d; padding: 0.25rem 0.5rem; font-size: 0.875rem; border-radius: 0.2rem;">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button data-id="'.$row->id.'" class="btn btn-sm deleteBrand" style="background-color: #ffebee; border: 1px solid #ef9a9a; color: #c62828; padding: 0.25rem 0.5rem; font-size: 0.875rem; border-radius: 0.2rem;"><i class="fas fa-trash-alt"></i></button>';
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
        public function getBrand($id)
    {
        $brand = Brand::findOrFail($id);
        return response()->json($brand);
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
