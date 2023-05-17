<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Restaurant::cursor() as $rest) {
            Coupon::factory()->create([
                'restaurant_id' => $rest->id,
                'code' => 'code_'.$rest->id,
            ]);
        }
    }
}
