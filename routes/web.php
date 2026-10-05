<?php

use Illuminate\Support\Facades\Route;
use \App\Livewire\TripItinerary;
use \App\Livewire\TripExpenses;
use \App\Livewire\TripPackingList;
use \App\Livewire\TripChat;
use \App\Models\Trip;
use \Illuminate\Support\Facades\Auth;
use \App\Http\Controllers\ItineraryExportController;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/trips/{trip}', TripItinerary::class)
    ->middleware(['auth'])
    ->name('trips.show');

Route::get('/trips/{trip}/expenses', TripExpenses::class)
    ->middleware(['auth'])
    ->name('trips.expenses');

Route::get('/trips/{trip}/packing-list', TripPackingList::class)
    ->middleware(['auth'])
    ->name('trips.packing-list');

Route::get('/trips/{trip}/chat', TripChat::class)
    ->middleware(['auth'])
    ->name('trips.chat');

Route::get('/trips/join/{code}', function ($code) {
    $trip = Trip::where('invite_code', $code)->firstOrFail();
    
    $user = Auth::user();

    // Check if the user is already on this trip
    if (!$user->trips->contains($trip->id)) {
        // Attach them as a member
        $user->trips()->attach($trip->id, ['role' => 'member']);
    }

    return redirect()->route('trips.show', $trip->id)
                     ->with('message', "Welcome to {$trip->title}!");
})->middleware(['auth'])->name('trips.join');

Route::get('/trips/{trip}/export', [ItineraryExportController::class, 'download'])
    ->middleware(['auth'])
    ->name('trips.export');

require __DIR__.'/auth.php';
