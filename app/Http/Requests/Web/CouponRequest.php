<?php

namespace App\Http\Requests\Web;

use App\Models\Restaurant;
use Attribute;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
//        dd('ok');
        return [
            'restaurant_id' => 'required',
            'code' => 'required' ,
            'discount_percentage' => 'required' ,
            'available_users' => 'required' ,
            'used_count' => 'required' ,
            'start_date' => 'required' ,
            'expire_date' => 'required' ,
        ];
    }


    public function attributes()
    {
        return [
          'restaurant_id' => __('coupons.restaurant_id')  ,
          'code' => __('coupons.code')
        ];
    }



}
