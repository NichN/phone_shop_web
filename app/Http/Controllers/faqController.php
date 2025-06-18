<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\faq;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $faq = DB::table('faqs')->get();

            return DataTables::of($faq)
                ->addColumn('action', function ($row) {
                    return '
                        <div>
                            <button class="btn btn-primary btn-sm editfaq" data-id="' . $row->id . '" title="Edit">
                                Edit
                            </button>
                            <button class="btn btn-danger btn-sm deletefaq" data-id="' . $row->id . '" title="Delete">
                                Delete
                            </button>
                        </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('Admin.faq.faq');
    }
    public function store(Request $request){
         try{
            $validated = $request->validate([
                'question' => 'required|string|max:255|',
                'answer' => 'required|string|max:255|',
            ]);
            $data = [
                'question' => $validated['question'],
                'answer' => $validated['answer']
            ];
            faq::create($data);
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
    public function delete($id)
    {
        $faq = faq::findOrFail($id);
        $faq->delete();
        return response()->json(['success' => true]);
    }
    public function edit($id){
        $faq = faq::findOrFail($id);
        return response()->json($faq);
    }
    public function update(Request $request, $id)
    {
        $faq = faq::findOrFail($id);
        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer
        ]);

        return response()->json(['success' => true]);
    }
}
