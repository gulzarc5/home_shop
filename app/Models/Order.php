<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id','amount','shipping_charge','payment_request_id','payment_id','shipping_address_id','order_type','delivery_type','payment_type','payment_status','delivery_status','bulk_order_id','delivery_boy_id','assign_date'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id',$this->primaryKey);
    }

    public function deliveryBoy()
    {
        return $this->belongsTo('App\Models\DeliveryBoy','delivery_boy_id',$this->primaryKey);
    }

    public function shippingAddress()
    {
        return $this->belongsTo('App\Models\Address','shipping_address_id',$this->primaryKey);
    }

    public function orderDetails()
    {
        return $this->hasMany('App\Models\OrderDetalis','order_id',$this->primaryKey);
    }
}
