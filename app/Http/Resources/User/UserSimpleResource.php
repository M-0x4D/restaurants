<?php

namespace App\Http\Resources\User;

use App\Helper\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSimpleResource extends JsonResource
{

    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'country_code' => $this->country_code,
            'phone' => $this->phone,
            'address' => (count($this->addresses ?? [])) ? ($this->addresses()->whereDefault(1)->first()->address ?? null) : null,
            'lang' => $this->lang,
            'avatar'=> Helper::getFullPath($this->profile->avatar ?? 'avatar/avatar.png'), //asset('https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y'),
            'token' => $this->when($this->token,$this->token),
        ];
    }
}
