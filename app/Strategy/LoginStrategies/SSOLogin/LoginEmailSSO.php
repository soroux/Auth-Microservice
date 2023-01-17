<?php


namespace App\Strategy\LoginStrategies\SSOLogin;
use App\Http\Resources\UserResources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginEmailSSO implements SSOLoginStrategyInterface
{

    function checkInput($request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return ['status' => false, 'message' => $validator->errors()];

        }

        return ['status'=>true,'data'=>$request];
    }
    public function SendCode($request)
    {
        return ['status'=>true,'message'=>'code sent successfully'];

//        SSOClient::setApiKeyHttpHeader('apiKey3')
//            ->registerUserWithEmail($request->email)
//            ->loginGenerateOTPWithEmail($request->email);
    }

    function loginAttempt($request)
    {
//        SSOClient::setApiKeyHttpHeader('apiKey3')
//            ->registerUserWithEmail($this->email)
//            ->loginVerifyOTPWithEmail($this->email,$this->code);

        $user = User::where('email', $request->email)->where('application_id',$request->application_id)->first();
        if (!$user){
            return [
                'status' => false,
                'message' => 'Email not Exist does not match with our record.',
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
