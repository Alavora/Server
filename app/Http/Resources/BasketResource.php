<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BasketResource extends JsonResource
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
            'comments' => $this->comments,
            'closed' => $this->closed,
            'status' => $this->status,
            'shop_id' => $this->shop_id,
            'user_id' => $this->user_id,
            'distributor_id' => $this->distributor_id,
            'items' => ItemResource::collection($this->items),
            'user' => new UserResource($this->user),
        ];
    }
}
