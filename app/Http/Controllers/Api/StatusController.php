<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Status;
use Illuminate\Http\Request;
use App\Helper\Helper;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
return Helper::responseJson(200 , 'success' , 'statuses retrieved Successfully' , null ,Status::all() , 200 );
        // return response()->json(['message' => 'statuses retrieved Successfully', 'status' => 200, 'data' => Status::all()], 200);
    }


}
