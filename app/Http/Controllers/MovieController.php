<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    /**
     * Display a paginated list of movies from the database.
     * Default 10 movies per page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        
        $movies = Movie::paginate($perPage);
        
        return response()->json($movies);
    }
}