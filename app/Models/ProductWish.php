<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductWish extends Model
{
    use HasFactory;

    function profile(){
        return $this->belongsTo(Profile::class);
    }

    function product(){
        return $this->belongsTo(Product::class);
    }

    protected $fillable = [
        'user_id',
        'product_id'
    ];
}
