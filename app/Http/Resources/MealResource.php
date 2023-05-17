<?php

namespace App\Http\Resources;

use App\Helper\Helper;
use App\Models\Review;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;

/**
 * @mixin Builder
 */
class MealResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $reviews_count = Review::where('reviewable_type', 'App\Models\Meal')->where('reviewable_id', $this->id)->count();
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

        $mealImage = $image ??  array_rand($allMedia);

        $route = Route::current()->uri;

        return [
            'id' => $this->id,
            'restaurant_id' => $this->restaurant_id,
            'delivery_fees' => $this->restaurant->delivery_fees,
            'tag_id' => $this->tag_id,
            'name' => $this->translation->name,
            'description' => Helper::stripText($this->translation->description),
            'price' => (double) $this->price,
            'currency' => 'EGP',
            'media' => $allMedia,
            'image' => $mealImage,
            'reviews_count' => $reviews_count,
            'reviews' => $this->whenLoaded('reviews'),
            'rating' => empty($this->reviews) || $this->reviews->pluck('rating')->count() == 0 ?  0 : round($this->reviews->pluck('rating')->sum() / $this->reviews->pluck('rating')->count(), 1),
            'favorite' => $this->favorites()->whereUserId((auth()->user()->id ?? null))->exists() ? 1 : 0,
            'features' => FeatureResource::collection($this->whenLoaded('features')),
            'calories' => count($this->features()->get()) ? (int) $this->features()->get()->toArray()[0]['value'] : 0,
            'ingredients' => IngredientResource::collection($this->whenLoaded('ingredients')),
            'sizes' => SizeResource::collection($this->whenLoaded('sizes')),
//            'options' => OptionResource::collection($this->whenLoaded('options')),
            'drinks' => MealDrinkResource::collection($this->drinks),
            'addons' => $route !== 'api/get_popular_meals' ? MealAddonResource::collection($this->addons): null,
//            'sides' => SideResource::collection($this->whenLoaded('sides')),
        ];
    }
}
