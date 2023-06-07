<?php

namespace App\Http\Resources;

use App\Helper\Helper;
use App\Models\Review;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Builder
 */
class RecommendedMealForHomeResource extends JsonResource
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

        if (count($this->media()->get())){
            $medias = $this->media()->get()->toArray();
            $image = $this->media()->where('default', 1)->first()->media ?? null;
            $image = Helper::getFullPath($image);
            foreach ($medias as $media) {
                $allMedia[] = Helper::getFullPath($media['media']);
            }
        }

    if(!empty($allMedia)) $mealImage = $image ??  array_rand($allMedia);

        return [
            'id' => $this->id,
            'restaurant_id' => $this->restaurant->id ?? 0,
            'delivery_fees' => $this->restaurant->delivery_fees ?? 0,
            'name' => $this->translation->name ?? '',
            'description' => Helper::stripText($this->description) ?? '',
            'price' => (double) $this->price ?? 0,
            'currency' => 'EGP',
            'media' => $allMedia ?? [],
            'image' => $mealImage ?? '',
            'rating' => empty($this->reviews) || $this->reviews->pluck('rating')->count() == 0 ?  0 : round($this->reviews->pluck('rating')->sum() / $this->reviews->pluck('rating')->count(), 1),
            'favorite' => $this->favorites()->whereUserId((auth()->user()->id ?? null))->exists() ? 1 : 0,
        ];
    }
}
