<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use\App\Http\Resources\ProductSizeResource;

class RelatedProductResource extends JsonResource
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
            'product_id' => $this->product->id,
            'name' => $this->product->name,
            'main_image' => $this->product->main_image,
            'mrp' => $this->product->mrp,
            'min_price' => $this->product->min_price,
            'stock' => $this->product->stock,
            'product_type' => $this->product->product_type,
            'sizes' => ProductSizeResource::collection($this->product->sizes),
        ];
    }
}
