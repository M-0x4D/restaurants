<?php

namespace App\Http\Resources;

use App\Helper\Helper;
use App\Models\Review;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;
use App\Models\MealMedia;
use App\Models\Meal;


use App\Models\Language;
use App\Upload\Upload;
use Illuminate\Support\Facades\App;

/**
 * @mixin Builder
 */
class MealResource extends JsonResource
{
    protected $drinks = [];
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $reviews_count = Review::where('reviewable_type', 'App\Models\Meal')->where('reviewable_id', $this->id)->count();
        
        $meal = Meal::where('id' , $this->meal_id)->first();
        
    // dd($meal);
        $allMedia = [];
        $image = null;
        
        if(!$meal) return ;
        if (count($meal->media()->get() ?? [])){
            $medias = $meal->media()->get()->toArray();
            
            $image = $meal->media()->where('default', 1)->first()->media ?? null;
            $image = Helper::getFullPath($image);
            foreach ($medias as $media) {
                $allMedia[] = Helper::getFullPath($media['media']);
                
            }
        }
        $mealImage = $image ??  !empty($allMedia) ? $allMedia[0] : '';
        

        $route = Route::current()->uri;
        // $mealImage = MealMedia::where('meal_id' , $this->meal_id)->first()->media;
        // dd($meal->sizes);
        // dd($meal->drinks);

        
        for($i=0 ; $i < count($meal->drinks) ; $i++)
        {
            $drink = $meal->drinks[$i]->join('drink_translations' , 'drinks.id' , '=' , 'drink_translations.drink_id')->where('language_id' , Helper::currentLanguage(App::getLocale())->id)->where('drink_id' , $meal->drinks[$i]->id)->first();
            
            $drink->image = Helper::getFullPath($drink->image);
            $drink->id = $drink->drink_id;
            $drink->currency = "EGP";
            $drinks[] = $drink;

        }

        $ingredients = $meal->ingredients()->join('ingredient_translations' , 'ingredients.id' , '=' , 'ingredient_translations.ingredient_id')->where('language_id' , Helper::currentLanguage(App::getLocale())->id)->get();
        foreach ($ingredients as $ingredient) {
                $ingredient['image'] = Helper::getFullPath($ingredient['image']);
                $ingredient->id = $ingredient->ingredient_id;
            }
        
        
        $sizes = $meal->sizes()->join('size_translations' , 'sizes.id' , '=' , 'size_translations.size_id')->where('language_id' , Helper::currentLanguage(App::getLocale())->id)->get();
       
        
        foreach ($sizes as $size)
        {
            $size->id = $size->size_id;
        }
        
        // dd(count($meal->features()->get()));
        return [
            'id' => $this->meal_id,
            'restaurant_id' => $this->restaurant_id,
            'delivery_fees' => $meal->restaurant->delivery_fees ?? null,
            'tag_id' => $meal->tag_id,
            'name' => $meal->translation->name ?? null,
            'description' => Helper::stripText($meal->translation->description ?? '') ?? null,
            'price' => (double) $this->price,
            'currency' => 'EGP',
            'media' => $allMedia,
            'image' => $mealImage,
            'reviews_count' => $reviews_count,
            'reviews' => $this->whenLoaded('reviews'),
            'rating' => empty($meal->reviews) || $meal->reviews->pluck('rating')->count() == 0 ?  0 : round($meal->reviews->pluck('rating')->sum() / $meal->reviews->pluck('rating')->count(), 1),
            'favorite' => $meal->favorites()->whereUserId((auth()->user()->id ?? null))->exists() ? 1 : 0,
            'features' => $meal->features,//FeatureResource::collection($this->whenLoaded('features')),
            'calories' => count($meal->features()->get() ?? []) ?? 0,
            'ingredients' => $ingredients,//IngredientResource::collection($this->whenLoaded('ingredients')),
            'sizes' => $sizes ?? [],//SizeResource::collection($this->whenLoaded('sizes')),
//            'options' => OptionResource::collection($this->whenLoaded('options')),
            'drinks' => $drinks ?? [],//MealDrinkResource::collection($meal->drinks),
            'addons' => $meal->addons ? MealAddonResource::collection($meal->addons): null,
            'sides' => SideResource::collection($this->whenLoaded('sides')),
        ];
    }
}
