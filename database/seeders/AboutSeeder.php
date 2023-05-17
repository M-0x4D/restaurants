<?php

namespace Database\Seeders;

use App\Models\About;
use Illuminate\Database\Seeder;

class AboutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        About::factory([
            'name_ar' => 'من نحن',
            'name_en' => 'Who We Are',
        ])->create();
        About::factory([
            'name_ar' => 'رؤيتنا',
            'name_en' => 'Our vision',
        ])->create();
        About::factory([
            'name_ar' => 'هدفنا',
            'name_en' => 'Our Goals',
        ])->create();
        About::factory([
            'name_ar' => 'رسالتنا',
            'name_en' => 'Our Messages',
        ])->create();

    }
}
