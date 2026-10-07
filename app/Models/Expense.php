<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'trip_id', 'description', 'amount', 'date', 'is_personal'];
    
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function payer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}