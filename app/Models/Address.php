<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'address';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id', 'name', 'state','city','email','mobile','address','pin','latitude','longtitude'
    ];
}
