<?php

use App\Http\Controllers\Consultant\MissionController;
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
    // Dashboard
    Route::view('dashboard', 'consultant.dashboard')->name('dashboard');

    // Profil consultant
    Route::view('profile', 'consultant.profile')->name('profile');

    // Missions
    Route::get('missions', [MissionController::class, 'index'])->name('missions.index');
    Route::get('missions/{mission}', [MissionController::class, 'show'])->name('missions.show');

    // Candidatures
    Route::view('applications', 'consultant.applications.index')->name('applications.index');

    // Messages
    Route::view('messages', 'consultant.messages.index')->name('messages.index');
});
