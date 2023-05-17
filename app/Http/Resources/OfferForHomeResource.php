<?php

namespace App\Http\Resources;

use App\Helper\Helper;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferForHomeResource extends JsonResource
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
            'description' => Helper::stripText($this->description),
            'image' => Helper::getFullPath($this->image),
            'color' => $this->color,
            'percentage' => $this->percentage.' %',
        ];
    }
}
