<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoomController;

Route::middleware(['auth'])->prefix('admin')->group(function () {

    // Rooms CRUD
    Route::resource('rooms', RoomController::class);

});
