<?php

namespace Database\Seeders;

use App\Helper\Helper;
use App\Models\Side;
use App\Models\SideTranslation;
use Database\Factories\SideFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sides = Side::factory(3)->create();

        foreach ($sides as $side) {
            foreach (Helper::languages() as $language) {
                SideTranslation::factory([
                    'side_id' => $side->id,
                    'language_id' => $language->id,
                ])->create();
            }

        }
    }
}
