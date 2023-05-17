<?php

use App\Http\Controllers\Admin\AddonController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DayController;
use App\Http\Controllers\Admin\DrinkController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\IngredientController;
use App\Http\Controllers\Admin\MealController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\TermsAndConditionsController;
use App\Http\Controllers\Admin\OptionController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\ReviewsController;
use App\Http\Controllers\Admin\ReviewsMealsController;
use App\Http\Controllers\Admin\ReviewsRestaurantController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Admin\SideController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\SubTagController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\WeekHourController;
use App\Http\Controllers\Auth\AdminLoginController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\Admin;
use Illuminate\Support\Facades\Storage;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Temp Then I'll remove
Auth::routes();

Route::get('testt', function(){
    Storage::disk('public')->put('fakepath/Afaker.txt' , 'test');
    $contents = Storage::disk('public')->get('fakepath/Afaker.txt');
    echo $contents;
});


Route::group([
    'prefix' => LaravelLocalization::setLocale() , 
    'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
], function()
{
Route::get('admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AdminLoginController::class, 'login']);
Route::post('admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
Route::group(['middleware' => ['assign.guard:admin,admin/login'] , 'prefix' => 'admin'],function(){

    route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    route::resource('users', AdminUserController::class);
    Route::get('users/{user}/delete', [AdminUserController::class, 'destroy'])->name('users.delete');
    route::get('users/{user}/status', [AdminUserController::class, 'setStatus'])->name('users.set_status');

    Route::resource('admins', AdminController::class);
    Route::get('admins/{admin}/delete', [AdminController::class, 'destroy'])->name('admins.delete');

    Route::resource('offers', OfferController::class);
    Route::get('offers', [OfferController::class, 'indexTwo'])->name('offers.index');
    Route::get('offers_index', [OfferController::class, 'index'])->name('offers.indextwo');
    Route::get('offers/{offer}/delete', [OfferController::class, 'destroy'])->name('offers.delete');

    Route::resource('categories', CategoryController::class);
    Route::get('categories', [CategoryController::class, 'indexTwo'])->name('categories.index');
    Route::get('categories_index', [CategoryController::class, 'index'])->name('categories.indextwo');
    Route::get('categories/{category}/delete', [CategoryController::class, 'destroy'])->name('categories.delete');

    Route::resource('restaurants', RestaurantController::class);
    Route::get('restaurants', [RestaurantController::class, 'indexTwo'])->name('restaurants.index');
    Route::get('restaurants_index', [RestaurantController::class, 'index'])->name('restaurants.indextwo');
    Route::get('restaurants/{restaurant}/delete', [RestaurantController::class, 'destroy'])->name('restaurants.delete');
    Route::get('list_restaurants/{id}', [RestaurantController::class, 'list'])->name('list_restaurants');

    Route::get('restaurants/{restaurant}/order',[RestaurantController::class, 'indexOrder'])->name('restaurants.list.order');


    Route::resource('restaurants/{restaurant}/meals', MealController::class);
    Route::get('restaurants/{restaurant}/meals', [MealController::class, 'indexTwo'])->name('meals.index');
    Route::get('restaurants/{restaurant}/meals_index', [MealController::class, 'index'])->name('meals.indextwo');
    Route::get('restaurants/{restaurant}/meals/{meal}/delete', [MealController::class, 'destroy'])->name('meals.delete');
    Route::get('restaurants/{restaurant}/meals/{meal}', [MealController::class, 'show'])->name('meals.show');
    Route::get('restaurants/{restaurant}/meals/{meal}/edit', [MealController::class, 'edit'])->name('meals.edit');
    Route::get('restaurants/{restaurant}/meals/{meal}/upload', [MealController::class, 'upload'])->name('meals.upload');
    Route::post('restaurants/{restaurant}/meals/{meal}/upload', [MealController::class, 'uploadMedia'])->name('meals.upload_media');
    Route::get('restaurants/{restaurant}/meals/{meal}/getMedia', [MealController::class, 'getMedia'])->name('meals.get_media');
    Route::get('list_meals/{id}', [MealController::class, 'list'])->name('list_meals');

    Route::post('search', [SearchController::class, 'getFilters'])->name('search');

    Route::resource('tags', TagController::class);
    Route::get('tags', [TagController::class, 'indexTwo'])->name('tags.index');
    Route::get('tags_index', [TagController::class, 'index'])->name('tags.indextwo');
    Route::get('tags/{tag}/delete', [TagController::class, 'destroy'])->name('tags.delete');

    Route::resource('tags/{tag}/subtags', SubTagController::class);
    Route::get('tags/{tag}/subtags', [SubTagController::class, 'indexTwo'])->name('subtags.index');
    Route::get('tags/{tag}/subtags_index', [SubTagController::class, 'index'])->name('subtags.indextwo');
    Route::get('tags/{tag}/subtags/{subtag}/delete', [SubTagController::class, 'destroy'])->name('subtags.delete');
    Route::get('list_subtags/{tag}', [SubTagController::class, 'list'])->name('list_subtags');

    Route::get('features/{feature}', [FeatureController::class, 'destroy'])->name('features.delete');
    Route::get('ingredients/{ingredient}', [IngredientController::class, 'destroy'])->name('ingredients.delete');
    Route::get('sizes/{size}', [SizeController::class, 'destroy'])->name('sizes.delete');
    Route::get('options/{option}', [OptionController::class, 'destroy'])->name('options.delete');
    Route::get('drinks/{drink}', [DrinkController::class, 'destroy'])->name('drinks.delete');
    Route::get('sides/{side}', [SideController::class, 'destroy'])->name('sides.delete');

    Route::resource('addons', AddonController::class);
    Route::get('addons', [AddonController::class, 'indexTwo'])->name('addons.index');
    Route::get('addons_index', [AddonController::class, 'index'])->name('addons.indextwo');
    Route::get('addons/{addon}/delete', [AddonController::class, 'destroy'])->name('addons.delete');



    Route::resource('drinks', DrinkController::class);
    Route::get('drinks', [DrinkController::class, 'indexTwo'])->name('drinks.index');
    // Route::get('drinks/create', [DrinkController::class, 'create'])->name('drinks.create');
    Route::get('drinks_index', [DrinkController::class, 'index'])->name('drinks.indextwo');
    Route::get('drinks/{drink}/delete', [DrinkController::class, 'destroy'])->name('drinks.delete');


    Route::resource('days', DayController::class);
    Route::get('days', [DayController::class, 'indexTwo'])->name('days.index');
    Route::get('days_index', [DayController::class, 'index'])->name('days.indextwo');
    Route::get('days/{day}/delete', [DayController::class, 'destroy'])->name('days.delete');
    Route::get('days/{day}/edit', [DayController::class, 'edit'])->name('days.edit');


    Route::resource('sides', SideController::class);
    Route::get('sides', [SideController::class, 'indexTwo'])->name('sides.index');
    Route::get('sides_index', [SideController::class, 'index'])->name('sides.indextwo');
    Route::get('sides/{side}/delete', [SideController::class, 'destroy'])->name('sides.delete');



    // week_hours
    Route::resource('week_hours', WeekHourController::class);
    Route::get('week_hours', [WeekHourController::class, 'indexTwo'])->name('week_hours.index');
    Route::get('week_hours_index', [WeekHourController::class, 'index'])->name('week_hours.indextwo');
    Route::get('week_hours/{week_hour}/delete', [WeekHourController::class, 'destroy'])->name('week_hours.delete');


    // reviews
    Route::resource('reviews', ReviewsController::class);
    Route::get('reviews', [ReviewsController::class, 'indexTwo'])->name('reviews.index');
    Route::get('reviews_index', [ReviewsController::class, 'index'])->name('reviews.indextwo');
    Route::get('reviews/{week_hour}/delete', [ReviewsController::class, 'destroy'])->name('reviews.delete');

    // reviews-restaurant
    Route::resource('reviewsrestaurants', ReviewsRestaurantController::class);
    Route::get('reviewsrestaurants', [ReviewsRestaurantController::class, 'index'])->name('reviewsrestaurants.index');
    // Route::get('reviewsrestaurants_index', [ReviewsRestaurantController::class, 'index'])->name('reviewsrestaurants.indextwo');
    Route::get('reviewsrestaurants/{reviewsrestaurants}/delete', [ReviewsRestaurantController::class, 'destroy'])->name('reviewsrestaurants.delete');

    // reviews-meals
    Route::resource('reviewsmeals', ReviewsMealsController::class);
    Route::get('reviewsmeals', [ReviewsMealsController::class, 'index'])->name('reviewsmeals.index');
    Route::get('reviewsmeals/{reviewsmeals}/update', [ReviewsMealsController::class, 'update'])->name('reviewsmeals.update');
    Route::get('reviewsmeals/{reviewsmeals}/edit', [ReviewsMealsController::class, 'edit'])->name('reviewsmeals.edit');
    Route::get('reviewsmeals/{reviewsmeals}/delete', [ReviewsMealsController::class, 'destroy'])->name('reviewsmeals.delete');


    // drivers
    Route::resource('drivers', DriverController::class);
    Route::get('drivers', [DriverController::class, 'index'])->name('drivers.index');
    // Route::get('drivers_index', [DriverController::class, 'index'])->name('drivers.indextwo');
    Route::get('drivers/{week_hour}/delete', [DriverController::class, 'destroy'])->name('drivers.delete');



    // orders
    Route::resource('orders', OrderController::class);
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    // Route::get('orders_index', [OrderController::class, 'index'])->name('orders.indextwo');
    Route::get('orders/{week_hour}/delete', [OrderController::class, 'destroy'])->name('orders.delete');


    // coupons
    Route::resource('coupons', CouponController::class);
    Route::get('coupons', [CouponController::class, 'index'])->name('coupons.index');
    // Route::get('coupons_index', [CouponController::class, 'index'])->name('coupons.indextwo');
    Route::get('coupons/{coupons}/edit', [CouponController::class, 'edit'])->name('coupons.edit');
    Route::get('coupons/{week_hour}/delete', [CouponController::class, 'destroy'])->name('coupons.delete');


    // payment methods
    Route::resource('paymentmethods', PaymentMethodController::class);
    Route::get('paymentmethods', [PaymentMethodController::class, 'indexTwo'])->name('paymentmethods.index');
    Route::get('paymentmethods_index', [PaymentMethodController::class, 'index'])->name('paymentmethods.indextwo');
    Route::get('paymentmethods/{week_hour}/delete', [PaymentMethodController::class, 'destroy'])->name('paymentmethods.delete');


    Route::resource('termsAndConditions' , TermsAndConditionsController::class);
    Route::get('termsAndConditions' , [TermsAndConditionsController::class , 'index'])->name('termsAndConditions.index');


});

	
});


Route::get('/', function () {
    return redirect('admin/login');
});

Route::get('create-user' , function(){
   $user =  Admin::create([
    'name' => 'test',
        'email' => 'test@test.com' ,
        'password' => bcrypt('admin123')
    ]);
    return response()->json($user);
});

// DON'T Put it inside the '/admin' Prefix , Otherwise you'll never get the page due to assign.guard that will redirect you too many times



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::get('/storage-link', function () {
    Artisan::call('storage:link');
});

