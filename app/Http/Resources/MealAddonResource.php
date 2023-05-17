<?php

namespace App\Http\Resources;

use App\Helper\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class MealAddonResource extends JsonResource
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
            'id' => $this->addon->id,
            'image' => Helper::getFullPath($this->addon->image),
            'name' => $this->addon->name,
            'price' => (double) $this->addon->price,
            'currency' => 'EGP',
            'qty' =>$this->addon->qty
          ];
    }
}
