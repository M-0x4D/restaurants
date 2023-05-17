<?php

namespace Database\Factories;

use App\Models\Addon;
use App\Models\Drink;
use App\Models\Restaurant;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class MealFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [

//            'restaurant_id' => $this->faker->randomElement(Restaurant::pluck('id')->toArray()),
//            'tag_id' => $this->faker->randomElement(Tag::pluck('id')->toArray()),
            'is_offer' => $this->faker->boolean,
            'price' => $this->faker->randomFloat(0, 100, 200),
        ];
    }
}
