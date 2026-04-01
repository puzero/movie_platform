<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
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
    public function register(AuthRegisterRequest $request)
    {
        $validated = $request->validated();

        try {
            $user = User::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create user. Please try again later..'
            ], 500);
        }

        $token = $user->createToken('user_auth')->plainTextToken;

        return response()->json([
            'user_id' => $user->id,
            'token' => $token
        ], 201);
    }

    public function formLogin(){
        return view('welcome');
    }

    public function login(AuthLoginRequest $request){

        $validated = $request->validated();

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
