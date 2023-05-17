<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class SideFactory extends Factory
{

    private static $i = 1;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $optionImages = ['option1.png','option2.png', 'option3.png', 'option4.png', 'option5.png'];
        $names = ['Fries(M)', 'Onion Rings (S)', 'Mozzarella Sticks (M)'];
        if (self::$i = 3) self::$i = 1;
        return [
            'image' => 'storage/sides/'.$optionImages[rand(0,4)],
            'price' => $this->faker->randomFloat(0, 20, 50)
        ];
    }
}
