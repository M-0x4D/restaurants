<?php

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Api\ApiMasterRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;


/**
 * @property mixed $phone
 * @property mixed $password
 * @property mixed $country_code
 */
class LoginRequest extends ApiMasterRequest
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
        $countryCodeRegex = "/^[00|+]{1}([0-9]{1,4})$/";

        return [
            'country_code' => ['required', 'exists:countries,code'],
            'phone'=>'required|exists:users,phone',
            'password'=>'required'
        ];
    }

    public function attributes()
    {
        return [
            'country_code'=> __('users.country_code'),
            'phone'=> __('users.phone'),
            'password'=> __('users.password')
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
