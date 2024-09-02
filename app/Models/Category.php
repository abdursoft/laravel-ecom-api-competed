<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    function product(){
        return $this->hasMany(Product::class);
    }

    function sub_category(){
        return $this->hasMany(SubCategory::class);
    }

    protected $fillable= [
        'categoryName',
        'categoryImg'
    ];
}
