<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Trip;
use App\Models\PackingListItem;
use Illuminate\Support\Facades\Auth;

class TripPackingList extends Component
{
    public Trip $trip;
    public $newItem = '';

    public function mount(Trip $trip)
    {
        // Security check
        if (!Auth::user()->trips->contains($trip->id)) {
            abort(403, 'Unauthorized access to this trip.');
        }
        $this->trip = $trip;
    }

    public function addItem()
    {
        $this->validate([
            'newItem' => 'required|string|max:255',
        ]);

        $this->trip->packingListItems()->create([
            'item' => $this->newItem,
            'is_packed' => false,
        ]);

        $this->newItem = ''; // Clear the input field
    }

    public function togglePacked($itemId)
    {
        $item = $this->trip->packingListItems()->find($itemId);
        if ($item) {
            $item->update([
                'is_packed' => !$item->is_packed
            ]);
        }
    }

    public function deleteItem($itemId)
    {
        $item = $this->trip->packingListItems()->find($itemId);
        if ($item) {
            $item->delete();
        }
    }

    public function render()
    {
        $items = $this->trip->packingListItems()->latest()->get();
        
        // Calculate progress
        $totalItems = $items->count();
        $packedItems = $items->where('is_packed', true)->count();
        $progress = $totalItems > 0 ? round(($packedItems / $totalItems) * 100) : 0;

        return view('livewire.trip-packing-list', [
            'items' => $items,
            'progress' => $progress,
            'totalItems' => $totalItems,
            'packedItems' => $packedItems
        ])->layout('layouts.app');
    }
}