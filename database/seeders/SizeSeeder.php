<?php

namespace Database\Seeders;

use App\Helper\Helper;
use App\Models\Meal;
use App\Models\Size;
use App\Models\SizeTranslation;
use Database\Factories\SizeFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        foreach (Meal::cursor() as $meal) {
            Size::factory(3, ['meal_id' => $meal->id])->create();
        }

        foreach (Size::cursor() as $size) {
            foreach (Helper::languages() as $language) {
                SizeTranslation::factory([
                    'name' => 'S',
                    'size_id' => $size->id,
                    'language_id' => $language->id,
                ])->create();
                SizeTranslation::factory([
                    'name' => 'M',
                    'size_id' => $size->id,
                    'language_id' => $language->id,
                ])->create();
                SizeTranslation::factory([
                    'name' => 'L',
                    'size_id' => $size->id,
                    'language_id' => $language->id,
                ])->create();
            }
        }


    }
}
