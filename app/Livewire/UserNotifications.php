<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class UserNotifications extends Component
{
    public function markAsRead($notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
            $this->dispatch('notifications-read'); // Tells the badge to update
        }
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        $this->dispatch('notifications-read');
    }

    public function readAndRedirect($notificationId, $tripId, $tab)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        if ($notification && is_null($notification->read_at)) {
            $notification->markAsRead();
            $this->dispatch('notifications-read');
        }

        return $this->redirectRoute('trips.show', [
            'trip' => $tripId, 
            'activeTab' => $tab
        ], navigate: true);
    }

    public function render()
    {
        return view('livewire.user-notifications', [
            'notifications' => Auth::user()->notifications()->get()
        ])->layout('layouts.app');
    }
}