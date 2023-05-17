<?php

namespace Database\Seeders;

use App\Helper\Helper;
use App\Models\Category;
use App\Models\CategoryTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $images = [
            'storage/categories/burger.png',
            'storage/categories/pizza.png',
            'storage/categories/pasta.png',
            'storage/categories/salad.png',
        ];

        foreach ($images as $image) {
            Category::factory(['image' => $image])->create();
        }

        foreach (Helper::languages() as $language) {
            CategoryTranslation::factory([
                'category_id' => 1,
                'language_id' => $language->id,
                'name' => 'burger',
            ])->create();

            CategoryTranslation::factory([
                'category_id' => 2,
                'language_id' => $language->id,
                'name' => 'pizza',
            ])->create();

            CategoryTranslation::factory([
                'category_id' => 3,
                'language_id' => $language->id,
                'name' => 'pasta',
            ])->create();

            CategoryTranslation::factory([
                'category_id' => 4,
                'language_id' => $language->id,
                'name' => 'salad',
            ])->create();
        }

//        DB::table('categories')->insert(
//            [
//                [
//                    'name' => 'burger',
//                    'image' => asset('storage/categories/burger.png'),
//                    'color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
//                    'border_color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
//                    ],
//                [
//                    'name' => 'pizza',
//                    'image' => asset('storage/categories/pizza.png'),
//                    'color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
//                    'border_color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
//                    ],
//                [
//                    'name' => 'pasta',
//                    'image' => asset('storage/categories/pasta.png'),
//                    'color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
//                    'border_color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
//                ],
//                [
//                    'name' => 'salad',
//                    'image' => asset('storage/categories/salad.png'),
//                    'color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
//                    'border_color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
//                ],
//            ]
//        );
    }
}
