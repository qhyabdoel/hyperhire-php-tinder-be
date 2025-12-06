<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::get('/users', [UserController::class, 'index']);
Route::post('/users/{person}/like', [UserController::class, 'like']);
Route::post('/users/{person}/dislike', [UserController::class, 'dislike']);
Route::get('/liked-users', [UserController::class, 'likedPeople']);
