<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopsBasketsResource extends JsonResource
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
            "shop_name" => $this->shop->name,
            "shop_id" => $this->shop->id,
            "items" => ItemResource::collection($this->items),
        ];
    }
}
