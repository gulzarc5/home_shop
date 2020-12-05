<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProductSizeResource;
use App\Http\Resources\ProductImageResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'main_image' => $this->main_image,
            'description' => $this->description,
            'min_price' => $this->min_price,
            'mrp' => $this->mrp,
            'stock' => $this->stock,
            'product_type' => $this->product_type,
            'status' => $this->status,
            'sizes' => ProductSizeResource::collection($this->sizes),
            'images' => ProductImageResource::collection($this->images),
        ];
    }
}
