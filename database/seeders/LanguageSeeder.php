<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

//        foreach (LaravelLocalization::getSupportedLocales() as $local => $supportedLocale) {
//            Language::factory([
//                'local' => $local,
//                'name' => $supportedLocale,
//            ])->create();
//        }

        Language::factory([
            'local' => 'ar',
            'name' => 'Arabic',
        ])->create();
        Language::factory([
            'local' => 'en',
            'name' => 'English',
        ])->create();
        Language::factory([
            'local' => 'fr',
            'name' => 'France',
        ])->create();
    }
}
