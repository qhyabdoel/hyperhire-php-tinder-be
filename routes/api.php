<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use L5Swagger\Http\Controllers\SwaggerController;

Route::get('/users/recommended', [UserController::class, 'index']);
Route::post('/likes', [UserController::class, 'like']);
Route::post('/dislikes', [UserController::class, 'dislike']);
Route::get('/users/{id}/likes', [UserController::class, 'likedPeople']);

Route::get('/docs', [SwaggerController::class, 'api'])->name('l5-swagger.default.api');
Route::get('/docs/asset/{asset}', [SwaggerController::class, 'asset'])->name('l5-swagger.default.asset');
Route::get('/docs/oauth2_callback', [SwaggerController::class, 'oauth2Callback'])->name('l5-swagger.default.oauth2_callback');