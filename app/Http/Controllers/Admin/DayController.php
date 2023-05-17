<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\DatDataTable;
use App\DataTables\DaysDataTable;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Day;
use App\Models\DayTranslation;
use App\Upload\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class DayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DaysDataTable $dataTable)
    {
        return $dataTable->render('admin.days.index');
    }
    public function indexTwo()
    {
        return view('admin.days.indextwo', ['days' => Day::join('day_translations' , 'days.id' , '=' , 'day_translations.day_id')
        ->where('language_id' , Helper::currentLanguage(App::getLocale())->id)->get()]);
    }

    public function create()
    {
        return view('admin.days.add');
    }


    public function show(Day $day)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),
            [
                'name_ar' => 'required|string|min:5',
            ]);

        if($validator->fails()){
            toastr()->error('Check Errors In Add Days Form');

            return redirect()->back()->withErrors($validator);
        }

        $day = Day::create($request->except('name_ar' , 'name_en' , 'name_fr' , '_token'));
        $langs = Helper::languages();
        foreach ($langs as $lang) {
            // dd($lang->id);
            $day->translations()->create([
                'name' => $request->{'name_' . $lang->local},
                'day_id' => $day->id ,
                'language_id' => $lang->id
            ]);
        }
        toastr()->success('Day Added Successfully');

        return redirect()->route('days.index')->with('status', 'Day Created Successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Day $day)
    {
        return view('admin.days.edit',['day' => $day->join('day_translations' , 'days.id' , '=' , 'day_translations.day_id')
        ->where('language_id' , Helper::currentLanguage(App::getLocale())->id)->first()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, Day $day)
    {
        
        $langs = Helper::languages();

        $validator = Validator::make($request->all(),
            [
                'name_ar' => 'required|string|min:2',
            ]);

        if($validator->fails()){
            toastr()->error('Check Errors In Edit Days Form');

            return redirect()->back()->withErrors($validator);
        }

        $day->update($request->except('name_ar' , 'name_en' , 'name_fr'));
        $i=0;
        foreach ($langs as $key => $lang) {

            $day->translations[$i]->update([
                'name' => $request->{'name_'.$lang->local},
                'day_id' => $day->id,
                'language_id' => $lang->id
            ]);
            $i++;
        }
        toastr()->success('Day Updated Successfully');

        return redirect()->route('days.index')->with('status', 'Days Updated Successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Day $day)
    {

        $day->delete();

        toastr()->success('Day Removed Successfully');

        return redirect()->back();
    }
}
