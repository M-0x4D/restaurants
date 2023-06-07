<?php

namespace App\Http\Controllers\Api;

use App\Helper\UploadFilesHelper;
use App\Http\Requests\Api\User\CheckEmailRequest;
use App\Http\Requests\Api\User\CheckOtpForUpdatePhoneRequest;
use App\Http\Requests\Api\User\UpdateEmailRequest;
use App\Http\Requests\Api\User\UpdateProfile;
use App\Http\Requests\UpdatePhoneRequest;
use App\Jobs\EmailOtpJob;
use App\Models\Profile;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;
use App\Models\User;
use App\Mail\sendOtp;
use App\Helper\Helper;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Api\User\LoginRequest;
use App\Http\Requests\Api\User\ChangePasswordRequest;
use App\Http\Requests\Api\User\CheckOtpRequest;
use App\Http\Resources\User\UserSimpleResource;
use App\Http\Requests\Api\User\CheckPhoneRequest;
use App\Http\Requests\Api\User\SocialLoginRequest;
use App\Http\Requests\Api\User\UpdatePasswordRequest;
use App\Http\Requests\Api\FirstCompleteRegisterRequest;
use App\Http\Requests\Api\User\CheckOtpForEmailRequest;
use PHPUnit\TextUI\Help;

class UserController extends Controller
{

    public $helper;
    public function __construct()
    {
        $this->helper=new helper();
    }


    public $successStatus = 200;
    /**
     * login api
     *
     */
    public function login(LoginRequest $request){

        $user = User::where(['phone' => $request->phone, 'country_code' => $request->country_code])->first();
        if (!$user){
            
            return Helper::responseJson(422, 'failed' , null , ['default' => [__('users.country_code_phone_error')]] , null ,422);
            // return response()->json([
            //     'status' => 422,
            //     'message' => null,
            //     'errors' => ['default' => [__('users.country_code_phone_error')]],
            //     'result' => 'failed',
            //     'data' => null
            // ], 422);
        }

        /**
         * Examination of the validity of the otp,
         * whether it passed more than half an hour or not
         */
        $otpValid = $user->otp_valid ? Carbon::make($user->otp_valid ?? null)->addMinutes(30)->format('Y-m-d H:i') : null;
        $now = Carbon::now()->format('Y-m-d H:i');
        if ($user->otp && $otpValid && $otpValid < $now){
            $data =[ 
                        'user' =>
                            [
                                'otp' => (int) $user->otp,
                                'phone' => $user->phone,
                                'country_code' => $user->country_code,
                                'last_step' => 'verify',
                            ]
                    ];
            return Helper::responseJson(200 , 'success' , null , null , $data , 200 );
            // return response()->json([
            //     'status' => 200,
            //     'message' => null,
            //     'last_step' => 'verify',
            //     'otp' => (int) $user->otp,
            //     'errors' => null,
            //     'result' => 'success',
            //     'data' => [
            //         'user' =>
            //             [
            //                 'otp' => (int) $user->otp,
            //                 'phone' => $user->phone,
            //                 'country_code' => $user->country_code,
            //             ]
            //     ]
            // ], 200);
        }

        $auth = Auth::attempt(['phone' => $request->phone, 'password' => $request->password], true);
        if (!$auth){
            return response()->json([
                'status' => 422,
                'message' => null,
                'errors' => ['default' => [__('auth.failed')]],
                'result' => 'failed',
                'data' => null
            ], 422);
                        // return Helper::responseJson(422, 'failed' , 'test' , null ,null ,422);

            
        }

        $user = Auth::user();
        
        /**
         * If user not active or not complete register then prevent login
         */
        if(!$user->is_active){
            return response()->json([
                'status' => 200,
                'message' => null,
                'last_step' => 'verify',
//                'errors' => ['default' => [__('auth.failed')]],
                'result' => 'success',
                'data' => [
                    'user' =>
                        [
                            'otp' => (int) $user->otp,
                            'phone' => $user->phone,
                            'country_code' => $user->country_code,
                        ]
                ]
            ], 200);
        }

        $token =  $user->createToken('MyLaravelApp')->accessToken;
        $data = UserSimpleResource::make($user);
        $data = data_set($data, 'token', $token);
                    // return Helper::responseJson(200, 'success' , 'test' , null , $data ,200);
        return response()->json([
            'status' => 200,
            'message' => __('users.login_success'),
            'errors' => null,
            'result' => 'success',
            'data' => ['user' => $data]
        ], 200);
    }

