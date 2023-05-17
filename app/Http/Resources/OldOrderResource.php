<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OldOrderResource extends JsonResource
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
            'sub_total' => $this->sub_total,
            'delivery_fees' => $this->delivery_fees,
            'total' => $this->total,
            'driver' => new UserResource($this->whenLoaded('driver')),
            //'driver.profile' => new ProfileResource($this->whenLoaded('driver.profile')),
            'user' => new UserResource($this->whenLoaded('user')),
            //'user.profile' => new ProfileResource($this->whenLoaded('user.profile')),
            'address' => $this->user->addresses()->whereDefault(1)->first(),
            'payment_type'=>$this->payment_type,
            'status' => $this->status,
        ];
    }
}
