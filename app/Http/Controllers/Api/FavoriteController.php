<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\MealFavoriteResource;
use App\Http\Resources\MealResource;
use App\Http\Resources\RestaurantFavoriteResource;
use App\Http\Resources\RestaurantResource;
use App\Models\Favorite;
use App\Models\Meal;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index($sort)
    {
        if($sort == 'meal') {
//            $mealFavorites = auth()->user()->mealFavorites()->get();
            $langId = Helper::currentLanguage()->id;
            $mealFavorites = Favorite::where(['user_id' => auth()->id(),'favoriteable_type' => 'App\Models\Meal'])
                ->join('meals', 'meals.id', 'favorites.favoriteable_id')
                ->join('meal_media', 'meals.id', 'meal_media.meal_id')
                ->where('meal_media.default', 1)
                ->join('meal_translations as mTrans', 'meals.id', 'mTrans.meal_id')
                ->where(['mTrans.language_id' => $langId])
                ->select([
                    'favorites.favoriteable_id as meal_id',
                    'mTrans.name',
                    'mTrans.description',
                    'meal_media.media as image',
                    'meals.avg_rate',
                    'meals.price',
                ])->cursor();
            return response()->json([
                'status' => 200,
                'message' => __('meals.data_retrieved_success'),
                'errors' => null,
                'result' => 'success',
                'data' => ['meals' => MealFavoriteResource::collection($mealFavorites)]
            ], 200);
        }
        if($sort == 'restaurant') {
//            $restaurantFavorites = auth()->user()->restaurantFavorites()->cursor();
            $langId = Helper::currentLanguage()->id;
            $restaurantFavorites = Favorite::where(['user_id' => auth()->id(),'favoriteable_type' => 'App\Models\Restaurant'])
                ->join('restaurants', 'restaurants.id', 'favorites.favoriteable_id')
                ->join('restaurant_translations as rTrans', 'restaurants.id', 'rTrans.restaurant_id')
                ->where(['rTrans.language_id' => $langId])
                ->select([
                    'favorites.favoriteable_id as restaurant_id',
                    'rTrans.name',
                    'restaurants.address',
                    'restaurants.image',
                    'restaurants.delivery_time',
                    'restaurants.avg_rate',
                    'restaurants.delivery_fees',
                ])->cursor();

            return response()->json([
                'status' => 200,
                'message' => __('restaurants.data_retrieved_success'),
                'errors' => null,
                'result' => 'success',
                'data' => ['restaurants' => RestaurantFavoriteResource::collection($restaurantFavorites)]
            ], 200);
        }

    }
}
