<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\Address\IsDefaultRequest;
use App\Http\Requests\Api\User\Address\AddAddressRequest;
use App\Http\Resources\Api\Address\AddressResource;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Helper\Helper;

class AddressController extends Controller
{
    public function getAddresses()
    {
        $addresses =   Address::where('user_id', auth()->id())->orderByDesc('default')->cursor();
        return Helper::responseJson(200, 'success', __('addresses.data_retrieved_success'), null, ['address' => AddressResource::collection($addresses)], 200);
        // return response()->json([
        //     'status' => 200,
        //     'message' => __('addresses.data_retrieved_success'),
        //     'errors' => null,
        //     'result' => 'success',
        //     'data' => ['address' => AddressResource::collection($addresses)]
        // ], 200);

    }
    public function show($id)
    {
        $address = Address::where([
            'id' => $id,
            'user_id' => auth()->id(),
        ])->first();

        if (!$address){

            return Helper::responseJson(422, 'failed', ['default' => __('main.error_message')], null, null , 422);
            // return response()->json([
            //     'status' => 422,
            //     'message' => null,
            //     'errors' => ['default' => __('main.error_message')],
            //     'result' => 'failed',
            //     'data' => null
            // ], 422);
        }

        return Helper::responseJson(200, 'success', __('addresses.data_retrieved_success'), null, ['address' => AddressResource::make($address)], 200);
        // return response()->json([
        //     'status' => 200,
        //     'message' => __('addresses.data_retrieved_success'),
        //     'errors' => null,
        //     'result' => 'success',
        //     'data' => ['address' => AddressResource::make($address)]
        // ], 200);

    }
    public function add(AddAddressRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();
        $data['user_id'] = $user->id;

        $isHaveDefaultAddress = Address::where(['user_id' => $user->id, 'default' => 1])->first();

        // Check if it's first address, must be default = 1
        if (!$isHaveDefaultAddress){
            $data['default'] = 1;
        }

        $this->checkAddresses($request->default);
        Address::create($data);
        $addresses = Address::where(['user_id' => auth()->id()])->orderByDesc('default')->cursor();
        return Helper::responseJson(200, 'success', __('addresses.address_saved_success'), null, ['address' => AddressResource::collection($addresses)], 200);

        // return response()->json([
        //     'status' => 200,
        //     'message' => __('addresses.address_saved_success'),
        //     'errors' => null,
        //     'result' => 'success',
        //     'data' => ['address' => AddressResource::collection($addresses)]
        // ], 200);
    }

    public function update(AddAddressRequest $request, $id)
    {
        $address = Address::where(['id' => $id, 'user_id' => auth()->id()])->first();
        try {
            $this->checkAddresses($request->default, $address->id);
            $address->update($request->validated());

            $addresses = Address::where(['user_id' => auth()->id()])->orderByDesc('default')->cursor();
        return Helper::responseJson(200, 'success', __('addresses.address_saved_success'), null, ['address' => AddressResource::collection($addresses)], 200);

            // return response()->json([
            //     'status' => 200,
            //     'message' => __('addresses.address_saved_success'),
            //     'errors' => null,
            //     'result' => 'success',
            //     'data' => ['address' => AddressResource::collection($addresses)]
            // ], 200);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        return Helper::responseJson(422, 'failed', null, ['default' => __('main.error_message')], null, 422);

            // return response()->json([
            //     'status' => 422,
            //     'message' => null,
            //     'errors' => ['default' => __('main.error_message')],
            //     'result' => 'failed',
            //     'data' => null
            // ], 422);

        }
    }
    public function destroy($id)
    {
        $address = Address::where([
            'id' => $id,
            'user_id' => auth()->id(),
        ])->first();

        if (!$address){
            return Helper::responseJson(422, 'failed', null, ['default' => __('main.error_message')], null, 422);

            // return response()->json([
            //     'status' => 422,
            //     'message' => null,
            //     'errors' => ['default' => __('main.error_message')],
            //     'result' => 'failed',
            //     'data' => null
            // ], 422);
        }

        $address->delete();

        $addresses = Address::where(['user_id' => auth()->id()])->cursor();
        return Helper::responseJson(200, 'success', __('addresses.data_deleted_success'), null, ['address' => AddressResource::collection($addresses)], 200);

        // return response()->json([
        //     'status' => 200,
        //     'message' => __('addresses.data_deleted_success'),
        //     'errors' => null,
        //     'result' => 'success',
        //     'data' => ['address' => AddressResource::collection($addresses)]
        // ], 200);
    }

    public function isDefault(IsDefaultRequest $request, $id)
    {
        $address = Address::where([
            'id' => $id,
            'user_id' => auth()->id(),
        ])->first();
        if (!$address){
            return Helper::responseJson(422, 'failed', null, ['default' => __('main.error_message')], null, 422);

            // return response()->json([
            //     'status' => 422,
            //     'message' => null,
            //     'errors' => ['default' => __('main.error_message')],
            //     'result' => 'failed',
            //     'data' => null
            // ], 422);
        }
        $this->checkAddresses($request->default, $address->id);
        $address->update($request->validated());
        return Helper::responseJson(200, 'success', __('addresses.address_saved_success'), null, ['address' => AddressResource::make($address)], 200);
        
        // return response()->json([
        //     'status' => 200,
        //     'message' => __('addresses.address_saved_success'),
        //     'errors' => null,
        //     'result' => 'success',
        //     'data' => ['address' => AddressResource::make($address)]
        // ], 200);
    }
    public function defaultAddress()
    {
    return Helper::responseJson(200, 'success', 'Default Address Retrieved successfully', null, Address::whereDefault(1)->first(), 200);

        // return response()->json(['message' => 'Default Address Retrieved successfully', 'status' => 200, 'data' => Address::whereDefault(1)->first()], 200);
    }
    private  function checkAddresses($isDefault, $addressId = null)
    {
        if ($isDefault == 1) {
            Address::where(['user_id' => auth()->id(), 'default' => 1])
            ->when($addressId, function ($query) use ($addressId){
                $query->where('id', '!=', $addressId);
            })->update(['default' => 0]);
        }
        return true;
    }


}
