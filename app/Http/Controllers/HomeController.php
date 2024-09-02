<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductOffer;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $data['products'] = (new ProductController)->show();
        $data['categories'] = (new CategoryController)->show();
        $data['remarks'] = Product::distinct()->get(['remark']);
        $data['offers'] = ProductOffer::with('product')->where('expired_at','>',date('Y-m-d H:i:s'))->get();
        return view('home',$data);
    }
}
