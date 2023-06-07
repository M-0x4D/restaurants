<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Profile;
use App\Models\User;
use App\Upload\Upload;
use Illuminate\Http\Request;
use Validator;
use App\Helper\Helper;

class ProfileController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user=auth()->user();
        $profile=Profile::where('user_id',$user->id)->get();

        $validator = Validator::make(
           $request->all(), [
            'email'    => 'nullable|email|unique:users,email,'.$user->id,
            'avatar_image' => 'nullable|mimes:jpeg,jpg,png,gif',
            /** to ignore the unique */
            'telephone' => 'nullable|unique:users,email,'.$user->id,
        ]);



        if ($validator->fails()) {
        return Helper::responseJson(422, 'failed', 'something wrong please try again', $validator->errors(), null, 422);
            
            // return response()->json(['message' => 'something wrong please try again', 'status' => 400 ,'errors' => $validator->errors()], 400);
        }

        if ($request->email !== $user->email) {

            $request->merge(['email' => $request->email]);

        }


        // if($request->has('avatar_img')){

        //    $file_name='avatar_'.$user->id.'.'.$request->avatar_img->extension();
        //    $img = $request->avatar_img->move(storage_path('users'),$file_name);

        //    $request->merge(['avatar'=>$file_name]);

        //     //

        // }

        // dd( $request->all());

        $user->update($request->except('telephone', 'avatar'));

        $user->profile->update($request->only('telephone','avatar'));

        return Helper::responseJson(200, 'success', 'user and profile update successfully', null, $user, 422);

        // return response()->json(['message' => 'user and profile update successfully', 'status' => 200], 200);
    }
}
