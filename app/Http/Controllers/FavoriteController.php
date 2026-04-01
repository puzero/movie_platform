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

        $user = $request->user();

        $favoriteMovies = $user->movies()->paginate(10); 

        return response()->json($favoriteMovies);
    }

    public function show(Request $request, $movieId)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $movie = Movie::find($movieId);
        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        return response()->json([
            'movie' => $movie
        ]);
    }

    public function store(Request $request, $movieId)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $movie = Movie::find($movieId);
        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        $exists = UserMovie::where('user_id', $user->id)
                           ->where('movie_id', $movie->id)
                           ->exists();
        if ($exists) {
            return response()->json(['message' => 'Movie already in favorites'], 409);
        }

        UserMovie::create([
            'user_id' => $user->id,
            'movie_id' => $movie->id
        ]);

        return response()->json(['message' => 'Movie "' . $movie->name . '" added to favorites'], 201);
    }

    public function destroy(Request $request, $movieId)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $movie = Movie::find($movieId);
        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        $deleted = UserMovie::where('user_id', $user->id)
                            ->where('movie_id', $movie->id)
                            ->delete();

        if ($deleted) {
            return response()->json(['message' => 'Movie "' . $movie->name . '" removed from favorites'], 200);
        } else {
            return response()->json(['message' => 'Movie was not in favorites'], 404);
        }
    }

    public function notFavorites(Request $request){

        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $loaderType = $request->query('loaderType', 'sql');

        if ($loaderType == 'sql') {
            $notFavoriteMovies = Movie::whereNotExists(function ($query) use ($user) {
                $query->select('*')
                    ->from('user_movies')
                    ->whereColumn('user_movies.movie_id', 'movies.id')
                    ->where('user_movies.user_id', $user->id);
            })->paginate();
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
            return response()->json(['error' => 'Incorrect parameter loaderType. Use "sql" or "inMemory".'], 400);
        }

        return response()->json($notFavoriteMovies);
    }   
}