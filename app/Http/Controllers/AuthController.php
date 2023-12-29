<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|max:100',
                'email' => 'email|required|unique:users',
                'password' => 'required|confirmed'
            ]);

            $validatedData['password'] = bcrypt($request->password);

            $user = User::create($validatedData);
            
            return response()->json(['message' => 'User created', 'user' => $user->makeHidden('password')]);
        } catch (\Exception) {
            return response()->json(['message' => 'Server error'], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            if (!auth()->attempt($request->only('email', 'password'))) {
                return response()->json(['message' => 'Wrong email or password'], 401);
            }

            $user = User::where('email', $request->email)->first();

            $accessToken = $user->createToken('authToken')->plainTextToken;

            return response()->json(['message' => 'Logged in', 'token' => $accessToken]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Server error'], 500);
        }
    }

    public function user()
    {
        try {
            $user = User::where('id', auth()->id())->first();

            return response()->json(['user' => $user]);
        } catch (\Exception) {
            return response()->json(['message' => 'Server error'], 500);
        }
    }

    public function logout()
    {
        try {
            $user = User::where('id', auth()->id())->first();
            $user->tokens()->delete();

            return response()->json(['message' => 'Logged out']);
        } catch (\Exception) {
            return response()->json(['message' => 'Server error'], 500);
        }
    }
}
