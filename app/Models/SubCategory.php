<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory  extends Model
{

    protected $table = 'sub_category';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name','status','image','category_id'
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\Category','category_id',$this->primaryKey);
    }
}
