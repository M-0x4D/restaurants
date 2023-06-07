<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Helper\Helper;

class TagController extends Controller
{

    /**
     * Get sub tags by tag_id
     * @param $tag_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($tag_id)
    {
        $tag = Tag::find($tag_id);
        if (!$tag){
            return response()->json([
                'status' => 422,
                'message' => null,
                'errors' => ['default' => __('tags.no_data')],
                'result' => 'failed',
                'data' => null
            ], 422);
        }

        return response()->json([
            'status' => 200,
            'message' => __('restaurants.data_retrieved_success'),
            'errors' => null,
            'result' => 'success',
            'data' => ['tag' => TagResource::collection($tag->subTags)]
        ], 200);
    }
}
