<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopsBasketsItemsResource extends JsonResource
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
            "id" => $this->id, // 1,
            // "created_at" => $this-> , // "2021-05-21T13:54:06.000000Z",
            // "updated_at" => $this-> , // "2021-05-21T13:54:06.000000Z",
            "status" => $this->status, // "0",
            "quantity" => $this->quantity, // "0.00",
            "price" => $this->price, // "40.03",
            "total_price" => number_format($this->price * $this->quantity, 2), // "40.03",
            "product_id" => $this->product_id, // 1,
            "product_name" => Product::findOrFail($this->product_id)->name, // 1,
            "unit_id" => $this->unit_id, // 1,
            "unit_symbol" => Unit::findOrFail($this->unit_id)->symbol,
            "units" => UnitIndexResource::collection(Product::findOrFail($this->product_id)->units),
        ];
    }
}
