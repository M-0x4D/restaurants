<?php

namespace App\Http\Resources;

use App\Helper\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class MealDrinkResource extends JsonResource
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
            'id' => $this->drink->id,
            'image' => Helper::getFullPath($this->drink->image),
            'name' => $this->drink->translation->name,
            'price' => (double) $this->drink->price_value,
            'currency' => 'EGP',
          ];
    }
}
