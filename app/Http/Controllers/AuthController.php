<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{
    //
    public function register(Request $request){

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|string|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);
        


        return response()->json([
            'message' => 'успешная регистрация',
            'user_id' => $user->id,
        ]);
    }

    public function login(Request $request){

        $validated = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8'
        ]);

        $user = User::where('email',$validated['email'])->first();

        if (!$user || !Hash::check($validated['password'],$user->password)){
            return response()->json([
                'message' => 'Ошибка авторизации',
            ]);
        }

        return response()->json([
            'message' => 'Успешная авторизация',
            'user_id' => $user->id,
        ]);
    }
}
