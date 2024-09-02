<?php

namespace App\Http\Controllers;

use App\Models\ProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductDetailController extends Controller
{
    public function index(){

    }

    public function create(Request $request){
        $validate = Validator::make($request->all(),[
            'img1' => 'required|file|mimes:png,jpg,webp',
            'img2' => 'required|file|mimes:png,jpg,webp',
            'img3' => 'required|file|mimes:png,jpg,webp',
            'size' => 'required|string',
            'color' => 'required|string',
            'description' => 'required|string',
            'product_id' => 'required|int|exists:products,id'
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => 'fail',
                'errors' => $validate->errors()
            ],400);
        }

        try {
            $path = "uploads/products/".date('Y').'/'.date('F').'/'.date('d');
            ProductDetail::create([
                'img1' => Storage::disk('public')->put( "uploads/" . date( 'Y' ) . "/" . date( 'F' ) . "/" . date( 'd' ), $request->file('img1') ),
                'img2' => Storage::disk('public')->put( "uploads/" . date( 'Y' ) . "/" . date( 'F' ) . "/" . date( 'd' ), $request->file('img2') ),
                'img3' => Storage::disk('public')->put( "uploads/" . date( 'Y' ) . "/" . date( 'F' ) . "/" . date( 'd' ), $request->file('img3') ),
                'size' => $request->post('size'),
                'color' => $request->post('color'),
                'description' => $request->post('description'),
                'product_id' => $request->post('product_id')
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Product details successfully added'
            ],201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'fail',
                'errors' => $th->getMessage()
            ],400);
        }
    }

    public function show($id=null){
        if($id){
            return ProductDetail::find($id);
        }else{
            return ProductDetail::get();
        }
    }


    public function edit(Request $request){
        $validate = Validator::make($request->all(),[
            'img1' => 'file|mimes:png,jpg,webp',
            'img2' => 'file|mimes:png,jpg,webp',
            'img3' => 'file|mimes:png,jpg,webp',
            'size' => 'required|string',
            'color' => 'required|string',
            'description' => 'required|string',
            'product_id' => 'required|int|exists:products,id',
            'id' => 'required|int|exists:product_details,id',
        ]);
        if($validate->fails()){
            return response()->json([
                'status' => 'fail',
                'errors' => $validate->errors()
            ],400);
        }

        try {
            $exist = ProductDetail::find($request->post('id'));
            ProductDetail::where('id',$request->post('id'))
            ->update([
                'img1' => $request->hasFile('img1') ? Storage::disk('public')->put( "uploads/" . date( 'Y' ) . "/" . date( 'F' ) . "/" . date( 'd' ), $request->file('img1') ) : $exist->img1,

                'img2' => $request->hasFile('img2') ? Storage::disk('public')->put( "uploads/" . date( 'Y' ) . "/" . date( 'F' ) . "/" . date( 'd' ), $request->file('img2') ) : $exist->img2,

                'img3' => $request->hasFile('img3') ? Storage::disk('public')->put( "uploads/" . date( 'Y' ) . "/" . date( 'F' ) . "/" . date( 'd' ), $request->file('img3') ) : $exist->img3,

                'size' => $request->post('size'),
                'color' => $request->post('color'),
                'description' => $request->post('description'),
                'product_id' => $request->post('product_id')
            ]);


            $request->hasFile('img1') ? Storage::disk('public')->delete($exist->img1) : null;
            $request->hasFile('img2') ? Storage::disk('public')->delete($exist->img2) : null;
            $request->hasFile('img3') ? Storage::disk('public')->delete($exist->img3) : null;

            return response()->json([
                'status' => 'success',
                'message' => 'Product details successfully updated'
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'fail',
                'errors' => $th->getMessage()
            ],400);
        }
    }

    public function delete($id){
        try {
            $exist = ProductDetail::find( $id );
            
            $exist->img1 != null ? Storage::disk('public')->delete($exist->img1) : null;
            $exist->img2 != null ? Storage::disk('public')->delete($exist->img2) : null;
            $exist->img3 != null ? Storage::disk('public')->delete($exist->img3) : null;

            $exist->delete();
            return response()->json( [
                'status'  => 'success',
                'message' => 'Product details successfully deleted',
            ], 200 );
        } catch ( \Throwable $th ) {
            return response()->json( [
                'status'  => 'fail',
                'message' => "Unauthorized access or internal error",
            ], 400 );
        }
    }
}
