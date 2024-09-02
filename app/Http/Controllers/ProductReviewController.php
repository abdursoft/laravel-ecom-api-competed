<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductReviewController extends Controller
{
    /**
     * Store the product review
     */
    public function store(Request $request){
        $validate = Validator::make($request->all(),[
            'description' => 'required|string|max:1000',
            'product_id' => 'required|exists:products,id',
            'star' => 'required',
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => 'fail',
                'message' => 'Review coludn\'t save',
                'errors' => $validate->errors()
            ],400);
        }

        $profile = Profile::where('user_id',$request->header('id'))->first();
        if($profile){
            try {
                ProductReview::updateOrCreate(
                    [
                        'profile_id' => $profile->id,
                        'product_id' => $request->input('product_id')
                    ],
                    [
                        'description' => $request->input('description'),
                        'star' => $request->input('star'),
                        'profile_id' => $profile->id,
                        'product_id' => $request->input('product_id')
                    ]
                );
                return response()->json([
                    'status' => 'success',
                    'message' => 'Review successfully save',
                ],200);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Coludn\'t post your review',
                    'errors' => $th->getMessage()
                ],400);
            }
        }else{
            return response()->json([
                'status' => 'fail',
                'message' => 'Please setup your profile first!',
            ],400);
        }
    }

    /**
     * show the review against the product
     */
    public function show($id){
        return ProductReview::with('profile')->where('product_id',$id)->get();
    }

    /**
     * Destroy the product review by ID
     */
    public function destroy(Request $request,$id){
        $profile = Profile::where('user_id',$request->header('id'))->first();
        if($profile){
            try {
                $review = ProductReview::where('profile_id',$profile->id)->where('id',$id)->first();
                if($review){
                    $review->delete();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Review successfully removed',
                    ],200);
                }else{
                    return response()->json([
                        'status' => 'fail',
                        'message' => 'Unauthorized action',
                    ],400);
                }
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Review ID or Profile couldn\'t match for the review',
                ],400);
            }
        }else{
            return response()->json([
                'status' => 'fail',
                'message' => 'Unauthorized access',
            ],400);
        }
    }
}
