<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchHistory\SearchHistoryRequest;
use App\Http\Resources\MealResource;
use App\Http\Resources\SearchHistory\SearchHistoryResource;
use App\Models\Meal;
use App\Models\SearchHistory;
use Illuminate\Http\Request;
use App\Helper\Helper;
use Illuminate\Support\Facades\App;


class SearchHistoryController extends Controller
{

    //store keyword search
    public function store(SearchHistoryRequest $request){
        auth()->user()->searchHistories()->updateOrCreate(['keyword' => $request->keyword , 'user_id' => auth()->id()],['keyword' => $request->keyword , 'user_id' => auth('api')->id()]);
        return response()->json(['status' => 1, 'message' =>'', 'data' => null], 200);

    }

    public function recentSearches(){
        $searches = SearchHistory::where('user_id', auth()->id())
            ->latest()->take(10)->get()->unique('keyword');
            return Helper::responseJson(200 , 'success' , __('meals.data_retrieved_success') , null ,['search' => SearchHistoryResource::collection($searches)] , 200 );
        // return response()->json([
        //     'status' => 200,
        //     'message' => __('meals.data_retrieved_success'),
        //     'errors' => null,
        //     'result' => 'success',
        //     'data' => ['search' => SearchHistoryResource::collection($searches)]
        // ], 200);

    }

    public function deleteSearches(){
        auth()->user()->searchHistories()->delete();
        return Helper::responseJson(200 , 'success' , __('meals.data_retrieved_success') , null ,[] , 200 );

        // return response()->json([
        //     'status' => 200,
        //     'message' => __('meals.data_retrieved_success'),
        //     'errors' => null,
        //     'result' => 'success',
        //     'data' => []
        // ], 200);
    }

    public function filter(Request $request)
    {
        $filteredMeals = [];
        $meals = Meal::when($request->keyword, function ($q) use ($request) {
            $q->join('meal_translations as mTrans', 'mTrans.meal_id', 'meals.id')->where('mTrans.name', 'LIKE', "%{$request->keyword}%")->orderBy('name', 'desc');
            
            if (auth()->check()){
                auth()->user()->searchHistories()->updateOrCreate(['keyword' => $request->keyword , 'user_id' => auth()->id()],['keyword' => $request->keyword , 'user_id' => auth()->id()]);
            }
        })->when($request->min_price && $request->max_price,function ($q) use ($request) {
                    $q->where(function ($q) use ($request) {
                        $min = $request->min_price;
                        $max = $request->max_price;
                        $q->whereBetween('price', [$min, $max]);
            });
        })->when($request->rate, function ($q) use ($request) {
            
           $q->where('avg_rate', '=', $request->rate);
        })->when($request->category_id,function ($q) use ($request) {
        $q->whereHas('restaurant', function ($query) use ($request) {
            $query->where('category_id', $request->category_id);
        });
        })->when($request->sorted_by, function ($q) use ($request) {
            if ($request->sorted_by == 'offers'){
                $q->where('is_offer', '=', 1);
            }
        })->paginate(15);
        
        if(!$request->all()['keyword']) $meals = [];

        return Helper::responseJson(200 , 'success' , __('meals.data_retrieved_success') , null , ['meal' => !$meals ? [] : MealResource::collection($meals)->response()->getData(true)] , 200);
        // return response()->json([
        //     'status' => 200,
        //     'message' => __('meals.data_retrieved_success'),
        //     'errors' => null,
        //     'result' => 'success',
        //     'data' => ['meal' => !$meals ? [] : MealResource::collection($meals)->response()->getData(true)]
        // ], 200);

//        return MealResource::collection($meals)->additional(['status' => 1, 'message' => '']);
    }

    public function filterAuth(Request $request)
    {
        return $this->filter($request);
    }

//return ProductResource::collection($products)->additional(['status' => 'success', 'message' => '']);
}
