<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\ApiMasterRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;


/**
 * @property mixed $phone
 * @property mixed $password
 * @property mixed $country_code
 */
class ApplyCouponRequest extends ApiMasterRequest
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
            'coupon_code' => 'required|exists:coupons,code',
            'restaurant_id'=>'required|exists:restaurants,id',
        ];
    }

    public function attributes()
    {
        return [
            'coupon_code'=> __('orders.coupon_code'),
            'restaurant_id'=> __('orders.restaurant_id'),
        ];
    }


    protected function failedValidation(Validator $validator) :void
    {
        // Change response attitude if the request done via Ajax requests like API requests
        $errors = (new ValidationException($validator))->errors();
        $message = $validator->errors()->first();
        throw new HttpResponseException(response()->json([
            'status' => 422,
            'message'=> null,
            'errors' => $errors,
            'result' => 'failed',
            'data' => null
        ], 422));
    }

}
