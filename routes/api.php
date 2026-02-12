<?php

use Illuminate\Support\Facades\Route;

// ==========================
// API CONTROLLERS
// ==========================
use App\Http\Controllers\Api\Tenant\AuthController;
use App\Http\Controllers\Api\Tenant\ResidentApiController;
use App\Http\Controllers\Api\Tenant\ProfileApiController;
use App\Http\Controllers\Api\RoomApiController;
use App\Http\Controllers\Tenant\PaymentController;
use App\Http\Controllers\Api\Tenant\PaymentApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Base URL : /api
*/

/*
|--------------------------------------------------------------------------
| TENANT AUTH & TENANT FEATURES
|--------------------------------------------------------------------------
*/
Route::prefix('tenant')->group(function () {

    // ==========================
    // AUTH (PUBLIC)
    // ==========================
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/social-login', [AuthController::class, 'socialLogin']);

    // ==========================
    // AUTHENTICATED TENANT (SANCTUM)
    // ==========================
    Route::middleware('auth:sanctum')->group(function () {

        // ==========================
        // AUTH
        // ==========================
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh-token', [AuthController::class, 'refreshToken']);

        // ==========================
        // PROFILE MANAGEMENT
        // ==========================
        Route::get('/profile', [ProfileApiController::class, 'getProfile']);
        Route::put('/profile', [ProfileApiController::class, 'updateProfile']);
        Route::post('/profile/upload-document', [ProfileApiController::class, 'uploadDocument']);
        Route::delete('/profile/delete-document', [ProfileApiController::class, 'deleteDocument']);

        // ==========================
        // RESIDENCE INFORMATION
        // ==========================
        Route::get('/residence/current', [ResidentApiController::class, 'getCurrentResidence']);
        Route::get('/residence/history', [ResidentApiController::class, 'getResidenceHistory']);

        // ==========================
        // BOOKING (NEXT PHASE)
        // ==========================
        // Route::get('/bookings', [BookingController::class, 'index']);
        // Route::post('/bookings', [BookingController::class, 'store']);
        // Route::get('/bookings/{id}', [BookingController::class, 'show']);
        // Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel']);

        // ==========================
        // PAYMENT
        // ==========================
        Route::get('/payments', [PaymentApiController::class, 'index']);
        Route::get('/payments/{id}', [PaymentApiController::class, 'show']);
        Route::post('/payments/{id}/midtrans', [PaymentApiController::class, 'midtrans']);
        Route::get('/payments/{id}/check-status', [PaymentApiController::class, 'checkStatus']);
    });

    // ==========================
    // MIDTRANS CALLBACK (PUBLIC)
    // ==========================
    Route::post('/payment/midtrans/callback', [PaymentController::class, 'callback']);
});


/*
|--------------------------------------------------------------------------
| PUBLIC API (NO AUTH)
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| ROOMS API
|--------------------------------------------------------------------------
*/
Route::prefix('rooms')->group(function () {

    // List rooms (search, filter, sort, pagination)
    Route::get('/', [RoomApiController::class, 'index']);

    // Room detail
    Route::get('/{room}', [RoomApiController::class, 'show']);

    // Available rooms only
    Route::get('/available/list', [RoomApiController::class, 'available']);
});

Route::post('/payment/midtrans/callback', [PaymentApiController::class, 'callback']);


/*
|--------------------------------------------------------------------------
| REVIEWS API (NEXT PHASE)
|--------------------------------------------------------------------------
*/
// Route::get('/rooms/{room}/reviews', [ReviewApiController::class, 'index']);
// Route::middleware('auth:sanctum')
//     ->post('/rooms/{room}/reviews', [ReviewApiController::class, 'store']);
