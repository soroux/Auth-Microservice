<?php


namespace App\Strategy\LoginStrategies\SSOLogin;


use App\Http\Resources\UserResources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginPhoneSSO implements SSOLoginStrategyInterface
{

    function checkInput($request){
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|ir_mobile',
        ]);

        if ($validator->fails()) {
            return ['status' => false, 'message' => $validator->errors()];

        }

        return ['status'=>true,'data'=>$request];
    }
    public function SendCode($request)
    {
        return ['status'=>true,'message'=>'code sent successfully'];
//        SSOClient::setApiKeyHttpHeader('qwe')
//            ->registerUserWithMobile($request->phone_number)
//            ->loginGenerateOTPWithMobile($request->phone_number);
    }

    function loginAttempt($request)
    {
//        SSOClient::setApiKeyHttpHeader('qwe')->registerUserWithMobile($this->phone_number)
//            ->loginVerifyOTPWithMobile($this->phone_number,$this->code);

        $user = User::where('phone_number', $request->phone_number)->where('application_id',$request->application_id)->first();
        if (!$user){
            return [
                'status' => false,
                'message' => 'phone_number not Exist does not match with our record.',
            ];
        }
        Auth::login($user);
        return [
            'status' => true,
            'message' => 'User Logged In Successfully',
            'token' => $user->createToken("auth")->plainTextToken,
            'user'=>new UserResource($user)
        ];

    }



}
