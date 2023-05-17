<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UpdateProfile extends FormRequest
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
            'avatar' => 'nullable|mimes:jpeg,jpg,png',
            'name' => 'required|min:3|max:50',
//            'email' => [
//                'required',
//                'email',
//                'max:50',
//                Rule::unique('users')->ignore($this->user? $this->user->id : 0, 'id')
//            ],
//            'phone' => [
//                'required',
//                'min:3',
//                'max:50',
//                Rule::unique('users')->ignore($this->user? $this->user->id : 0, 'id')
//            ],
        ];
    }

    public function attributes()
    {
        return [
            'avatar' => __('users.avatar'),
            'name' => __('users.name'),
            'email' => __('users.email'),
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
