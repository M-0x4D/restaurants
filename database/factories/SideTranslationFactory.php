<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SideTranslationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $names  = ['Fries', 'Onion Rings', 'Mozzarella Sticks (M)'];
        return [
            'name' => $this->faker->randomElement($names)
        ];
    }
}
