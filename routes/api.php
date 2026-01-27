<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\PaymentController;

Route::post('/payment/midtrans/callback', [PaymentController::class, 'callback']);
