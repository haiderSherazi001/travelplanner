<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'title',
        'type',
        'scheduled_at',
        'location',
        'booking_url',      
        'reservation_code', 
        'notes'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function getUpvotesAttribute()
    {
        return $this->votes->where('value', 1)->count();
    }

    public function getDownvotesAttribute()
    {
        return $this->votes->where('value', -1)->count();
    }
}