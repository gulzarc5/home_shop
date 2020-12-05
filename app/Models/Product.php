<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name','category_id','sub_category_id','main_image','description','min_price','mrp','stock','status ','product_type'
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\Category','category_id',$this->primaryKey);
    }

    public function subCategory()
    {
        return $this->belongsTo('App\Models\SubCategory','sub_category_id',$this->primaryKey);
    }

    public function sizes()
    {
        return $this->hasMany('App\Models\ProductSize','product_id',$this->primaryKey);
    }

    public function minSize()
    {
        return $this->hasMany('App\Models\ProductSize','product_id',$this->primaryKey)
        ->where('product_sizes.price',$this->sizes->min('price'));
    }


    public function images()
    {
        return $this->hasMany('App\Models\ProductImage','product_id',$this->primaryKey);
    }
}
