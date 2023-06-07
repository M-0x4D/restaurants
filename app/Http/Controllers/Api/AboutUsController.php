<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutResource;
use App\Http\Resources\TermResource;
use App\Models\About;
use App\Models\Term;
use Illuminate\Http\Request;
use App\Helper\Helper;

class AboutUsController extends Controller
{
    public function index()
    {
        $about = About::cursor();
        $data = AboutResource::collection($about);
        return Helper::responseJson(200, 'success', __('abouts.data_retrieved_success'), null, $data, 200);
        // return response()->json([
        //     'status' => 200,
        //     'message' => __('abouts.data_retrieved_success'),
        //     'errors' => null,
        //     'result' => 'success',
        //     'data' => ['about' => $data]
        // ], 200);

    }


    public function terms()
    {

        $terms = Term::cursor();

        //   dd($terms);
        $data = TermResource::collection($terms);

        return Helper::responseJson(200, 'success', _('terms.data_retrieved_success'), null, $data, 200);
        // return response()->json([
        //     'status' => 200,
        //     'message' => __('terms.data_retrieved_success'),
        //     'errors' => null,
        //     'result' => 'success',
        //     'data' => ['about' => $data]
        // ], 200);
    }
}