<?php

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Api\ApiMasterRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

/**
 * @property mixed $phone
 * @property mixed $old_password
 * @property mixed $id
 * @property mixed $otp
 * @property mixed $password
 * @property mixed $country_code
 */
class ChangePasswordRequest extends ApiMasterRequest
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
        $rules = [];
        $countryCodeRegex = "/^[00|+]{1}([0-9]{1,4})$/";

        if (auth()->user()){
            $rules['old_password'] = 'required';
        } else {
            $rules['country_code'] = ['required', 'regex:'.$countryCodeRegex];
            $rules['phone'] = 'required|exists:users,phone';
            $rules['otp'] = 'required|exists:users,otp';
        }
        $rules['password'] = 'required|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/';
        $rules['confirm_password'] = 'required|same:password';

        return $rules;
    }

    public function attributes()
    {
        return [
            'old_password' => __('users.old_password'),
            'country_code' => __('users.country_code'),
            'phone' => __('users.phone'),
            'otp' => __('users.otp'),
            'password' => __('users.password'),
            'confirm_password' => __('users.confirm_password'),
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
