<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
            'border_color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
        ];
    }
}
