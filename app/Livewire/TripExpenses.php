<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Trip;
use Illuminate\Support\Facades\Auth;

class TripExpenses extends Component
{
    public Trip $trip;
    public $description, $amount, $date;

    public function mount(Trip $trip)
    {
        // Security check
        if (!Auth::user()->trips->contains($trip->id)) {
            abort(403, 'Unauthorized access to this trip.');
        }
        
        $this->trip = $trip;
        $this->date = now()->format('Y-m-d'); // Default to today
    }

    public function addExpense()
    {
        $this->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
        ]);

        $this->trip->expenses()->create([
            'user_id' => Auth::id(), // The currently logged-in user pays
            'description' => $this->description,
            'amount' => $this->amount,
            'date' => $this->date,
        ]);

        $this->reset(['description', 'amount']);
        session()->flash('message', 'Expense logged successfully!');
    }

    public function render()
    {
        // Fetch expenses and eager load the user who paid
        $expenses = $this->trip->expenses()->with('payer')->orderByDesc('date')->get();
        
        // Calculate the total trip cost
        $totalCost = $expenses->sum('amount');

        return view('livewire.trip-expenses', [
            'expenses' => $expenses,
            'totalCost' => $totalCost
        ])->layout('layouts.app');
    }
}