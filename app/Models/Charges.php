<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Charges extends Model
{

    protected $table = 'charges';
    protected $primaryKey = 'id';
    protected $fillable = [
        'description','amount','status'
    ];
}
