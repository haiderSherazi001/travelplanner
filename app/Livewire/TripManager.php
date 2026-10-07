<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\Trip;
use Illuminate\Support\Facades\Auth;

class TripManager extends Component
{
    public Trip $trip;
    
    #[Url(history: true)]
    public $activeTab = 'itinerary';

    public $unreadTabs = []; // Array to store tabs with new activity

    public function mount(Trip $trip)
    {
        if (!Auth::user()->trips->contains($trip->id)) {
            abort(403, 'Unauthorized access to this trip.');
        }
        $this->trip = $trip;
        
        if (!in_array($this->activeTab, ['itinerary', 'finances', 'packing'])) {
            $this->activeTab = 'itinerary';
        }
    }

    // This dynamically registers the WebSocket listener for this specific trip
    public function getListeners()
    {
        return [
            "echo-private:trip.{$this->trip->id},TripTabUpdated" => 'markTabUnread',
        ];
    }

    // This fires instantly when the WebSocket event is received
    public function markTabUnread($event)
    {
        $tab = $event['tabName'];
        
        // If we are not currently looking at the tab, add a red dot
        if ($this->activeTab !== $tab && !in_array($tab, $this->unreadTabs)) {
            $this->unreadTabs[] = $tab;
        }
        
        // Also refresh the component to pull in the new data!
        $this->dispatch('$refresh');
    }

    // Custom method to change tabs and clear the red dot
    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        // Remove the tab from the unread list when we click it
        $this->unreadTabs = array_diff($this->unreadTabs, [$tab]);
    }

    public function render()
    {
        return view('livewire.trip-manager')->layout('layouts.app');
    }
}