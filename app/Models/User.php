<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    function profile(){
        return $this->hasOne(Profile::class);
    }

    function product_cart(){
        return $this->hasMany(ProductCart::class);
    }

    function product_wish(){
        return $this->hasMany(ProductWish::class);
    }

    function payment(){
        return $this->hasMany(Payment::class);
    }

    function shipping(){
        return $this->hasMany(Shipping::class);
    }

    function billing(){
        return $this->hasMany(Billing::class);
    }

    function invoiceProduct(){
        return $this->hasMany(InvoiceProduct::class);
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'otp',
        'role',
        'is_verified',
        'is_blocked'
    ];
}
