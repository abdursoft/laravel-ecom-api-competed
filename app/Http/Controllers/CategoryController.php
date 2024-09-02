<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    public function index() {}

    public function create(Request $request)
    {
        if ($request->hasFile('image')) {
            $Validator = Validator::make($request->all(), [
                'category' => 'required|string|max:40|unique:categories,categoryName',
                'image'    => 'required|file|mimes:png,jpg,webp',
            ]);

            if ($Validator->fails()) {
                return response()->json([
                    'status' => 'fail',
                    'errors' => $Validator->errors(),
                ], 400);
            } else {
                try {
                    $img = $request->file('image');
                    Category::create([
                        'categoryName' => $request->post('category'),
                        'categoryImg'  => Storage::disk('public')->put("uploads/" . date('Y') . "/" . date('F') . "/" . date('d'), $img),
                    ]);
                    return response()->json([
                        'status'  => 'success',
                        'message' => 'Category successfully added',
                    ], 201);
                } catch (\Throwable $th) {
                    return response()->json([
                        'status' => 'fail',
                        'errors' => $th->getMessage(),
                    ], 400);
                }
            }
        } else {
            return response()->json([
                'status' => 'fail',
                'errors' => 'Category image missing in the post request',
            ], 400);
        }
    }

    public function show($id = null)
    {
        if ($id) {
            return Category::with('sub_category')->find($id);
        } else {
            return Category::with('sub_category')->get();
        }
    }

    public function edit(Request $request)
    {
        if ($request->hasFile('image')) {
            $Validator = Validator::make($request->all(), [
                'category_id' => 'required|int|exists:categories,id',
                'category'    => 'required|string|unique:categories,categoryName,' . $request->post('category_id') . ',id',
                'image'       => 'required|file|mimes:png,jpg,webp',
            ]);
        } else {
            $Validator = Validator::make($request->all(), [
                'category_id' => 'required|int|exists:categories,id',
                'category'    => 'required|string|unique:categories,categoryName,' . $request->post('category_id') . ',id',
            ]);
        }
        if ($Validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'errors' => $Validator->errors(),
            ], 400);
        }

        try {

            $exist = Category::find($request->input('category_id'));
            Category::where('id', $request->post('category_id'))->update([
                'categoryName' => $request->post('category'),
                'categoryImg'  => $request->hasFile('image') ? Storage::disk('public')->put("uploads/" . date('Y') . "/" . date('F') . "/" . date('d'), $request->file('image')) : $exist->categoryImg,
            ]);

            $request->hasFile('image') ? Storage::disk('public')->delete($exist->categoryImg) : null;

            return response()->json([
                'status'  => 'success',
                'message' => 'Category successfully updated',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'fail',
                'message' => 'Category couldn\'t updated',
            ], 200);
        }
    }

    public function delete($id)
    {
        try {
            $exist = Category::findOrFail($id);
            Storage::disk('public')->delete($exist->categoryImg);
            $exist->delete();
            return response()->json([
                'status'  => 'success',
                'message' => 'Category successfully deleted',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'fail',
                'message' => $th->getMessage(),
            ], 400);
        }
    }
}
