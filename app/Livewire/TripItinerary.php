<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Trip;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TripItinerary extends Component
{
    public Trip $trip;
    
    // Form fields
    public $title, $type = 'general', $scheduled_at, $location, $notes;

    public function mount(Trip $trip)
    {
        // Security check: Make sure the logged-in user belongs to this trip
        if (!Auth::user()->trips->contains($trip->id)) {
            abort(403, 'Unauthorized access to this trip.');
        }
        
        $this->trip = $trip;
        
        // Default the activity date to the trip's start date
        $this->scheduled_at = $trip->start_date . 'T10:00'; 
    }

    public function addActivity()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'scheduled_at' => 'required|date',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $this->trip->activities()->create([
            'title' => $this->title,
            'type' => $this->type,
            'scheduled_at' => $this->scheduled_at,
            'location' => $this->location,
            'notes' => $this->notes,
        ]);

        $this->reset(['title', 'location', 'notes']);
        $this->type = 'general';
        session()->flash('message', 'Activity added to itinerary!');
    }

    public function render()
    {
        // Fetch and group activities by date for the Day-by-Day schedule view
        $groupedActivities = $this->trip->activities()
            ->orderBy('scheduled_at')
            ->get()
            ->groupBy(function($activity) {
                return Carbon::parse($activity->scheduled_at)->format('Y-m-d');
            });

        return view('livewire.trip-itinerary', [
            'groupedActivities' => $groupedActivities
        ])->layout('layouts.app'); // Wrap it in the standard authenticated layout
    }
}