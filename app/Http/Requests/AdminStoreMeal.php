<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminStoreMeal extends FormRequest
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
            'main_image' => 'required'
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
