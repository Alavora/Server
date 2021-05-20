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
            // "created_at" => $this-> ,
            // "updated_at" => $this-> ,
            "name" => $this->name,
            "image_url" => $this->image_url,
            "price" => $this->price,
            "shop_id" => $this->shop_id,
            "units" => UnitIndexResource::collection($this->units),
            // "units" => $this->units,
        ];
    }
}
