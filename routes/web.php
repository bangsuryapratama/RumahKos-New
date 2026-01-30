<?php

use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Tenant\Auth\LoginController;
use App\Http\Controllers\Tenant\Auth\RegisterController;
use App\Http\Controllers\Tenant\Auth\SocialAuthController;
use App\Http\Controllers\Tenant\Auth\ForgotPasswordController;
use App\Http\Controllers\Tenant\Auth\ResetPasswordController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\BookingController;
use App\Http\Controllers\Tenant\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Landing Page
|--------------------------------------------------------------------------
*/

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/rooms/{room}', [LandingController::class, 'roomDetail'])->name('rooms.detail');

/*
|--------------------------------------------------------------------------
| Dashboard Redirect (fix Breeze error)
|--------------------------------------------------------------------------
|
| Breeze tetap manggil route('dashboard'), jadi kita redirect otomatis
| ke admin dashboard biar gak error.
|
*/

Route::middleware(['auth'])->get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->name('dashboard');


/*
|--------------------------------------------------------------------------
| CMS ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('admin')->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])
        ->name('admin.dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Room CRUD
    Route::resource('rooms', RoomController::class)
        ->names('admin.rooms');

    //Management User
    Route::resource('users', UserController::class)
        ->names('admin.users');

    //Management Role
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class)
        ->names('admin.roles');

    //Management Property
    Route::resource('properties', \App\Http\Controllers\Admin\PropertyController::class)
        ->names('admin.properties');

    //managemnt facility
    Route::resource('facilities', \App\Http\Controllers\Admin\FacilityController::class)
        ->names('admin.facilities');

    //management facility room
    Route::resource('facility_rooms', \App\Http\Controllers\Admin\FacilityRoomController::class)
        ->names('admin.facility_rooms');

    //management tenants
    Route::resource('tenants', \App\Http\Controllers\Admin\TenantController::class)
        ->names('admin.tenants');




    Route::post('/review/{review}/reply', [ReviewController::class, 'reply'])
        ->name('admin.review.reply');

    // Delete reply
    Route::delete('/review-reply/{reply}', [ReviewController::class, 'deleteReply'])
        ->name('admin.review.reply.delete');
});





Route::middleware(['auth:tenant'])->group(function () {
     // Create review
    Route::post('/room/{room}/review', [ReviewController::class, 'store'])
        ->name('room.review.store');

    // Update review
    Route::put('/review/{review}', [ReviewController::class, 'update'])
        ->name('review.update');

    // Delete review
    Route::delete('/review/{review}', [ReviewController::class, 'destroy'])
        ->name('review.destroy');
});


/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';


//Error page for unauthorized access
Route::get('/no-access', function () {
    return view('errors.no-access');
})->name('no-access');


/*
|--------------------------------------------------------------------------
| Tenant Routes (Penghuni Kos)
|--------------------------------------------------------------------------
*/

Route::prefix('tenant')->name('tenant.')->group(function () {

    // Guest Routes (belum login)
    Route::middleware('guest:tenant')->group(function () {

        // Login
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [LoginController::class, 'login']);

        // Register
        Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('register', [RegisterController::class, 'register']);

        // Social Login
        Route::get('auth/{provider}', [SocialAuthController::class, 'redirect'])->name('social.redirect');
        Route::get('auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');

        //  Forgot Password
        Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
            ->name('password.request');
        Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
            ->name('password.email');

        //  Reset Password
        Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
            ->name('password.reset');
        Route::post('password/reset', [ResetPasswordController::class, 'reset'])
            ->name('password.update');
    });

    // Authenticated Routes (sudah login)
    Route::middleware('auth:tenant')->group(function () {

        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Profile Update
        Route::put('profile', [DashboardController::class, 'updateProfile'])->name('profile.update');

        // Booking
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('/booking/{room}', [BookingController::class, 'create'])->name('booking.create');
        Route::post('/booking/{room}', [BookingController::class, 'store'])->name('booking.store');

        // Payment Routes
        Route::get('/payment/midtrans/{payment}', [PaymentController::class, 'midtrans'])
            ->name('payment.midtrans');
        Route::get('/payment/finish/{payment}', [PaymentController::class, 'finish'])
            ->name('payment.finish');
        // Route::get('/payment/manual/{payment}', [PaymentController::class, 'manual'])
        //     ->name('payment.manual');
        // Route::post('/payment/upload/{payment}', [PaymentController::class, 'uploadProof'])
        //     ->name('payment.upload-proof');
        Route::get('/payment/{payment}/check-status', [PaymentController::class, 'checkStatus'])
            ->name('payment.check-status');

        // Logout
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    });


    Route::post('/payment/callback', [PaymentController::class, 'callback'])
        ->name('payment.callback');
});
