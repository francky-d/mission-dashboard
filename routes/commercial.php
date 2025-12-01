<?php

use App\Models\Mission;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Commercial Routes
|--------------------------------------------------------------------------
|
| Routes accessible uniquement par les commerciaux.
|
*/

Route::middleware(['auth', 'verified', 'commercial'])->prefix('commercial')->name('commercial.')->group(function () {
    // Dashboard
    Route::view('dashboard', 'commercial.dashboard')->name('dashboard');

    // Missions
    Route::view('missions', 'commercial.missions.index')->name('missions.index');
    Route::view('missions/create', 'commercial.missions.create')->name('missions.create');
    Route::get('missions/{mission}', fn (Mission $mission) => view('commercial.missions.show', compact('mission')))->name('missions.show');
    Route::get('missions/{mission}/edit', fn (Mission $mission) => view('commercial.missions.edit', compact('mission')))->name('missions.edit');

    // Consultants
    Route::get('consultants/{user}', fn (User $user) => view('commercial.consultants.show', compact('user')))->name('consultants.show');

    // Messages
    Route::view('messages', 'commercial.messages.index')->name('messages.index');
});
