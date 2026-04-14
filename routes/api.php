<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Tenant\AuthController;
use App\Http\Controllers\Api\Tenant\DashboardController;
use App\Http\Controllers\Api\Tenant\ProfileApiController;
use App\Http\Controllers\Api\Tenant\ResidentApiController;
use App\Http\Controllers\Api\Tenant\PaymentApiController;
use App\Http\Controllers\Api\RoomApiController;

/*
|--------------------------------------------------------------------------
| TENANT API
|--------------------------------------------------------------------------
*/

Route::prefix('tenant')->group(function () {

    // ================= AUTH (PUBLIC)
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/social-login', [AuthController::class, 'socialLogin']);

    // ================= AUTH (PROTECTED)
    Route::middleware('auth:sanctum')->group(function () {

        // DASHBOARD
        Route::get('/dashboard', [DashboardController::class, 'index']);
        // AUTH
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh-token', [AuthController::class, 'refreshToken']);

        // PROFILE
        Route::get('/profile', [ProfileApiController::class, 'getProfile']);
        Route::put('/profile', [ProfileApiController::class, 'updateProfile']);
        Route::post('/profile/upload-document', [ProfileApiController::class, 'uploadDocument']);
        Route::delete('/profile/delete-document', [ProfileApiController::class, 'deleteDocument']);

        // RESIDENCE
        Route::get('/residence/current', [ResidentApiController::class, 'getCurrentResidence']);
        Route::get('/residence/history', [ResidentApiController::class, 'getResidenceHistory']);

        // PAYMENTS
        Route::get('/payments', [PaymentApiController::class, 'index']);
        Route::get('/payments/{id}', [PaymentApiController::class, 'show']);
        Route::post('/payments/{id}/midtrans', [PaymentApiController::class, 'midtrans']);
        Route::get('/payments/{id}/check-status', [PaymentApiController::class, 'checkStatus']);
    });
});

/*
|--------------------------------------------------------------------------
| PUBLIC ROOMS API
|--------------------------------------------------------------------------
*/

Route::prefix('rooms')->group(function () {
    Route::get('/', [RoomApiController::class, 'index']);
    Route::get('/available/list', [RoomApiController::class, 'available']);
    Route::get('/{room}', [RoomApiController::class, 'show']);
});

/*
|--------------------------------------------------------------------------
| MIDTRANS CALLBACK (PUBLIC)
|--------------------------------------------------------------------------
*/

Route::post('/payment/midtrans/callback', [PaymentApiController::class, 'callback']);