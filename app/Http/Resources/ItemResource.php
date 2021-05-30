<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
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
            "status" => $this->status,
            "quantity" => $this->quantity,
            "price" => $this->price,
            "total_price" => number_format($this->price * $this->quantity, 2),
            "product_id" => $this->product_id,
            "product_name" => Product::findOrFail($this->product_id)->name,
            "unit_id" => $this->unit_id,
            "unit_symbol" => Unit::findOrFail($this->unit_id)->symbol,
            "units" => UnitIndexResource::collection(Product::findOrFail($this->product_id)->units),
            "basket_id" => $this->basket_id, // 1
        ];
    }
}
