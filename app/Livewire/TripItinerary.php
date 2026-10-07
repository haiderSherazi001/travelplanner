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
    public $title, $type = 'general', $scheduled_at, $location, $booking_url, $reservation_code, $notes;
    
    // Tracks if we are editing an existing activity
    public $editingId = null;

    public function mount(Trip $trip)
    {
        if (!Auth::user()->trips->contains($trip->id)) {
            abort(403, 'Unauthorized access to this trip.');
        }
        $this->trip = $trip;
        $this->scheduled_at = $trip->start_date . 'T10:00'; 
    }

    public function getListeners()
    {
        return [
            "echo-private:trip.{$this->trip->id},TripTabUpdated" => 'handleTabUpdate',
        ];
    }

    public function handleTabUpdate($event)
    {
        if (isset($event['tabName']) && $event['tabName'] === 'itinerary') {
            // Forces Livewire to re-render
        }
    }

    public function saveActivity()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'scheduled_at' => 'required|date',
            'location' => 'nullable|string|max:255',
            'booking_url' => 'nullable|url|max:255',
            'reservation_code' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        if ($this->editingId) {
            // Update existing
            $activity = $this->trip->activities()->find($this->editingId);
            if ($activity) {
                $activity->update([
                    'title' => $this->title,
                    'type' => $this->type,
                    'scheduled_at' => $this->scheduled_at,
                    'location' => $this->location,
                    'booking_url' => $this->booking_url,
                    'reservation_code' => $this->reservation_code,
                    'notes' => $this->notes,
                ]);
            }
            $this->editingId = null;
        } else {
            // Create new
            $this->trip->activities()->create([
                'title' => $this->title,
                'type' => $this->type,
                'scheduled_at' => $this->scheduled_at,
                'location' => $this->location,
                'booking_url' => $this->booking_url,
                'reservation_code' => $this->reservation_code,
                'notes' => $this->notes,
            ]);
        }

        $this->reset(['title', 'location', 'booking_url', 'reservation_code', 'notes']);
        $this->type = 'general';
        $this->scheduled_at = $this->trip->start_date . 'T10:00';
        
        broadcast(new \App\Events\TripTabUpdated($this->trip->id, 'itinerary'))->toOthers();
    }

    public function editActivity($id)
    {
        $activity = $this->trip->activities()->find($id);
        if ($activity) {
            $this->editingId = $activity->id;
            $this->title = $activity->title;
            $this->type = $activity->type;
            $this->scheduled_at = Carbon::parse($activity->scheduled_at)->format('Y-m-d\TH:i');
            $this->location = $activity->location;
            $this->booking_url = $activity->booking_url;
            $this->reservation_code = $activity->reservation_code;
            $this->notes = $activity->notes;
        }
    }

    public function cancelEdit()
    {
        $this->reset(['editingId', 'title', 'location', 'booking_url', 'reservation_code', 'notes']);
        $this->type = 'general';
        $this->scheduled_at = $this->trip->start_date . 'T10:00';
    }

    public function deleteActivity($id)
    {
        $activity = $this->trip->activities()->find($id);
        if ($activity) {
            $activity->delete();
            broadcast(new \App\Events\TripTabUpdated($this->trip->id, 'itinerary'))->toOthers();
        }
    }

    public function castVote($activityId, $value)
    {
        $existingVote = Vote::where('user_id', Auth::id())
                            ->where('activity_id', $activityId)
                            ->first();

        if ($existingVote && $existingVote->value == $value) {
            $existingVote->delete();
        } else {
            Vote::updateOrCreate(
                ['user_id' => Auth::id(), 'activity_id' => $activityId],
                ['value' => $value]
            );
        }
        broadcast(new \App\Events\TripTabUpdated($this->trip->id, 'itinerary'))->toOthers();
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
        ]);
    }
}