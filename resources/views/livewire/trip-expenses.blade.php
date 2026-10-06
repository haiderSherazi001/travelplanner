<div class="flex flex-col lg:flex-row gap-6">
    <!-- Left Column: Add Expense Form -->
    <div class="w-full lg:w-1/3 bg-gray-50 p-4 rounded-xl border border-gray-200 self-start">
        <h3 class="text-sm font-bold mb-3 text-gray-900 uppercase tracking-wide">Log an Expense</h3>
        <form wire:submit="addExpense">
            <div class="mb-3">
                <label class="block text-xs font-semibold text-gray-600 mb-1">What was it for?</label>
                <input type="text" wire:model="description" placeholder="e.g., Hotel booking" class="block w-full text-sm rounded-md border-gray-300 shadow-sm py-1.5 focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>
            
           <!-- Replaced grid with full-width stacked inputs -->
            <div class="mb-3">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Amount ($)</label>
                <input type="number" step="0.01" wire:model="amount" placeholder="0.00" class="block w-full text-sm rounded-md border-gray-300 shadow-sm py-1.5 focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Date Paid</label>
                <input type="date" wire:model="date" class="block w-full text-sm rounded-md border-gray-300 shadow-sm py-1.5 focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>

            <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition text-sm font-bold shadow-sm">Log Expense</button>
        </form>
    </div>

    <!-- Right Column: Expense List & Summary -->
    <div class="w-full lg:w-2/3 flex flex-col gap-4">
        <!-- Compact Summary Card -->
        <div class="bg-gray-900 text-white p-4 rounded-xl shadow-sm flex justify-between items-center">
            <div>
                <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-wider">Total Trip Cost</h3>
                <p class="text-lg font-extrabold mt-0.5">${{ number_format($totalCost, 2) }}</p>
            </div>
            <div class="bg-gray-800 px-3 py-1.5 rounded-lg text-right border border-gray-700">
                <span class="block text-gray-400 text-[10px] uppercase tracking-wider mb-0.5">Per Person ({{ $memberCount }})</span>
                <span class="text-sm font-bold text-white">${{ number_format($perPersonShare, 2) }}</span>
            </div>
        </div>

        <!-- Compact Expense List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <ul class="divide-y divide-gray-100">
                @forelse ($expenses as $expense)
                    <li class="p-3 hover:bg-gray-50 flex justify-between items-center transition">
                        <div>
                            <p class="font-bold text-sm text-gray-900">{{ $expense->description }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                Paid by <span class="font-semibold text-gray-700">{{ $expense->payer->name }}</span> on {{ \Carbon\Carbon::parse($expense->date)->format('M d') }}
                            </p>
                        </div>
                        <div class="text-sm font-bold text-gray-900 bg-gray-100 px-2 py-1 rounded border border-gray-200">
                            ${{ number_format($expense->amount, 2) }}
                        </div>
                    </li>
                @empty
                    <li class="p-6 text-center text-gray-500 text-sm">No expenses logged yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>