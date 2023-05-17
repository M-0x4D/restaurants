<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $array = [

            'user_name' =>$this->auth()->user()->name,
            'contact_title' =>$this->contact_title,
            'user_name'=>auth()->user()->name,
            'telephone' =>$this->telephone,
            'address' =>$this->address,

        ];



    }
}
