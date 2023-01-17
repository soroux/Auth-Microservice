<?php


namespace App\Strategy\LoginStrategies\ClassicLogin;


use App\Http\Resources\UserResources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginClassicPhoneNumber implements ClassicLoginStrategyInterface
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


    function loginAttempt($request)
    {
        if(!Auth::attempt(['phone_number' => $request->phone_number, 'password' => $request->password, 'application_id' => $request->application_id])){
            return [
                'status' => false,
                'message' => 'phone_number & Password does not match with our record.',
            ];
        }
        $user = User::where('phone_number', $request->phone_number)->where('application_id',$request->application_id)->first();
        return [
            'status' => true,
            'message' => 'User Logged In Successfully',
            'token' => $user->createToken("auth")->plainTextToken,
            'user'=>new UserResource($user)
        ];
    }


}
