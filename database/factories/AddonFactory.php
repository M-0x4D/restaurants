<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AddonFactory extends Factory
{
    private static $i = 1;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        if(self::$i == 100) self::$i = 1;

        $optionImages = ['option1.png','option2.png', 'option3.png', 'option4.png', 'option5.png'];

        return [
//            'meal_id' => self::$i++,
            'image' => asset('storage/sides/'.$optionImages[rand(0,4)]),
//            'name' => $this->faker->name,
            'price' => $this->faker->randomFloat(0, 20, 50)
        ];
    }
}
