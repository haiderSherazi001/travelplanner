<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Trip;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TripDashboard extends Component
{
    public $title, $destination, $start_date, $end_date;

    public function createTrip()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $trip = Trip::create([
            'title' => $this->title,
            'destination' => $this->destination,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'invite_code' => Str::random(10), // Generates a random code for future invites
        ]);

        // Attach the currently logged-in user as the trip owner
        Auth::user()->trips()->attach($trip->id, ['role' => 'owner']);

        // Clear the form fields
        $this->reset(['title', 'destination', 'start_date', 'end_date']);
        
        session()->flash('message', 'Trip created successfully!');
    }

    public function render()
    {
        // Fetch all trips for the logged-in user
        $trips = Auth::user()->trips()->orderBy('start_date')->get();
        
        return view('livewire.trip-dashboard', compact('trips'));
    }
}