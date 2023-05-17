<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Order;
use App\Helper\Helper;
use App\Models\Coupon;
use App\Models\Status;
use App\Models\Address;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use PHPUnit\Framework\Constraint\Count;
use App\Http\Resources\OrderItemResource;
use App\Http\Resources\SimpleOrderResource;

use App\Http\Resources\SimpleOrderStatusResource;
use App\Http\Resources\Api\Address\AddressResource;
use App\Http\Requests\Api\Coupon\StoreCouponRequest;
use App\Http\Resources\TrackOrderResource;

class OldOrderController extends Controller
{
    public $totalCart;
    public $helper;

    public function __construct()
    {
        $this->helper= new Helper();

    }


    /**
     * Display order .
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        return response()
        ->json([
            'message' => 'Orders Reservations Retrieved Successfully',
             'orders' => OrderResource::collection(auth()->user()->orders()->get())]);
    }


    /**
     * Display History Order.
     *
     */
    public function getHistoryOrder()
    {

        $order=Order::where('user_id',auth()->user()->id)->whereNotIn('status',['finished','cancelled'])->get();
        return response()->json(['message' => 'order retrieved Successfully', 'status' => 200, 'data' => SimpleOrderResource::collection($order)], 200);

    }
        /**
     * Display Upcoming Order.
     *
     * @return \Illuminate\Http\Response
     */

    public function getUpcomingOrder()
    {
        $order=Order::where('user_id',auth()->user()->id)->whereIn('status',['finished','cancelled'])->get();

        return response()->json(['message' => 'order retrieved Successfully', 'status' => 200, 'data' => SimpleOrderResource::collection($order)], 200);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCouponRequest $request)
    {

        $this->totalCart = $this->helper->calculateTotal();
        $cart = auth()->user()->cart()->get();
        $address=Address::whereId($request->address_id)->first();

        if($request->coupon_code)
        {
            $coupon = Coupon::live()->where('code',$request->coupon_code)->first();

            $subtotal_price=$this->totalCart['subTotal'];
            $percentage = $coupon->discount_percentage / 100;
            $price_before_coupon = $subtotal_price;
            $copun_discount= $subtotal_price * $percentage ;
            $subtotal_price -= $copun_discount;
            $coupon->increment('user_used_code_count',1);
            $number_of_use = ((int)optional(@$coupon->users()->wherePivot('user_id',auth()->user()->id)->first()->pivot)->number_of_use) + 1;
            $coupon->users()->syncWithoutDetaching([auth()->user()->id => ['number_of_use' => $number_of_use]]);

        }
        else{
            $subtotal_price=$this->totalCart['subTotal'];

        }

        $total=$this->totalCart['deliveryFees'] +$subtotal_price;
        $data=['subTotal'=>$subtotal_price,'Delivery Cost'=>$this->totalCart['deliveryFees'],'Total'=>$total];
        $order = auth()->user()->orders()->create(['user_id' => auth()->user()->id, 'sub_total' => $subtotal_price, 'delivery_fees' => $this->totalCart['deliveryFees'] , 'total' => $total,'payment_type'=>$request->pay_type,'address_id'=>$address->id,'driver_id'=>2]);


        foreach ($cart as $item) {
            $order->items()->create(['meal_id' => $item->meal->id, 'size_id' => $item->size->id, 'options' => $item->options, 'drinks' => $item->drinks, 'sides' => $item->sides, 'qty' => $item->qty, 'price' => $item->meal->price, 'total_price' => $item->meal->price*$item->qty]);
        }

        auth()->user()->cart()->delete();

        return response()->json(['message' => 'order Added Successfully', 'status' => 200,'data'=>$data], 200);

    }

/**
 * Display Order Status
 */

    public function getOrderStatus($order)
    {
        $orders=Order::where(['user_id'=>auth()->user()->id,'id'=>$order])->get();

        return response()->json(['message' => 'Order Status', 'status' => 200, 'data' => SimpleOrderStatusResource::collection($orders)], 200);



    }

    /***
     * Display Track Order
     */
    public function getOrderTrack($order)
    {
        $orders=Order::where(['user_id'=>auth()->user()->id,'id'=>$order])->get();

        return response()->json(['message' => 'Order Status', 'status' => 200, 'data' => TrackOrderResource::collection($orders)], 200);


    }

    /**
     * Display order details
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($order)
    {

       $order_items=OrderItem::where('order_id',$order)->first();

        return response()->json(['message' => 'order retrieved Successfully', 'status' => 200, 'data' => new OrderItemResource($order_items)], 200);
    }


    public function assignToDriver(Request $request, Order $order)
    {
        $order->update(['driver_id' => $request->driver_id]);

        return response()->json(['message' => 'order Assigned To Driver Successfully', 'status' => 200, 'data' => $order], 200);
    }

    public function getOrderDeliveryTime(Request $request, Order $order)
    {
        $getDistanceAndTimeBetweenTwoPoints = 'https://maps.googleapis.com/maps/api/distancematrix/json?origins='.auth()->user()->addresses()->whereDefault(1)->first()->lat.','.auth()->user()->addresses()->whereDefault(1)->first()->lat.'&destinations='.$request->driver_lat.','.$request->driver_lng.'&departure_time=now&key=AIzaSyA2obCxpDHFCwyBJe7z5EyrBTgdI1vm8RE';

        //  Initiate curl
        $ch = curl_init();
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL,$getDistanceAndTimeBetweenTwoPoints);
        // Execute
        $result =json_decode(curl_exec($ch) , true);

        $delivery_time = $result['rows']['0']['elements']['0']['duration_in_traffic']['text'];

        return response()->json(['message' => 'order Delivery Time Retrieved Successfully', 'status' => 200, 'data' => $delivery_time], 200);
    }




/*
    public function calculateTotal()
    {
        $cart = auth()->user()->cart()->get();

        $subTotals = [];
        $deliveryFees = [];

        foreach ($cart as $cartItem) {
            // Get Price
            $price = $cartItem->meal->price;

            // Get Price Size
            $size_price = $cartItem->size->price;

            // Get prices Options
            if ($cartItem->options != NULL) {
                $option_prices = collect(Cart::getOptions($cartItem->options))->sum('price');
            }else {
                $option_prices = 0;
            }

            // Get prices drinks
            if ($cartItem->drinks != NULL) {
                $drink_prices = collect(Cart::getDrinks($cartItem->drinks))->sum('price');
            }else {
                $drink_prices = 0;
            }

            // Get prices sides
            if ($cartItem->sides != NULL) {
                $side_prices = collect(Cart::getSides($cartItem->sides))->sum('price');
            } else {
                $side_prices = 0;
            }

            // Get Total Meal Price From "Meal Price , Options, Drinks, Sides"
            $total_meal_price = $price + $size_price + $option_prices + $drink_prices + $side_prices;

            // push into cart Subtotals
            array_push($subTotals, $total_meal_price);

            // push delivery fees Item
            array_push($deliveryFees, $cartItem->meal->restaurant->delivery_fees);
        }

        $subTotal = array_sum($subTotals);
        $deliveryFees = $deliveryFees[0];
        $total = $subTotal + $deliveryFees;

        return ['subTotal' => $subTotal, 'deliveryFees' => $deliveryFees, 'total' => $total];
    }
    */
}
