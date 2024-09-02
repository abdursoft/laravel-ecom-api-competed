<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\SslCommerze;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class SslCommerzeController extends Controller
{
    /**
     * Create the payment method
     */
    public function store(Request $request){
        $validate = Validator::make($request->all(),[
            'store_id' => 'required:string',
            'store_password' => 'required|string',
            'currency'  => 'required',
            'success_url' => 'required',
            'cancel_url' => 'required',
            'fail_url' => 'required',
            'ipn_url' => 'required',
            'init_url' => 'required'
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => 'fail',
                'message' => 'Couldn\'t create the payment gateway',
                'error' => $validate->errors()
            ],400);
        }

        try {
            SslCommerze::updateOrCreate([
                'payment_name' => 'sslcommerze'
            ],[
                'store_id' => $request->input('store_id'),
                'store_password' => $request->input('store_password'),
                'currency'  => $request->input('currency'),
                'success_url' => $request->input('success_url'),
                'cancel_url' =>$request->input('cancel_url'),
                'fail_url' => $request->input('fail_url'),
                'ipn_url' => $request->input('ipn_url'),
                'init_url' => $request->input('init_url'),
                'payment_name' => 'sslcommerze'
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Sslcommerze successfully saved'
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Couldn\'t create the payment gateway',
            ],400);
        }
    }

    /**
     * Show the sslcommerze data
     */
    public function show(){
        return SslCommerze::first();
    }

    /**
     * Destroy the payment gateway
     */
    public function destroy(){
        try {
            SslCommerze::where('payment_name','sslcommerze')->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Sslcommerze successfully removed'
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Sslcommerze could\'t delete'
            ],400);
        }
    }


    /**
     * ================================**
     * ========== Payment Process =======
     * ==================================
     */

     static function  InitiatePayment($billing,$shipping,$payable,$tran_id,$user_email): array
     {
        try{
            $ssl= SslCommerze::first();
            $response = Http::asForm()->post($ssl->init_url,[
                "store_id"=>$ssl->store_id,
                "store_passwd"=>$ssl->store_password,
                "total_amount"=>$payable,
                "currency"=>$ssl->currency,
                "tran_id"=>$tran_id,
                "success_url"=>"$ssl->success_url?tran_id=$tran_id",
                "fail_url"=>"$ssl->fail_url?tran_id=$tran_id",
                "cancel_url"=>"$ssl->cancel_url?tran_id=$tran_id",
                "ipn_url"=>$ssl->ipn_url,
                "cus_name"=>$billing->first_name,
                "cus_email"=>$user_email,
                "cus_add1"=>$billing->address,
                "cus_add2"=>$billing->address,
                "cus_city"=>$billing->city,
                "cus_state"=>$billing->district,
                "cus_postcode"=> $billing->post_code,
                "cus_country"=>$billing->country,
                "cus_phone"=>$billing->phone,
                "cus_fax"=>$billing->phone,
                "shipping_method"=>"YES",
                "ship_name"=>$shipping->first_name,
                "ship_add1"=>$shipping->address,
                "ship_add2"=>$shipping->address,
                "ship_city"=>$shipping->city,
                "ship_state"=>$shipping->district,
                "ship_country"=>$shipping->country ,
                "ship_postcode"=>$shipping->post_code,
                "product_name"=>"Apple Shop Product",
                "product_category"=>"Apple Shop Category",
                "product_profile"=>"Apple Shop Profile",
                "product_amount"=>$payable,
            ]);
            return $response->json('desc');
        }
        catch (Exception $e){
            return $ssl;
        }
  
      }
  
  
  
      static function InitiateSuccess($tran_id):int{
          Invoice::where(['trans_id'=>$tran_id,'val_id'=>0])->update(['payment_status'=>'Success']);
          return 1;
      }
  
      static function InitiateFail($tran_id):int{
         Invoice::where(['trans_id'=>$tran_id,'val_id'=>0])->update(['payment_status'=>'Fail']);
         return 1;
      }
  
  
  
      static function InitiateCancel($tran_id):int{
          Invoice::where(['trans_id'=>$tran_id,'val_id'=>0])->update(['payment_status'=>'Cancel']);
          return 1;
      }
  
      static function InitiateIPN($tran_id,$status,$val_id):int{
          Invoice::where(['trans_id'=>$tran_id,'val_id'=>0])->update(['payment_status'=>$status,'val_id'=>$val_id]);
          return 1;
      }
  

     function PaymentSuccess(Request $request){
        self::InitiateSuccess($request->query('tran_id'));
        return redirect('/');
    }


    function PaymentCancel(Request $request){
        self::InitiateCancel($request->query('tran_id'));
        return redirect('/');
    }

    function PaymentFail(Request $request){
        self::InitiateFail($request->query('tran_id'));
        return redirect('/');
    }

    function PaymentIPN(Request $request){
        return self::InitiateIPN($request->input('tran_id'),$request->input('status'),$request->input('val_id'));
    }
}
