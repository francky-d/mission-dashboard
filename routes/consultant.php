<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Consultant Routes
|--------------------------------------------------------------------------
|
| Routes accessible uniquement par les consultants.
|
*/

Route::middleware(['auth', 'verified', 'consultant'])->prefix('consultant')->name('consultant.')->group(function () {
    // Profil consultant
    Route::view('profile', 'consultant.profile')->name('profile');
});
