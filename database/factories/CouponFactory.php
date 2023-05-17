<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'discount_percentage' => $this->faker->randomFloat(0, 10, 20),
            'start_date' => Carbon::now()->format('Y-m-d'),
            'expire_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
            'available_users' => $this->faker->randomElement([10,20,30]),
            'used_count' => $this->faker->randomElement([5,10,15]),
            'is_active' => 1,
        ];
    }
}
