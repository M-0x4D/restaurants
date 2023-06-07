<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\FastDeliveryRestaurantForHomeResource;
use App\Http\Resources\NearbyRestaurantForHomeResource;
use App\Http\Resources\RecommendedMealForHomeResource;
use App\Http\Resources\OfferForHomeResource;
use App\Http\Resources\PopularRestaurantForHomeResource;
use App\Models\Category;
use App\Models\Meal;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {


        $categories = CategoryResource::collection(Category::cursor());
        $offers = OfferForHomeResource::collection(Offer::cursor());
        $popularRestaurants = PopularRestaurantForHomeResource::collection(Restaurant::withAvg('reviews', 'rating')->orderByDesc('reviews_avg_rating')->get());
        $recommendedMeals = RecommendedMealForHomeResource::collection(Meal::withAvg('reviews', 'rating')->orderByDesc('reviews_avg_rating')->get());
        $fastDelivery = FastDeliveryRestaurantForHomeResource::collection(Restaurant::orderBy('delivery_time')->get());
        $avatar = $this->getUserAvatar();
        //        return response()->json(['data' => $avatar]);

        // dd((float)request()->header('lat') == 0);
        if (request()->header('lat') != 0 || request()->header('lng') != 0) {

            $nearestRestaurants = Restaurant::select(
                "restaurants.id",
                "restaurants.address",
                "restaurants.image",
                "restaurants.cover",
                "restaurants.delivery_time",
                "restaurants.delivery_fees"
                , DB::raw("6371 * acos(cos(radians(" . request()->header('lat') . "))
        * cos(radians(restaurants.lat))
        * cos(radians(restaurants.lng) - radians(" . request()->header('lng') . "))
        + sin(radians(" . request()->header('lat') . "))
        * sin(radians(restaurants.lat))) AS distance")
            )
                ->groupBy("restaurants.id", "restaurants.lat", "restaurants.lng", "restaurants.address", "restaurants.image", "restaurants.cover", "restaurants.delivery_time", "restaurants.delivery_fees")
                ->OrderBy('distance')
                ->cursor();

            $nearbyRestaurants = NearbyRestaurantForHomeResource::collection($nearestRestaurants);
        }

        $data = [
            'categories' => $categories,
            'offers' => $offers,
            'popular_restaurants' => $popularRestaurants,
            'recommended_meals' => $recommendedMeals,
            'fast_delivery' => $fastDelivery,
            'nearby_restaurants' => $nearbyRestaurants ?? [],
                           'avatar' => $avatar,
               'reservations' => null
        ];

        return Helper::responseJson(200, 'success', null, null, $data, 200);
        //         return response()->json([
//             'status' => 200,
//             'message' => null,
//             'errors' => null,
//             'result' => 'success',
//             'data' => [
//                 'categories' => $categories,pos
//                 'offers' => $offers,
//                 'popular_restaurants' => $popularRestaurants,
//                 'recommended_meals' => $recommendedMeals,
//                 'fast_delivery' => $fastDelivery,
//                 'nearby_restaurants' => $nearbyRestaurants ?? [],
// //                'avatar' => $avatar,
// //                'reservations' => null
//             ]
//         ], 200);
    }

    public function indexAuth()
    {
        return $this->index();
    }

    public function getUserAvatar()
    {
        return Helper::getFullPath(Auth::user()->profile->avatar ?? 'avatar/avatar.png');
    }

    public function test(Request $request)
    {
        $cart = Order::where(['user_id' => auth()->id(), 'status' => 'pending'])->first();

        if (!$cart) {
            return Helper::responseJson(422, 'failed', null, __('orders.no_data'), null, 422);

            // return response()->json([
            //     'status' => 422,
            //     'message' => null,
            //     'errors' => __('orders.no_data'),
            //     'result' => 'failed',
            //     'data' => null
            // ], 422);
        }

        $cart->update(['status' => $request->status]);
        return Helper::responseJson(200, 'success', null, __('orders.cart_added_success'), [], 200);
        
        // return response()->json([
        //     'status' => 200,
        //     'message' => __('orders.cart_added_success'),
        //     'errors' => null,
        //     'result' => 'success',
        //     'data' => []
        // ], 200);
    }
}