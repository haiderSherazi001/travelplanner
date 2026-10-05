<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'destination', 
        'start_date', 
        'end_date', 
        'invite_code'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps();
    }
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
    public function packingListItems()
    {
        return $this->hasMany(PackingListItem::class);
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}