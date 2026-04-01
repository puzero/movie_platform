<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserIsBlocked
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if(!$user){
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        
        if($user->is_blocked){
            return response()->json(['message' => 'User was blocked']);
        }

        return $next($request);
    }
}
