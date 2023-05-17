<?php

namespace Database\Seeders;

use App\Helper\Helper;
use App\Models\Category;
use App\Models\Day;
use App\Models\Restaurant;
use App\Models\RestaurantTranslation;
use App\Models\WeekHour;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $images = [
            'storage/restaurants/burger_king_big.png',
            'storage/restaurants/mac_big.png',
            'storage/restaurants/kentucky_big.png',
            'storage/restaurants/kentucky_big.png',
        ];

        $covers = [
            'storage/restaurants/burger_king.png',
            'storage/restaurants/mac.png',
            'storage/restaurants/kentucky.png',
            'storage/restaurants/kentucky.png',
        ];

        foreach ($images as $key => $image) {
            Restaurant::factory([
                'image' => $image,
                'cover' => $covers[$key],
            ])->create();
        }

        foreach (Restaurant::cursor() as $restaurant) {
            foreach (Helper::languages() as $language) {
                RestaurantTranslation::factory([
                    'restaurant_id' => $restaurant->id,
                    'language_id' => $language->id,
                    'name' => 'burger_king',
                    'description' => 'Enjoy Free Delivery with KFC Egypt. Check
                out KFC menu Enjoy Free Delivery with KFC Egypt. Check
                out KFC menu Enjoy Free Delivery with KFC Egypt. Check
                out KFC menu ',
                ])->create();
            }
        }

        foreach (Restaurant::cursor() as $rest) {
            foreach (Day::cursor() as $day) {
                WeekHour::factory(1, [
                    'restaurant_id' => $rest->id,
                    'day_id' => $day->id,
                    'from' => Carbon::now()->format('H:i:s'),
                    'to' => Carbon::now()->addHours(8)->format('H:i:s'),
                ])->create();
            }

        }
    }
}
