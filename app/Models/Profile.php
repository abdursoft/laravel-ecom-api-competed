<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    function user(){
        return $this->belongsTo(User::class);
    }

    function reviews(){
        return $this->hasMany(ProductReview::class);
    }

    function invoice(){
        return $this->hasMany(Invoice::class);
    }

    protected $fillable = [
        'first_name',
        'last_name',
        'profile',
        'mobile',
        'address',
        'user_id'
    ];
}
