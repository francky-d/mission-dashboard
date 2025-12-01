<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    // Legacy routes (kept for backwards compatibility)
    Volt::route('register', 'pages.auth.register')
        ->name('register');

    Volt::route('login', 'pages.auth.login')
        ->name('login');

    // Consultant authentication routes
    Volt::route('consultant/register', 'pages.auth.consultant.register')
        ->name('consultant.register');

    Volt::route('consultant/login', 'pages.auth.consultant.login')
        ->name('consultant.login');

    // Commercial authentication routes
    Volt::route('commercial/register', 'pages.auth.commercial.register')
        ->name('commercial.register');

    Volt::route('commercial/login', 'pages.auth.commercial.login')
        ->name('commercial.login');

    Volt::route('forgot-password', 'pages.auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'pages.auth.reset-password')
        ->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm');
});
