<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\WeekHourDataTable;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Day;
use App\Models\Restaurant;
use App\Models\WeekHour;
use App\Upload\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class WeekHourController extends Controller
{
    public function index(WeekHourDataTable $dataTable)
    {
        return $dataTable->render('admin.week_hours.index');
    }
    public function indexTwo()
    {
        // dd(WeekHour::join('days' , 'days.id' , '=' , 'week_hours.day_id')
        // ->join('day_translations' , 'days.id' , '=' , 'day_translations.day_id')
        // // ->join('restaurants' , 'week_hours.restaurant_id' , '=' , 'restaurants.id')
        // // ->join('restaurant_translations' , 'week_hours.restaurant_id' , '=' , 'restaurant_translations.restaurant_id')
        // // ->select('restaurant_translations.name AS resName')
        // ->where('language_id' , Helper::currentLanguage(App::getLocale())->id)->get());
        return view('admin.week_hours.indextwo', ['week_hours' => 
        WeekHour::join('days' , 'days.id' , '=' , 'week_hours.day_id')
        
        // join('restaurant_translations' , 'week_hours.restaurant_id' , '=' , 'restaurant_translations.restaurant_id')
        ->join('day_translations' , 'days.id' , '=' , 'day_translations.day_id')
        ->where('language_id' , Helper::currentLanguage(App::getLocale())->id)->get()]);
    }


    public function create()
    {
        return view('admin.week_hours.add',['restaurants' => Restaurant::join('restaurant_translations' , 'restaurants.id' , '=' , 'restaurant_translations.restaurant_id')
        ->where('language_id' , Helper::currentLanguage(App::getLocale())->id)->get(),'days'=>Day::join('day_translations' , 'days.id' , '=' , 'day_translations.day_id')
        ->where('language_id' , Helper::currentLanguage(App::getLocale())->id)->get()]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),
            [
                'restaurant_id' => 'required|exists:restaurants,id',
                'day_id' => 'required|exists:days,id',
                'from' => 'required',
                'to' => 'required',
            ]);
//dd($request->all());
        if($validator->fails()){
            toastr()->error('Check Errors In Add Week Hours Form');

            return redirect()->back()->withErrors($validator);
        }



//        $restaurant= WeekHour::find($request->restaurant_id);
//        $day= WeekHour::find($request->day_id);
//        if ($restaurant->restaurant_id && $day->day_id)
//        {
//            toastr()->error('Check Errors In Add WeekHours Form');
//
//            return redirect()->back()->with('status', 'Tag Not Found');
//        }


        $week_hours = WeekHour::create($request->all());


        toastr()->success('Week Hours Added Successfully');

        return redirect()->route('week_hours.index')->with('status', 'Week Hours Created Successfully');




    }

    public function show(WeekHour $weekHour)
    {

    }


    public function edit(WeekHour $weekHour)
    {
        return view('admin.week_hours.edit',['weekHour' => $weekHour,'restaurants' => Restaurant::all(),'days'=>Day::all()]);
    }


    public function update(Request $request, WeekHour $weekHour)
    {
        $validator = Validator::make($request->all(),
            [
                'restaurant_id' => 'required|exists:restaurants,id',
                'day_id' => 'required|exists:days,id',
                'from' => 'required',
                'to' => 'required',
            ]);

        if($validator->fails()){
            toastr()->error('Check Errors In Edit Week Hours Form');

            return redirect()->back()->withErrors($validator);
        }

        $weekHour->update($request->all());

        toastr()->success('Week Hours Updated Successfully');

        return redirect()->route('week_hours.index')->with('status', 'Week Hours Updated Successfully');
    }


    public function destroy(WeekHour $weekHour)
    {
        toastr()->success('Week Hours Deleted Successfully');

        $weekHour->delete();

        return redirect()->route('week_hours.index')->with('status', 'Week Hours Deleted Successfully');
    }


}
