<?php

namespace Database\Seeders;

use App\Helper\Helper;
use App\Models\Day;
use App\Models\DayTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = [
            'Saturday',
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday'
        ];

        $days = Day::factory(7)->create();
        foreach ($days as $key => $day) {
            foreach (Helper::languages() as $language) {
                DayTranslation::factory([
                    'name' => $names[$key],
                    'day_id' => $day->id,
                    'language_id' => $language->id,
                ])->create();
            }
        }
    }
}
