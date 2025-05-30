<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('pages.auth.login');
})->name('login.index');

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard.index');

