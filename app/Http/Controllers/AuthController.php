<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{
    //

    public function formRegister(){
        return view('welcome');
    }
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'user_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                'string',
                'min:6',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ]
        ], [
            'password.regex' => 'Пароль должен содержать хотя бы одну строчную букву, одну заглавную букву и одну цифру.',
            'email.unique' => 'Пользователь с таким email уже зарегистрирован.'
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'user_name' => $validated['user_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Не удалось создать пользователя. Попробуйте позже.'
            ], 500);
        }

        $token = $user->createToken('user_auth')->plainTextToken;

        return response()->json([
            'message' => 'Регистрация успешна',
            'user_id' => $user->id,
            'token' => $token
        ], 201);
    }

    public function formLogin(){
        return view('welcome');
    }

    public function login(Request $request){

        $validated = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8'
        ]);

        $user = User::where('email',$validated['email'])->first();

        if (!$user || !Hash::check($validated['password'],$user->password)){
            return response()->json([
                'message' => 'Incorrect e-mail or password',
            ]);
        }

        $token = $user->createToken('user_auth');

        return response()->json([
            'user_id' => $user->id,
            'token' => $token->plainTextToken
        ],200);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user->currentAccessToken()->delete();

        return response()->json(['message' => 'logout'], 200);
    }
}
