<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ReviewsMealsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewsMealsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ReviewsMealsDataTable $dataTable)
    {
     return $dataTable->render('admin.reviews-meals.index');
        
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexTwo()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit( $id)
    {
        return view('admin.reviews-meals.edit');
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Review::where('reviewable_type' , Meal::class)->where('id' , $id)->update([
            'comment' => $request->comment
        ]);
        return redirect('reviewsmeals.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Review::where('reviewable_type' , Meal::class)->where('id' , $id)->delete();
    }
}
