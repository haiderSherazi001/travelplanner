<?php

use Illuminate\Support\Facades\Route;
use  \App\Livewire\TripItinerary;

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

require __DIR__.'/auth.php';
