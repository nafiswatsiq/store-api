<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect()
    {
        $redirect = Socialite::driver('google')->redirect();
        return response()->json([
            'redirect' => $redirect
        ]);
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        return response()->json([
            'error' => false,
            'user' => $googleUser,
        ]);
    }
}
