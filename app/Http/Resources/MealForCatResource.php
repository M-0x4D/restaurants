<?php

namespace App\Http\Resources;

use App\Helper\Helper;
use App\Models\Review;
use App\Models\Meal;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Builder
 */
class MealForCatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $allMedia = [];
        $image = null;
        
        // dd($this);

        // $meal = Meal::where('id' , $this->meal_id)->first();

        if (count($this->media()->get())){
            $medias = $this->media()->get()->toArray();
            $image = $this->media()->where('default', 1)->first()->media ?? null;
            foreach ($medias as $media) {
                $allMedia[] = $media['media'];
            }
        }

        $mealImage = $image ?? $allMedia? array_rand($allMedia) : '';

        return [
            'id' => $this->id,
            'name' => $this->translation->name ?? null,
            'description' => Helper::stripText($this->description),
            'price' => (double) $this->price,
            'currency' => 'EGP',
            'image' => Helper::getFullPath($mealImage),
            'rating' => empty($this->reviews) || $this->reviews->pluck('rating')->count() == 0 ?  0 : round($this->reviews->pluck('rating')->sum() / $this->reviews->pluck('rating')->count(), 1),
            'favorite' => $this->favorites()->whereUserId((auth()->user()->id ?? null))->exists() ? 1 : 0,
        ];
    }
}
