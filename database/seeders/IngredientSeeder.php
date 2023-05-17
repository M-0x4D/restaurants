<?php

namespace Database\Seeders;

use App\Helper\Helper;
use App\Models\Ingredient;
use App\Models\IngredientTranslation;
use App\Models\Meal;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Meal::cursor() as $meal) {
            Ingredient::factory(5,['meal_id' => $meal->id])->create();
        }

        foreach (Ingredient::cursor() as $ingredient) {
            foreach (Helper::languages() as $language) {
                IngredientTranslation::factory([
                    'ingredient_id' => $ingredient->id,
                    'language_id' => $language->id,
                ])->create();
            }
        }
    }
}
