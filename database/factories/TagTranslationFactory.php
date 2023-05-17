<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TagTranslationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['beef burger', 'pasta one', 'chicken burger'])
        ];
    }
}
