<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Review\AddReviewRequest;
use App\Http\Resources\Review\ReviewResource;
use App\Models\Meal;
use App\Models\Order;
use App\Models\Review;
use App\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{ 

    public function add(AddReviewRequest $request)
    {
        $user = auth()->user();
        $reviewableType = 'App\Models\\'.ucfirst($request->type);
        $type = $request->type;
        $id = $request->reviewable_id;
        $isOrder = 0;
        /**
         * To prevent user add reviews
         * if not make restaurant order
         */
        if ($request->type == 'restaurant'){
            $isOrder = Order::where(['user_id' => $user->id, 'status' => 'finished', 'restaurant_id' => $request->reviewable_id])->count();
        }

        if ($request->type == 'meal'){
            $isOrder = Order::where(['user_id' => $user->id, 'status' => 'finished'])
            ->join('order_items', 'order_items.order_id', 'orders.id')
            ->where(['meal_id' => $request->reviewable_id])->count();
        }

        if (!$isOrder){
        return Helper::responseJson(422, 'failed', null, ['default' => [__('reviews.review_not_allow')]], null, 422);
            
            // return response()->json([
            //     'status' => 422,
            //     'message' => null,
            //     'errors' => ['default' => [__('reviews.review_not_allow')]],
            //     'result' => 'failed',
            //     'data' => null
            // ], 422);
        }

        $rate = Review::updateOrCreate([
            'user_id' => $user->id,
            'reviewable_id' => $request->reviewable_id,
            'reviewable_type' => $reviewableType
        ], [
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        $avg = $this->rate_avg($request->reviewable_id, $request->type);
        $rate->reviewable()->update([
            'avg_rate' => $avg,
        ]);

        /**
         * ##############################
         */

        $reviews = Review::where('reviewable_type', $reviewableType)->latest('updated_at')->paginate(15);
        $reviews_count = Review::where('reviewable_type', $reviewableType)->count();
        return Helper::responseJson(200, 'success', __('reviews.data_retrieved_success'), null, [
                    'review' => ReviewResource::collection($reviews)->response()->getData(true),
                    'reviews_count' => $reviews_count,
                    'rate_avg' => $this->rate_avg($id, $type),
                ], 200);

        // return response()->json([
        //     'status' => 200,
        //     'message' => __('reviews.data_retrieved_success'),
        //     'errors' => null,
        //     'result' => 'success',
        //     'data' => [
        //         'review' => ReviewResource::collection($reviews)->response()->getData(true),
        //         'reviews_count' => $reviews_count,
        //         'rate_avg' => $this->rate_avg($id, $type),
        //     ]
        // ], 200);

    }

    public function show(Request $request)
    {
        $id = $request->id;
        $type = $request->type;
        $reviewableTypes = ['meal', 'restaurant'];
        if (!in_array($type, $reviewableTypes)){
            return Helper::responseJson(422, 'failed', null, ['default' => [__('main.error_message')]], null, 422);

            // return response()->json([
            //     'status' => 422,
            //     'message' => null,
            //     'errors' => ['default' => [__('main.error_message')]],
            //     'result' => 'failed',
            //     'data' => null
            // ], 422);
        }

        $reviewableType = 'App\Models\\'.ucfirst($request->type);

        $reviews = Review::where('reviewable_type', $reviewableType)->where('reviewable_id', $id)->latest('updated_at')->paginate(15);
        $reviews_count = Review::where('reviewable_type', $reviewableType)->where('reviewable_id', $id)->count();
        return Helper::responseJson(200, 'success', __('reviews.data_retrieved_success'), null, [
                    'review' => ReviewResource::collection($reviews)->response()->getData(true),
                    'reviews_count' => $reviews_count,
                    'rate_avg' => $this->rate_avg($id, $type),
                ], 200);

        // return response()->json([
        //     'status' => 200,
        //     'message' => __('reviews.data_retrieved_success'),
        //     'errors' => null,
        //     'result' => 'success',
        //     'data' => [
        //         'review' => ReviewResource::collection($reviews)->response()->getData(true),
        //         'reviews_count' => $reviews_count,
        //         'rate_avg' => $this->rate_avg($id, $type),
        //     ]
        // ], 200);
    }

    protected function rate_avg($id, $type)
    {
        $reviewableType = 'App\Models\\'.ucfirst($type);
        $avg = round(Review::where('reviewable_type', $reviewableType)->where('reviewable_id', $id)->avg('rating'), 2);
        return $avg;
    }


}
