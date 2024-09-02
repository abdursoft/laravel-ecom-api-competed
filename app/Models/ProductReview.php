<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;

    function product(){
        return $this->belongsTo(Product::class);
    }

    function profile(){
        return $this->belongsTo(Profile::class);
    }

    protected $fillable = [
        'description',
        'star',
        'product_id',
        'profile_id'
    ];
}
