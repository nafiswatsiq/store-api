<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function checkAuth(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validate->errors()
            ], 400);
        } else {
            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return response()->json([
                    'error' => true,
                    'message' => 'The provided credentials are incorrect.'
                ], 401);
            }

            $token = $user->createToken('api-token', expiresAt:now()->addHours(3))->plainTextToken;
            $user['token'] = $token;
        }
     
        return response()->json([
            'error' => false,
            'user' => $user,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'error' => false,
            'message' => 'Token deleted.'
        ], 200);
    }

    public function user(Request $request)
    {
        return response()->json($request->user(), 200);
    }
}
