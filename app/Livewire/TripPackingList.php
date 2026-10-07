<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Trip;
use Illuminate\Support\Facades\Auth;

class TripPackingList extends Component
{
    public Trip $trip;
    public $newItem = '';

    public function mount(Trip $trip)
    {
        if (!Auth::user()->trips->contains($trip->id)) {
            abort(403, 'Unauthorized access to this trip.');
        }
        $this->trip = $trip;
    }

    // Listen directly to the WebSocket channel for this specific trip
    public function getListeners()
    {
        return [
            "echo-private:trip.{$this->trip->id},TripTabUpdated" => 'handleTabUpdate',
        ];
    }

    public function handleTabUpdate($event)
    {
        if (isset($event['tabName']) && $event['tabName'] === 'packing') {
        }
    }

    public function addItem()
    {
        $this->validate([
            'newItem' => 'required|string|max:255',
        ]);

        $this->trip->packingListItems()->create([
            'item' => $this->newItem,
        ]);

        broadcast(new \App\Events\TripTabUpdated($this->trip->id, 'packing'))->toOthers();
        $this->newItem = '';
    }

    public function togglePacked($itemId)
    {
        $item = $this->trip->packingListItems()->find($itemId);
        if ($item) {
            // This magically adds the user if they aren't there, or removes them if they are
            $item->packedBy()->toggle(Auth::id());
            
            broadcast(new \App\Events\TripTabUpdated($this->trip->id, 'packing'))->toOthers();
        }
    }

    public function deleteItem($itemId)
    {
        $item = $this->trip->packingListItems()->find($itemId);
        if ($item) {
            $item->delete();
            broadcast(new \App\Events\TripTabUpdated($this->trip->id, 'packing'))->toOthers();
        }
    }

    public function render()
    {
        // Load items along with the users who packed them
        $items = $this->trip->packingListItems()->with('packedBy')->latest()->get();
        
        $totalItems = $items->count();
        
        // Calculate MY personal progress
        $myPackedCount = $items->filter(function($item) {
            return $item->packedBy->contains(Auth::id());
        })->count();
        
        $progress = $totalItems > 0 ? round(($myPackedCount / $totalItems) * 100) : 0;

        return view('livewire.trip-packing-list', [
            'items' => $items,
            'progress' => $progress,
            'tripMembers' => $this->trip->users
        ]);
    }
}