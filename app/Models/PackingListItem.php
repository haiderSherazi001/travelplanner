<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackingListItem extends Model
{
    use HasFactory;

    protected $fillable = ['trip_id', 'item', 'is_packed'];

    protected $casts = [
        'is_packed' => 'boolean',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}