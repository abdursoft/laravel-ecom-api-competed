<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;

    function user(){
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'first_name',
        'last_name',
        'company',
        'shipping_email',
        'phone',
        'country',
        'address',
        'city',
        'district',
        'post_code',
        'street',
        'user_id'
    ];
}
