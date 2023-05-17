<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\AddonDataTable;
use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\Category;
use App\Upload\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index(AddonDataTable $dataTable)
    {
        return $dataTable->render('admin.addons.index');
    }
    public function indexTwo()
    {
        return view('admin.addons.indextwo', ['addons' => Addon::get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.addons.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->has('main_image'))
        {
            $request->merge(['image' => Upload::uploadImage($request->main_image, 'addons' , $request->name)]);
        }

        $validator = Validator::make($request->all(),
            [
                'name' => 'required|string|min:2',
                'main_image' => 'mimes:jpeg,jpg,png,gif|required|max:10000',
                'price' => 'required|numeric'
            ]);

        if($validator->fails()){
            toastr()->error('Check Errors In Add Category Form');

            return redirect()->back()->withErrors($validator);
        }

        $addons = Addon::create($request->except('main_image'));

        toastr()->success('Addons Added Successfully');

        return redirect()->route('addons.index')->with('status', 'Addons Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Addon  $addon
     * @return \Illuminate\Http\Response
     */
    public function show(Addon $addon)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Addon  $addon
     * @return \Illuminate\Http\Response
     */
    public function edit(Addon $addon)
    {
        return view('admin.addons.edit',['addon' => $addon]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Addon  $addon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Addon $addon)
    {
        if($request->has('main_image'))
        {
            $request->merge(['image' => Upload::uploadImage($request->main_image, 'addons' , $request->name)]);
        }

        $validator = Validator::make($request->all(),
            [
                'name' => 'required|string|min:2',
                'price' => 'required|numeric'
            ]);

        if($validator->fails()){
            toastr()->error('Check Errors In Edit Addons Form');

            return redirect()->back()->withErrors($validator);
        }

        $addon->update($request->except('main_image'));

        toastr()->success('Addons Updated Successfully');

        return redirect()->route('addons.index')->with('status', 'Addons Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Addon  $addon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Addon $addon)
    {
        toastr()->success('Addons Deleted Successfully');

        $addon->delete();

        return redirect()->route('addons.index')->with('status', 'Addons Deleted Successfully');
    }
}
