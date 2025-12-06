<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::get('/users/recommended', [UserController::class, 'index']);
Route::post('/likes', [UserController::class, 'like']);
Route::post('/dislikes', [UserController::class, 'dislike']);
Route::get('/users/{id}/likes', [UserController::class, 'likedPeople']);
