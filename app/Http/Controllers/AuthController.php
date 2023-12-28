<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
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

    public function user(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'User is not authenticated'], 401);
        }

        try {
            $user = User::where('id', Auth::id())->first();
        } catch (\Exception) {
            return response()->json(['error' => 'Server error'], 500);
        }

        return response()->json(['user' => $user]);
    }

    public function logout(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'User is not authenticated'], 401);
        }

        try {
            $user = User::where('id', Auth::id())->first();
            $user->tokens()->delete();
        } catch (\Exception) {
            return response()->json(['error' => 'Server error'], 500);
        }

        return response()->json(['message' => 'Logged out']);
    }
}
