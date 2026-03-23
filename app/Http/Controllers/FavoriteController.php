<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\User;
use App\Models\UserMovie;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->header('User-Id');
        if (!$userId) {
            return response()->json(['message' => 'нет User-Id заголовка'], 400);
        }

        try {
            $user = User::findOrFail($userId);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Пользователь не найден'], 404);
        }

        $favoriteMovies = $user->movies()->paginate(10); 

        return response()->json($favoriteMovies);
    }

    public function show(Request $request, $movieId)
    {
        $userId = $request->header('User-Id');
        if (!$userId) {
            return response()->json(['message' => 'нет User-Id заголовка'], 400);
        }

        try {
            $user = User::findOrFail($userId);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Пользователь не найден'], 404);
        }

        $movie = Movie::find($movieId);
        if (!$movie) {
            return response()->json(['message' => 'Фильм не найден'], 404);
        }

        return response()->json([
            'movie' => $movie
        ]);
    }

    public function store(Request $request, $movieId)
    {
        $userId = $request->header('User-Id');
        if (!$userId) {
            return response()->json(['message' => 'нет User-Id заголовка'], 400);
        }

        try {
            $user = User::findOrFail($userId);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Пользователь не найден'], 404);
        }

        $movie = Movie::find($movieId);
        if (!$movie) {
            return response()->json(['message' => 'Фильм не найден'], 404);
        }

        $exists = UserMovie::where('user_id', $user->id)
                           ->where('movie_id', $movie->id)
                           ->exists();
        if ($exists) {
            return response()->json(['message' => 'Фильм уже в избранном'], 409);
        }

        UserMovie::create([
            'user_id' => $user->id,
            'movie_id' => $movie->id
        ]);

        return response()->json(['message' => 'Фильм "' . $movie->name . '" добавлен в избранное'], 201);
    }

    public function destroy(Request $request, $movieId)
    {
        $userId = $request->header('User-Id');
        if (!$userId) {
            return response()->json(['message' => 'User-Id header is required'], 400);
        }

        try {
            $user = User::findOrFail($userId);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Пользователь не найден'], 404);
        }

        $movie = Movie::find($movieId);
        if (!$movie) {
            return response()->json(['message' => 'Фильм не найден'], 404);
        }

        $deleted = UserMovie::where('user_id', $user->id)
                            ->where('movie_id', $movie->id)
                            ->delete();

        if ($deleted) {
            return response()->json(['message' => 'Фильм "' . $movie->name . '" удалён из избранного'], 200);
        } else {
            return response()->json(['message' => 'Фильм не был в избранном'], 404);
        }
    }

    public function notFavorites(Request $request){

        $userId = $request->header('User-Id');
        if (!$userId) {
            return response()->json(['message' => 'нет User-Id заголовка'], 400);
        }

        try {
            $user = User::findOrFail($userId);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Пользователь не найден'], 404);
        }

        $loaderType = $request->query('loaderType', 'sql');
        $perPage = (int) $request->query('per_page', 10);
        $page = (int) $request->query('page', 1);

        if ($loaderType === 'sql') {
            $notFavoriteMovies = Movie::whereNotExists(function ($query) use ($user) {
                $query->select('*')
                    ->from('user_movies')
                    ->whereColumn('user_movies.movie_id', 'movies.id')
                    ->where('user_movies.user_id', $user->id);
            })->paginate($perPage, ['*'], 'page', $page);
        } elseif ($loaderType === 'inMemory') {
            $allMovies = Movie::all();

            $favoriteIds = UserMovie::where('user_id', $user->id)
                            ->pluck('movie_id')
                            ->toArray();
            $notFavoriteMovies = [];
            foreach ($allMovies as $movie) {
                if (!in_array($movie->id, $favoriteIds)) {
                    $notFavoriteMovies[] = $movie;
                }
            }
        } else {
            return response()->json(['error' => 'Неверный параметр loaderType.Используйте "sql" или "inMemory".'], 400);
        }

        return response()->json($notFavoriteMovies);
    }   
}