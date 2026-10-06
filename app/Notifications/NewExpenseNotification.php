<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Expense;

class NewExpenseNotification extends Notification
{
    use Queueable;

    public $expense;

    public function __construct(Expense $expense)
    {
        $this->expense = $expense;
    }

    public function via(object $notifiable): array
    {
        return ['database']; // We are saving this to the database for in-app display
    }

    public function toArray(object $notifiable): array
    {
        return [
            'trip_id' => $this->expense->trip_id,
            'trip_title' => $this->expense->trip->title,
            'amount' => $this->expense->amount,
            'message' => "{$this->expense->payer->name} logged a new expense of \${$this->expense->amount} for '{$this->expense->description}'.",
        ];
    }
}