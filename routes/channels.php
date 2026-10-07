<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Trip;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('trip.{tripId}', function ($user, $tripId) {
    $trip = Trip::find($tripId);
    return $trip && $user->trips->contains($tripId);
});
