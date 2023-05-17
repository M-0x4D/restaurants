<?php

namespace App\Http\Resources;

use App\Helper\Helper;
use App\Models\Restaurant;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantFavoriteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $res = Restaurant::find($this->restaurant_id);
        return [
            'restaurant_id' => $this->restaurant_id,
            'name' => $this->name,
            'address' => $this->address,
            'image' => Helper::getFullPath($this->image),
            'delivery_time' => $this->delivery_time . __('restaurants.minutes'),
            'rating' => $this->avg_rate ?? 0,
            'delivery_fees_trans' => $this->delivery_fees == 0 ? 'free delivery' : ($this->delivery_fees . ' EGP'),
            'distance' => $res::distance($res->lat,$res->lng,request()->header('lat'),request()->header('lng'),"K")." Km",
            'favorite' => $res->favorites()->whereUserId((auth()->user()->id ?? null))->exists() ? 1 : 0,
        ];
    }
}
