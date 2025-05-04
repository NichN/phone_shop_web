<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Psy\Readline\Hoa\Console;
use App\Models\image;

class imageController extends Controller
{
   public function index(Request $request){
    if ($request->ajax()) {
        $photo = DB::table('image')->get();
        return DataTables::of($photo)
        ->addColumn('action', function ($row) {
            $btn = '<div>
                        <button class="btn btn-danger btn-sm deleteImage" data-id="' . $row->id . '" data-toggle="tooltip" title="Delete">
                            Delete
                        </button>
                    </div>';
            return $btn;
        })
        ->rawColumns(['action'])
        ->make(true);
        }
    return view('Admin.image.index');
   }

   public function store(Request $request)
   {
       if ($request->hasFile('file_path')) {
           $path = $request->file('file_path')->store('public/product_images');
           $relativePath = str_replace('public/', '', $path);
   
           Image::create([
               'pr_item_id' => $request->input('pr_item_id'),
               'file_name' => $request->file('file_path')->getClientOriginalName(),
               'file_path' => $relativePath,
               'name' => $request->name
           ]);
   
           return response()->json([
               'message' => 'Image uploaded successfully!',
               'paths' => [asset('storage/' . $relativePath)],
           ]);
       }
   }
   public function delete($id){
    image::findOrFail($id)->delete();
    return response()->json(['success' => true, 'message' => 'image deleted successfully']);
   }
}
