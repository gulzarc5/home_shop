<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use\App\Http\Resources\ProductSizeResource;

class CartProductResource extends JsonResource
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
            'product_id' => $this->id,
            'name' => $this->name,
            'main_image' => $this->main_image,
            'mrp' => $this->mrp,
            'min_price' => $this->min_price,
            'stock' => $this->stock,
            'product_type' => $this->product_type,
            'sizes' => ProductSizeResource::collection($this->sizes),
        ];
    }
}
