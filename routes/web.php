<?php

use Illuminate\Support\Facades\Route;
use \App\Livewire\TripItinerary;
use \App\Livewire\TripExpenses;
use \App\Livewire\TripPackingList;
use \App\Livewire\TripChat;
use \App\Livewire\TripManager;
use \App\Models\Trip;
use \Illuminate\Support\Facades\Auth;
use \App\Http\Controllers\ItineraryExportController;
use \App\Livewire\UserNotifications;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/notifications', UserNotifications::class)
    ->middleware(['auth'])
    ->name('notifications.index');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/trips/{trip}', TripManager::class)
    ->middleware(['auth'])
    ->name('trips.show');

Route::get('/trips/join/{code}', function ($code) {
    $trip = Trip::where('invite_code', $code)->firstOrFail();
    
    $user = Auth::user();

    if (!$user->trips->contains($trip->id)) {
        $user->trips()->attach($trip->id, ['role' => 'member']);
    }

    return redirect()->route('trips.show', $trip->id)
                     ->with('message', "Welcome to {$trip->title}!");
})->middleware(['auth'])->name('trips.join');

Route::get('/trips/{trip}/export', [ItineraryExportController::class, 'download'])
    ->middleware(['auth'])
    ->name('trips.export');

require __DIR__.'/auth.php';
