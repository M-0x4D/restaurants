<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CouponsDataTable;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\CouponRequest;
use App\Models\Coupon;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CouponsDataTable $datatable)
    {
        return $datatable->render('admin.coupons.index');
    }
    public function indexTwo()
    {
        return view('admin.coupons.indexTwo' , ['coupons' => Coupon::get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.coupons.add' , ['restaurants' => Restaurant::join('restaurant_translations' , 'restaurants.id' , '=' , 'restaurant_translations.restaurant_id')
        ->where('language_id' , Helper::currentLanguage(App::getLocale())->id)->get()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(CouponRequest $request) // CouponRequest
    {
       $data =  $request->validated();
       $data['is_active'] = 1;
        Coupon::create($data);
        return redirect()->route('coupons.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', [ 'coupon' => $coupon,'restaurants' => Restaurant::join('restaurant_translations' , 'restaurants.id' , '=' , 'restaurant_translations.restaurant_id')
        ->where('language_id' , Helper::currentLanguage(App::getLocale())->id)->get()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CouponRequest $request , $id)
    {
        $data = $request->validated();
        $data['is_active'] = 1;
        Coupon::where('id' , $id)->update($data);
        return redirect()->route('coupons.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Coupon::where('id' , $id)->delete();
        return redirect()->route('coupons.index');
    }
}
