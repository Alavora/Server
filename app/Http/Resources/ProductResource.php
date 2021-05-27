<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            "id" => $this->id,
            "name" => $this->name,
            "price" => $this->price,
            // "shop" => new ShopResource($this->shop),
            "shop" => new ShopResource($this->shop),
            "units" => $this->units,
            // "units" => new UnitProductResource($this->units),
            // "units" => new UnitResource($this->units),
        ];
    }
}
