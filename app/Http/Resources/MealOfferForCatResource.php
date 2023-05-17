<?php

namespace App\Http\Resources;

use App\Helper\Helper;
use App\Models\Review;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Builder
 */
class MealOfferForCatResource extends JsonResource
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
            'meal_id' => $this->id,
            'name' => $this->offer->name,
            'description' => Helper::stripText($this->offer->description),
            'image' => Helper::getFullPath($this->offer->image),
            'color' => $this->offer->color,
            'percentage' => $this->offer->percentage.' %',

        ];
    }
}
