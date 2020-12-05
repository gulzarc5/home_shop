<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\OrderDetailsResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'order_id' => $this->id,
            'amount' => $this->amount,
            'shipping_charge' => $this->shipping_charge,
            'total_amount' => $this->amount+$this->shipping_charge,
            'order_type' => $this->order_type,
            'delivery_type' => $this->delivery_type,
            'payment_type' => $this->payment_type,
            'payment_status' => $this->payment_type,
            'delivery_status' => $this->delivery_status,
            'is_refund' => $this->is_refund,
            'date' => $this->created_at->format('Y-m-d H:i:s'),
            'items' => OrderDetailsResource::collection($this->orderDetails),
        ];
    }
}
