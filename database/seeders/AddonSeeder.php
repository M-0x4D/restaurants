<?php

namespace Database\Seeders;

use App\Models\Addon;
use App\Models\Meal;
use App\Models\MealAddon;
use Database\Factories\AddonFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Addon::factory(['name' => 'Mushroom'])->create();
        Addon::factory(['name' => 'Tomatoes'])->create();
        Addon::factory(['name' => 'Olives'])->create();
        Addon::factory(['name' => 'Cheese'])->create();

        foreach (Meal::cursor() as $meal) {
            foreach (Addon::cursor() as $addon) {
                MealAddon::factory(['meal_id' => $meal->id, 'addon_id' => $addon->id])->create();
            }
        }
    }
}
