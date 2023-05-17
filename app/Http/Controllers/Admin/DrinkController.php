<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\DrinkDataTable;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Drink;
use App\Upload\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;


class DrinkController extends Controller
{

    public function index(DrinkDataTable $dataTable)
    {
        return $dataTable->render('admin.drinks.index');
    }

    public function indexTwo()
    {
        return view('admin.drinks.indextwo', ['drinks' => Drink::join('drink_translations' , 'drinks.id' , '=' , 'drink_translations.drink_id')
        ->where(['language_id' => Helper::currentLanguage(App::getLocale())->id])->get()]);
    }

    public function store(Request $request)
    {

        // image name on server using name_en
        $request->merge(['image' => Upload::uploadImage($request->main_image , 'drinks' , $request->name_en. rand(0 , 5000))])->all();
        $data = $request->except( 'main_image', 'name_ar' , 'name_en' , 'name_fr');
        $drink = Drink::create($data);
        $langs = Helper::languages();
        foreach ($langs as $key => $lang) {
            $drink->translations()->create([
                'name' => $request->{'name_' . $lang->local},
                'drink_id' => $drink->id ,
                'language_id' => $lang->id
            ]);
        }
        toastr()->success('Category Added Successfully');
        return redirect()->route('drinks.index');
    }
    public function show()
    {
        // dd('fkrjf');
        return view('admin.drinks.add');
    }



    public function edit(Drink $drink)
    {

        return view('admin.drinks.edit', [ 'drink' => $drink->join('drink_translations' , 'drinks.id' , '=' , 'drink_translations.drink_id')
        ->where('drink_id' , $drink->id)->get()]);
    }


    public function update(Request $request , Drink $drink)
    {
        // dd($request->name_en);

        if($request->has('main_image'))
        {
            $request->merge(['image' => Upload::uploadImage($request->main_image, 'drinks' , $request->name_en)]);
        }

        $validator = Validator::make($request->all(), ['name_ar' => 'required']);

        if($validator->fails()){
            toastr()->error('Check Errors In Edit Category Form');

            return redirect()->back()->withErrors($validator);
        }


        $drink->update($request->except('main_image' , 'name_ar' , 'name_en' , 'name_fr'));

        $languages = Helper::languages();
        $i = 0;
        foreach ($languages as $language) {
        $drink->translations[$i]->update([
            'name' => $request->{'name_'.$language->local},
            'drink_id' => $drink->id,
            'language_id' => $language->id
        ]);
        $i++;
       }

        toastr()->success('Drink Updated Successfully');

        return redirect()->route('drinks.index')->with('status', 'Drink Updated Successfully');

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Drink  $drink
     * @return \Illuminate\Http\Response
     */
    public function destroy(Drink $drink)
    {
        $drink->delete();

        toastr()->success('Drink Removed Successfully');

        return redirect()->back();
    }
}
