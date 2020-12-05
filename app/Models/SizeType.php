<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SizeType extends Model
{

    protected $table = 'size_type';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
    ];

    // public function subCategory()
    // {
    //     return $this->hasMany('App\Models\SubCategory','category_id',$this->primaryKey);
    // }
}
