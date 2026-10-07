<div class="flex flex-col lg:flex-row gap-6">
    <!-- Left Column: Add/Edit Expense Form -->
    <div class="w-full lg:w-1/3 bg-gray-50 p-4 rounded-xl border border-gray-200 self-start">
        <h3 class="text-sm font-bold mb-3 {{ $editingId ? 'text-green-600' : 'text-gray-900' }} uppercase tracking-wide">
            {{ $editingId ? 'Edit Expense' : 'Log an Expense' }}
        </h3>
        <form wire:submit="saveExpense">
            <div class="mb-3">
                <label class="block text-xs font-semibold text-gray-600 mb-1">What was it for?</label>
                <input type="text" wire:model="description" placeholder="e.g., Hotel booking" class="block w-full text-sm rounded-md border-gray-300 shadow-sm py-1.5 focus:border-green-500 focus:ring-green-500" required>
            </div>
            
            <div class="mb-3">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Amount ($)</label>
                <input type="number" step="0.01" wire:model="amount" placeholder="0.00" class="block w-full text-sm rounded-md border-gray-300 shadow-sm py-1.5 focus:border-green-500 focus:ring-green-500" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Date Paid</label>
                <input type="date" wire:model="date" class="block w-full text-sm rounded-md border-gray-300 shadow-sm py-1.5 focus:border-green-500 focus:ring-green-500" required>
            </div>

            <div class="mb-4 flex items-center bg-white p-2.5 rounded-lg border border-gray-200 shadow-sm">
                <input type="checkbox" id="is_personal" wire:model="is_personal" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                <label for="is_personal" class="ml-2 block text-xs font-bold text-gray-700">
                    Personal Expense (Do not split)
                </label>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition text-sm font-bold shadow-sm">
                    {{ $editingId ? 'Update Expense' : 'Log Expense' }}
                </button>
                @if($editingId)
                    <button type="button" wire:click="cancelEdit" class="bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-50 transition text-sm font-bold shadow-sm">
                        Cancel
                    </button>
                @endif
            </div>
        </form>
    </div>

    <!-- Right Column: Expense List & Summary -->
    <div class="w-full lg:w-2/3 flex flex-col gap-4">
        <!-- Advanced Summary Card -->
        <div class="bg-gray-900 text-white p-4 rounded-xl shadow-sm grid grid-cols-2 gap-4">
            <div>
                <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-wider">Group Total</h3>
                <p class="text-xl font-extrabold mt-0.5">${{ number_format($sharedTotal, 2) }}</p>
                <p class="text-xs text-gray-400 mt-1">Split {{ $memberCount }} ways = ${{ number_format($perPersonShare, 2) }}</p>
            </div>
            <div class="bg-gray-800 p-3 rounded-lg border border-gray-700 flex flex-col justify-center">
                <span class="block text-green-400 text-[10px] uppercase tracking-wider font-bold mb-0.5">My Total Cost</span>
                <span class="text-xl font-bold text-white">${{ number_format($myTotalResponsibility, 2) }}</span>
                @if($myPersonalTotal > 0)
                    <span class="text-[10px] text-gray-400 mt-0.5">Includes ${{ number_format($myPersonalTotal, 2) }} personal</span>
                @endif
            </div>
        </div>

        <!-- Expense List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <ul class="divide-y divide-gray-100">
                @forelse ($expenses as $expense)
                    @php $isMine = $expense->user_id === auth()->id(); @endphp
                    <li class="p-3 hover:bg-gray-50 flex justify-between items-center transition group relative">
                        <!-- Edit/Delete Action Hover -->
                        @if($isMine || auth()->id() === $trip->user_id)
                            <div class="absolute top-1/2 -translate-y-1/2 right-2 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1 bg-white border border-gray-200 rounded-md shadow-sm p-1">
                                <button wire:click="editExpense({{ $expense->id }})" class="p-1 text-gray-400 hover:text-green-600 rounded transition" title="Edit">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <button wire:click="deleteExpense({{ $expense->id }})" wire:confirm="Delete this expense?" class="p-1 text-gray-400 hover:text-red-600 rounded transition" title="Delete">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        @endif

                        <div>
                            <p class="font-bold text-sm text-gray-900 flex items-center gap-2">
                                {{ $expense->description }}
                                @if($expense->is_personal)
                                    <span class="px-1.5 py-0.5 bg-purple-50 text-purple-700 text-[9px] uppercase tracking-wide rounded font-extrabold border border-purple-200">Personal</span>
                                @endif
                            </p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                Paid by <span class="font-semibold {{ $isMine ? 'text-green-600' : 'text-gray-700' }}">{{ $isMine ? 'You' : $expense->payer->name }}</span> on {{ \Carbon\Carbon::parse($expense->date)->format('M d') }}
                            </p>
                        </div>
                        <div class="text-sm font-bold text-gray-900 bg-gray-100 px-2 py-1 rounded border border-gray-200 mr-14 group-hover:mr-16 transition-all">
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