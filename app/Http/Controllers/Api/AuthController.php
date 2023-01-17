<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequests\ClassicLoginRequest;
use App\Http\Requests\AuthRequests\ProviderCallBackRequest;
use App\Http\Requests\AuthRequests\ProviderRedirectRequest;
use App\Http\Requests\AuthRequests\RegisterRequest;
use App\Http\Requests\AuthRequests\SSOLoginRequest;
use App\Http\Resources\UserResources\UserResource;
use App\Services\TypeService;
use App\Services\UserService;
use App\Strategy\LoginStrategies\ClassicLogin\LoginClassicEmail;
use App\Strategy\LoginStrategies\ClassicLogin\LoginClassicPhoneNumber;
use App\Strategy\LoginStrategies\ProviderLogin\LoginWithGoogle;
use App\Strategy\LoginStrategies\SSOLogin\LoginEmailSSO;
use App\Strategy\LoginStrategies\SSOLogin\LoginPhoneSSO;

/**
 * @group auth management
 *
 * APIs for managing authentications
 */
class AuthController extends Controller
{
    /**
     * @var UserService
     */
    private  $userService;
    /**
     * @var TypeService
     */
    private $typeService;
    /**
     * @var LoginClassicEmail
     */
    private $loginClassicEmail;
    /**
     * @var LoginClassicPhoneNumber
     */
    private $loginClassicPhoneNumber;
    /**
     * @var LoginEmailSSO
     */
    private $loginEmailSSO;
    /**
     * @var LoginPhoneSSO
     */
    private $loginPhoneSSO;
    /**
     * @var LoginWithGoogle
     */
    private $loginWithGoogle;

    public function __construct(UserService $userService,
                                LoginClassicEmail $loginClassicEmail,
                                LoginClassicPhoneNumber $loginClassicPhoneNumber,
                                LoginEmailSSO $loginEmailSSO,
                                LoginPhoneSSO $loginPhoneSSO,
                                LoginWithGoogle $loginWithGoogle,
                                TypeService $typeService

    ){
        $this->userService = $userService;
        $this->loginClassicEmail = $loginClassicEmail;
        $this->loginClassicPhoneNumber = $loginClassicPhoneNumber;
        $this->loginEmailSSO = $loginEmailSSO;
        $this->loginPhoneSSO = $loginPhoneSSO;
        $this->loginWithGoogle = $loginWithGoogle;
        $this->typeService = $typeService;

    }
    /**
     * User Register
     *
     * @param RegisterRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register (RegisterRequest $request)
    {
        $input =$request->all();
        $user = $this->userService->storeUser($input);
        $token = $user->createToken('auth')->plainTextToken;

        $response = [
            'status' => true,
            'message' => 'User registered In Successfully',
            'token' => $token,
            'user'=>new UserResource($user)
        ];
        return  response()->json($response,200);
    }
    /**
     * User login by classic types
     *
     * @param ClassicLoginRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function classicLogin (ClassicLoginRequest $request)
    {
        $type = $this->typeService->getType($request->type_id);
        $login =null;
        switch ($type->name){
            case "email and password":
                $login = $this->loginClassicEmail;
                break;
            case "phone and password":
                $login = $this->loginClassicPhoneNumber;
        }
        if ($login ==null){
            return response()->json(['message'=>"please choose another approach"],400);
        }
        $validate = $login->checkInput($request);
        if ($validate['status']==false){
            return response()->json(['message'=>$validate['message']],401);
        }

        $login = $login->loginAttempt($validate['data']);
        if ($login['status'] == false){
            return response()->json(['message'=>$login['message']],401);
        }
        return  response()->json($login,200);
    }
    /**
     * User sendCode by sso types
     *
     * @param SSOLoginRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function SSOSendCode(SSOLoginRequest $request)
    {
        $type = $this->typeService->getType($request->type_id);
        $login =null;
        switch ($type->name){
            case "email sso":
                $login = $this->loginEmailSSO;
            case "phone sso":
                $login = $this->loginPhoneSSO;

        }
        if ($login ==null){
            return response()->json(['message'=>"please choose another approach"],400);
        }
        $validate = $login->checkInput($request);
        if ($validate['status']==false){
            return response()->json(['message'=>$validate['message']],401);
        }

        $sendData = $login->SendCode($validate['data']);
        if ($sendData['status'] == false){
            return response()->json(['message'=>$sendData['message']],401);
        }
        return  response()->json($sendData,200);
    }
    /**
     * User login by sso types
     *
     * @param SSOLoginRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function SSOLogin(SSOLoginRequest $request)
    {
        $type = $this->typeService->getType($request->type_id);
        $login =null;
        switch ($type->name){
            case "email sso":
                $login = $this->loginEmailSSO;
            case "phone sso":
                $login = $this->loginPhoneSSO;

        }
        if ($login ==null){
            return response()->json(['message'=>"please choose another approach"],400);
        }
        $validate = $login->checkInput($request);
        if ($validate['status']==false){
            return response()->json(['message'=>$validate['message']],401);
        }

        $login = $login->loginAttempt($validate['data']);
        if ($login['status'] == false){
            return response()->json(['message'=>$login['message']],401);
        }
        return  response()->json($login,200);
    }
    /**
     * User login by sso types
     *
     * @param ProviderRedirectRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function redirectToProvider(ProviderRedirectRequest $request)
    {
        $type = $this->typeService->getType($request->type_id);
        switch ($type->name){
            case "google":
                $login = $this->loginWithGoogle;
            default:
                $login =null;
        }
        if ($login ==null){
            return response()->json(['message'=>"please choose another approach"],400);
        }
        $validate = $login->checkInput($request);
        if ($validate['status']==false){
            return response()->json(['message'=>$validate['message']],401);
        }

        return $login->sendData($validate['data']);

    }
    /**
     * User login by sso types
     *
     * @param ProviderCallBackRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ProviderCallBackLogin(ProviderCallBackRequest $request)
    {
        $type = $this->typeService->getType($request->type_id);
        $login =null;
        switch ($type->name){
            case "google":
                $login = $this->loginWithGoogle;
        }
        if ($login ==null){
            return response()->json(['message'=>"please choose another approach"],400);
        }

        $login= $login->callback($request);
        if ($login['status'] == false){
            return response()->json(['message'=>$login['message']],401);
        }
        return  response()->json($login,200);


    }
}
