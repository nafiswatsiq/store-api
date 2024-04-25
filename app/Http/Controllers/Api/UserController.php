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
    public function register(Request $request) 
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validate->errors()
            ], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile' => 'https://source.boringavatars.com/beam/120/'.$request->name.''
        ]);

        $token = $user->createToken('api-token', expiresAt:now()->addHours(3))->plainTextToken;

        return response()->json([
            'error' => false,
            'token' => $token,
            'user' => $user,
        ], 201);
    }

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
        }

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => true,
                'message' => 'Username or password is incorrect.'
            ], 401);
        }

        $token = $user->createToken('api-token', expiresAt:now()->addHours(3))->plainTextToken;
     
        return response()->json([
            'error' => false,
            'token' => $token,
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