    /**
     * Register
     * @param FirstCompleteRegisterRequest $request
     * @return JsonResponse
     */
    public function register(FirstCompleteRegisterRequest $request)
    {
//        return response()->json([
//            'data' => $request->all()
//        ], 422);

        DB::beginTransaction();
        try {
                $user_data = ['name', 'phone', 'country_code', 'lang', 'provider','provider_id', 'password'];
                $otp = 1234 ?? rand(0000,9999);
                $data['otp'] = $otp;
                $data['phone'] = $request->phone;
                $data['country_code'] = $request->country_code;
                $data['lang'] = $request['lang'];
                $user = User::create(Arr::only($request->validated(), $user_data) + ['otp' => $otp, 'otp_valid' => now(), 'is_active' => 0]);

                if($request->provider && $request->provider_id){
                    $user->providers()->create(['provider'=>$request->provider, 'provider_id'=>$request->provider_id,'is_active' => 0]);
                }
                 @$user->profile()->create();
                 DB::commit();
//                 $data['token'] = $user->createToken('API Token')->accessToken;
                return response()->json([
                    'status' => 200,
                    'message' => __('users.register_first_phase_success'),
                    'errors' => null,
                    'result' => 'success',
                    'data' => ['user' => $data]
                ], 200);
            } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 422,
                'message' => null,
                'errors' => ['default' => [__('auth.failed')]],
                'result' => 'failed',
                'data' => null
            ], 422);
        }

    }

    public function updateProfile(UpdateProfile $request)
    {
        $user = auth()->user();
        $data = $request->validated();
        $user->update($data);

        if ($user->profile){
            if ($data['avatar'] ?? null){
                $data['avatar'] = UploadFilesHelper::upload($request->avatar, 'profile');
                $user->profile->update($data);
            } else {
                $data['avatar'] = $user->profile->avatar;
            }
        }

        if (!$user->profile){
            if ($data['avatar'] ?? null){
                $data['avatar'] = UploadFilesHelper::upload($request->avatar, 'profile');
                $user->profile()->create($data);
            } else {
                $data['avatar'] = 'avatar/avatar.png';
            }
        }

        $data['avatar'] = Helper::getFullPath($data['avatar']);
        return response()->json([
            'status' => 200,
            'message' => __('users.profile_update_success'),
            'errors' => null,
            'result' => 'success',
            'data' => ['user' => $data]
        ], 200);
    }

    public function updatePhone(UpdatePhoneRequest $request)
    {
        $user = Auth::user();

        if($request->phone == $user->phone){
            return response()->json([
                'status' => 422,
                'message' => null,
                'errors' => ['default' => [__('users.change_phone_validation')]],
                'result' => 'failed',
                'data' => null
            ], 422);
        }

        $otp = 1111 ?? rand(0000,9999);
        $user->update(['otp' => $otp, 'otp_valid' => now()]);
        $data = [
            'phone' => $request->phone,
            'country_code' => $request->country_code,
            'otp' => $otp,
        ];
        return response()->json([
            'status' => 200,
            'message' => __('users.otp_send_successfully'),
            'errors' => null,
            'result' => 'success',
            'data' => ['user' => $data]
        ], 200);

    }

    /**
     * Check phone and send otp
     * @param CheckPhoneRequest $request
     * @return JsonResponse
     */
    public function checkPhone(CheckPhoneRequest $request)
    {

        $user = User::where(['phone' => $request->phone, 'country_code' => $request->country_code])->first();
        if (!$user){
            return response()->json([
                'status' => 422,
                'message' => null,
                'errors' => ['default' => [__('users.country_code_phone_error')]],
                'result' => 'failed',
                'data' => null
            ], 422);
        }

        if (!$user->is_active){
            return response()->json([
                'status' => 422,
                'message' => null,
                'errors' => ['default' => [__('users.please_complete_register')]],
                'result' => 'failed',
                'data' => null
            ], 422);
        }

        $otp = 1234 ?? rand(0000,9999);
        $user->update(['otp' => $otp, 'otp_valid' => now()]);
        $data = [
            'phone' => $request->phone,
            'country_code' => $request->country_code,
            'otp' => $otp,
        ];
        return response()->json([
            'status' => 200,
            'message' => __('users.otp_send_successfully'),
            'errors' => null,
            'result' => 'success',
            'data' => ['user' => $data]
        ], 200);


    }

    /**
     * check otp
     * @param CheckOtpRequest $request
     * @return JsonResponse
     */
    public function checkOtp(CheckOtpRequest $request)
    {
        $user = User::where(['otp' => $request->otp, 'phone' => $request->phone]);

        if ($request->operation_type == 'register'){
            $user->whereNull('phone_verified_at');
        }
        if ($request->operation_type == 'reset_password'){
            $user->whereIsActive(1);
        }

        $user = $user->first();
        if (!($user->id ?? null)) {
            return response()->json([
                'status' => 422,
                'message' => null,
                'errors' => ['default' => [__('auth.failed')]],
                'result' => 'failed',
                'data' => null
            ], 422);
        }

        if ($user->country_code != $request->country_code){
            return response()->json([
                'status' => 422,
                'message' => null,
                'errors' => ['default' => [__('users.country_code_phone_error')]],
                'result' => 'failed',
                'data' => null
            ], 422);
        }

        $data = [];
        /**
         * In case user in register
         */
        if ($request->operation_type == 'register'){
            $data['id'] = $user->id;
            $data['name'] = $user->name;
            $data['email'] = $user->email;
            $data['phone'] = $user->phone;
            $data['country_code'] = $user->country_code;
            $data['avatar'] = Helper::getFullPath($this->profile->avatar ?? 'avatar/avatar.png');
            $data['token'] =  $user->createToken('MyLaravelApp')->accessToken;
            $user->update(['otp' => null, 'otp_valid' => null, 'phone_verified_at' => now(),'is_active' => 1]);
        }

        /**
         * In case user not logged in
         */
        if ($request->operation_type == 'reset_password'){
            $data['otp'] = intval( $request->otp);
            $data['phone'] = $request->phone;
            $data['country_code'] = $request->country_code;

        }

        return response()->json([
            'status' => 200,
            'message' => __('users.otp_verified_success'),
            'errors' => null,
            'result' => 'success',
            'data' => ['user' => $data]
        ], 200);

    }

    public function checkOtpForUpdatePhone(CheckOtpForUpdatePhoneRequest $request)
    {
        $user = Auth::user();

        if ($user->otp != $request->otp){
            return response()->json([
                'status' => 422,
                'message' => null,
                'errors' => ['default' => [__('users.otp_not_equal')]],
                'result' => 'failed',
                'data' => null
            ], 422);
        }

        if($request->phone == $user->phone){
            return response()->json([
                'status' => 422,
                'message' => null,
                'errors' => ['default' => [__('users.change_phone_validation')]],
                'result' => 'failed',
                'data' => null
            ], 422);
        }

        $data = [
            'country_code' => $request->country_code,
            'phone' => $request->phone,
        ];

        $user->update(['otp_valid' => null, 'otp' => null, 'country_code' => $request->country_code, 'phone' => $request->phone]);
        return response()->json([
            'status' => 200,
            'message' => __('users.phone_update_success'),
            'errors' => null,
            'result' => 'success',
            'data' => ['user' => $data]
        ], 200);

    }

    public function updateEmail(UpdateEmailRequest $request)
    {
        $user = Auth::user();

        $otp = 1234 ?? rand(0000,9999);

        // EmailOtpJob::dispatch([
        //     'email' => $request->email,
        //     'otp' => $otp,
        //     'user' => $user->name,
        // ], 'emails.email_otp');

        $user->update(['otp' => $otp, 'otp_valid' => now()]);

        $data = [
            'email' => $request->email,
            'otp' => $otp,
            'user_id' => $user->id,
        ];
        return response()->json([
            'status' => 200,
            'message' => __('users.otp_email_send_successfully'),
            'errors' => null,
            'result' => 'success',
            'data' => ['user' => $data]
        ], 200);

    }
    public function checkOtpForEmail(CheckOtpForEmailRequest $request)
    {

        $user = User::where(['otp' => $request->otp,
            'id' => $request->user_id])->first();

        if (!($user->id ?? null)) {
            return response()->json([
                'status' => 422,
                'message' => null,
                'errors' => ['default' => [__('auth.failed')]],
                'result' => 'failed',
                'data' => null
            ], 422);
        }

        $user->update(['email' => $request->email, 'otp' => null, 'otp_valid' => null]);

        return response()->json([
            'status' => 200,
            'message' => __('users.email_change_success'),
            'errors' => null,
            'result' => 'success',
            'data' => []
        ], 200);
    }


    /**
     * Change password when user id authenticated
     * @param ChangePasswordRequest $request
     * @return JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request)
    {

        $user = Auth::user();

        if ($request->old_password == $request->password){
            return response()->json([
                'status' => 422,
                'message' => null,
                'errors' => ['change_password' => [__('users.old_pass_match_new')]],
                'result' => 'failed',
                'data' => null
            ], 422);
        }

        if (!Hash::check($request->old_password, $user->password)){
            return response()->json([
                'status' => 422,
                'message' => null,
                'errors' => ['change_password' => [__('users.old_pass_not_correct')]],
                'result' => 'failed',
                'data' => null
            ], 422);
        }

        $user->update(['password' => $request->password]);
        return response()->json([
            'status' => 200,
            'message' => __('users.password_changed_success'),
            'errors' => null,
            'result' => 'success',
            'data' => null
        ], 200);

    }

    /**
     * Reset password when user no authenticated
     * @param ChangePasswordRequest $request
     * @return JsonResponse
     */
    public function forgetPassword(ChangePasswordRequest $request)
    {
        $user = User::where(['phone' => $request->phone, 'otp' => $request->otp, 'country_code' => $request->country_code])->first();
        if (!($user->id ?? null)) {
            return response()->json([
                'status' => 422,
                'message' => null,
                'errors' => ['default' => [__('auth.failed')]],
                'result' => 'failed',
                'data' => null
            ], 422);
        }

        $user->update(['password' => $request->password, 'otp' => null]);
        return response()->json([
            'status' => 200,
            'message' => __('users.password_changed_success'),
            'errors' => null,
            'result' => 'success',
            'data' => null
        ], 200);

    }

    /**
     * Resend OTP
     * send otp again
     */
    public function resendOtp(CheckPhoneRequest $request)
    {
        $user = User::where(['phone' => $request->phone, 'country_code' => $request->country_code])->first();
        if (!$user){
            return response()->json([
                'status' => 422,
                'message' => null,
                'errors' => ['default' => [__('users.country_code_phone_error')]],
                'result' => 'failed',
                'data' => null
            ], 422);
        }

        $otp = 1111 ?? rand(0000,9999);
        $user->update(['otp' => $otp]);
        $data = ['phone' => $request->phone, 'country_code' => $request->country_code, 'otp' => $otp];
        return response()->json([
            'status' => 200,
            'message' => __('users.otp_send_successfully'),
            'errors' => null,
            'result' => 'success',
            'data' => ['user' => $data]
        ], 200);


    }

    public function resendOtpForEmail(UpdateEmailRequest $request)
    {
        $user = Auth::user();

        $otp = 1111;
        // $otp = 1234 ?? rand(0000,9999);

        // EmailOtpJob::dispatch([
        //     'email' => $request->email,
        //     'otp' => $otp,
        //     'user' => $user->name,
        // ], 'emails.email_otp');

        $user->update(['otp' => $otp, 'otp_valid' => now()]);

        $data = [
            'email' => $request->email,
            'otp' => $otp,
            'user_id' => $user->id,
        ];
        return response()->json([
            'status' => 200,
            'message' => __('users.otp_email_send_successfully'),
            'errors' => null,
            'result' => 'success',
            'data' => ['user' => $data]
        ], 200);

    }




    /**
     * Get user details
     * @return JsonResponse
     */
    public function userDetails()
    {
        $user = Auth::user();
        $token =  $user->createToken('MyLaravelApp')->accessToken;
        data_set($user,'token' , $token);

        return response()->json([
            'status' => 200,
            'message' => __('users.data_retrieved_success'),
            'errors' => null,
            'result' => 'success',
            'data' => ['user' => UserSimpleResource::make($user)]
        ], 200);
    }

    /**
     * Logout
     * @return JsonResponse
     */
    public function logout()
    {
//        $user = Auth::user()->token();
//        $user->revoke();

        $user = Auth::user();
        $tokens =  $user->tokens->pluck('id');
        Token::whereIn('id', $tokens)
            ->update(['revoked'=> true]);

        RefreshToken::whereIn('access_token_id', $tokens)->update(['revoked' => true]);

        return response()->json([
            'message' => __('users.logout_success'),
            'errors' => null,
            'result' => 'success',
            'data' => null
        ], 200);
    }

    public function socialLogin(SocialLoginRequest $request)
    {

        $user = User::whereHas('providers', function ($query) use ($request) {
            $query->where(['provider' => $request->provider, 'provider_id' => $request->provider_id]);
        })->first();
        if (!$user) {
            /*
            $user = User::create(['name' => $request->name,'email'=>$request->email, 'email_verified_at' => now()]);
            $user->providers()->create(Arr::except($request->validated(), ['device_type', 'device_token']));
            $user->providers()->create(['provider'=>$request->provider, 'provider_id'=>$request->provider_id]);
            */
            return response()->json([
                'has_profile' => 0,
                'status' => 422,
                'message'=> null,
                'errors' => ['social' => __('users.user_not_in_system')],
                'result' => 'failed',
                'data' => null
            ],422);
        }

        /**
         * Examination of the validity of the otp,
         * whether it passed more than half an hour or not
         */
        if (!$user->is_active){
            return response()->json([
                'status' => 200,
                'message' => null,
                'last_step' => 'verify',
                'has_profile' => 0,
                'otp' => (int) $user->otp,
//                'errors' => ['default' => [__('users.please_complete_register')]],
                'result' => 'success',
                'data' => [
                    'user' =>
                        [
                            'otp' => (int) $user->otp,
                            'phone' => $user->phone,
                            'country_code' => $user->country_code,
                        ]
                ]
            ], 200);
        }
//        if (!$user->is_active){
//            return response()->json([
//                'status' => 422,
//                'message' => null,
//                'has_profile' => 0,
//                'errors' => ['default' => [__('users.please_complete_register')]],
//                'result' => 'failed',
//                'data' => null
//            ], 422);
//        }

//        if ($request->device_type && $request->device_token) {
//            $user->devices()->firstOrCreate($request->only(['device_type', 'device_token']));
//        }
        $user->update(['otp' => null, 'otp_valid' => null]);
        $token =  $user->createToken('MyLaravelApp')->accessToken;
        data_set($user, 'token', $token);
        return response()->json([
//            'token' => $token,
            'has_profile' => 1,
            'status' => 200,
            'message' => trans('app.messages.success_login'),
            'errors' => null,
            'result' => 'success',
            'data' => ['user' => new UserResource($user)],
        ],200);
    }

    /**
     * Password
     * @param UpdatePasswordRequest $request
     * @return JsonResponse
     */
    public function editPassword(UpdatePasswordRequest $request)
    {
        DB::beginTransaction();
        try{
            $user = auth()->user();
            $user->update(Arr::only($request->validated(),['password']));
            DB::commit();
            return response()->json(['status' =>1 , 'data' => null ,'message'=>'Password updated successfully'], 200);
//            return (new UserProfileResource($user))->additional(['status' => 1,'message'=>"Password updated successfully"]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status' =>0 , 'data' => null ,'message'=>'لم يتم التعديل حاول مرة اخرى'], 401);
        }

    }


}
