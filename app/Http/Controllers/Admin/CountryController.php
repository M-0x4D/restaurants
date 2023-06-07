<?php

namespace App\Http\Controllers\Admin;


use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Language;
use App\Upload\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\DataTables\CountryDataTable;


class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function index(CountryDataTable $dataTable)
    {
        return $dataTable->render('admin.countries.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
                return view('admin.countries.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Country::create([
            'name' => $request->name,
            'flag' => 'storage/countries/'. Upload::uploadImage( $request->main_image , 'countries' , $request->name),
            'code' => $request->code
            ]);
            toastr()->success('Country Added Successfully');

        return redirect()->route('countries.index')->with('status', 'Country Created Successfully');
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
    public function edit(Country $country)
    {
        
        
        return view('admin.countries.edit',
            ['country' => $country]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country)
    {
        // dd($request->all());
        $country->update([
            'name' => $request->name,
            'code' => $request->code,
            'flag' =>  'storage/countries/'. Upload::uploadImage( $request->main_image , 'countries' , $request->name)
            ]);
        
        toastr()->success('Country Added Successfully');

        return redirect()->route('countries.index')->with('status', 'Country Created Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {

        toastr()->success('Country Deleted Successfully');
        $country->delete();

        return redirect()->route('countries.index')->with('status', 'Country Created Successfully');
    }
}
