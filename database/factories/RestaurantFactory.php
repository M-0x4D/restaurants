<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RestaurantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_id' => 1,
            'address' => $this->faker->streetAddress,
            'delivery_time' => $this->faker->randomFloat(0, 30, 50),
            'delivery_fees' => $this->faker->randomFloat(0, 10, 30),
            'lat' => $this->faker->latitude(),
            'lng' => $this->faker->longitude(),
        ];
    }
}
