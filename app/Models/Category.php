<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $table = 'category';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name','status','is_sub_category','image','category_type'
    ];

    public function subCategory()
    {
        return $this->hasMany('App\Models\SubCategory','category_id',$this->primaryKey);
    }

    // public function productSubCategory()
    // {
    //     return $this->hasMany('App\Models\Product','sub_category_id',$this->primaryKey);
    // }
}
