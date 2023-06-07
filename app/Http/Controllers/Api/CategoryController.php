<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\MealForCatResource;
use App\Http\Resources\MealResource;
use App\Http\Resources\MealOfferForCatResource;
use App\Http\Resources\RestaurantForCatResource;
use App\Http\Resources\RestaurantResource;
use App\Models\Category;
use App\Models\Meal;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Helper\Helper;

class CategoryController extends Controller
{
    public function index()
    {
        return Helper::responseJson(200 , 'success' , null ,null ,["category" => CategoryResource::collection(Category::all())] , 200);
        // return response()->json([
        //     'status' => 200,
        //     'message' => null,
        //     'errors' => null,
        //     'result' => 'success',
        //     'data' => ["category" => CategoryResource::collection(Category::all())]
        // ], 200);
    }

    public function minions(Request $request)
    {
//        $category = Category::find($request->category_id);
        $filters = ['restaurants', 'meals', 'offers'];

        if (!in_array($request->filter, $filters)){
        return Helper::responseJson(422 , 'failed' , null ,['default' => __('categories.no_data')] ,null, 422);

            // return response()->json([
            //     'status' => 422,
            //     'message' => null,
            //     'errors' => ['default' => __('categories.no_data')],
            //     'result' => 'failed',
            //     'data' => null
            // ], 422);
        }

        if ($request->filter == 'restaurants'){
            $restaurants = Restaurant::whereCategoryId($request->category_id)->cursor();
            $data = RestaurantForCatResource::collection($restaurants);
            return Helper::responseJson(200 , 'success' , null ,['default' => __('categories.no_data')] ,["$request->filter" => $data], 200);

            // return response()->json([
            //     'status' => 200,
            //     'message' => null,
            //     'errors' => null,
            //     'result' => 'success',
            //     'data' => ["$request->filter" => $data]
            // ], 200);
        }

        if ($request->filter == 'meals' || $request->filter == 'offers'){
            $data = [];
            $meals = Meal::where('category_id', $request->category_id);

            if ($request->filter == 'meals'){
                $meals->where('is_offer', '0');
                $meals = $meals->cursor();
                $data = MealForCatResource::collection($meals);
            }
            if ($request->filter == 'offers'){
                $meals->where('is_offer', 1);
                $meals = $meals->cursor();
                $data = MealOfferForCatResource::collection($meals);
            }
            return Helper::responseJson(200 , 'success' , null ,null ,["$request->filter" => $data], 200);


            // return response()->json([
            //     'status' => 200,
            //     'message' => null,
            //     'errors' => null,
            //     'result' => 'success',
            //     'data' => ["$request->filter" => $data]
            // ], 200);
        }

    }

    public function authMinions(Request $request)
    {
        return $this->minions($request);
    }
}
