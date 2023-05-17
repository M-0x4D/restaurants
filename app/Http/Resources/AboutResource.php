<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AboutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $local = app()->getLocale();
        return[
            'id' => $this->id,
            'name' => $this['name_'.$local],
            'description' => $this['description_'.$local],
        ];
    }
}
