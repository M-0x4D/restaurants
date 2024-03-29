<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CountryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->country,
            'code' => '+'.$this->faker->randomNumber(2),
            'flag' => 'assets/web/images/icons/flag.png',
        ];
    }
}
