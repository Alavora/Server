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
            'id' => $this->id, //: 1,
            // 'created_at' => $this->, //: "2021-05-30T17:55:33.000000Z",
            // 'updated_at' => $this->, //: "2021-05-30T17:58:38.000000Z",
            'comments' => $this->comments, //: "",
            'closed' => $this->closed, //: null,
            'status' => $this->status, //: "1",
            'shop_id' => $this->shop_id, //: 1,
            'user_id' => $this->user_id, //: 11,
            'distributor_id' => $this->distributor_id, //: null
            'items' => ItemResource::collection($this->items),
            'user' => new UserResource($this->user),
        ];
    }
}
