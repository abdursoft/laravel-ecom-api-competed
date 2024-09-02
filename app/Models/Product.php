<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    function category(){
        return $this->belongsTo(Category::class);
    }

    function sub_category(){
        return $this->belongsTo(SubCategory::class);
    }

    function brand(){
        return $this->belongsTo(Brand::class);
    }

    function product_detail(){
        return $this->hasOne(ProductDetail::class);
    }

    function product_review(){
        return $this->hasMany(ProductReview::class);
    }

    function product_wish(){
        return $this->hasMany(ProductWish::class);
    }

    function product_cart(){
        return $this->hasMany(ProductCart::class);
    }

    function payment(){
        return $this->hasMany(Payment::class);
    }

    function product_offer(){
        return $this->hasOne(ProductOffer::class);
    }

    protected $fillable = [
        'title',
        'short_desc',
        'price',
        'image',
        'stock',
        'star',
        'remark',
        'product_code',
        'bar_code',
        'category_id',
        'sub_category_id',
        'brand_id'
    ];

    protected $attributes = [
        'discount' => 0,
        'discount_price' => ""
    ];
}
