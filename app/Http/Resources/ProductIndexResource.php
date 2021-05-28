<?php

namespace App\Http\Resources;

use App\Models\Unit;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductIndexResource extends JsonResource
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
            "image_url" => $this->image_url,
            "shop_id" => $this->shop_id,
            "units" => UnitProductResource::collection($this->units),
        ];
    }
}
