<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SslCommerze extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'store_password',
        'currency',
        'success_url',
        'cancel_url',
        'fail_url',
        'ipn_url',
        'init_url',
        'payment_name'
    ];
}
