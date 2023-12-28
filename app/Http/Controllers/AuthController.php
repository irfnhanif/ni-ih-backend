<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:100',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed'
        ]);

        $validatedData['password'] = bcrypt($request->password);
        try {
            $user = User::create($validatedData);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error'], 500);
        }
        
        return response()->json(['message' => 'User created', 'user' => $user]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = User::where('email', $request->email)->first();
        try {
            $accessToken = $user->createToken('authToken')->plainTextToken;
        } catch (\Exception) {
            return response()->json(['error' => 'Server error'], 500);
        }

        return response()->json(['message' => 'User authenticated', 'token' => $accessToken]);
    }

    public function user()
    {
        try {
            $user = User::where('id', auth()->id())->first();
        } catch (\Exception) {
            return response()->json(['error' => 'Server error'], 500);
        }

        return response()->json(['user' => $user]);
    }

    public function logout()
    {
        try {
            $user = User::where('id', auth()->id())->first();
            $user->tokens()->delete();
        } catch (\Exception) {
            return response()->json(['error' => 'Server error'], 500);
        }

        return response()->json(['message' => 'Logged out']);
    }
}
