<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Trip;

class TripMembers extends Component
{
    public Trip $trip;

    public function render()
    {
        return view('livewire.trip-members', [
            'members' => $this->trip->users
        ]);
    }
}