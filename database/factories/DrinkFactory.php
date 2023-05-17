<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DrinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $optionImages = ['option1.png','option2.png', 'option3.png', 'option4.png', 'option5.png'];

        return [
            'image' => 'storage/sides/'.$optionImages[rand(0,4)],
            'price' => $this->faker->randomFloat(0, 30, 50)

        ];
    }
}
