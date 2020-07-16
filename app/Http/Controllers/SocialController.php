<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    //
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callBack($provider)
    {
        $userSocial =   Socialite::driver($provider)->user();
        $user = $this->createUser($userSocial,$provider);
        auth()->login($user);
        return redirect()->to('/home');
    }

    function createUser($getInfo,$provider){
        $user = User::where('provider_id', $getInfo->id)->first();
        if (!$user) {
            $user = User::create([
                'name'     => $getInfo->name,
                'email'    => $getInfo->email,
                'provider' => $provider,
                'image'=>$getInfo->avatar,
                'provider_id' => $getInfo->id
            ]);
        }
        return $user;
    }
}
