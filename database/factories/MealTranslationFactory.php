<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MealTranslationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Meal ' . $this->faker->randomFloat(0, 1, 1000),
            'description' => 'Enjoy Free Delivery with KFC Egypt. Check
                    out KFC menu Enjoy Free Delivery with KFC Egypt. Check
                    out KFC menu Enjoy Free Delivery with KFC Egypt. Check
                    out KFC menu ',
        ];
    }
}
