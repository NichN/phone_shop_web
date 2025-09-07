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
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $photos = Image::all();
            return DataTables::of($photos)
                ->addColumn('action', function ($photo) {
                    $btn = '<div>
                                <button class="btn btn-outline-warning btn-sm editImage" data-id="' . $photo->id . '" data-toggle="tooltip" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm deleteImage" data-id="' . $photo->id . '" data-toggle="tooltip" title="Delete">
                                    <i class="fa fa-trash"></i>
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
    $request->validate([
        'name' => 'required|string|max:255',
        'img_type' => 'required|string|max:255',
        'file_path' => 'required|image',
        'caption' => 'nullable|string|max:255',
        'description' => 'nullable|string|max:2025',
    ]);

    if ($request->hasFile('file_path')) {
        $path = $request->file('file_path')->store('public/product_images');
        $relativePath = str_replace('public/', '', $path);

        Image::create([
            'pr_item_id' => $request->input('pr_item_id'),
            'file_name' => $request->file('file_path')->getClientOriginalName(),
            'file_path' => $relativePath,
            'name' => $request->name,
            'img_type' => $request->img_type,
            'caption' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Image uploaded successfully!',
            'paths' => [asset('storage/' . $relativePath)],
        ]);
    }
}


   public function delete($id){
    image::findOrFail($id)->delete();

    return response()->json(['success' => true, 'message' => 'image deleted successfully']);
   }

   public function updateDefaultStatus(Request $request, $id)
{
    $banner = Image::find($id);

    if (!$banner) {
        return response()->json(['success' => false, 'message' => 'Banner not found'], 404);
    }

    $banner->is_default = $request->is_default; // can be 0 or 1
    $banner->save();

    return response()->json(['success' => true]);
}
// Fetch data for edit
public function edit($id)
{
    $image = Image::findOrFail($id);
    return response()->json($image);
}

// Update image
public function update(Request $request, $id)
{
    $image = Image::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'img_type' => 'required|string|max:255',
        'file_path' => 'nullable|image',
        'caption' => 'nullable|string|max:255',
        'description' => 'nullable|string|max:2025',
    ]);

    $image->name = $request->name;
    $image->img_type = $request->img_type;
    $image->caption = $request->title;
    $image->description = $request->description;

    if($request->hasFile('file_path')){
        $path = $request->file('file_path')->store('public/product_images');
        $relativePath = str_replace('public/', '', $path);
        $image->file_path = $relativePath;
    }

    $image->save();

    return response()->json(['success' => true]);
}







}
