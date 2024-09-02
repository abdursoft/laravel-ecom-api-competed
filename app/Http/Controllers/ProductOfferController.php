<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductOfferController extends Controller
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
    public function create(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'amount' => 'required|string',
            'start_date' => 'required|string',
            'expire_date' => 'required|string',
            'product_id' => 'required|exists:products,id|unique:product_offers,product_id'
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => 'fail',
                'message' => 'Product offer couldn\'t added',
                'errors' => $validate->errors(),
            ],400);
        }

        $product = Product::find($request->input('product_id'));
        if($product){
            if($request->post('amount') > $product->price){
                return response()->json([
                    'status' => 'fail',
                    'message' => "Product offer price greater than product price"
                ],400);
            }
            
        }else{
            return response()->json([
                'status' => 'fail',
                'message' => "Product ID not found"
            ],400);
        }

        try {
            ProductOffer::create([
                'offer_type' => strtolower($request->post('offer_type')) ?? 'percent',
                'price_amount' => $request->post('amount'),
                'started_at' => $request->post('start_date'),
                'expired_at' => $request->post('expire_date'),
                'product_id' => $request->post('product_id')
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Product Offer successfully created'
            ],201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'fail',
                'message' => $th->getMessage()
            ],400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id=null)
    {
        if($id){
            return ProductOffer::find($id);
        }else{
            return ProductOffer::get();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'amount' => 'required|string',
            'start_date' => 'required|string',
            'expire_date' => 'required|string',
            'product_id' => 'required|exists:products,id|exists:product_offers,product_id',
            'id' => 'required|int|exists:product_offers,id'
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => 'fail',
                'errors' => $validate->errors()
            ],400);
        }

        try {
            ProductOffer::updateOrCreate(
                [
                    'id' => $request->input('id')
                ],
                [
                'offer_type' => strtolower($request->post('offer_type')) ?? 'percent',
                'price_amount' => $request->post('amount'),
                'started_at' => $request->post('start_date'),
                'expired_at' => $request->post('expire_date'),
                'product_id' => $request->post('product_id')
                ]
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Product Offer successfully updated'
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'fail',
                'message' => $th->getMessage()
            ],400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductOffer $productOffer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $exist = ProductOffer::find( $id );
            $exist->delete();
            return response()->json( [
                'status'  => 'success',
                'message' => 'Product-offer successfully deleted',
            ], 200 );
        } catch ( \Throwable $th ) {
            return response()->json( [
                'status'  => 'fail',
                'message' => $th->getMessage(),
            ], 400 );
        }
    }
}
