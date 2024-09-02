<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(){
        return view('checkout');
    }

    public function create(Request $request){
        $validate = Validator::make($request->all(),[
            'title' => 'required|string|max:200|unique:products,title',
            'short_desc' => 'required|string',
            'price' => 'required|string',
            'image' => 'required|file|mimes:jpg,png,webp',
            'stock' => 'required|boolean',
            'star' => 'required|string',
            'remark' => 'required',
            'product_code' => 'required|string|unique:products,product_code',
            'bar_code' => 'required|string|unique:products,bar_code',
            'category_id' => 'required|int|exists:categories,id',
            'sub_category_id' => 'required|int|exists:sub_categories,id',
            'brand_id' => 'required|int|exists:brands,id'
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => 'fail',
                'errors' => $validate->errors()
            ],400);
        }

        try {
            $img = $request->file('image');
            Product::create([
                'title' => $request->post('title'),
                'short_desc' => $request->post('short_desc'),
                'price' => $request->post('price'),
                'image' => Storage::disk('public')->put( "uploads/" . date( 'Y' ) . "/" . date( 'F' ) . "/" . date( 'd' ), $img ),
                'stock' => $request->post('stock'),
                'star' => $request->post('star'),
                'remark' => $request->post('remark'),
                'product_code' => $request->post('product_code'),
                'bar_code' => $request->post('bar_code'),
                'category_id' => $request->post('category_id'),
                'sub_category_id' => $request->post('sub_category_id'),
                'brand_id' => $request->post('brand_id'),
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Product successfully created'
            ],201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'fail',
                'errors' => $th->getMessage()
            ],400);
        }

    }

    public function show($id=null,$order='asc'){
        if($id && $order == 'asc'){
            return Product::with(['category','sub_category','brand','product_detail','product_offer'])->find($id);
        }elseif($id && $order == 'desc'){
            return Product::query()->orderBy('id',$order)->with(['category','sub_category','brand','product_detail','product_offer'])->get();
        }else{
            return Product::query()->orderBy('id',$order)->with(['category','sub_category','brand','product_detail','product_offer'])->get();
        }
    }

    public function edit(Request $request){
        $validate = Validator::make($request->all(),[
            'id' => 'required|int|exists:products,id',
            'title' => 'required|string|max:200|unique:products,title,'.$request->post('id').',id',
            'short_desc' => 'required|string',
            'price' => 'required|string',
            'image' => 'file|mimes:jpg,png,webp',
            'stock' => 'required|boolean',
            'star' => 'required|string',
            'remark' => 'required',
            'product_code' => 'required|string|unique:products,product_code,'.$request->post('id').',id',
            'bar_code' => 'string|unique:products,bar_code,'.$request->post('id').',id',
            'category_id' => 'required|int|exists:categories,id',
            'sub_category_id' => 'required|int|exists:sub_categories,id',
            'brand_id' => 'required|int|exists:brands,id'
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => 'fail',
                'errors' => $validate->errors()
            ],400);
        }

        try {
            $img = $request->hasFile('image');
            $exist = Product::find($request->post('id'));
            Product::where('id',$request->post('id'))
            ->update([
                'title' => $request->post('title'),
                'short_desc' => $request->post('short_desc'),
                'price' => $request->post('price'),
                'image' => $img ? Storage::disk('public')->put( "uploads/" . date( 'Y' ) . "/" . date( 'F' ) . "/" . date( 'd' ), $request->file('image')) : $exist->image,
                'stock' => $request->post('stock'),
                'star' => $request->post('star'),
                'remark' => $request->post('remark'),
                'product_code' => $request->post('product_code'),
                'bar_code' => $request->post('bar_code'),
                'category_id' => $request->post('category_id'),
                'sub_category_id' => $request->post('sub_category_id'),
                'brand_id' => $request->post('brand_id'),
            ]);
            $request->hasFile('image') ? Storage::disk('public')->delete($exist->image) : null;
            return response()->json([
                'status' => 'success',
                'message' => 'Product successfully updated'
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
            $exist = Product::findOrFail( $id );
            $exist->image ? Storage::disk('public')->delete($exist->image) : null;
            $exist->delete();
            return response()->json( [
                'status'  => 'success',
                'message' => 'Product successfully deleted',
            ], 200 );
        } catch ( \Throwable $th ) {
            return response()->json( [
                'status'  => 'fail',
                'message' => $th->getMessage(),
            ], 400 );
        }
    }
}
