<?php

use Illuminate\Support\Facades\Route;
use  \App\Livewire\TripItinerary;
use \App\Livewire\TripExpenses;
use \App\Livewire\TripPackingList;
use \App\Livewire\TripChat;

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

require __DIR__.'/auth.php';
