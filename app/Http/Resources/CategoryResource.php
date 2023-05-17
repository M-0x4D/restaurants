<?php

namespace App\Http\Resources;

use App\Helper\Helper;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class CategoryResource extends JsonResource
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
            'name' => $this->translation->name,
            'image' => Helper::getFullPath($this->image),
            'color' => $this->color,
            'border_color' => $this->border_color
        ];
    }
}
