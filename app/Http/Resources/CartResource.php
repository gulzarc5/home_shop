<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use\App\Http\Resources\CartProductResource;

class CartResource extends JsonResource
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
            'cart_id' => $this->id,
            'user_id' => $this->user_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'size_id' => $this->size_id,
            'product' => new CartProductResource($this->product),
        ];
    }
}
