<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CategoriesDataTable;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\CategoryRequest;
use App\Models\Category;
use App\Models\Language;
use App\Upload\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CategoriesDataTable $dataTable)
    {
        return $dataTable->render('admin.categories.index');
    }

    public function indexTwo()
    {
        return view('admin.categories.indextwo', ['categories' => Category::join('category_translations' , 'categories.id' , '=' , 'category_translations.category_id')->where('language_id' , Helper::currentLanguage(App::getLocale())->id)->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categories.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        if($request->has('main_image'))
        {
            $request->merge(['image' => Upload::uploadImage($request->main_image, 'categories' , $request->name_en)]);
        }

        // $validator = Validator::make($request->all(), ['name_ar' => 'required','name_en' => 'required','name_fr' => 'required', 'main_image' => 'mimes:jpeg,jpg,png,gif|required|max:10000', 'color' => 'required']);

        // if($validator->fails()){
        //     toastr()->error('Check Errors In Add Category Form');

        //     return redirect()->back()->withErrors($validator);
        // }
        $data = $request->except(['main_image' , 'name_ar' , 'name_en' , 'name_fr']);
        $category = Category::create($data);
        $languages = Helper::languages();
        
        foreach ($languages as $language) {
        $category->translations()->create([
            'name' => $request->{'name_'.$language->local},
            'category_id' => $category->id,
            'language_id' => $language->id
        ]);
       }

        toastr()->success('Category Added Successfully');

        return redirect()->route('categories.index')->with('status', 'Category Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        // dd($category->join('category_translations' , 'categories.id' , '=' , 'category_translations.category_id')
        // ->where('category_id' , $category->id)->get());
        return view('admin.categories.edit', [
            'category' => $category->join('category_translations' , 'categories.id' , '=' , 'category_translations.category_id')
        ->where('category_id' , $category->id)->get()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        if($request->has('main_image'))
        {
            $request->merge(['image' => Upload::uploadImage($request->main_image, 'categories' , $request->name_en)]);
        }

        $validator = Validator::make($request->all(), ['name_ar' => 'required', 'color' => 'required']);

        if($validator->fails()){
            toastr()->error('Check Errors In Edit Category Form');

            return redirect()->back()->withErrors($validator);
        }


        $category->update($request->except('main_image' , 'name_ar' , 'name_en' , 'name_fr'));

        $languages = Helper::languages();
        $i = 0;
        foreach ($languages as $language) {
        $category->translations[$i]->update([
            'name' => $request->{'name_'.$language->local},
            'category_id' => $category->id,
            'language_id' => $language->id
        ]);
        $i++;
       }

        toastr()->success('Category Updated Successfully');

        return redirect()->route('categories.index')->with('status', 'Category Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        toastr()->success('Category Deleted Successfully');

        $category->delete();

        return redirect()->route('categories.index')->with('status', 'Category Deleted Successfully');
    }
}
