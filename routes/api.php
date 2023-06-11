<?php

use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Api\AboutUsController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\ReviewController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\MealController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\OldOrderController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\SearchHistoryController;
use App\Http\Controllers\DriverController as ControllersDriverController;
use App\Http\Controllers\RoomController;
use App\Http\Middleware\CheckUserLangMiddleware;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['middleware' => 'check_user_lang'], function () {


    Route::post('login', [UserController::class, 'login']);
    Route::post('social-login', [UserController::class, 'socialLogin']);
    Route::post('register', [UserController::class, 'register']);
    Route::post('check-phone', [Usercontroller::class, 'checkPhone']);
    Route::post('forget-password', [UserController::class, 'forgetPassword']);
    Route::post('check-otp', [UserController::class, 'checkOtp']);
    Route::post('resend-otp', [UserController::class, 'resendOtp']);

    // This for non auth
    Route::get('home', [HomeController::class, 'index']);

    // Get restaurant by restaurant_id
    Route::get('restaurants/{restaurant_id}', [RestaurantController::class, 'show']);

    // Get sub tags by tag_id
    Route::get('sub-tags/{tag_id}', [TagController::class, 'show']);

    Route::group(['prefix' => 'meals'], function () {
        // Get Meal Details by meal_id
        Route::get('show', [MealController::class, 'show']);

        // Get Meals By restaurant_id and tag_id
        Route::get('/', [MealController::class, 'index']);
    });

    // Get category minions [restaurants, recommended meals,  offer meals]
    Route::get('category-minions', [CategoryController::class, 'minions']);

    // Get all countries or by id
    Route::get('countries/{country_id?}', [CountryController::class, 'index']);


    Route::get('popular-restaurants', [RestaurantController::class, 'popularRestaurants']);
    Route::get('fast-delivery', [RestaurantController::class, 'fastDelivery']);
    Route::get('popular-meals', [MealController::class, 'popularMeals']);
    Route::get('offers', [OfferController::class, 'index']);
    Route::get('categories', [CategoryController::class, 'index']);

    // It's for non auth
    Route::get('filter', [SearchHistoryController::class, 'filter']);



    //    Route::post('check-otp-for-phone', [UserController::class , 'checkOtpForPhone']);

    Route::get('nearest_restaurants', [RestaurantController::class, 'nearestRestaurants']);


    Route::group(['middleware' => 'auth:api'], function () {

        // Must be cleared after finish test
        Route::post('test', [HomeController::class, 'test']);

        Route::post('delete-account', [UserController::class, 'delete_account']);

        Route::post('create-room', [RoomController::class, 'create_room']);
        Route::post('create-message', [MessageController::class, 'create_message']);
        Route::post('get-rooms', [RoomController::class, 'return_rooms']);
        Route::post('logout', [UserController::class, 'logout']);
        Route::post('get-users', [UserController::class, 'get_users']);
        Route::post('get-drivers', [ControllersDriverController::class, 'get_drivers']);

        // This for authentication
        Route::get('auth-home', [HomeController::class, 'indexAuth']);

        // It's for non auth
        Route::get('auth-filter', [SearchHistoryController::class, 'filterAuth']);


        Route::post('change-password', [UserController::class, 'changePassword']);
        Route::post('logout', [UserController::class, 'logout']);
        Route::get('user-details', [UserController::class, 'userDetails']);
        Route::post('update-profile', [UserController::class, 'updateProfile']);

        Route::post('update-phone', [UserController::class, 'updatePhone']);
        Route::post('check-otp-for-update-phone', [UserController::class, 'checkOtpForUpdatePhone']);

        Route::post('update-email', [UserController::class, 'updateEmail']);
        Route::post('check-otp-for-update-email', [UserController::class, 'checkOtpForEmail']);
        Route::post('resend-otp-for-update-email', [UserController::class, 'resendOtpForEmail']);

        Route::post('editPassword', [UserController::class, 'editPassword']);

        Route::group(['prefix' => 'addresses'], function () {
            Route::get('/get-addresses', [AddressController::class, 'getAddresses']);
            Route::get('/show/{id}', [AddressController::class, 'show']);
            Route::post('/add', [AddressController::class, 'add']);
            Route::post('/update/{id}', [AddressController::class, 'update']);
            Route::delete('/destroy/{id}', [AddressController::class, 'destroy']);
            Route::post('is-default/{id}', [AddressController::class, 'isDefault']);
            Route::get('default-address', [AddressController::class, 'defaultAddress']);
        });

        Route::group(['prefix' => 'orders'], function () {
            Route::post('/', [OrderController::class, 'orders']);
            Route::post('/details', [OrderController::class, 'orderDetails']);
            Route::post('/add', [OrderController::class, 'add']);
            Route::post('/track', [OrderController::class, 'trackOrder']);

            //            Route::post('update_cart_qty', [OrderController::class, 'updateQty']);
        });

        Route::post('apply-coupon', [OrderController::class, 'applyCoupon']);

        Route::group(['prefix' => 'reviews'], function () {
            //reviews
            Route::post('/add', [ReviewController::class, 'add']);
            Route::get('/show', [ReviewController::class, 'show']);
        });

        Route::group(['prefix' => 'favorites'], function () {
            Route::get('/{sort}', [FavoriteController::class, 'index']);
            Route::post('meal/{meal}', [MealController::class, 'addToFavorite']);
            Route::post('restaurant/{restaurant}', [RestaurantController::class, 'addToFavorite']);
        });

        Route::group(['prefix' => 'meals-for-auth'], function () {
            // Get Meal Details by meal_id
            Route::get('show', [MealController::class, 'authShow']);

            // Get Meals By restaurant_id and tag_id
            Route::get('/', [MealController::class, 'authIndex']);
        });

        Route::get('restaurants-for-auth/{restaurant_id}', [RestaurantController::class, 'authShow']);

        // Get category minions [restaurants, recommended meals,  offer meals]
        Route::get('category-minions-for-auth', [CategoryController::class, 'minions']);

        Route::get('auth-popular-restaurants', [RestaurantController::class, 'authPopularRestaurants']);
        Route::get('auth-fast-delivery', [RestaurantController::class, 'authFastDelivery']);
        Route::get('auth-popular-meals', [MealController::class, 'authPopularMeals']);

        //        Route::get('restaurants/{category}', [RestaurantController::class, 'index']);


        // Get Recommended Meals By Restaurant Id
        Route::get('get_meals_recommended/{category}', [MealController::class, 'recommended']);

        // Get Offer Meals By Restaurant Id
        Route::get('get_meals_offers/{category}', [MealController::class, 'mealOffers']);





        // Route::resource('orders', OldOrderController::class);
//        Route::get('orders',[OldOrderController::class,'index']);
//        Route::post('orders',[OldOrderController::class,'store']);
//        Route::get('orders/{order}',[OldOrderController::class,'show']);
//        Route::get('get_history_order',[OldOrderController::class,'getHistoryOrder']);
//        Route::get('get_upcoming_order',[OldOrderController::class,'getUpcomingOrder']);
//        Route::post('assign_driver_to_order/{order}', [OldOrderController::class, 'assignToDriver']);
//        Route::post('get_order_delivery_time/{order}', [OldOrderController::class, 'getOrderDeliveryTime']);
//        Route::get('getOrderStatus/{order}',[OldOrderController::class,'getOrderStatus']);
//        Route::get('getOrderTrack/{order}',[OldOrderController::class,'getOrderTrack']);
        //Route::get('get_orders_history/{order}', [OldOrderController::class, 'getOrderHistory']);

        Route::get('statuses', [StatusController::class, 'index']);






        //search
        Route::get('recent-searches', [SearchHistoryController::class, 'recentSearches']);
        Route::post('recent-searches/delete', [SearchHistoryController::class, 'deleteSearches']);


    });


    Route::get('/login/{provider}', [AuthController::class, 'redirectToProvider']);
    Route::get('/login/{provider}/callback', [AuthController::class, 'handleProviderCallback']);

    Route::post('/message', [MessageController::class, 'broadcast']);

    Route::get('about', [AboutUsController::class, 'index']);
    Route::get('term', [AboutUsController::class, 'terms']);

    Route::get('/storage-link', function () {
        Artisan::call('storage:link');
    });

});







//////////////////////////////////


Route::post('create-user', [UserController::class, 'register']);
Route::post('sign-user', [UserController::class, 'sign_in']);
Route::post('create-driver', [DriverController::class, 'register']);
Route::post('sign-driver', [DriverController::class, 'sign_in']);