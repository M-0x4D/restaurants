<?php

namespace Database\Seeders;

use App\Models\Addon;
use App\Models\Admin;
use App\Models\Drink;
use App\Models\Feature;
use App\Models\Ingredient;
use App\Models\Option;
use App\Models\Profile;
use App\Models\Side;
use App\Models\User;
use App\Models\WeekHour;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create();
        User::factory([
            'name' => 'Omar Adel',
            'email' => 'omar@dev.com',
            'phone' => '1004881647',
            'password' => '123456@Omar',
            'country_code' => '+20',
            'phone_verified_at' => now(),
        ])->create();
        User::factory([
            'name' => 'Eslam Awwad',
            'email' => 'eslam@dev.com',
            'phone' => '2000000000',
            'password' => '123456@islam',
            'country_code' => '+20',
            'phone_verified_at' => now(),
        ])->create();
        $this->call([
            LanguageSeeder::class,
            AdminSeeder::class,
            AddressSeeder::class,
            CategorySeeder::class,
            DaySeeder::class,
            RestaurantSeeder::class,
            TagSeeder::class,
            MealSeeder::class,
            MealMediaSeeder::class,
            IngredientSeeder::class,
            SizeSeeder::class,
            AddonSeeder::class,
            DrinkSeeder::class,
            SideSeeder::class,
            FeatureSeeder::class,
            OfferSeeder::class,
            CountrySeeder::class,
            AboutSeeder::class,
            TermSeeder::class,
            CouponSeeder::class,

//            ReviewSeeder::class,
//            FavoriteSeeder::class,
//            ProfileSeeder::class,
//            AddressSeeder::class,
//            StatusSeeder::class,
        ]);


//        Feature::factory(300)->create();

        //WeekHour::factory(100)->create();

        //Admin::factory(10)->create();


        //Ingredient::factory(500)->create();

        //Option::factory(500)->create();

        // Drink::factory(500)->create();

        // Side::factory(500)->create();

    }
}
