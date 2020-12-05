<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{

    protected $table = 'app_setting';
    protected $primaryKey = 'id';
    protected $fillable = [
        'image','status','slider_type'
    ];
}
