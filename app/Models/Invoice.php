<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    function profile(){
        return $this->belongsTo(Profile::class);
    }

    function shipping(){
        return $this->belongsTo(Shipping::class);
    }

    function billing(){
        return $this->belongsTo(Billing::class);
    }

    function invoiceProduct(){
        return $this->hasMany(InvoiceProduct::class);
    }

    protected $fillable = [
        "vat",
        "total",
        "discount",
        "sub_total",
        "payable",
        "profile",
        "shipping",
        "billing",
        "trans_id",
        "val_id",
        "delivery_status",
        "shipping_status"
    ];
}
