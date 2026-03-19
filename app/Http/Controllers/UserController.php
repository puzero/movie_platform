<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Получение информации об авторизованном пользователе.
     */
    public function show(Request $request)
    {

        $userId = $request->header('User-Id');

        try {
            $user = User::findOrFail((int) $userId);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Пользователь не найден'], 404);
        }

        return response()->json([
            'id'       => $user->id,
            'email'    => $user->email,
            'username' => $user->username,
            'name'     => $user->name,
        ]);
    }

    public function update(Request $request)
    {

        $userId = $request->header('User-Id');

        try {
            $user = User::findOrFail((int) $userId);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Пользователь не найден'], 404);
        }

        if($user->is_blocked){
            return response()->json(['message' => 'Пользователь заблокирован администратором']);
        }

        $validated = $request->validate([
            'username' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'name' => 'sometimes|string|max:255',
        ]);

        if (empty($validated)) {
            return response()->json(['error' => 'Нет данных для обновления'], 400);
        }

        $user->update($validated);

        return response()->json([
            'id'       => $user->id,
            'email'    => $user->email,
            'username' => $user->username,
            'name'     => $user->name,
        ]);
    }

    public function destroy(Request $request)
    {
        $userId = $request->header('User-Id');

        try {
            $user = User::findOrFail((int) $userId);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Пользователь не найден'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Успешное удаление'], 204);
    }
}