<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cart\AddToCartRequest;
use App\Http\Requests\Api\ApplyCouponRequest;
use App\Http\Resources\CartResource;
use App\Http\Resources\CouponResource;
use App\Http\Resources\OrderItemResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\TrackOrderResource;
use App\Models\Addon;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\CouponUser;
use App\Models\Drink;
use App\Models\Ingredient;
use App\Models\Meal;
use App\Models\MealAddon;
use App\Models\MealDrink;
use App\Models\Option;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Restaurant;
use App\Models\Side;
use App\Models\Size;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{

    public function add(AddToCartRequest $request) // AddToCartRequest
    {

        $data = $request->all();
        $data['user_id'] = auth()->id();

        // Check if request data is valid for meal or not
        $checkMeal = $this->checkMealBeforeAddToCart($data);
        if ($checkMeal['errors'] ?? null) {
            return Helper::responseJson(422, 'failed', null, ['default' => $checkMeal['errors']], null, 422);
            //  return response()->json([
            //      'status' => 422,
            //      'message' => null,
            //      'errors' => ['default' => $checkMeal['errors']],
            //      'result' => 'failed',
            //      'data' => null
            //  ], 422);
        }

        DB::beginTransaction();
        try {
            $cart = Order::where(['user_id' => auth()->id(), 'status' => 'pending'])->first();
            if (!$cart) {
                $data['order_number'] = 'A' . rand(0, 90000);
                $cart = Order::create($data);
            } else {

                $cart->update(['restaurant_id' => $data['restaurant_id']]);
                OrderItem::where(['order_id' => $cart->id])->delete();
                //  dd('jhlk');
                Option::where(['order_id' => $cart->id])->delete();
            }
            /**
             * Create order items whether create new order or update
             */
            foreach ($data['meals'] as $meal) {
                $mealItem = Meal::find($meal['meal_id']);
                OrderItem::create([
                    'order_id' => $cart->id,
                    'meal_id' => $meal['meal_id'],
                    'size_id' => $meal['size_id'],
                    'price' => $mealItem->price,
                    'qty' => $meal['qty'],
                    'total_price' => ($mealItem->price * $meal['qty']),
                    'notes' => $meal['notes'] ?? null,
                ]);
                if (count($meal['ingredients'])) {
                    foreach ($meal['ingredients'] as $ingredientId) {
                        $ingredientItem = Ingredient::find($ingredientId);
                        $ingredientItem->options()->create(['order_id' => $cart->id]);
                    }
                }
                if (count($meal['addons'])) {
                    foreach ($meal['addons'] as $addonId) {
                        $addonItem = Addon::find($addonId);
                        $addonItem->options()->create(['order_id' => $cart->id, 'price' => $addonItem->price]);
                    }
                }
                if (count($meal['drinks'])) {
                    foreach ($meal['drinks'] as $drinkId) {
                        $drinkItem = Drink::find($drinkId);
                        $drinkItem->options()->create(['order_id' => $cart->id, 'price' => $drinkItem->price]);
                    }
                }
                if (count($meal['sides'])) {
                    foreach ($meal['sides'] as $sideId) {
                        $sideItem = Side::find($sideId);
                        $sideItem->options()->create(['order_id' => $cart->id, 'price' => $sideItem->price]);
                    }
                }
            }

            if ($request->coupon_id) {

                $check = $this->checkCouponWhenAddOrder($request);
                if ($check['errors']) {
                    return Helper::responseJson(422, 'failed', null, ['default' => [$check['errors']]], null, 422);
                    //  return response()->json([
                    //      'status' => 422,
                    //      'message' => null,
                    //      'errors' => ['default' => [$check['errors']]],
                    //      'result' => 'failed',
                    //      'data' => null
                    //  ], 422);
                }
            }
            DB::commit();
            return Helper::responseJson(200, 'success', __('orders.cart_added_success'), ['default' => [$check['errors']]], ['order' => ['id' => $cart->id]], 200);

            //  return response()->json([
            //      'status' => 200,
            //      'message' => __('orders.cart_added_success'),
            //      'errors' => null,
            //      'result' => 'success',
            //      'data' => ['order' => ['id' => $cart->id]]
            //  ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            Log::info($e->getMessage());
            return Helper::responseJson(422, 'failed', null, __('main.error_message'), null, 422);
            //  return response()->json([
            //      'status' => 422,
            //      'message' => null,
            //      'errors' => __('main.error_message'),
            //      'result' => 'failed',
            //      'data' => null
            //  ], 422);
        }

    }

    /**
     * Get orders depend on status
     * [up_coming => [pending], history => [finished, canceled]]
     * @param Request $request
     */
    public function orders(Request $request)
    {
        $user = Auth::user();
        $key = null;
        $title = null;
        $orders = [];
        if ($request->status == 'upcoming') {
            $key = 'upcoming';
            $orders = Order::where(['user_id' => $user->id, 'status' => 'pending'])->cursor();
        }

        if ($request->status == 'history') {
            $key = 'history';
            $orders = Order::where(['user_id' => $user->id])->whereIn('status', ['finished', 'canceled'])->cursor();
        }
        return Helper::responseJson(200, 'success', __('orders.data_retrieved_success'), null, ["$key" => OrderResource::collection($orders)], 200);

        // return response()->json([
        //     'status' => 200,
        //     'message' => __('orders.data_retrieved_success'),
        //     'errors' => null,
        //     'result' => 'success',
        //     'data' => ["$key" => OrderResource::collection($orders)]
        // ], 200);
    }

    /**
     * Get order details depend on order_id
     * @param Request $request
     */
    public function orderDetails(Request $request)
    {
        $user = Auth::user();
        $order = Order::where(['orders.user_id' => $user->id, 'orders.id' => $request->order_id])
            ->whereNotIn('status', ['cart'])
            ->join('users', 'users.id', 'orders.user_id')
            ->leftJoin('addresses', 'addresses.id', 'orders.address_id')
            ->select([
                'orders.id',
                'orders.order_number',
                'orders.restaurant_id',
                'orders.total',
                'orders.status',
                'orders.sub_total',
                'orders.delivery_fees',
                'orders.discount_amount',
                'addresses.phone',
                'addresses.country_code',
                'addresses.address',
                'users.name as userName',
            ])->first();

        if (!$order) {
            return Helper::responseJson(422, 'failed', null, ['default' => __('orders.no_data')], null, 422);

            // return response()->json([
            //     'status' => 422,
            //     'message' => null,
            //     'errors' => ['default' => __('orders.no_data')],
            //     'result' => 'failed',
            //     'data' => null
            // ], 422);
        }

        $items = $order->items()->cursor();
        //            ->join('meals', 'meals.id', 'order_items.meal_id')
//            ->join('sizes', 'sizes.id', 'order_items.size_id')
//            ->select([
//                'order_items.id',
//                'meals.name',
//                'order_items.price',
//                'order_items.qty',
//                'sizes.name as sizeName',
//            ])->cursor();

        return Helper::responseJson(200, 'success', __('orders.data_retrieved_success'), null, [
            'order' => OrderResource::make($order),
            'items' => OrderItemResource::collection($items),
        ], 200);

        // return response()->json([
        //     'status' => 200,
        //     'message' => __('orders.data_retrieved_success'),
        //     'errors' => null,
        //     'result' => 'success',
        //     'data' => [
        //         'order' => OrderResource::make($order),
        //         'items' => OrderItemResource::collection($items),
        //     ]
        // ], 200);
    }

    public function trackOrder(Request $request)
    {
        $orderId = $request->order_id;
        $order = Order::where(['status' => 'pending', 'user_id' => auth()->id(), 'id' => $orderId])->first();
        if (!$order) {
            return Helper::responseJson(422, 'failed', null, ['default' => __('orders.no_data')], null, 422);

            // return response()->json([
            //     'status' => 422,
            //     'message' => null,
            //     'errors' => ['default' => __('orders.no_data')],
            //     'result' => 'failed',
            //     'data' => null
            // ], 422);
        }

        return Helper::responseJson(200, 'success', __('orders.data_retrieved_success'), null, TrackOrderResource::make($order), 200);

        // return response()->json([
        //     'status' => 200,
        //     'message' => __('orders.data_retrieved_success'),
        //     'errors' => null,
        //     'result' => 'success',
        //     'data' => TrackOrderResource::make($order)

        // ], 200);
    }

    private function checkMealBeforeAddToCart($request)
    {
        foreach ($request['meals'] as $meal) {
            // Check if restaurant valid ot not
            $restaurantMeal = Meal::where(['id' => $meal['meal_id'], 'restaurant_id' => $request['restaurant_id']])->first();
            if (!$restaurantMeal) {
                return ['errors' => __('orders.restaurant_not_valid')];
            }

            // Check size
            $size = Size::where(['id' => $meal['size_id'], 'meal_id' => $meal['meal_id']])->first();
            if (!$size) {
                return ['errors' => __('orders.size_not_valid')];
            }

            // Check ingredients
            $ingredientsDB = Ingredient::where('meal_id', $meal['meal_id'])->pluck('id')->toArray();
            $mealIngredients = $meal['ingredients'];
            foreach ($mealIngredients as $ingredient) {
                if (!in_array($ingredient, $ingredientsDB)) {
                    return ['errors' => __('orders.ingredient_not_valid')];
                }
            }


            // Check addons
            // $addonsDB = MealAddon::where('meal_id', $meal['meal_id'])->pluck('addon_id')->toArray();
            // $mealAddons = $meal['addons'];
            // foreach ($mealAddons as $addon) {
            //     if (!in_array($addon, $addonsDB)){
            //         return ['errors' => __('orders.addons_not_valid')];
            //     }
            // }

            // Check drinks
            $drinksDB = MealDrink::where('meal_id', $meal['meal_id'])->pluck('drink_id')->toArray();
            $mealDrinks = $meal['drinks'];
            foreach ($mealDrinks as $drink) {
                if (!in_array($drink, $drinksDB)) {
                    return ['errors' => __('orders.drink_not_valid')];
                }
            }


        }
        return true;
    }

    public function applyCoupon(ApplyCouponRequest $request)
    {
        $coupon = Coupon::where(['restaurant_id' => $request->restaurant_id, 'code' => $request->coupon_code])->first();

        if (!$coupon) {
            return response()->json([
                'status' => 422,
                'message' => null,
                'errors' => ['default' => [__('coupons.no_data')]],
                'result' => 'failed',
                'data' => null
            ], 422);
        }

        $expireDate = Carbon::make($coupon->expire_date)->format('Y-m-d');
        $now = Carbon::now()->format('Y-m-d');
        if ($expireDate < $now || $coupon->is_active == 0 || ($coupon->available_users <= $coupon->used_count)) {
            return Helper::responseJson(422, 'failed', null, ['default' => [__('coupons.coupon_not_valid')]], null, 422);

            // return response()->json([
            //     'status' => 422,
            //     'message' => null,
            //     'errors' => ['default' => [__('coupons.coupon_not_valid')]],
            //     'result' => 'failed',
            //     'data' => null
            // ], 422);
        }

        $CouponUsed = CouponUser::where(['coupon_id' => $coupon->id, 'user_id' => auth()->id()])->first();
        if ($CouponUsed) {
            return Helper::responseJson(422, 'failed', null, ['default' => [__('coupons.coupon_used_before')]], null, 422);

            // return response()->json([
            //     'status' => 422,
            //     'message' => null,
            //     'errors' => ['default' => [__('coupons.coupon_used_before')]],
            //     'result' => 'failed',
            //     'data' => null
            // ], 422);
        }

        CouponUser::create([
            'coupon_id' => $coupon->id,
            'user_id' => auth()->id()
        ]);
        return Helper::responseJson(200, 'success', __('coupons.data_retrieved_success'), null, ['coupon' => CouponResource::make($coupon)], 200);

        // return response()->json([
        //     'status' => 200,
        //     'message' => __('coupons.data_retrieved_success'),
        //     'errors' => null,
        //     'result' => 'success',
        //     'data' => ['coupon' => CouponResource::make($coupon)]
        // ], 200);

    }

    public function checkCouponWhenAddOrder($request)
    {
        $coupon = Coupon::find($request->coupon_id);
        if (!$coupon) {
            return ['errors' => __('coupons.no_data')];
        }

        $expireDate = Carbon::make($coupon->expire_date)->format('Y-d-m');
        $now = Carbon::now()->format('Y-d-m');
        if ($expireDate < $now || $coupon->is_active == 0 || ($coupon->available_users <= $coupon->used_count)) {
            return ['errors' => __('coupons.coupon_not_valid')];
        }

        //        $CouponUsed = CouponUser::where(['coupon_id' => $coupon->id, 'user_id' => auth()->id()])->first();
//        if($CouponUsed){
//            return ['errors' => __('coupons.coupon_used_before')];
//        }

        $coupon->update(['available_users' => ($coupon->available_users - 1), 'used_count' => ($coupon->used_count + 1)]);
        CouponUser::create(['user_id' => auth()->id(), 'coupon_id' => $request->coupon_id]);
        return ['errors' => false];
    }

}