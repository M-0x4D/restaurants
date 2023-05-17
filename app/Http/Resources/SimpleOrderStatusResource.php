<?php

namespace App\Http\Resources;

use App\Http\Resources\SimpleDriverResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SimpleOrderStatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'order_id' => $this->id,
            'status' => StatusResource::collection($this->statuses),
            'driver'=>new SimpleDriverResource($this->driver),




        ];
    }
}
