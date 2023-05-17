<?php

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Api\ApiMasterRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

/**
 * @property mixed $phone
 * @property mixed $otp
 * @property mixed $country_code
 */
class CheckOtpRequest extends ApiMasterRequest
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
        $phoneRegex = '/^[0-9]{9,10}$/';
        $countryCodeRegex = "/^[00|+]{1}([0-9]{1,4})$/";

        return [
            'otp' => 'required|exists:users,otp',
            'country_code' => ['required', 'exists:countries,code'],
            'phone' => 'required|regex:'.$phoneRegex.'|exists:users,phone',
            'operation_type' => 'required|in:reset_password,register',
        ];
    }

    public function attributes()
    {
        return [
            'otp' => __('users.otp'),
            'country_code' => __('users.country_code'),
            'phone' => __('users.phone'),
            'operation_type' => __('users.operation_type'),
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
