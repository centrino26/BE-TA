<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function register(Request $a)
    {
        $fields = $a->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string',
            
            
            
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'avatar' => "avatar"
        ]);
        $token = $user->createToken('token')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token,
            'status' => "success"
        ];

        return response($response, 201);
    }

    public function login (Request $request){
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // check email
        $user = User::where('email', $fields['email'])->first(); 
        // check Password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'bad gateway'
            ], 401);
        }

        $token = $user->createToken('token')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token,
            'log' => 'true'
        ];

        return response($response, 201);
    }

    public function logout(Request $request){
        Auth()->user()->tokens()->delete();
        return[
            'message' => 'logged out'
        ];
    }
}
