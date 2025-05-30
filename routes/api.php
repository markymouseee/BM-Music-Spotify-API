<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.req');

    Route::post('/register', [UserController::class, 'store'])
        ->name('user.store');

    Route::post('/logout', [DashboardController::class, 'logout'])
        ->name('logout');
});
