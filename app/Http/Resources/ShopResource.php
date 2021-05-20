<?php

namespace App\Http\Resources;

use App\Models\Market;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
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
            "cif" => $this->cif,
            "phone" => $this->phone,
            "address" => $this->address,
            // "market_id" => $this->market_id,
            "market" => new MarketResource($this->market),
        ];
    }
}
