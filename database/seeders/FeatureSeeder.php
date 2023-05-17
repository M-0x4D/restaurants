<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Meal;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Meal::cursor() as $meal) {
            Feature::factory(['name' => 'Calories', 'meal_id' => $meal->id])->create();
            Feature::factory(['name' => 'Proteins', 'meal_id' => $meal->id])->create();
            Feature::factory(['name' => 'Fats', 'meal_id' => $meal->id])->create();
            Feature::factory(['name' => 'Sugar', 'meal_id' => $meal->id])->create();
            Feature::factory(['name' => 'Carbs', 'meal_id' => $meal->id])->create();
            Feature::factory(['name' => 'Fibres', 'meal_id' => $meal->id])->create();
            Feature::factory(['name' => 'Sodium', 'meal_id' => $meal->id])->create();
            Feature::factory(['name' => 'Vitamins', 'meal_id' => $meal->id])->create();
        }
    }
}
