<?php

namespace App\Http\Resources;

use App\Helper\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class AddonResource extends JsonResource
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
            'name' => $this->name,
            'image' => Helper::getFullPath($this->image),
            'price' => (double) $this->price,
            'currency' => 'EGP',
          ];
    }
}
