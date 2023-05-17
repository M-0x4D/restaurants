<?php

namespace App\Http\Resources;

use App\Helper\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $title = null;
        if (($this->id ?? null) && $this->status == 'pending'){
            $title =  __('orders.food_on_way');
        }
        if (($this->id ?? null) && $this->status == 'finished'){
            $title =  __('orders.food_delivered');
        }
        if (($this->id ?? null) && $this->status == 'canceled'){
            $title =  __('orders.food_canceled');
        }
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'restaurant_id' => $this->restaurant_id ?? null,
            'image' => Helper::getFullPath($this->restaurant->image ?? null),
            'name' => $this->restaurant->translation->name ?? null,
            'title' =>  $title,
            'total' => (double) $this->total,
            'sub_total' => (double) $this->sub_total,
            'currency' => 'EGP',
            'delivery_time' => $this->restaurant->delivery_time ?? null,
            'delivery_fees' => (double) $this->restaurant->delivery_fees ?? null,
            'time_unit' => __('orders.minutes'),
            'discount_amount' => (double) $this->discount_amount,
            'order_date' => date('M d-h:i A'),
            'items_count' => count($this->items ?? []),
            'phone' => $this->phone ?? null,
            'country_code' => $this->country_code ?? null,
            'address' => $this->address ?? null,
            'user_name' => $this->userName ?? null,
        ];
    }
}
