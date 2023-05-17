<?php

namespace App\Http\Requests\Api\User\Address;

use App\Http\Requests\Api\ApiMasterRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

/**
 * @property mixed $default
 */
class AddAddressRequest extends ApiMasterRequest
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

    public function rules()
    {
        $phoneRegex = '/^[1]{1}[0-9]{9}$/';
        return [
            'title' =>  'required|string|min:3|max:30',
            'country_code' => 'required|exists:countries,code',
            'phone' => 'required|regex:'.$phoneRegex,
            'address' => 'required|min:3|max:500',
            'lat' =>  'required|numeric',
            'lng' =>  'required|numeric',
            'default' =>  'required|in:0,1',


        ];
    }

    public function attributes()
    {
        return [
            'title' =>  __('addresses.title'),
            'country_code' => __('addresses.country_code'),
            'phone' =>  __('addresses.phone'),
            'address' =>  __('addresses.address'),
            'lat' =>  __('addresses.lat'),
            'lng' => __('addresses.lng'),
            'default' => __('addresses.default'),

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
