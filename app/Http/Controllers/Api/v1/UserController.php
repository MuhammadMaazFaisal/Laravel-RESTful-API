<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function token(Request $request)
    {
        if (!$request->has('email') || !$request->has('password')) {
            return response()->json([
                'error' => 'Please provide both email and password'
            ], 400);
        }
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);
        $token = auth()->attempt($request->only('email', 'password'));
        if (!$token) {
            return response()->json([
                'error' => 'Invalid Credentials'
            ], 401);
        }
        $user=User::where('email',$request->email)->first();
        $user->remember_token=$user->createToken('api_token')->plainTextToken;
        $user->save();
        $token=$user->remember_token;
        return [
            'status' => 'success',
            'token_type' => 'Bearer',
            'access_token' => $token
        ];
    }
}