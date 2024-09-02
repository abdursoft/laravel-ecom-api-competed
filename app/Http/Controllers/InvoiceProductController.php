<?php

namespace App\Http\Controllers;

use App\Models\InvoiceProduct;
use Illuminate\Http\Request;

class InvoiceProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,$id=null)
    {
        if($id){
            return InvoiceProduct::where('user_id',$request->header('id'))->where('id',$id)->first();
        }else{
            return InvoiceProduct::where('user_id',$request->header('id'))->get();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,$id=null)
    {
        try {
            if($id){
                InvoiceProduct::where('user_id',$request->header('id'))->where('id',$id)->delete();
            }else{
                InvoiceProduct::where('user_id',$request->header('id'))->delete();
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Invoice product successfully removed'
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Invoice product couldn\'t removed'
            ],400);
        }
    }
}
