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
            'basket_id' => $this->id,
            "shop_name" => $this->shop->name,
            "shop_id" => $this->shop->id,
            "items" => ShopsBasketsItemsResource::collection($this->items),
        ];
    }
}
