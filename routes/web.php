<?php

use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\SocialMediaController;
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
use App\Services\SecureDocumentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| LANDING
|--------------------------------------------------------------------------
*/

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/rooms/{room}', [LandingController::class, 'roomDetail'])->name('rooms.detail');

/*
|--------------------------------------------------------------------------
| DASHBOARD REDIRECT
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->get('/dashboard', function () {
    $user = Auth::user();

    if ($user->isAdmin()) return redirect()->route('admin.dashboard');
    if ($user->isPenghuni()) return redirect()->route('tenant.dashboard');

    Auth::logout();
    return redirect('/')->with('error', 'Akun tidak memiliki akses.');
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardAdminController::class, 'index'])
        ->name('dashboard');
        
    Route::get('/document/ktp', [DashboardController::class, 'serveKtp'])
        ->name('document.ktp');

    Route::get('/document/sim', [DashboardController::class, 'serveSim'])
        ->name('document.sim');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('rooms', RoomController::class)->names('rooms');
    Route::resource('users', UserController::class)->names('users');
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class)->names('roles');
    Route::resource('properties', \App\Http\Controllers\Admin\PropertyController::class)->names('properties');
    Route::resource('facilities', \App\Http\Controllers\Admin\FacilityController::class)->names('facilities');
    Route::resource('socialmedia', \App\Http\Controllers\Admin\SocialMediaController::class)->names('socialmedia');
    Route::resource('facility_rooms', \App\Http\Controllers\Admin\FacilityRoomController::class)->names('facility_rooms');
    Route::resource('tenants', \App\Http\Controllers\Admin\TenantController::class)->names('tenants');

    Route::post('tenants/residents/{resident}/activate', [TenantController::class, 'activate'])
        ->name('tenants.activate');

    Route::post('tenants/residents/{resident}/deactivate', [TenantController::class, 'deactivate'])
        ->name('tenants.deactivate');

    Route::post('/review/{review}/reply', [ReviewController::class, 'reply'])
        ->name('review.reply');

    Route::delete('/review-reply/{reply}', [ReviewController::class, 'deleteReply'])
        ->name('review.reply.delete');

    Route::get('reports/tenants', [ReportController::class, 'tenants'])->name('reports.tenants');
    Route::get('reports/finance', [ReportController::class, 'finance'])->name('reports.finance');
});

/*
|--------------------------------------------------------------------------
| TENANT AUTH
|--------------------------------------------------------------------------
*/

Route::prefix('tenant')->name('tenant.')->group(function () {

    Route::middleware('guest:tenant')->group(function () {

        Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [LoginController::class, 'login']);

        Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('register', [RegisterController::class, 'register']);
    });

    Route::middleware('auth:tenant')->group(function () {

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::put('profile', [DashboardController::class, 'updateProfile'])->name('profile.update');

        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('/booking/{room}', [BookingController::class, 'create'])->name('booking.create');
        Route::post('/booking/{room}', [BookingController::class, 'store'])->name('booking.store');

        Route::delete('/bookings/{resident}', [BookingController::class, 'destroy'])->name('bookings.destroy');

        Route::get('/payment/midtrans/{payment}', [PaymentController::class, 'midtrans'])
            ->name('payment.midtrans');

        Route::get('/payment/finish/{payment}', [PaymentController::class, 'finish'])
            ->name('payment.finish');

        Route::get('/payment/{payment}/check-status', [PaymentController::class, 'checkStatus'])
            ->name('payment.check-status');

        Route::get('/payment/{payment}/invoice', [PaymentController::class, 'invoice'])
            ->name('payment.invoice');

        // optional document (tenant)
        Route::get('/document/ktp', [DashboardController::class, 'serveKtp'])
            ->name('document.ktp');

        Route::get('/document/sim', [DashboardController::class, 'serveSim'])
            ->name('document.sim');

        Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    });
});