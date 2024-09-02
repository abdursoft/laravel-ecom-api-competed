<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller {
    public function index() {

    }

    public function create( Request $request ) {
        if ( $request->hasFile( 'image' ) ) {
            $validate = Validator::make( $request->all(), [
                'brand' => 'required|string|unique:brands,brandName',
                'image' => 'required|file|mimes:png,jpg,webp',
            ] );

            if ( $validate->fails() ) {
                return response()->json( [
                    'status' => 'fail',
                    'errors' => $validate->errors(),
                ], 400 );
            }

            try {
                $img = $request->file( 'image' );
                Brand::create( [
                    'brandName' => $request->post( 'brand' ),
                    'brandImg'  => Storage::disk('public')->put( "uploads/" . date( 'Y' ) . "/" . date( 'F' ) . "/" . date( 'd' ), $img ),
                ] );
                return response()->json( [
                    'status'  => 'success',
                    'message' => 'Brand Successfully Created',
                ], 200 );
            } catch ( \Throwable $th ) {
                return response()->json([
                    'status' => 'fail',
                    'message' => $th->getMessage()
                ],400);
            }
        } else {
            return response()->json( [
                'status'  => 'fail',
                'message' => 'Brand image missing in the post request',
            ], 302 );
        }
    }

    public function show($id=null){
        if($id){
            return Brand::find($id);
        }else{
            return Brand::get();
        }
    }

    public function edit(Request $request){
        if ( $request->hasFile( 'image' ) ) {
            $Validator = Validator::make( $request->all(), [
                'id' => 'required|int|exists:brands,id',
                'brand'    => 'required|string|unique:brands,brandName,' . $request->post( 'id' ) . ',id',
                'image'       => 'required|file|mimes:png,jpg,webp',
            ] );
        } else {
            $Validator = Validator::make( $request->all(), [
                'id' => 'required|int|exists:brands,id',
                'brand'    => 'required|string|unique:brands,brandName,' . $request->post( 'id' ) . ',id',
            ] );
        }
        if ( $Validator->fails() ) {
            return response()->json( [
                'status' => 'fail',
                'errors' => $Validator->errors(),
            ], 400 );
        }

        try {
            $exist = Brand::find( $request->input( 'id' ) );
            Brand::where( 'id', $request->post( 'id' ) )->update( [
                'brandName' => $request->post( 'brand' ),
                'brandImg'  => $request->hasFile( 'image' ) ? Storage::disk('public')->put( "uploads/" . date( 'Y' ) . "/" . date( 'F' ) . "/" . date( 'd' ), $request->file( 'image' ) ) : $exist->brandImg,
            ] );

            $request->hasFile('image') ? Storage::disk('public')->delete($exist->brandImg) : null;

            return response()->json( [
                'status'  => 'success',
                'message' => 'Brand successfully updated',
            ], 200 );
        } catch ( \Throwable $th ) {
            return response()->json( [
                'status'  => 'fail',
                'message' => 'Brand couldn\'t updated',
            ], 200 );
        }
    }

    public function delete( $id ) {
        try {
            $exist = Brand::findOrFail( $id );
            $exist->brandImg ? Storage::disk('public')->delete($exist->brandImg) : null;
            $exist->delete();
            return response()->json( [
                'status'  => 'success',
                'message' => 'Brand successfully deleted',
            ], 200 );
        } catch ( \Throwable $th ) {
            return response()->json( [
                'status'  => 'fail',
                'message' => "Brand id $id not found or internal error",
            ], 400 );
        }
    }
}
