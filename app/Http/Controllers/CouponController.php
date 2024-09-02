<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required|string',
            'code' => 'required|string|unique:coupons,code,'.$request->input('id').',id',
            'amount' => 'required|string',
            'coupon_type' => 'required|string',
            'started_at' => 'required',
            'expired_at' => 'required',
            'banner' => 'file|mimes:jpeg,jpg,png,webp'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Coupon couldn\'t create',
                'errors' => $validate->errors()
            ], 400);
        }

        try {
            $exist = Coupon::find($request->input('id')) ?? null;
            if(!$exist){
                Coupon::create([
                    'title' => $request->input('title'),
                    'code' => $request->input('code'),
                    'amount' => $request->input('amount'),
                    'coupon_type' => $request->input('coupon_type'),
                    'started_at' => $request->input('started_at'),
                    'expired_at' => $request->input('expired_at'),
                    'banner' =>  $request->hasFile('banner') ? Storage::disk('public')->put("uploads/" . date( 'Y' ) . "/" . date( 'F' ) . "/" . date( 'd' ), $request->file('banner')) : null
                ]);
            }else{
                Coupon::where('id',$request->input('id'))->update([
                    'title' => $request->input('title'),
                    'code' => $request->input('code'),
                    'amount' => $request->input('amount'),
                    'coupon_type' => $request->input('coupon_type'),
                    'started_at' => $request->input('started_at'),
                    'expired_at' => $request->input('expired_at'),
                    'banner' => $request->hasFile('banner') ? Storage::disk('public')->put("uploads/" . date( 'Y' ) . "/" . date( 'F' ) . "/" . date( 'd' ), $request->file('banner')) : $exist->banner
                ]);
                ($request->hasFile('banner') && $exist->banner != null) ? Storage::disk('public')->delete($exist->banner) : null;
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Coupon successfully saved'
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Coupon couldn\'t save',
                'error' => $th->getMessage()
            ],400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id=null)
    {
        if($id){
            return Coupon::find($id);
        }else{
            return Coupon::get();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(coupon $coupon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $exist = Coupon::where('id',$id)->first();
            if($exist){
                $exist->banner != null ? Storage::disk('public')->delete($exist->banner) : null;
                $exist->delete();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Coupon successfully removed'
                ],200);
            }else{
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Coupon couldn\'t found'
                ],400);
            } 
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Coupon couldn\'t remove'
            ],400);
        }
    }

    /**
     * Verify user coupon
     */
    public function couponVerify(Request $request){
        if(!empty($request->input('code'))){
            $coupon = Coupon::where('code',$request->input('code'))->first();
            if($coupon){
                if(strtotime($coupon->expired_at) > time() && time() > strtotime($coupon->started_at)){
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Coupon code exist',
                        'data' => $coupon
                    ],200);
                }else{
                    return response()->json([
                        'status' => 'fail',
                        'message' => 'Coupon code expired or not begine'
                    ],400);
                }
            }else{
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Coupon code not exist'
                ],400);
            }
        }else{
            return response()->json([
                'status' => 'fail',
                'message' => 'Coupon code is requried'
            ],400);
        }
    }
}
