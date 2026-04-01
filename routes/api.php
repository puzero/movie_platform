<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/auth/register', [AuthController::class, 'formRegister']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::get('/auth/login',[AuthController::class, 'formLogin'])->name('login');
Route::post('/auth/login', [AuthController::class, 'login']);


Route::middleware(['auth:sanctum', 'User-Id','UserIsBlocked'])->group(function (){
    Route::get('/me', [UserController::class, 'me']);  
    Route::post('/me', [UserController::class, 'update']); 
    Route::delete('/me', [UserController::class, 'destroy']);
    Route::get('/user/{id}', [UserController::class, 'show']);

    Route::get('/favorites',[FavoriteController::class,'index']);
    Route::post('/favorites/{movieId}',[FavoriteController::class,'store']);
    Route::get('/favorites/{movieId}',[FavoriteController::class,'show']);
    Route::delete('/favorites/{movieId}',[FavoriteController::class,'destroy']);
    Route::get('/not-favorites',[FavoriteController::class, 'notFavorites']);

    Route::post('/logout',[AuthController::class,'logout']);  
});
Route::get('/movies',[MovieController::class, 'index']);