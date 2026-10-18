<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\MeController;
use App\Http\Controllers\Api\V1\SubmissionController;
use App\Http\Middleware\VerifySupabaseJwt;

Route::prefix('v1')->group(function () {
    Route::get('/leaderboard', function () {
        return response()->json([
            'data' => [],
            'meta' => [
                'page' => 1,
                'limit' => 20,
                'total' => 0,
            ],
        ]);
    });

    Route::get('/awards', function () {
        return response()->json(['data' => []]);
    });

    Route::middleware(VerifySupabaseJwt::class)->group(function () {

        Route::post('/auth/sync', [
            AuthController::class,
            'sync',
        ]);

        Route::get('/me', [
            MeController::class,
            'show',
        ]);

        Route::get('/me/submissions', [
            SubmissionController::class,
            'index',
        ]);

        Route::post('/submissions/competitions', [
            SubmissionController::class,
            'storeCompetition',
        ]);
    });
});