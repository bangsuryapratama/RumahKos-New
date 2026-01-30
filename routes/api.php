<?php

use Illuminate\Support\Facades\Route;

// ==========================
// API CONTROLLERS
// ==========================
use App\Http\Controllers\Api\Tenant\AuthController;
use App\Http\Controllers\Api\RoomApiController;
use App\Http\Controllers\Tenant\PaymentController;

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

        // Auth
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::post('/refresh-token', [AuthController::class, 'refreshToken']);

        // ==========================
        // BOOKING (NEXT PHASE)
        // ==========================
        // Route::get('/bookings', [BookingController::class, 'index']);
        // Route::post('/bookings', [BookingController::class, 'store']);
        // Route::get('/bookings/{id}', [BookingController::class, 'show']);
        // Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel']);

        // ==========================
        // PAYMENT (NEXT PHASE)
        // ==========================
        // Route::get('/payments', [PaymentController::class, 'index']);
        // Route::get('/payments/{id}', [PaymentController::class, 'show']);
        // Route::get('/payments/{id}/midtrans', [PaymentController::class, 'getMidtransToken']);
        // Route::get('/payments/{id}/check-status', [PaymentController::class, 'checkStatus']);
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


/*
|--------------------------------------------------------------------------
| REVIEWS API (NEXT PHASE)
|--------------------------------------------------------------------------
*/
// Route::get('/rooms/{room}/reviews', [ReviewApiController::class, 'index']);
// Route::middleware('auth:sanctum')
//     ->post('/rooms/{room}/reviews', [ReviewApiController::class, 'store']);
