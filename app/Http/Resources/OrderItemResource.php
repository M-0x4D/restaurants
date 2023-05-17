<?php

namespace App\Http\Resources;


use App\Helper\Helper;
use App\Models\Addon;
use App\Models\Drink;
use App\Models\Ingredient;
use App\Models\Option;
use App\Models\Side;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $ingredientsIds = $this->order
            ->options()
            ->where([
                'order_id' => $this->order->id,
                'optionable_type' => 'App\Models\Ingredient'
            ])->pluck('optionable_id')->toArray();

        $ingredients = Ingredient::whereIn('id', $ingredientsIds)->cursor();

        $drinksIds = $this->order
            ->options()
            ->where([
                'order_id' => $this->order->id,
                'optionable_type' => 'App\Models\Drink'
            ])->pluck('optionable_id')->toArray();

        $drinks = Drink::whereIn('id', $drinksIds)->cursor();

        $addonsIds = $this->order
            ->options()
            ->where([
                'order_id' => $this->order->id,
                'optionable_type' => 'App\Models\Addon'
            ])->pluck('optionable_id')->toArray();

        $addons = Addon::whereIn('id', $addonsIds)->cursor();

        $sidesIds = $this->order
            ->options()
            ->where([
                'order_id' => $this->order->id,
                'optionable_type' => 'App\Models\Side'
            ])->pluck('optionable_id')->toArray();

        $sides = Side::whereIn('id', $sidesIds)->cursor();

        return [
            'id' => $this->id,
            'meal_name' => $this->meal->translation->name,
            'price' => (double) $this->price,
            'currency' => 'EGP',
            'qty' => (int) $this->qty,
            'size' => $this->size->name,
            'image' => Helper::getFullPath($this->meal->media()->where(['default' => 1])->first()->media ?? null),
            'ingredients' => IngredientResource::collection($ingredients),
            'drinks' => DrinkResource::collection($drinks),
            'addons' => AddonResource::collection($addons),
            'sides' => SideResource::collection($sides),
        ];



    }

}
