<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordRequest extends Model
{
    protected $table = 'password_request';
    protected $fillable = [
        'user_id','status'
    ];

    public function customer()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }
}
