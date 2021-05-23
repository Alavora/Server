<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UnitProductResource extends JsonResource
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
            'value' => $this->name,
            'viewValue' => $this->symbol,
            'price' => $this->pivot->price,
            'name' => $this->name,
            'symbol' => $this->symbol,
        ];
    }
}
