<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Trip;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class TripChat extends Component
{
    public Trip $trip;
    public $content = '';

    public function mount(Trip $trip)
    {
        if (!Auth::user()->trips->contains($trip->id)) {
            abort(403, 'Unauthorized access to this trip.');
        }
        $this->trip = $trip;
    }

    public function sendMessage()
    {
        $this->validate([
            'content' => 'required|string|max:1000',
        ]);

        $this->trip->messages()->create([
            'user_id' => Auth::id(),
            'content' => $this->content,
        ]);

        $this->reset('content');
    }

    public function render()
    {
        // Fetch all messages and eager load the user who sent them
        $messages = $this->trip->messages()->with('user')->orderBy('created_at', 'asc')->get();

        return view('livewire.trip-chat', [
            'messages' => $messages
        ]);
    }
}