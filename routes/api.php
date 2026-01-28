<?php

use App\Http\Controllers\Api\Tenant\AuthController;
use App\Http\Controllers\Tenant\PaymentController;
use Illuminate\Support\Facades\Route;



Route::prefix('tenant')->group(function () {
    
    
    // Authentication
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/social-login', [AuthController::class, 'socialLogin']);
    
    // ========================================
    // Protected Routes (perlu auth dengan Sanctum)
    // ========================================
    
    Route::middleware('auth:sanctum')->group(function () {
        
        // Auth
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
        
        // Booking (akan dibuat nanti)
        // Route::get('/bookings', [BookingController::class, 'index']);
        // Route::post('/bookings', [BookingController::class, 'store']);
        // Route::get('/bookings/{id}', [BookingController::class, 'show']);
        // Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel']);
        
        // Payment (akan dibuat nanti)
        // Route::get('/payments', [PaymentController::class, 'index']);
        // Route::get('/payments/{id}', [PaymentController::class, 'show']);
        // Route::get('/payments/{id}/midtrans', [PaymentController::class, 'getMidtransToken']);
        // Route::get('/payments/{id}/check-status', [PaymentController::class, 'checkStatus']);
    });

    Route::post('/payment/midtrans/callback', [PaymentController::class, 'callback']);
});

// ========================================
// Public Routes (Rooms, Reviews, dll)
// ========================================

// Rooms (akan dibuat nanti)
// Route::get('/rooms', [RoomController::class, 'index']);
// Route::get('/rooms/{id}', [RoomController::class, 'show']);
// Route::get('/rooms/search', [RoomController::class, 'search']);

// Reviews (akan dibuat nanti)
// Route::get('/rooms/{id}/reviews', [ReviewController::class, 'index']);
// Route::middleware('auth:sanctum')->post('/rooms/{id}/reviews', [ReviewController::class, 'store']);






