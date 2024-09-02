<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceProduct extends Model
{
    use HasFactory;

    function user(){
        return $this->belongsTo(User::class);
    }

    function product(){
        return $this->belongsTo(Product::class);
    }

    function invoice(){
        return $this->hasMany(Invoice::class);
    }

    protected $fillable = [
        'quantity',
        'sale_price',
        'user_id',
        'invoice_id',
        'product_id'
    ];
}
