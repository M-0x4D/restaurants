<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\MealFavoriteResource;
use App\Http\Resources\MealResource;
use App\Http\Resources\OfferResource;
use App\Http\Resources\SideResource;
use App\Models\Category;
use App\Models\Drink;
use App\Models\Favorite;
use App\Models\Meal;
use App\Models\Offer;
use App\Models\Restaurant;
use App\Models\Side;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class MealController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->restaurant_id || !$request->sub_tag_id) {
            return Helper::responseJson(422, 'failed', null, ['default' => __('meals.no_data')], null, 422);
            // return response()->json([
            //     'status' => 422,
            //     'message' => null,
            //     'errors' => ['default' => __('meals.no_data')],
            //     'result' => 'failed',
            //     'data' => null
            // ], 422);
        }
        $meals = Meal::where(['restaurant_id' => $request->restaurant_id, 'tag_id' => $request->sub_tag_id])->join('meal_translations', 'meals.id', '=', 'meal_translations.meal_id')->where('language_id', Helper::currentLanguage(App::getLocale())->id)->get();
        if (!count($meals)) {
            return Helper::responseJson(422, 'failed', null, ['default' => __('meals.no_data')], null, 422);
            // return response()->json([
            //     'status' => 422,
            //     'message' => null,
            //     'errors' => ['default' => __('meals.no_data')],
            //     'result' => 'failed',
            //     'data' => null
            // ], 422);
        }
        return Helper::responseJson(200, 'success', __('meals.data_retrieved_success'), null, ['meal' => !$meals ? [] : MealResource::collection($meals)], 200);

        // return response()->json([
        //     'status' => 200,
        //     'message' => __('meals.data_retrieved_success'),
        //     'errors' => null,
        //     'result' => 'success',
        //     'data' => ['meal' => !$meals ? []: MealResource::collection($meals)]
        // ], 200);
    }

    public function show(Request $request)
    {
        $meal = Meal::join('meal_translations', 'meals.id', '=', 'meal_translations.meal_id')->with(['features', 'ingredients', 'sizes', 'addons'])->find($request->meal_id);
        if (!$meal) {
            return Helper::responseJson(422, 'failed', null, ['default' => __('meals.no_data')], null, 422);
            // return response()->json([
            //     'status' => 422,
            //     'message' => null,
            //     'errors' => ['default' => __('meals.no_data')],
            //     'result' => 'failed',
            //     'data' => null
            // ], 422);
        }

        $sides = Side::cursor();
        $sides = SideResource::collection($sides);

        return Helper::responseJson(200, 'success', __('meals.data_retrieved_success'), null, ['meal' => MealResource::make($meal), 'sides' => $sides], 200);

        // return response()->json([
        //     'status' => 200,
        //     'message' => __('meals.data_retrieved_success'),
        //     'errors' => null,
        //     'result' => 'success',
        //     'data' => ['meal' => MealResource::make($meal), 'sides' => $sides]
        // ], 200);
    }

    public function authIndex(Request $request)
    {
        return $this->index($request);
    }

    public function authShow(Request $request)
    {
        return $this->show($request);
    }

    public function popularMeals()
    {
        $meals = Meal::join('meal_translations', 'meals.id', '=', 'meal_translations.meal_id')->where('language_id', Helper::currentLanguage(App::getLocale())->id)->paginate(15);
        // dd($meals);

        // withAvg('reviews', 'rating')->orderByDesc('reviews_avg_rating')->latest()->
        return Helper::responseJson(200, 'success', __('meals.data_retrieved_success'), null, ['meal' => MealResource::collection($meals)->response()->getData(true)], 200);
        // return response()->json([
        //     'status' => 200,
        //     'message' => __('meals.data_retrieved_success'),
        //     'errors' => null,
        //     'result' => 'success',
        //     'data' => ['meal' => MealResource::collection($meals)->response()->getData(true)]
        // ], 200);
    }

    public function authPopularMeals()
    {
        return $this->popularMeals();
    }

    public function recommended(Category $category)
    {
        $restaurants = Restaurant::whereCategoryId($category->id)->get();

        foreach ($restaurants as $restaurant) {
            $recommendedMealsByRestaurant = Meal::whereRestaurantId($restaurant->id)->withAvg('reviews', 'rating')->orderByDesc('reviews_avg_rating')->get();
        }
        return Helper::responseJson(200, 'success', 'Meals Retrieved Successfully', null, MealResource::collection($recommendedMealsByRestaurant), 200);

        // return response()->json(['message' => 'Meals Retrieved Successfully', 'status' => 200, 'data' => MealResource::collection($recommendedMealsByRestaurant)], 200);
    }

    public function mealOffers(Category $category)
    {
        return Helper::responseJson(200, 'success', 'Offers Retrieved Successfully', null, OfferResource::collection(Offer::whereCategoryId($category->id)->get()), 200);
        // return response()->json(['message' => 'Offers Retrieved Successfully', 'status' => 200, 'data' =>
        // OfferResource::collection(Offer::whereCategoryId($category->id)->get())
        // ], 200);
    }

    public function addToFavorite(Meal $meal)
    {
        if ($meal->favorites()->whereUserId(auth()->user()->id)->exists()) {
            $meal->favorites()->whereUserId(auth()->user()->id)->delete();
            $message = __('meals.favorite_deleted_successfully');

        } else {
            $meal->favorites()->whereUserId(auth()->user()->id)->create(['user_id' => auth()->user()->id]);
            $message = __('meals.favorite_added_successfully');
        }

        $langId = Helper::currentLanguage()->id;
        $favorites = Favorite::where(['user_id' => auth()->id(), 'favoriteable_type' => 'App\Models\Meal'])
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

        return Helper::responseJson(200, 'success', $message, null, ['meals' => MealFavoriteResource::collection($favorites)], 200);
        // return response()->json([
        //     'status' => 200,
        //     'message' => $message,
        //     'errors' => null,
        //     'result' => 'success',
        //     'data' => ['meals' => MealFavoriteResource::collection($favorites)]
        // ], 200);

    }
}