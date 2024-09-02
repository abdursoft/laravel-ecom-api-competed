<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    use HasFactory;

    function product(){
        return $this->belongsTo(Product::class);
    }

    protected $fillable = [
        'img1',
        'img2',
        'img3',
        'description',
        'color',
        'size',
        'product_id'
    ];
}
