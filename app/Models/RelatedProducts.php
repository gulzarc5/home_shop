<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelatedProducts extends Model
{
    protected $table = 'related_products';
    protected $primaryKey = 'id';
    protected $fillable = ['product_id','related_product_id'];

    public function product(){
        return $this->belongsTo("App\Models\Product",'related_product_id',$this->primaryKey);
    }    
}
