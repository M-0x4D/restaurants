<?php

namespace Database\Seeders;

use App\Models\Meal;
use App\Models\MealMedia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MealMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $meals_pics = ['meals.png', 'meals1.png' , 'meals2.png' , 'meals3.png'];

        foreach (Meal::cursor() as $meal){
            $mealPic = $meals_pics[array_rand($meals_pics)];
            MealMedia::factory([
                'meal_id' => $meal->id,
                'default' => 1,
                'media' => 'storage/meals/'.$mealPic,
                'type' => 'image'
            ])->create();

            MealMedia::factory(3,[
                'meal_id' => $meal->id,
                'default' => 0,
                'media' => 'storage/meals/'.$mealPic,
                'type' => 'image'
            ])->create();
        }
//        for ($i = 0; $i < 500; $i++) {
//            DB::table('meal_media')->insert([
//                [
//                    'meal_id' => Meal::inRandomOrder()->first()->id,
//                    'default' => $i == 0 ? 1 : 0,
//                    'media' => asset('storage/meals/'.$meals_pics[rand(0,3)]),
//                    'type' => 'image'
//                ]
//            ]);
//        }
    }
}
