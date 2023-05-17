<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\TagsDataTable;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Tag;
use App\Models\TagTranslation;
use App\Upload\Upload;
use Carbon\Traits\Localization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Mcamara\LaravelLocalization\LaravelLocalization;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TagsDataTable $dataTable)
    {
        return $dataTable->render('admin.tags.index');
    }

    public function indexTwo()
    {
        return view('admin.tags.indextwo', ['tags' => Tag::where('parent_id' , null)->join('tag_translations' , 'tags.id' , '=' , 'tag_translations.tag_id')->where('language_id' , Helper::currentLanguage(App::getLocale())->id)->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tags.add', ['restaurants' => Restaurant::join('restaurant_translations' , 'restaurants.id' , '=' , 'restaurant_translations.restaurant_id')->where('language_id' , Helper::currentLanguage(App::getLocale())->id )->get()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['name_ar' => 'required']);

        if($validator->fails()){
            toastr()->error('Check Errors In Add Tag Form');

            return redirect()->back()->withErrors($validator);
        }
        $data = $request->except('name_ar' , 'name_en' , 'name_fr');
        $tag = Tag::create($data);
        $langs = Helper::languages();
        foreach ($langs as $key => $lang) {
            $tag->translations()->create([
                'name' => $request->{'name_' . $lang->local},
                'tag_id' => $tag->tag_id,
                'language_id' => $lang->id
            ]);
        }

        toastr()->success('Tag Added Successfully');

        return redirect()->route('tags.index')->with('status', 'Tag Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {

        // $category->join('category_translations' , 'categories.id' , '=' , 'category_translations.category_id')
        // ->where('category_id' , $category->id)->get()
        // dd($tag->join('tag_translations' , 'tags.id' , '=' , 'tag_translations.tag_id')
        // ->where('language_id' , Helper::currentLanguage(App::getLocale())->id)->get());
        return view('admin.tags.edit', ['tag' => $tag->join('tag_translations' , 'tags.id' , '=' , 'tag_translations.tag_id')
        ->where('tag_id' , $tag->id)->get(), 
        'restaurants' => Restaurant::join('restaurant_translations' , 'restaurants.id' , '=' , 'restaurant_translations.restaurant_id')
        ->where('language_id' , Helper::currentLanguage(App::getLocale())->id)->get()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        // dd($);
        $languages = Helper::languages();
        $validator = Validator::make($request->all(), ['name_ar' => 'required']);

        if($validator->fails()){
            toastr()->error('Check Errors In Edit Tag Form');

            return redirect()->back()->withErrors($validator);
        }
        // dd($request->all());

        $tag->update($request->except('name_ar' , 'name_en' , 'name_fr'));

        $i = 0;
        foreach ($languages as $key => $language) {

            $tag->translations[$i]->update([
            'name' => $request->{'name_'.$language->local},
            'tag_id' => $tag->id , 
            'language_id' => $language->id
            ]);
            $i++;
        }

        toastr()->success('Tag Updated Successfully');

        return redirect()->route('tags.index')->with('status', 'Tag Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        toastr()->success('Tag Deleted Successfully');

        $tag->delete();

        return redirect()->route('tags.index')->with('status', 'Tag Deleted Successfully');
    }
}
