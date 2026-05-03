<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;

Route::post('/auth/token',  [Api\AuthController::class, 'token']);
Route::post('/auth/logout', [Api\AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tournaments',                          [Api\TournamentApiController::class, 'index']);
    Route::get('/tournaments/{tournament}',             [Api\TournamentApiController::class, 'show']);
    Route::get('/tournaments/{tournament}/standings',   [Api\TournamentApiController::class, 'standings']);
    Route::get('/tournaments/{tournament}/matches',     [Api\TournamentApiController::class, 'matches']);
    Route::post('/matches/{match}/result',              [Api\MatchApiController::class, 'storeResult']);
});