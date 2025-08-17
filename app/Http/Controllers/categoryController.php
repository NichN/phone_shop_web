<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class categoryController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $category = DB::table('category')->get();
            return DataTables::of($category)
            ->addColumn('action', function ($row) {
                $btn = '<div>
                            <button class="btn btn-sm editCate" data-id="'. $row->id .'" data-toggle="tooltip" title="Edit" style="background-color: #fffde7; border: 1px solid #ffe082; color: #fbc02d; padding: 0.25rem 0.5rem; font-size: 0.875rem; border-radius: 0.2rem;">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button data-id="'.$row->id.'" class="btn btn-sm deleteCate" style="background-color: #ffebee; border: 1px solid #ef9a9a; color: #c62828; padding: 0.25rem 0.5rem; font-size: 0.875rem; border-radius: 0.2rem;"><i class="fas fa-trash-alt"></i></button>
                        </div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
            }
        return view('Admin.category.index');
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
                if ($request->hasFile('image')) {
                    $path = $request->file('image')->store('public/category_images');
                    $data['image'] = str_replace('public/', '', $path);
                }
                Category::create($data);
        
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
    public function show(){
        return view('Admin.category.addCat');
    }
    public function delete($id)
    {
       $category = Category::findOrFail($id);
        if ($category) {
            $category->delete();
            return response()->json(['success' => true, 'message' => 'Category deleted successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Category not found'], 404);
        }
    }
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $data = $request->only(['name', 'description']);
    
        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::delete('public/' . $category->image);
            }
            
            $path = $request->file('image')->store('public/category_images');
            $data['image'] = str_replace('public/', '', $path);
        }
        
        $category->update($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'data' => $category
        ]);
    }
}
?>
