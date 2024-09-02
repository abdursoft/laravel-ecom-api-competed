<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOffer extends Model
{
    use HasFactory;

    function product(){
        return $this->belongsTo(Product::class);
    }

    protected $fillable = [
        'percent',
        'price_amount',
        'started_at',
        'expired_at',
        'product_id'
    ];
}
