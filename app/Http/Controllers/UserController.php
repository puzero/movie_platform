<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function me(Request $request){
        
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        return response()->json([
            'user' => $user,
        ]);
    }
    public function show($id)
    {
        $user = User::find($id);

        if(!$user){
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'username' => $user->username,
            'name'     => $user->name,
        ]);
    }

    public function update(Request $request)
    {

        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        if($user->is_blocked){
            return response()->json(['message' => 'User was blocked']);
        }

        $validated = $request->validate([
            'username' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('users'),
            ],
            'name' => 'sometimes|string|max:255',
        ]);

        if (empty($validated)) {
            return response()->json(['error' => 'No data'], 400);
        }

        $user->update($validated);

        return response()->json([
            'user' => $user,
        ]);
    }

    public function destroy(Request $request)
    {

        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user->delete();

        return response()->json(['message' => 'Succesfull delete'], 200);
    }
}