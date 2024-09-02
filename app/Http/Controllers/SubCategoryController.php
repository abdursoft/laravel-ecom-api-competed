<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    public function index() {}

    public function create(Request $request)
    {
        if ($request->hasFile('image')) {
            $Validator = Validator::make($request->all(), [
                'sub_category' => 'required|string|max:40|unique:sub_categories,sub_category',
                'category_id' => 'required|exists:categories,id',
                'image'    => 'required|file|mimes:png,jpg,webp',
            ]);

            if ($Validator->fails()) {
                return response()->json([
                    'errors' => $Validator->errors(),
                ], 400);
            } else {
                try {
                    $img = $request->file('image');
                    SubCategory::create([
                        'sub_category' => $request->post('sub_category'),
                        'sub_category_img'  => Storage::disk('public')->put("uploads/" . date('Y') . "/" . date('F') . "/" . date('d'), $img),
                        'category_id' => $request->post('category_id')
                    ]);
                    return response()->json([
                        'status'  => 'success',
                        'message' => 'Sub-Category successfully added',
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
                'errors' => 'Sub-Category image missing in the post request',
            ], 400);
        }
    }

    public function show($id = null)
    {
        if ($id) {
            return SubCategory::find($id);
        } else {
            return SubCategory::get();
        }
    }

    public function edit(Request $request)
    {
        if ($request->hasFile('image')) {
            $Validator = Validator::make($request->all(), [
                'id' => 'required|int|exists:sub_categories,id',
                'category_id' => 'required|int|exists:categories,id',
                'sub_category'    => 'required|string|unique:sub_categories,sub_category,' . $request->post('id') . ',id',
                'image'       => 'required|file|mimes:png,jpg,webp',
            ]);
        } else {
            $Validator = Validator::make($request->all(), [
                'id' => 'required|int|exists:sub_categories,id',
                'category_id' => 'required|int|exists:categories,id',
                'sub_category'    => 'required|string|unique:sub_categories,sub_category,' . $request->post('id') . ',id',
            ]);
        }
        if ($Validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'errors' => $Validator->errors(),
            ], 400);
        }

        try {
            $exist = SubCategory::find($request->post('id'));
            SubCategory::where('id', $request->post('id'))
                ->update([
                    'sub_category' => $request->post('sub_category'),
                    'sub_category_img'  => $request->hasFile('image') ? Storage::disk('public')->put("uploads/" . date('Y') . "/" . date('F') . "/" . date('d'), $request->file('image')) : $exist->sub_category_img,
                    'category_id' => $request->post('category_id')
                ]);

            $request->hasFile('image') ? Storage::disk('public')->delete($exist->sub_category_img) : null;

            return response()->json([
                'status'  => 'success',
                'message' => 'Sub-Category successfully updated',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'fail',
                'message' => 'Sub-Category couldn\'t updated',
            ], 200);
        }
    }

    public function delete($id)
    {
        try {
            $exist = SubCategory::findOrFail($id);
            $exist->sub_category_img ? Storage::disk('public')->delete($exist->sub_category_img) : null;
            $exist->delete();
            return response()->json([
                'status'  => 'success',
                'message' => 'Sub-Category successfully deleted',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'fail',
                'message' => $th->getMessage(),
            ], 400);
        }
    }
}
