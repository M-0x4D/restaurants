<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SubTagsDataTable;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class SubTagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SubTagsDataTable $dataTable, Tag $tag)
    {
        return $dataTable->with('tag', $tag)->render('admin.subtags.index', ['tag' => $tag]);
    }

    public function indexTwo(Tag $tag)
    {
        // dd($tag->subTags()->join('tag_translations' , 'tags.id' , '=' , 'tag_translations.tag_id')->where('language_id' , Helper::currentLanguage(App::getLocale())->id)->get());
        return view('admin.subtags.indextwo', [
            'subtags' => $tag->subTags()->join('tag_translations' , 'tags.id' , '=' , 'tag_translations.tag_id')
            ->where('language_id' , Helper::currentLanguage(App::getLocale())->id)->get(), 
            'tag' => $tag]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Tag $tag)
    {
        return view('admin.subtags.add', ['tag' => $tag]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Tag $tag)
    {
        $langs = Helper::languages();
        $validator = Validator::make($request->all(), ['name_ar' => 'required']);

        if($validator->fails()){
            toastr()->error('Check Errors In Add Tag Form');

            return redirect()->back()->withErrors($validator);
        }

        // $request->merge(['restaurant_id' => $tag->restaurant_id]);
        $subTag = $tag->subTags()->create([$request->except('name_ar' , 'name_en' , 'name_fr'), 'parent_id' => $tag->id , 'restaurant_id' => $tag->restaurant_id ]);
        // dd($subTag->id);
        foreach ($langs as $key => $lang) {
            $subTag->translations()->create([
                'name' => $request->{'name_' . $lang->local},
                'tag_id' => $subTag->id,
                'language_id' => $lang->id
            ]);
        }
        toastr()->success('Sub Tag Added Successfully');

        return redirect()->route('subtags.index', $tag)->with('status', 'Sub Tag Created Successfully');
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
    public function edit(Tag $tag, tag $subtag)
    {
        return view('admin.subtags.edit',[
            'tag' => $tag, 'subtag' => $subtag->join('tag_translations' , 'tags.id' , '=' , 'tag_translations.tag_id')
            ->where('parent_id' , $tag->id)->get()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag, Tag $subtag)
    {
        $langs = Helper::languages();

        $validator = Validator::make($request->all(), ['name_ar' => 'required']);

        if($validator->fails()){
            toastr()->error('Check Errors In Edit Tag Form');

            return redirect()->back()->withErrors($validator);
        }

        $request->merge(['restaurant_id' => $tag->restaurant_id]);

        $subtag->update($request->except('name_ar' , 'name_en' , 'name_fr'));

        $i=0;
        foreach ($langs as $key => $lang) {
            $subtag->translations[$i]->update([
                'name' => $request->{'name_' . $lang->local},
                'tag_id' => $subtag->id,
                'language_id' => $lang->id
            ]);
            $i++;
        }

        toastr()->success('Tag Updated Successfully');

        return redirect()->route('subtags.index', $tag)->with('status', 'Tag Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag, Tag $subtag)
    {
        $subtag->delete();

        toastr()->success('Sub Tag Deleted Successfully');

        return redirect()->back()->with('status', 'Category Deleted Successfully');
    }

    /**
     *  List SubTags
     *
     * @param \App\Models\Tag $tag
     * @return \Illuminate\Http\Response
     */
    public function list(Tag $tag)
    {
        // dd(Tag::join('tag_translations' , 'tags.id' , '=' , 'tag_translations.tag_id')
        // // ->where('tag_id' , $tag->tag_id)
        // // ->where('language_id' , Helper::currentLanguage(App::getLocale())->id)
        // ->get());

        return $tag->subTags()->join('tag_translations' , 'tags.id' , '=' , 'tag_translations.tag_id')
        ->where('language_id' , Helper::currentLanguage(App::getLocale())->id)->get();
        // return Tag::join('tag_translations' , 'tags.id' , '=' , 'tag_translations.tag_id')
        // ->where('language_id' , Helper::currentLanguage(App::getLocale())->id)
        // ->where('parent_id' , $tag->id)
        // ->get();


        // return Tag::where('parent_id' , $tag->id)->join('tag_translations' , 'tags.id' , '=' , 'tag_translations.tag_id')
        // ->where('language_id' , Helper::currentLanguage(App::getLocale())->id)->get();


        // return $tag->subTags()->join('tag_translations' , 'tags.id' , '=' , 'tag_translations.tag_id')
        // ->where('parent_id' , $tag->id)->get();

        // return $tag->join('tag_translations' , 'tags.id' , '=' , 'tag_translations.tag_id')
        // ->where('language_id' , Helper::currentLanguage(App::getLocale())->id)
        // ->where('tag_id' , $tag->id)
        // ->get()->toArray();
    }
}
