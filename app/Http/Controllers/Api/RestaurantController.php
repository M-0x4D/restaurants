<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\RestaurantDetailsResource;
use App\Http\Resources\RestaurantFavoriteResource;
use App\Http\Resources\RestaurantResource;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Restaurant;
use Illuminate\Support\Facades\DB;

class RestaurantController extends Controller
{
    public function index(Category $category)
    {
return Helper::responseJson(200 , 'success' , 'Restaurants Retrieved Successfully' ,['default' => __('restaurants.no_data')] , RestaurantResource::collection(Restaurant::whereCategoryId($category->id)->get()) , 200 );
        // return response()->json(['message' => 'Restaurants Retrieved Successfully', 'status' => 200, 'data' => RestaurantResource::collection(Restaurant::whereCategoryId($category->id)->get())], 200);
    }

    /**
     * Get restaurant details by id
     * @param $restaurant_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($restaurant_id)
    {
                        if(request()->header('lat') != 0  || request()->header('lng') != 0)
{
        $restaurant = Restaurant::with(['tags','reviews','weekHours'])->whereId($restaurant_id)->first();

        if (!$restaurant){
            return Helper::responseJson(422 , 'failed' , null ,['default' => __('restaurants.no_data')] , null , 422 );
            // return response()->json([
            //     'status' => 422,
            //     'message' => null,
            //     'errors' => ['default' => __('restaurants.no_data')],
            //     'result' => 'failed',
            //     'data' => null
            // ], 422);
        }
            return Helper::responseJson(200 , 'success' , __('restaurants.data_retrieved_success') ,['default' => __('restaurants.no_data')] , ['restaurant' => RestaurantDetailsResource::make($restaurant)] , 200 );

        // return response()->json([
        //     'status' => 200,
        //     'message' => __('restaurants.data_retrieved_success'),
        //     'errors' => null,
        //     'result' => 'success',
        //     'data' => ['restaurant' => RestaurantDetailsResource::make($restaurant)]
        // ], 200);
        
}

else return Helper::responseJson(422 , 'failed' , '' , '' , null , 422);
    }

    public function authShow($restaurant_id)
    {
        return $this->show($restaurant_id);
    }

    public function popularRestaurants()
    {
         $restaurant=Restaurant::withAvg('reviews', 'rating')->latest()->paginate(15);
         return Helper::responseJson(200 , 'success' , __('restaurants.data_retrieved_success') ,null , ['restaurant' => RestaurantResource::collection($restaurant)->response()->getData(true)] , 200 );

        // return response()->json([
        //     'status' => 200,
        //     'message' => __('restaurants.data_retrieved_success'),
        //     'errors' => null,
        //     'result' => 'success',
        //     'data' => ['restaurant' => RestaurantResource::collection($restaurant)->response()->getData(true)]
        // ], 200);
    }

    public function fastDelivery()
    {
        $restaurant=Restaurant::orderBy('delivery_time')->latest()->paginate(10);
        return Helper::responseJson(200 , 'success' , __('restaurants.data_retrieved_success') ,null , ['restaurant' => RestaurantResource::collection($restaurant)->response()->getData(true)] , 200 );

        // return response()->json([
        //     'status' => 200,
        //     'message' => __('restaurants.data_retrieved_success'),
        //     'errors' => null,
        //     'result' => 'success',
        //     'data' => ['restaurant' => RestaurantResource::collection($restaurant)->response()->getData(true)]
        // ], 200);

    }

    public function nearestRestaurants()
    {
        
                if(request()->header('lat') != 0  || request()->header('lng') != 0)
{
        // Get Nearest Restaurants
        $nearestRestaurants = Restaurant::join('restaurant_translations' , 'restaurants.id' , '=' , 'restaurant_translations.restaurant_id')->select("restaurants.id","restaurant_translations.name","restaurants.address","restaurant_translations.description","restaurants.image","restaurants.cover","restaurants.delivery_time","restaurants.delivery_fees","restaurants.lat","restaurants.lng"
        ,DB::raw("6371 * acos(cos(radians(" . request()->header('lat') . "))
        * cos(radians(restaurants.lat))
        * cos(radians(restaurants.lng) - radians(" . request()->header('lng') . "))
        + sin(radians(" . request()->header('lat') . "))
        * sin(radians(restaurants.lat))) AS distance"))
        ->groupBy("restaurants.id","restaurants.lat","restaurants.lng","restaurant_translations.name","restaurants.address","restaurant_translations.description","restaurants.image","restaurants.cover","restaurants.delivery_time","restaurants.delivery_fees")
        ->OrderBy('distance')
        ->get();

        $data = [ 'restaurants' =>  RestaurantResource::collection($nearestRestaurants)];

        return Helper::responseJson(200, 'success', 'Nearest Restaurants Retrieved Successfully', null,$data, 200);
}

else return Helper::responseJson(422 , 'failed' , '' , '' , null , 422);

    }

    public function addToFavorite(Restaurant $restaurant)
    {
        if ($restaurant->favorites()->whereUserId(auth()->user()->id)->exists()) {
            $restaurant->favorites()->whereUserId(auth()->user()->id)->delete();
            $message = __('restaurants.favorite_deleted_successfully');
        } else {
            $restaurant->favorites()->whereUserId(auth()->user()->id)->create(['user_id' => auth()->user()->id]);
            $message = __('restaurants.favorite_added_successfully');
        }

        $langId = Helper::currentLanguage()->id;
        $favorites = Favorite::where(['user_id' => auth()->id(),'favoriteable_type' => 'App\Models\Restaurant'])
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
            return Helper::responseJson(200, 'success', $message, null,['restaurants' => RestaurantFavoriteResource::collection($favorites)], 200);

        // return response()->json([
        //     'status' => 200,
        //     'message' => $message,
        //     'errors' => null,
        //     'result' => 'success',
        //     'data' => ['restaurants' => RestaurantFavoriteResource::collection($favorites)]
        // ], 200);

    }

    public function authPopularRestaurants()
    {
        return $this->popularRestaurants();
    }

    public function authFastDelivery()
    {
        return $this->fastDelivery();
    }
}
