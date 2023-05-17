<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutResource;
use App\Http\Resources\TermResource;
use App\Models\About;
use App\Models\Term;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    public function index(){
        $about = About::cursor();
        $data = AboutResource::collection($about);
        return response()->json([
            'status' => 200,
            'message' => __('abouts.data_retrieved_success'),
            'errors' => null,
            'result' => 'success',
            'data' => ['about' => $data]
        ], 200);

    }


    public function terms()
    {

       $terms = Term::cursor();
       $data = TermResource::collection($terms);
        return response()->json([
            'status' => 200,
            'message' => __('terms.data_retrieved_success'),
            'errors' => null,
            'result' => 'success',
            'data' => ['about' => $data]
        ], 200);
    }
}
