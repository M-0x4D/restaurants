<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class SimpleOrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'order_id' => $this->order->id,
            'meal' => $this->meal->img_path,
            'date'  => $this->created_at->format('Y-m-d H:i:s'),
            'restaurant_img' => $this->meal->restaurant->img_path,


        ];



    }

}
