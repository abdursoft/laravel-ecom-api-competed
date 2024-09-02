<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AuthHelper\JwtToken;
use App\Models\Product;
use App\Models\ProductCart;
use Illuminate\Http\Request;

class ProductCartController extends Controller
{
    public function index(){
        return view('cart');
    }

    /**
     * Store|update the cart information
     */
    public function store(Request $request){
        $auth = JwtToken::verifyToken('token');
        if($auth){
            $exist = Product::with('product_offer')->find($request->input('product_id'));
            if($exist){
                $price = 0;
                $discount = 0;
                try {
                    if($exist->product_offer){
                        $offer = $exist->product_offer;
                        if(strtotime($offer->expired_at) > time()){
                            if($offer->offer_type == 'percent'){
                                $percent = $offer->price_amount / 100;
                                $discount = ($exist->price * $percent);
                                $price = $exist->price - $discount;
                            }else{
                                $discount = $offer->price_amount;
                                $price = $exist->price - $discount;
                            }
                        }else{
                            $price = $exist->price;
                        }
                    }else{
                        $price = $exist->price;
                    }

                    ProductCart::updateOrCreate(
                        [
                            'user_id' => $request->header('id'),
                            'product_id' => $request->input(['product_id'])
                        ],
                        [
                            'user_id' => $request->header('id'),
                            'product_id' => $request->input(['product_id']),
                            'quantity' => $request->input('quantity') ?? 1,
                            'color' => $request->input('color') ?? null,
                            'size' => $request->input('size') ?? null,
                            'price' => $price,
                            'discount' => $discount
                        ]

                    );
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Product successfully configured in your cart'
                    ],200);
                } catch (\Throwable $th) {
                    return response()->json([
                        'status' => 'fail',
                        'message' => 'Product couldn\'t add in your cart',
                    ],400);
                }
            }else{
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Product id not found'
                ],400);
            }
        }else{
            return response()->json([
                'status' => 'fail',
                'message' => 'Authentication failed'
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
                'carts' => ProductCart::with('product')->where('user_id',$request->header('id'))->get()
            ],200);
        }else{
            return response()->json([
                'status' => 'success',
                'carts' => ProductCart::with('product')->where('id',$id)->where('user_id',$request->header('id'))->first()
            ],200);
        }
    }

    /**
     * Destory the cart item
     */
    public function destroy(Request $request,$id){
        $exist = ProductCart::where('id',$id)->where('user_id',$request->header('id'))->first();
        if($exist){
            $exist->delete();
            return response()->json([
                'status' => 'success',
                'carts' => 'Product cart successfully removed'
            ],200);
        }else{
            return response()->json([
                'status' => 'fail',
                'carts' => "Unauthorized Access"
            ],401);
        }
    }
}
