<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function rigester(Request $request)
    {
        // Registration logic goes here
        $validate = $request->validate([
           'name'=> 'required|string|max:255',
            'email'=> 'required|string|email|max:255|unique:users', 
            'password'=> 'required|string|min:8',
        ]);

        $user = User::create([
            'name'=> $validate['name'],
            'email'=> $validate['email'],
            'password'=> bcrypt($validate['password']),
        ]);

        

        return response()->json([
            'user' => $user,
           
        ]);

    }

    public function login(Request $request)
    {
        // Login logic goes here
        $credentials = $request->validate([
            'email'=>['required','email'],
            'password'=>['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);

    }

    public function logout(Request $request)
    {
        // Logout logic goes here
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
