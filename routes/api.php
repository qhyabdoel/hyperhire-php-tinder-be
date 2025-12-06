<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PersonController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // People endpoints
    Route::get('/people', [PersonController::class, 'index']);
    Route::post('/people/{person}/like', [PersonController::class, 'like']);
    Route::post('/people/{person}/dislike', [PersonController::class, 'dislike']);
    Route::get('/liked-people', [PersonController::class, 'likedPeople']);
});
