<?php

namespace App\Http\Requests\Api\Review;
use App\Http\Requests\Api\ApiMasterRequest;
class AddReviewRequest extends ApiMasterRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type'=>'required|in:meal,restaurant',
            'reviewable_id'=>'required|numeric',
            'rating' => 'required|numeric|between:1,5',
            'comment' => 'nullable|string|max:255',

        ];
    }
}
