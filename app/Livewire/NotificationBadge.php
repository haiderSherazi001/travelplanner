<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class NotificationBadge extends Component
{
    #[On('notifications-read')]
    public function render()
    {
        return view('livewire.notification-badge', [
            'count' => auth()->user()->unreadNotifications->count()
        ]);
    }
}