<?php

use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\FacilityRoomController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\SocialMediaController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\Tenant\Auth\ForgotPasswordController;
use App\Http\Controllers\Tenant\Auth\LoginController;
use App\Http\Controllers\Tenant\Auth\RegisterController;
use App\Http\Controllers\Tenant\Auth\ResetPasswordController;
use App\Http\Controllers\Tenant\Auth\SocialAuthController;
use App\Http\Controllers\Tenant\BookingController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\PaymentController;
use App\Services\SecureDocumentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SEO & Search Engine Crawlers
|--------------------------------------------------------------------------
*/

Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('seo.sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('seo.robots');

/*
|--------------------------------------------------------------------------
| Public Landing & Room Showcase
|--------------------------------------------------------------------------
*/

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/rooms/{room}', [LandingController::class, 'roomDetail'])->name('rooms.show');
Route::get('/room/{room}', [LandingController::class, 'roomDetail'])->name('rooms.detail'); // Alias backward compatibility

/*
|--------------------------------------------------------------------------
| Smart Role-Based Dashboard Dispatcher
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->get('/dashboard', function () {
    /** @var \App\Models\User $user */
    $user = Auth::user();

    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->isPenghuni()) {
        $isSuspended = $user->residents()->where('status', 'suspended')->exists();
        if ($isSuspended) {
            return redirect()->route('tenant.suspended');
        }
        return redirect()->route('tenant.dashboard');
    }

    return redirect()->route('landing');
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| CMS ADMIN (Hotel & Property Management System)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');

    // Admin Secure Documents
    Route::get('/document/ktp', [DashboardController::class, 'serveKtp'])->name('document.ktp');
    Route::get('/document/sim', [DashboardController::class, 'serveSim'])->name('document.sim');

    // Admin Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Core Management Resources (Kebab-Case URLs)
    Route::resource('rooms', RoomController::class)->names('rooms');
    Route::resource('users', UserController::class)->names('users');
    Route::resource('roles', RoleController::class)->names('roles');
    Route::resource('properties', PropertyController::class)->names('properties');
    Route::resource('facilities', FacilityController::class)->names('facilities');

    // Social Media Management
    Route::resource('social-media', SocialMediaController::class)->names('social-media');
    Route::resource('socialmedia', SocialMediaController::class)->names('socialmedia'); // Backward alias

    // Facility Rooms Pivot
    Route::resource('facility-rooms', FacilityRoomController::class)->names('facility-rooms');
    Route::resource('facility_rooms', FacilityRoomController::class)->names('facility_rooms'); // Backward alias

    // Tenants Management
    Route::resource('tenants', TenantController::class)->names('tenants');
    Route::post('/tenants/residents/{resident}/activate', [TenantController::class, 'activate'])->name('tenants.activate');
    Route::post('/tenants/residents/{resident}/deactivate', [TenantController::class, 'deactivate'])->name('tenants.deactivate');

    // Reviews Management & Replies
    Route::post('/reviews/{review}/reply', [ReviewController::class, 'reply'])->name('review.reply');
    Route::delete('/review-replies/{reply}', [ReviewController::class, 'deleteReply'])->name('review.reply.delete');

    // Reports Engine
    Route::get('/reports/tenants', [ReportController::class, 'tenants'])->name('reports.tenants');
    Route::get('/reports/tenants/pdf', [ReportController::class, 'tenantsPdf'])->name('reports.tenants.pdf');
    Route::get('/reports/tenants/excel', [ReportController::class, 'tenantsExcel'])->name('reports.tenants.excel');

    Route::get('/reports/finance', [ReportController::class, 'finance'])->name('reports.finance');
    Route::get('/reports/finance/pdf', [ReportController::class, 'financePdf'])->name('reports.finance.pdf');
    Route::get('/reports/finance/excel', [ReportController::class, 'financeExcel'])->name('reports.finance.excel');
});

/*
|--------------------------------------------------------------------------
| Tenant & Guest Portal Routes
|--------------------------------------------------------------------------
*/

Route::prefix('tenant')->name('tenant.')->group(function () {

    // Guest Auth Redirects (Maps to unified auth system)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login']);

        Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [RegisterController::class, 'register']);

        // Social Login
        Route::get('/auth/{provider}', [SocialAuthController::class, 'redirect'])->name('social.redirect');
        Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');

        // Forgot / Reset Password
        Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
    });

    // Authenticated Tenant Portal
    Route::middleware(['auth'])->group(function () {

        // Guest Dashboard & Status
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/suspended', [LoginController::class, 'suspended'])->name('suspended');
        Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');

        // Room Bookings
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('/booking/{room}', [BookingController::class, 'create'])->name('booking.create');
        Route::post('/booking/{room}', [BookingController::class, 'store'])->name('booking.store');
        Route::delete('/bookings/{resident}', [BookingController::class, 'destroy'])->name('bookings.destroy');

        // Payment & Invoices
        Route::get('/payment/midtrans/{payment}', [PaymentController::class, 'midtrans'])->name('payment.midtrans');
        Route::get('/payment/finish/{payment}', [PaymentController::class, 'finish'])->name('payment.finish');
        Route::get('/payment/{payment}/check-status', [PaymentController::class, 'checkStatus'])->name('payment.check-status');
        Route::get('/payment/{payment}/invoice', [PaymentController::class, 'invoice'])->name('payment.invoice');

        // Tenant Documents
        Route::get('/document/ktp', [DashboardController::class, 'serveKtp'])->name('document.global.ktp');
        Route::get('/document/sim', [DashboardController::class, 'serveSim'])->name('document.global.sim');

        // Logout
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    });

    // Midtrans Payment Webhook / Notification Callback (Open endpoint)
    Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
});

/*
|--------------------------------------------------------------------------
| Tenant Review & Document Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::post('/rooms/{room}/reviews', [LandingController::class, 'storeReview'])->name('room.review.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('review.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('review.destroy');

    Route::get('/document/view/{type}', function ($type, SecureDocumentService $secureDoc) {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->profile;

        if (!$profile) {
            abort(404, 'Profile not found');
        }

        $path = match($type) {
            'ktp'      => $profile->ktp_photo,
            'sim'      => $profile->sim_photo,
            'passport' => $profile->passport_photo,
            default    => null
        };

        if (!$path) {
            abort(404, 'Document not found');
        }

        try {
            $content = $secureDoc->secureRead($path);
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $mimeType = match($extension) {
                'jpg', 'jpeg' => 'image/jpeg',
                'png'         => 'image/png',
                'webp'        => 'image/webp',
                default       => 'application/octet-stream',
            };

            return response($content)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'inline')
                ->header('X-Content-Type-Options', 'nosniff')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate');

        } catch (\Exception $e) {
            Log::error('Document access error', [
                'user_id' => $user->id,
                'type'    => $type,
                'error'   => $e->getMessage()
            ]);
            abort(500, 'Failed to load document');
        }
    })->name('document.view');
});

/*
|--------------------------------------------------------------------------
| Breeze Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Fallback & Error Pages
|--------------------------------------------------------------------------
*/

Route::get('/no-access', function () {
    return view('errors.no-access');
})->name('no-access');
