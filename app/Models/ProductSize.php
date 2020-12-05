<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{    
    protected $table = 'product_sizes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'size_type_id', 'product_id','size','mrp', 'price','min_ord_quantity','stock',
    ];

    public function sizeType()
    {
        return $this->belongsTo('App\Models\SizeType','size_type_id',$this->primaryKey);
    }
}
