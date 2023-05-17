<?php

namespace App\Http\Resources;

use App\Helper\Helper;
use App\Models\Day;
use App\Models\Review;
use App\Models\WeekHour;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantForCatResource extends JsonResource
{

    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'name' => $this->translation->name,
            'image' => Helper::getFullPath($this->image),
            'cover' => Helper::getFullPath($this->cover),
            'delivery_time' => $this->delivery_time_value,
            'delivery_fees' => $this->delivery_fees == 0 ? 'free delivery' : $this->delivery_fees_value,
            'rating'=> $this->reviews->isEmpty() ? 0 : round($this->reviews->pluck('rating')->sum() / $this->reviews->pluck('rating')->count(),1),
            'favorite' => $this->favorites()->whereUserId((auth()->user()->id ?? null))->exists() ? 1 : 0,
        ];
    }
}
