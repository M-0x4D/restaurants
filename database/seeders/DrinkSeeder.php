<?php

namespace Database\Seeders;

use App\Helper\Helper;
use App\Models\Drink;
use App\Models\DrinkTranslation;
use App\Models\Meal;
use App\Models\MealDrink;
use Database\Factories\DrinkFactory;
use Illuminate\Database\Seeder;

class DrinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $drinks = Drink::factory(3)->create();
        foreach ($drinks as $drink) {
            foreach (Helper::languages() as $language) {

                DrinkTranslation::factory([
                    'name' => 'Cocacola coke',
                    'drink_id' => $drink->id,
                    'language_id' => $language->id,
                ])->create();

                DrinkTranslation::factory([
                    'name' => 'Orange Juice',
                    'drink_id' => $drink->id,
                    'language_id' => $language->id,
                ])->create();
                DrinkTranslation::factory([
                    'name' => 'Chocolate shake',
                    'drink_id' => $drink->id,
                    'language_id' => $language->id,
                ])->create();

            }
        }

        foreach (Meal::cursor() as $meal) {
            foreach ($drinks as $drink) {
                MealDrink::factory(['meal_id' => $meal->id, 'drink_id' => $drink->id])->create();
            }
        }
    }
}
