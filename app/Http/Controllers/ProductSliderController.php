<?php

namespace App\Http\Controllers;

use App\Models\ProductSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductSliderController extends Controller
{
    /**
     * Create or update new Slider
     */
    public function store(Request $request){
        $validate = Validator::make($request->all(),[
            'title' => 'required|string|max:200',
            'image' => 'file|mimes:jpg,jpeg,png,webp',
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => 'fail',
                'message' => 'Slider couldn\'t create'
            ],400);
        }

        try {
            $exist = ProductSlider::find($request->input('id')) ?? null;
            if($exist){
                ProductSlider::where('id',$request->input('id'))->update([
                    'title' => $request->input('title'),
                    'image' =>  $request->hasFile('image') ? Storage::disk('public')->put("uploads/" . date( 'Y' ) . "/" . date( 'F' ) . "/" . date( 'd' ), $request->file('image')) : $exist->image,
                    'short_desc' => $request->input('short_desc') ?? null,
                    'label' => $request->input('label') ?? null,
                    'action' => $request->input('action') ?? null
                ]);
                $request->hasFile('image') ? Storage::disk('public')->delete($exist->image) : null;
            }else{
                ProductSlider::create([
                    'title' => $request->input('title'),
                    'image' =>  $request->hasFile('image') ? Storage::disk('public')->put("uploads/" . date( 'Y' ) . "/" . date( 'F' ) . "/" . date( 'd' ), $request->file('image')) : "",
                    'short_desc' => $request->input('short_desc') ?? null,
                    'label' => $request->input('label') ?? null,
                    'action' => $request->input('action') ?? null
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Slider information successfully saved'
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Slider couldn\'t create'
            ],400);
        }
    }

    /**
     * Show the slider information
     */
    public function show($id=null){
        if($id){
            return ProductSlider::find($id);
        }else{
            return ProductSlider::get();
        }
    }

    /**
     * To destroy the slider
     */
    public function destroy($id){
        try {
            $exist = ProductSlider::find($id);
            $exist ? Storage::disk('public')->delete($exist->image) : null;
            $exist->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Product slider successfully removed'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Product slider couldn\'t remove'
            ]);
        }
    }
}
