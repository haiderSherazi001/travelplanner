<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Trip;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewExpenseNotification;

class TripExpenses extends Component
{
    public Trip $trip;
    public $description, $amount, $date;

    public function mount(Trip $trip)
    {
        if (!Auth::user()->trips->contains($trip->id)) {
            abort(403, 'Unauthorized access to this trip.');
        }
        
        $this->trip = $trip;
        $this->date = now()->format('Y-m-d'); // Default to today
    }

    public function getListeners()
    {
        return [
            "echo-private:trip.{$this->trip->id},TripTabUpdated" => 'handleTabUpdate',
        ];
    }

    public function handleTabUpdate($event)
    {
        if (isset($event['tabName']) && $event['tabName'] === 'finances') {
        }
    }

    public function addExpense()
    {
        $this->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
        ]);

        $expense = $this->trip->expenses()->create([
            'user_id' => Auth::id(), 
            'description' => $this->description,
            'amount' => $this->amount,
            'date' => $this->date,
        ]);

        broadcast(new \App\Events\TripTabUpdated($this->trip->id, 'finances'))->toOthers();
        
        // Load the trip and payer relationships so the notification has access to them
        $expense->load(['trip', 'payer']);

        // Find all users attached to the trip EXCEPT the person who just paid
        $usersToNotify = $this->trip->users()->where('users.id', '!=', Auth::id())->get();
        
        // Send the notification
        if ($usersToNotify->isNotEmpty()) {
            Notification::send($usersToNotify, new NewExpenseNotification($expense));
        }

        $this->reset(['description', 'amount']);
        session()->flash('message', 'Expense logged successfully!');
    }

    public function render()
    {
        $expenses = $this->trip->expenses()->with('payer')->orderByDesc('date')->orderByDesc('created_at')->get();
        $totalCost = $expenses->sum('amount');
        
        // Count how many people are on the trip (minimum 1 to avoid division by zero)
        $memberCount = $this->trip->users()->count();
        $memberCount = $memberCount > 0 ? $memberCount : 1;
        
        // Calculate even split
        $perPersonShare = $totalCost / $memberCount;

        return view('livewire.trip-expenses', [
            'expenses' => $expenses,
            'totalCost' => $totalCost,
            'perPersonShare' => $perPersonShare,
            'memberCount' => $memberCount
        ]);
    }
}