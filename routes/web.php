<?php

use App\Enums\UserRole;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get('dashboard', function () {
    $user = auth()->user();

    return match ($user->role) {
        UserRole::Commercial => redirect()->route('commercial.dashboard'),
        UserRole::Consultant => redirect()->route('consultant.dashboard'),
        UserRole::Admin => redirect('/admin'),
        default => redirect('/'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
