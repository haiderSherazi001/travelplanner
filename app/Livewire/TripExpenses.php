<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Trip;
use Illuminate\Support\Facades\Auth;

class TripExpenses extends Component
{
    public Trip $trip;
    
    // Form fields
    public $description, $amount, $date, $is_personal = false;
    
    // Edit state
    public $editingId = null;

    public function mount(Trip $trip)
    {
        if (!Auth::user()->trips->contains($trip->id)) {
            abort(403, 'Unauthorized access to this trip.');
        }
        $this->trip = $trip;
        $this->date = date('Y-m-d');
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
            // Forces Livewire to re-render and fetch new data instantly
        }
    }

    public function saveExpense()
    {
        $this->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'is_personal' => 'boolean'
        ]);

        if ($this->editingId) {
            $expense = $this->trip->expenses()->find($this->editingId);
            if ($expense) {
                $expense->update([
                    'description' => $this->description,
                    'amount' => $this->amount,
                    'date' => $this->date,
                    'is_personal' => $this->is_personal,
                ]);
            }
            $this->editingId = null;
        } else {
            $expense = $this->trip->expenses()->create([
                'user_id' => Auth::id(),
                'description' => $this->description,
                'amount' => $this->amount,
                'date' => $this->date,
                'is_personal' => $this->is_personal,
            ]);

            // Only notify the group if it's a GROUP expense
            if (!$this->is_personal) {
                foreach ($this->trip->users as $user) {
                    if ($user->id !== Auth::id()) {
                        $user->notify(new \App\Notifications\NewExpenseNotification($expense));
                    }
                }
            }
        }

        $this->reset(['description', 'amount', 'is_personal']);
        $this->date = date('Y-m-d');
        
        broadcast(new \App\Events\TripTabUpdated($this->trip->id, 'finances'))->toOthers();
    }

    public function editExpense($id)
    {
        $expense = $this->trip->expenses()->find($id);
        if ($expense && ($expense->user_id === Auth::id() || Auth::id() === $this->trip->user_id)) {
            $this->editingId = $expense->id;
            $this->description = $expense->description;
            $this->amount = $expense->amount;
            $this->date = $expense->date;
            $this->is_personal = $expense->is_personal;
        }
    }

    public function cancelEdit()
    {
        $this->reset(['editingId', 'description', 'amount', 'is_personal']);
        $this->date = date('Y-m-d');
    }

    public function deleteExpense($id)
    {
        $expense = $this->trip->expenses()->find($id);
        // Only allow the person who created it (or the trip creator) to delete it
        if ($expense && ($expense->user_id === Auth::id() || Auth::id() === $this->trip->user_id)) {
            $expense->delete();
            broadcast(new \App\Events\TripTabUpdated($this->trip->id, 'finances'))->toOthers();
        }
    }

    public function render()
    {
        $expenses = $this->trip->expenses()
            ->with('payer')
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->get();
            
        $memberCount = max(1, $this->trip->users()->count());
        
        // Split math logic
        $sharedTotal = $expenses->where('is_personal', false)->sum('amount');
        $perPersonShare = $sharedTotal / $memberCount;
        
        $myPersonalTotal = $expenses->where('is_personal', true)->where('user_id', Auth::id())->sum('amount');
        $myTotalResponsibility = $perPersonShare + $myPersonalTotal;

        return view('livewire.trip-expenses', [
            'expenses' => $expenses,
            'sharedTotal' => $sharedTotal,
            'perPersonShare' => $perPersonShare,
            'myPersonalTotal' => $myPersonalTotal,
            'myTotalResponsibility' => $myTotalResponsibility,
            'memberCount' => $memberCount
        ]);
    }
}