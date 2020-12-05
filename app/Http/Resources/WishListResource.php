<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use\App\Http\Resources\ProductResource;

class WishListResource extends JsonResource
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
            'wish_list_id' => $this->id,
            'user_id' => $this->user_id,
            'product' => new ProductResource($this->product),
        ];
    }
}
