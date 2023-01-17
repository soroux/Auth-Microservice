<?php


namespace App\Strategy\LoginStrategies\ProviderLogin;


use App\Http\Resources\UserResources\UserResource;
use App\Models\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;


class LoginWithGoogle implements ProviderLoginStrategyInterface
{

    public function redirect($request)
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback($request)
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();
        } catch (ClientException $exception) {
            return ['status'=>false,'message' => 'Invalid credentials provided.'];
        }

        $userCreated = User::firstOrCreate(
            [
                'application_id'=>$request->application_id,
                'email' => $user->getEmail()
            ],
            [
                'email_verified_at' => now(),
                'first_name' => $user->user['firstName'],
                'last_name' => $user->user['lastName'],
                'status' => true,
            ]
        );
        $userCreated->providers()->updateOrCreate(
            [
                'provider' => 'google',
                'provider_id' => $user->getId(),
                'application_id' => $request->application_id,
            ],
            [
                'avatar' => $user->getAvatar()
            ]
        );
        Auth::login($userCreated);
        return [
            'status' => true,
            'message' => 'User Logged In Successfully',
            'token' => $userCreated->createToken("auth")->plainTextToken,
            'user'=>new UserResource($userCreated)
        ];
    }
}
