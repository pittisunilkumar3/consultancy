<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleFacebookCallback()
    {
        try {

            $facebookUser = Socialite::driver('facebook')->user();

            $findUser = User::where('facebook_id', $facebookUser->id)->first();

            if ($findUser) {

                Auth::login($findUser);

                return redirect()->route('login');

            } else {
                $user = User::where('email', $facebookUser->email)->first();

                if($user){
                    $user->update(['facebook_id' =>  $facebookUser->id]);
                }else {
                    $splitName = explode(' ', $facebookUser->name, 2);

                    $firstName = $splitName[0];
                    $lastName = !empty($splitName[1]) ? $splitName[1] : '';

                    $remember_token = Str::random(64);
                    $password = Str::random(8);
                    $google2fa = app('pragmarx.google2fa');

                    $user = User::create([
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $user->email,
                        'password' => Hash::make($password),
                        'role' => USER_ROLE_STUDENT,
                        'remember_token' => $remember_token,
                        'status' => USER_STATUS_ACTIVE,
                        'verify_token' => str_replace('-', '', Str::uuid()->toString()),
                        'google2fa_secret' => $google2fa->generateSecretKey(),
                        'facebook_id' => $facebookUser->id,
                    ]);
                }
                Auth::login($user);
                return redirect()->route('login');

            }

        } catch (Exception $e) {
            return redirect(route('login'))->with('error', $e->getMessage());
        }
    }
}
