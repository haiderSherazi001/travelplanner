<?php

use Illuminate\Support\Facades\Route;
use  \App\Livewire\TripItinerary;
use \App\Livewire\TripExpenses;

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

require __DIR__.'/auth.php';
