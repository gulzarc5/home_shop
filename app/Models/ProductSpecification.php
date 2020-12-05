<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    protected $table = 'product_specification';
    protected $primaryKey = 'id';
    protected $fillable = [
        'product_id','name', 'value', 
    ];
}
