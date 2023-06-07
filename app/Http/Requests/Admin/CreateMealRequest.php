<?php

namespace App\Http\Requests\Admin;

use App\Upload\Upload;
use Illuminate\Support\Facades\App;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;


use Illuminate\Foundation\Http\FormRequest;

class CreateMealRequest extends FormRequest
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
     
     // runs secend
    public function rules()
    {
        
        return [
            'main_image' => 'required',
            'ingredients.*.main_image' => 'required',
            'drinks.*.main_image' => 'required' ,
            'sides.*.main_image' => 'required'
            
        ];
    }
    
    
    
    // runs third
    public function messages(): array
{
    //   foreach ($this->messages() as $message)
    //     {
    //         toastr()->error($message);
    //     }
    

    return [
        'main_image.required' => 'A meal image is required',
        'ingredients.*.main_image.required' => 'An ingredients image is required',
        'drinks.*.main_image.required' => 'A drinks image is required' , 
        'sides.*.main_image.required' => 'A sides image is required',
        
    ];
}
    
        // $request->merge(['image' =>  Upload::uploadImage($request->main_image, 'meals' , $request->name) , 'category_id' => $restaurant->category_id]);
    
    
    // runs first
    protected function prepareForValidation(): void
{
    $this->main_image ?$this->merge(['image' =>  Upload::uploadImage($this->main_image, 'meals' , $this->name)]) : '';
    
}




}
