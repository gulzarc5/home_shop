<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductSizeResource extends JsonResource
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
            'size_type_id' => $this->size_type_id,
            'size' => $this->size,
            'mrp' => $this->mrp,
            'price' => $this->price,
            'min_ord_quantity' => $this->min_ord_quantity,
            'stock' => $this->stock,
        ];
    }
}
