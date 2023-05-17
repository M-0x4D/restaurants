<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index()
    {

        $offers=Offer::latest()->paginate(15);
        return response()->json([
            'status' => 200,
            'message' => __('meals.data_retrieved_success'),
            'errors' => null,
            'result' => 'success',
            'data' => ['offers' => OfferResource::collection($offers)->response()->getData(true)]
        ], 200);

    }
}
