<?php

namespace Database\Seeders;

use App\Helper\Helper;
use App\Models\Addon;
use App\Models\Drink;
use App\Models\Meal;
use App\Models\MealAddon;
use App\Models\MealDrink;
use App\Models\MealMedia;
use App\Models\MealTranslation;
use App\Models\Restaurant;
use App\Models\Tag;
use Database\Factories\MealFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        foreach (Restaurant::cursor() as $restaurant) {
            Meal::factory(1,[
                'restaurant_id' => $restaurant->id,
                'tag_id' => 1,
                'category_id' => $restaurant->category_id])->create();
            Meal::factory(9,[
                'restaurant_id' => $restaurant->id,
                'tag_id' => Tag::where('restaurant_id', $restaurant->id)->whereNotNull('parent_id')->inRandomOrder()->first()->id,
                'category_id' => $restaurant->category_id])->create();
        }


        foreach (Meal::cursor() as $meal) {
            foreach (Drink::cursor() as $drink) {
                MealDrink::factory(['meal_id' => $meal->id, 'drink_id' => $drink->id])->create();
            }

            foreach (Addon::cursor() as $addon) {
                MealAddon::factory(['meal_id' => $meal->id, 'addon_id' => $addon->id])->create();
            }

            foreach (Helper::languages() as $language) {
                MealTranslation::factory([
                    'meal_id' => $meal->id,
                    'language_id' => $language->id,
                ])->create();
            }

        }
    }
}
