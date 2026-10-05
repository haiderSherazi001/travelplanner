<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <!-- Header & Navigation -->
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">{{ $trip->title }} Finances</h1>
            <p class="text-gray-600 mt-2 text-sm">Keep track of who paid for what during the trip.</p>
        </div>
        <a href="{{ route('trips.show', $trip->id) }}" class="text-indigo-600 hover:text-indigo-800 font-medium bg-indigo-50 px-4 py-2 rounded-lg transition">
            ← Back to Itinerary
        </a>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 text-green-700 bg-green-100 p-3 rounded-lg border border-green-200">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Add Expense Form -->
        <div class="lg:col-span-1 bg-white p-6 rounded-xl shadow-sm border border-gray-200 self-start">
            <h3 class="text-lg font-bold mb-4 text-gray-900">Log an Expense</h3>
            <form wire:submit="addExpense">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">What was it for?</label>
                    <input type="text" wire:model="description" placeholder="e.g., Hotel booking" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Amount ($)</label>
                    <input type="number" step="0.01" wire:model="amount" placeholder="0.00" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Date Paid</label>
                    <input type="date" wire:model="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>

                <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition font-medium">Add Expense</button>
            </form>
        </div>

        <!-- Right Column: Expense List & Summary -->
        <div class="lg:col-span-2">
            <!-- Summary Card -->
            <div class="bg-gray-900 text-white p-6 rounded-xl shadow-sm mb-8 flex justify-between items-center">
                <div>
                    <h3 class="text-gray-400 text-sm font-semibold uppercase tracking-wider">Total Trip Cost</h3>
                    <p class="text-4xl font-extrabold mt-1">${{ number_format($totalCost, 2) }}</p>
                </div>
                <div class="bg-gray-800 p-3 rounded-lg text-sm text-gray-300 text-right">
                    <span class="block text-gray-400 text-xs uppercase tracking-wider mb-1">Per Person ({{ $memberCount }} Members)</span>
                    <span class="text-xl font-bold text-white">${{ number_format($perPersonShare, 2) }}</span>
                </div>
            </div>

            <h3 class="text-xl font-bold mb-4 text-gray-900">Expense History</h3>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <ul class="divide-y divide-gray-200">
                    @forelse ($expenses as $expense)
                        <li class="p-4 hover:bg-gray-50 transition flex justify-between items-center">
                            <div>
                                <p class="font-bold text-gray-900">{{ $expense->description }}</p>
                                <p class="text-sm text-gray-500 mt-1">
                                    Paid by <span class="font-semibold text-gray-700">{{ $expense->payer->name }}</span> on {{ \Carbon\Carbon::parse($expense->date)->format('M d, Y') }}
                                </p>
                            </div>
                            <div class="text-lg font-bold text-gray-900">
                                ${{ number_format($expense->amount, 2) }}
                            </div>
                        </li>
                    @empty
                        <li class="p-8 text-center text-gray-500">
                            No expenses logged yet.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>