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

    public function render()
    {
        return view('livewire.trip-manager')->layout('layouts.app');
    }
}