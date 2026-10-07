<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TripTabUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tripId;
    public $tabName;

    public function __construct($tripId, $tabName)
    {
        $this->tripId = $tripId;
        $this->tabName = $tabName;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('trip.' . $this->tripId),
        ];
    }
}