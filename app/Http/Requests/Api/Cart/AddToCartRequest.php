<?php

namespace App\Http\Requests\Api\Cart;

use App\Http\Requests\Api\ApiMasterRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

/**
 * @property mixed $coupon_id
 */
class AddToCartRequest extends ApiMasterRequest
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
        $rules = [
            'address_id' => 'required|exists:addresses,id',
            'restaurant_id' => 'required|exists:restaurants,id',
            'coupon_id' => 'nullable|exists:coupons,id',
        ];
//        return [
//            'restaurant_id' => 'required|exists:restaurants,id',
//            'meal_id' => 'required|array|exists:meals,id',
//            'size_id' => 'required|array|exists:sizes,id',
//            'ingredients' => 'nullable', // |exists:ingredients,id
//            'addons' => 'nullable', // |exists:addons,id
//            'drinks' => 'nullable|array', // |exists:drinks,id
//            'sides' => 'nullable', // |exists:sides,id
//            'qty' => 'required|array|min:3|max:3',
//            'notes' => 'required|array|min:3|max:5',
//            'total' => 'required',
//            'sub_total' => 'required',
//            'delivery_fees' => 'required',
//        ];


        $rules['total'] = 'required';
        $rules['sub_total'] = 'required';
        $rules['delivery_fees'] = 'required';
        $rules['discount_amount'] = 'required';

        return $rules;
    }

    public function attributes()
    {
        return [
            'address_id' => __('orders.address_id'),
            'restaurant_id' => __('orders.restaurant_id'),
            'coupon_id' => __('orders.coupon_id'),
            'meal_id' => __('orders.meal_id'),
            'size_id' => __('orders.size_id'),
            'ingredients' => __('orders.ingredients'),
            'addons' => __('orders.addons'),
            'drinks' => __('orders.drinks'),
            'sides' => __('orders.sides'),
            'qty' => __('orders.qty'),
            'notes' => __('orders.notes'),
            'total' => __('orders.total'),
            'sub_total' => __('orders.sub_total'),
            'delivery_fees' => __('orders.delivery_fees'),
            'discount_amount' => __('orders.discount_amount'),
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



//    public function getValidatorInstance()
//    {
//        $data = $this->all();
//                if (isset($data['sides']) && $data['sides']) {
//                    $data['sides'] = json_encode($data['sides'],true);
//                }
////                if (isset($data['options']) && $data['options']) {
////                    $data['options'] = json_encode($data['options'],true);
////                } if (isset($data['drinks']) && $data['drinks']) {
////                    $data['drinks'] = json_encode($data['drinks'],true);
////                }
//        $this->getInputSource()->replace($data);
//        return parent::getValidatorInstance();
//    }


}
