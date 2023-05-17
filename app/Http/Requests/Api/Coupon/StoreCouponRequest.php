<?php

namespace App\Http\Requests\Api\Coupon;

use App\Http\Requests\Api\ApiMasterRequest;
class StoreCouponRequest extends ApiMasterRequest
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
            'address_id'=>'required',
            'pay_type'=>'required|in:cash,online',
            'transaction_id'=>'required_if:pay_type,online',
            'coupon_code'=>'nullable'

        ];
    }

/*
    public function getValidatorInstance()
    {
        $data = $this->all();
        if (isset($data['start_date']) && $data['start_date'] != null) {
            $data['start_date'] = date('Y-m-d', strtotime($data['start_date']));
        }
        if (isset($data['expire_date']) && $data['expire_date'] != null) {
            $data['expire_date'] = date('Y-m-d', strtotime($data['expire_date']));
        }
        $this->getInputSource()->replace($data);
        return parent::getValidatorInstance();
    }


*/


}
