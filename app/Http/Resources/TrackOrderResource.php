<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrackOrderResource extends JsonResource
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
            'id' => $this->id,
            'status' => __('orders.'.$this->status),
            'status_trans' => __('orders.'.$this->status),
            'driver' => new SimpleDriverResource($this->driver),
            'address'=> $this->when($this->address, new SimpleAddressResource($this->address))
        ];
    }
}
