<?php

namespace Database\Seeders;

use App\Models\Meal;
use App\Models\Offer;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $offerImages = ['1.png' , '2.png'];

        foreach (Meal::cursor() as $meal) {
            Offer::factory(
                [
                    'meal_id' => $meal->id,
                    'category_id' => $meal->category->id,
                    'restaurant_id' => $meal->restaurant->id,
                    'percentage' => 50 ,
                    'image' => $offerImages[rand(0,1)],
                    'color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
                    'from_date' => Carbon::now(),
                    'to_date' => Carbon::now()->addMonth(),
                ]
            )->create();
        }
//        for ($i = 0; $i < 5; $i++) {
//            DB::table('offers')->insert([
//                ['name' => 'november offer', 'description' => 'description' , 'percentage' => 50 , 'image' => $offerImages[rand(0,1)], 'color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)) ]
//            ]);
//        }

    }
}
