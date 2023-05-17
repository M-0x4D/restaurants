<?php

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Api\ApiMasterRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends ApiMasterRequest
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
            'old_password' => ['required','min:6',function ($attribute, $value, $fail) {
                if (! \Hash::check($value, auth()->user()->password)) {
                    $fail(trans('Old password not correct'));
                }
            }],
            'password' => 'required|min:6',
        ];
    }
}
