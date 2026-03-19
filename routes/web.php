<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);


// Route::middleware('auth.user-id')->group(function () {
//     Route::get('/user', [UserController::class, 'show']);  
//     Route::post('/user', [UserController::class, 'update']); 
//     Route::delete('/user', [UserController::class, 'destroy']);
// });

// Route::get('/csrf-token', function() {
//     return response()->json(['token' => csrf_token()]);
// });