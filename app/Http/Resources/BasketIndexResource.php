<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BasketIndexResource extends JsonResource
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
            "id" => $this->id, // 1,
            "comments" => $this->comments, // "Lorem Ipsumjjjjjjjjjjjjjjj",
            "closed" => $this->closed, // null,
            "status" => $this->status, // "1",
            "shop_id" => $this->shop_id, // 1,
            "user_id" => $this->user_id, // 1,
            "distributor_id" => $this->distributor_id, // null
            "items" => ItemResource::collection($this->items),
            "size" => count($this->items),
        ];
    }
}
