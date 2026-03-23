<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/user', [UserController::class, 'show']);  
Route::post('/user', [UserController::class, 'update']); 
Route::delete('/user', [UserController::class, 'destroy']);


Route::get('/movies',[MovieController::class, 'index']);

Route::get('/favorites',[FavoriteController::class,'index']);
Route::post('/favorites/{movieId}',[FavoriteController::class,'store']);
Route::get('/favorites/{movieId}',[FavoriteController::class,'show']);
Route::delete('/favorites/{movieId}',[FavoriteController::class,'destroy']);
Route::get('/not-favorites',[FavoriteController::class, 'notFavorites']);