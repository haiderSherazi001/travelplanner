<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Trip;
use App\Models\Activity;
use \App\Models\Vote;
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

    public function castVote($activityId, $value)
    {
        $existingVote = Vote::where('user_id', Auth::id())
                                        ->where('activity_id', $activityId)
                                        ->first();

        // If the user clicks the same vote button again, remove their vote
        if ($existingVote && $existingVote->value == $value) {
            $existingVote->delete();
        } else {
            // Otherwise, create or update their vote
            Vote::updateOrCreate(
                ['user_id' => Auth::id(), 'activity_id' => $activityId],
                ['value' => $value]
            );
        }
    }

    public function render()
    {
        $groupedActivities = $this->trip->activities()
            ->with('votes') 
            ->orderBy('scheduled_at')
            ->get()
            ->groupBy(function($activity) {
                return Carbon::parse($activity->scheduled_at)->format('Y-m-d');
            });

        return view('livewire.trip-itinerary', [
            'groupedActivities' => $groupedActivities
        ])->layout('layouts.app');
    }
}