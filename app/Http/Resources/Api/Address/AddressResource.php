<?php

namespace App\Http\Resources\Api\Address;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'country_code' =>  $this->country_code,
            'phone'=> $this->phone,
            'address' => $this->address,
            'lat' => (double) $this->lat,
            'lng' => (double) $this->lng,
            'default' => $this->default,

        ];
    }
}
