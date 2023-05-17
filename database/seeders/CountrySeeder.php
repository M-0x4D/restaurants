<?php

namespace Database\Seeders;

use App\Models\Country;
use Database\Factories\CountryFactory;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::factory(['code' => '+20'])->create();
        Country::factory(6)->create();
    }
}
