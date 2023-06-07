<?php

namespace App\Http\Resources;

use App\Helper\Helper;
use App\Models\Day;
use App\Models\Restaurant;
use App\Models\Review;
use App\Models\WeekHour;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantDetailsResource extends JsonResource
{

    public function toArray($request)
    {

        $reviews_count = Review::where('reviewable_type', 'App\Models\Restaurant')->where('reviewable_id', $this->id)->count();
        return [
            'id' => $this->id,
            'name' => $this->translation->name,
            'image' => Helper::getFullPath($this->image),
            'cover' => Helper::getFullPath($this->cover),
            'address' => $this->address,
            'distance' => Restaurant::distance($this->lat,$this->lng,request()->header('lat'),request()->header('lng'),"K")." Km",
            'delivery_time' => $this->delivery_time_value,
            'delivery_fees_trans' => $this->delivery_fees == 0 ? 'free delivery' : $this->delivery_fees_value,
            'delivery_fees' => number_format( (float) $this->delivery_fees, 2, '.', ''),// (float) $this->delivery_fees,
            'description' => Helper::stripText($this->translation->description),
            'reviews_count' => $reviews_count,
            'reviews' => $this->whenLoaded('reviews'),
            'rating'=> $this->reviews->isEmpty() ? 0 : round($this->reviews->pluck('rating')->sum() / $this->reviews->pluck('rating')->count(),1),
            'favorite' => $this->favorites()->whereUserId((auth()->user()->id ?? null))->exists() ? 1 : 0,
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'week_hours' => WeekHourResource::collection($this->whenLoaded('weekHours')),
            'today_week_hours' => $this->today_working_hours,
            'status' => $this->status_value,
        ];
    }
}
