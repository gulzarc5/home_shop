<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailsResource extends JsonResource
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
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product_name' => $this->product->name,
            'product_type' => $this->product->product_type,
            'size_type_id' => isset($this->productSizes->size_type_id) ? $this->productSizes->size_type_id :'',
            'product_image' => $this->product->main_image,
            'size' => $this->size,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'mrp' => $this->mrp,
        ];
    }
}
