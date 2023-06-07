<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\RestaurantsDataTable;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\RestaurantFormRequest;
use App\Models\Category;
use App\Models\Day;
use App\Models\Meal;
use App\Models\OrderItem;
use App\Models\Restaurant;
use App\Models\RestaurantTranslation;
use App\Upload\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(RestaurantsDataTable $dataTable)
    {
        return $dataTable->render('admin.restaurants.index');
    }

    public function indexTwo()
    {
        // $protocol = (!empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) ? 'https://' : 'http://';
        // $host = $_SERVER['HTTP_HOST'];
        $restaurants = Restaurant::join('restaurant_translations' , 'restaurants.id' , '=' , 'restaurant_translations.restaurant_id')
        ->where('language_id' , Helper::currentLanguage(App::getLocale())->id )->get();
        // foreach ($restaurants as $restaurant)
        // {
        //     $restaurant['image'] = $protocol . $host . '/' . $restaurant['image'];
        // }
        // dd(Restaurant::join('restaurant_translations' , 'restaurants.id' , '=' , 'restaurant_translations.restaurant_id')->where('language_id' , Helper::currentLanguage(App::getLocale())->id )->get());
        return view('admin.restaurants.indextwo', ['restaurants' => $restaurants]);
    }

/**
     * Display list of orders of all restaurant.
     *
     * @return \Illuminate\Http\Response
     */

    public function indexOrder($restaurant)
    {
        $the_restaurant=Restaurant::whereId($restaurant)->get();
        $meal=[];
        foreach($the_restaurant as $the_meal)
        {

            $meals=$the_meal->meals->pluck('id');
            array_push($meal,$meals);

        }

        $order=OrderItem::whereIn('meal_id',[$meal])->get();



       return view('admin.restaurants.indexOrder', ['order' => $order]);





    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.restaurants.add', ['categories' => Category::join('category_translations' , 'categories.id' , '=' , 'category_translations.category_id')
        ->where('language_id' , Helper::currentLanguage(App::getLocale())->id)->get()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RestaurantFormRequest $request)
    {
        $latlngArray = explode(',' , $request->input('latlng'));

        $request->merge(['lat' => $latlngArray[0], 'lng' => $latlngArray[1], 'image' => Upload::uploadImage($request->main_image, 'restaurants' , $request->name), 'cover' => Upload::uploadImage($request->main_cover, 'restaurants' , $request->name.'-cover')]);
        $data = $request->except('latlng','weekhours','main_image','main_cover' , 'name_ar' , 'name_en' , 'name_fr' , 'description_ar' , 'description_en' , 'description_fr');
        $data['image'] = "storage/restaurants/" . $data['image'];
        $data['cover'] = "storage/restaurants/" . $data['cover'];
        $restaurant = Restaurant::create($data);
        $langs = Helper::languages();
        foreach ($langs as $key => $lang) {
            $restaurant->translations()->create(
                [
                    'name' => $request->{'name_' . $lang->local},
                    'description' => $request->{'description_' . $lang->local},
                    'language_id' => $lang->id,
                    'restaurant_id' => $restaurant->id
                ]
            );
        }
        $days = Day::get()->pluck('id');
        for ($i=0; $i < count($request->weekhours['from']) ; $i++) {
            $restaurant->weekHours()->create(['day_id' => $days[$i], 'from' => $request->weekhours['from'][$i], 'to' => $request->weekhours['to'][$i]]);
        }

        toastr()->success('Restaurant Added Successfully');

        return redirect()->route('restaurants.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function show(Restaurant $restaurant)
    {


        $bestMeal = Meal::whereRestaurantId($restaurant->id)->join('meal_translations' , 'meals.id' , '=' , 'meal_translations.meal_id')
        ->where('language_id' , Helper::currentLanguage(App::getLocale())->id)
        ->with(['reviews' => function($query) {
            $query->orderByDesc('rating');
        }])->first();

        $bestMeals = Meal::whereRestaurantId($restaurant->id)->join('meal_translations' , 'meals.id' , '=' , 'meal_translations.meal_id')
        ->where('language_id' , Helper::currentLanguage(App::getLocale())->id)
        ->with(['reviews' => function($query) {
            $query->orderByDesc('rating');
        }])->take(6)->get();

        $restaurant = $restaurant->where('restaurant_id' , $restaurant->id)->join('restaurant_translations' , 'restaurants.id' , '=' , 'restaurant_translations.restaurant_id')
        ->where('language_id' , Helper::currentLanguage(App::getLocale())->id)->first();

        $restaurant->bestMeal = $bestMeal;
        $restaurant->bestMeals = $bestMeals;


        return view('admin.restaurants.show', ['restaurant' => $restaurant]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function edit(Restaurant $restaurant)
    {
        return view('admin.restaurants.edit', [
            'restaurant' => $restaurant->join('restaurant_translations' , 'restaurants.id' , '=' , 'restaurant_translations.restaurant_id')
            ->where('restaurant_id' , $restaurant->id)->get(),


            'categories' => Category::join('category_translations' , 'categories.id' , '=' , 'category_translations.category_id')
            ->where('language_id' , Helper::currentLanguage(App::getLocale())->id)->get()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function update(RestaurantFormRequest $request, Restaurant $restaurant)
    {


        if ($request->latlng != null) {
            $latlngArray = explode(',' , $request->input('latlng'));

            $request->merge(['lat' => $latlngArray[0], 'lng' => $latlngArray[1]]);
        }

        if($request->has('main_image'))
        {
            $request->merge(['image' => Upload::uploadImage($request->main_image, 'restaurants' , $request->name)]);
        }

        if($request->has('main_cover'))
        {
            $request->merge(['cover' => Upload::uploadImage($request->main_cover, 'restaurants' , $request->name.'-cover')]);
        }

        $restaurant->update($request->except('latlng','weekhours','main_image','main_cover' , 'name_ar' , 'name_en' , 'name_fr' , 'description_ar' , 'description_en' , 'description_fr'));

        $langs = Helper::languages();

        $i = 0;
        foreach ($langs as $key => $lang) {

            $restaurant->translations[$i]->update(
                [
                    'name' => $request->{'name_' . $lang->local},
                    'description' => $request->{'description_' . $lang->local},
                    'language_id' => $lang->id,
                    'restaurant_id' => $restaurant->id
                ]
            );

            $i++;
        }

        $days = Day::get()->pluck('id');
        $restaurant->weekHours()->delete();
        for ($i=0; $i < count($request->weekhours['from']) ; $i++) {
            $restaurant->weekHours()->create(['day_id' => $days[$i], 'from' => $request->weekhours['from'][$i], 'to' => $request->weekhours['to'][$i]]);
        }

        toastr()->success('Restaurant Edited Successfully');

        return redirect()->route('restaurants.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Restaurant $restaurant)
    {
        $restaurant->delete();

        toastr()->success('Restaurant Deleted Successfully');

        return redirect()->route('restaurants.index');
    }

    public function list($id)
    {
        return Restaurant::where('category_id' , $id)
        ->join('restaurant_translations' , 'restaurants.id' , '=' , 'restaurant_translations.restaurant_id')
        ->where(['language_id' => Helper::currentLanguage(App::getLocale())->id ])->get();
    }
}
