<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetalis extends Model
{
    protected $table = 'order_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id','order_id','product_id','size','size_id','quantity','price','mrp'
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id',$this->primaryKey);
    }
    public function productSizes()
    {
        return $this->belongsTo('App\Models\ProductSize','size_id',$this->primaryKey);
    }
}
