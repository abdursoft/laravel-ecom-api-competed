<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\ProductCart;
use App\Models\Profile;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created invoice.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $trans_id = uniqid();
            $total_price = 0;
            $discount_price = 0;
            $userID = $request->header('id');

            $carts = ProductCart::with('product')->where('user_id',$userID)->get();

            $shipping = Shipping::where('user_id',$userID)->first();
            $billing = Billing::where('user_id',$userID)->first();
            $profile = Profile::where('user_id',$userID)->first();

            foreach($carts as $cart){
                $total_price += $cart->price;
                $discount_price += $cart->discount;
            }

            $vat = $total_price * env('PURCHASE_VAT')/100;
            $payable = $total_price + $vat;


            $invoice = Invoice::create([
                'vat' => $vat,
                'total' => $total_price,
                'sub_total' => $total_price,
                'discount' => $discount_price,
                'payable' => $payable,
                'shipping' => $shipping->id,
                'billing' => $billing->id,
                'profile' => $profile->id ?? 1,
                'trans_id' => $trans_id,
                'val_id' => 0,
                'delivery_status' => 'pending',
                'payment_status' => 'pending'
            ]);
            $invoiceID = $invoice->id;

            foreach($carts as $cart){
                InvoiceProduct::create([
                    'quantity' => $cart->quantity,
                    'sale_price' => $cart->price,
                    'user_id' => $userID,
                    'invoice_id' => $invoiceID,
                    'product_id' => $cart->product_id,
                ]);
            }

            $paymentMethods = SslCommerzeController::InitiatePayment($billing,$shipping,$payable,$trans_id,$request->header('email'));
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Invoice successfully created',
                'payable' => $payable,
                'total' => $total_price,
                'vat' => $vat,
                'payments' => $paymentMethods
            ],201);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'fail',
                'message' => 'Invoice couldn\'t created',
                'error' => $th->getMessage()
            ],400);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id=null)
    {
        $profile = Profile::where('user_id',$request->header('id'))->first();
        if($id){
            return Invoice::where('profile',$profile->id)->where('id',$id)->first();
        }else{
            return Invoice::where('profile',$profile->id)->get();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id=null)
    {
        try {
            $profile = Profile::where('user_id',$request->header('id'))->first();
            if($id){
                Invoice::where('profile',$profile->id)->where('id',$id)->delete();
            }else{
                Invoice::where('profile',$profile->id)->delete();
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Invoice successfully removed'
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'success',
                'message' => 'Invoice couldn\'t removed'
            ],400);
        }
    }
}
