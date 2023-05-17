<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class UpdatePhoneRequest extends FormRequest
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
        return [
            'country_code' => ['required', 'exists:countries,code'],
            'phone' => ['required', 'regex:'.$phoneRegex],
        ];
    }

    public function attributes()
    {
        return [
            'country_code' => __('users.country_code'),
            'phone' => __('users.phone'),
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
