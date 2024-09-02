<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    function category(){
        return $this->belongsTo(Category::class);
    }

    function product(){
        return $this->hasMany(Product::class);
    }

    protected $fillable = [
        'category_id',
        'sub_category',
        'sub_category_img'
    ];
}
