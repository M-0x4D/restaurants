<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

/**
 * @property mixed $country_code
 */
class FirstCompleteRegisterRequest extends ApiMasterRequest
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
//        $phoneRegex = "/^[0-9]{11}$/";
        $phoneRegex = '/^[1]{1}[0-9]{9}$/';
        $countryCodeRegex = "/^[00|+]{1}([0-9]{1,4})$/";

        return [
            'name' => 'required|min:3|max:30',
//            'email' => 'required|email|unique:users,email',
            'country_code' => ['required', 'exists:countries,code'],
            'phone' => 'required|regex:'.$phoneRegex.'|numeric|unique:users,phone',
            'provider' => 'nullable|in:facebook,twitter,google,apple',
            'provider_id' => 'nullable|string|between:2,255',
            'password' => 'required|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'terms' => 'required|in:1',
            'lang' => 'nullable',
//            'confirm_password' => 'required|same:password',


        ];
    }

    public function attributes()
    {
        return [
            'name' => __('users.name'),
            'email' => __('users.email'),
            'phone' => __('users.phone'),
            'password' => __('users.password'),
            'confirm_password' => __('users.confirm_password'),
            'provider' => __('users.provider'),
            'provider_id' => __('users.provider_id'),
            'country_code' => __('users.country_code'),
            'terms' => __('users.terms'),
        ];
    }

    public function messages()
    {
        return[
            'password.regex'=> __('users.password_validation'),
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
