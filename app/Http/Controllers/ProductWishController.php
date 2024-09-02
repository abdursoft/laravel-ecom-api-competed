<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductWish;
use Illuminate\Http\Request;

class ProductWishController extends Controller
{
    public function index(){
        return view('cart');
    }

    /**
     * Store|update the cart information
     */
    public function store(Request $request){
        $exist = Product::with('product_offer')->find($request->input('product_id'));
        if($exist){
            try {
                ProductWish::updateOrCreate(
                    [
                        'user_id' => $request->header('id'),
                        'product_id' => $request->input(['product_id'])
                    ],
                    [
                        'user_id' => $request->header('id'),
                        'product_id' => $request->input(['product_id']),
                    ]

                );
                return response()->json([
                    'status' => 'success',
                    'message' => 'Product successfully configured in your wishlist'
                ],200);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Product couldn\'t add in your wishlist',
                ],400);
            }
        }else{
            return response()->json([
                'status' => 'fail',
                'message' => 'Product id not found'
            ],400);
        }

    }

    /**
     * Show the cart items
     */
    public function show(Request $request,$id=null){
        if($id == null){
            return response()->json([
                'status' => 'success',
                'wish_list' => ProductWish::with('product')->where('user_id',$request->header('id'))->get()
            ],200);
        }else{
            return response()->json([
                'status' => 'success',
                'wish_list' => ProductWish::with('product')->where('id',$id)->where('user_id',$request->header('id'))->first()
            ],200);
        }
    }

    /**
     * Destory the cart item
     */
    public function destroy(Request $request,$id){
        $exist = ProductWish::where('id',$id)->where('user_id',$request->header('id'))->first();
        if($exist){
            $exist->delete();
            return response()->json([
                'status' => 'success',
                'carts' => 'Product wishes successfully removed'
            ],200);
        }else{
            return response()->json([
                'status' => 'success',
                'carts' => "Unauthorized Access"
            ],401);
        }
    }
}
