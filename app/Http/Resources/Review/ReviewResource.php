<?php

namespace App\Http\Resources\Review;

use App\Helper\Helper;
use App\Http\Resources\User\UserSimpleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'rating'=>(float)$this->rating,
            'comment' =>$this->comment,
            'reviewable_id' =>$this->reviewable_id,
            'created_at'=>$this->created_at->toFormattedDateString(),
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'avatar'=> Helper::getFullPath($this->user->profile->avatar ?? 'avatar/avatar.png'), //asset('https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y'),
            ],
        ];
    }
}
