<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'name_ar' => 'required',
            'name_en' => 'required',
            'name_fr' => 'required',
            'main_image' => 'mimes:jpeg,jpg,png,gif|required|max:10000', 
            'color' => 'required'
    ];
    }



    /**
 * Get the error messages for the defined validation rules.
 *
 * @return array<string, string>
 */
public function messages(): array
{
    return [
        'name_ar.required' => 'A name_ar is required',
        'name_en.required' => 'A name_en is required',
    ];
}


public function attributes()
{
    return [
        
    ];
}


}
