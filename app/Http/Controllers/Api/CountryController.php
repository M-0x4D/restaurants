<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Helper\Helper;

class CountryController extends Controller
{
    public function index($country_id = null)
    {
        $countries = Country::select(['id', 'name', 'code', 'flag']);
        if (!count($countries->cursor())){
            return Helper::responseJson(422 , 'failed' , null , ['default' => __('countries.no_data')] , null , 422 );
            // return response()->json([
            //     'status' => 422,
            //     'message' => null,
            //     'errors' => ['default' => __('countries.no_data')],
            //     'result' => 'failed',
            //     'data' => null
            // ], 422);
        }

        $data = [];
        if ($country_id){
            $countries = $countries->find($country_id);
            $data = CountryResource::make($countries);
        } else {
            $countries = $countries->cursor();
            $data = CountryResource::collection($countries);
        }

        return Helper::responseJson(200 , 'success' , __('countries.data_retrieved_success') ,null,['country' => $data] ,  200);
        // return response()->json([
        //     'status' => 200,
        //     'message' => __('countries.data_retrieved_success'),
        //     'errors' => null,
        //     'result' => 'success',
        //     'data' => ['country' => $data]
        // ], 200);

    }
}
