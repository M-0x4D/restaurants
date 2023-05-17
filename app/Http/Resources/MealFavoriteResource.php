<?php

namespace App\Http\Resources;

use App\Helper\Helper;
use App\Models\Meal;
use App\Models\Review;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;

class MealFavoriteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $meal = Meal::find($this->meal_id);
        return [
            'meal_id' => $this->meal_id,
            'name' => $this->name,
            'description' => Helper::stripText($this->description),
            'image' => Helper::getFullPath($this->image),
            'rating' => (double) $this->avg_rate ?? 0,
            'currency' => 'EGP',
            'price' => (double) $this->price,
            'favorite' => $meal->favorites()->whereUserId((auth()->user()->id ?? null))->exists() ? 1 : 0,
        ];
    }

}
