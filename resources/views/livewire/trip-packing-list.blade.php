<div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
    <!-- Header & Navigation -->
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">{{ $trip->title }} Packing List</h1>
            <p class="text-gray-600 mt-2 text-sm">Collaborate on what to bring. Everyone sees the same list.</p>
        </div>
        <a href="{{ route('trips.show', $trip->id) }}" class="text-indigo-600 hover:text-indigo-800 font-medium bg-indigo-50 px-4 py-2 rounded-lg transition">
            ← Back to Itinerary
        </a>
    </div>

    <!-- Progress Bar -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-8">
        <div class="flex justify-between items-center mb-2">
            <span class="text-sm font-semibold text-gray-700">Packing Progress</span>
            <span class="text-sm font-bold {{ $progress == 100 ? 'text-green-600' : 'text-indigo-600' }}">{{ $progress }}%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5">
            <div class="h-2.5 rounded-full transition-all duration-500 {{ $progress == 100 ? 'bg-green-500' : 'bg-indigo-600' }}" style="width: {{ $progress }}%"></div>
        </div>
        <p class="text-xs text-gray-500 mt-2">{{ $packedItems }} of {{ $totalItems }} items packed</p>
    </div>

    <!-- Add Item Form -->
    <form wire:submit="addItem" class="mb-8 flex gap-4">
        <input type="text" wire:model="newItem" placeholder="e.g., Passports, Sunscreen, Phone Chargers..." class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-lg py-3 px-4" required>
        <button type="submit" class="bg-indigo-600 text-white py-3 px-6 rounded-lg hover:bg-indigo-700 transition font-bold shadow-sm">
            Add Item
        </button>
    </form>

    <!-- Checklist -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <ul class="divide-y divide-gray-200">
            @forelse ($items as $item)
                <li class="p-4 hover:bg-gray-50 transition flex justify-between items-center group">
                    <label class="flex items-center gap-4 cursor-pointer flex-1">
                        <input type="checkbox" wire:click="togglePacked({{ $item->id }})" 
                               class="w-6 h-6 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer" 
                               {{ $item->is_packed ? 'checked' : '' }}>
                        <span class="text-lg {{ $item->is_packed ? 'line-through text-gray-400' : 'text-gray-900 font-medium' }}">
                            {{ $item->item }}
                        </span>
                    </label>
                    <button wire:click="deleteItem({{ $item->id }})" class="text-gray-300 hover:text-red-500 transition opacity-0 group-hover:opacity-100 p-2 focus:opacity-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </li>
            @empty
                <li class="p-10 text-center text-gray-500">
                    <p class="text-lg mb-1">Your packing list is empty.</p>
                    <p class="text-sm">Start adding items above so the group doesn't forget anything!</p>
                </li>
            @endforelse
        </ul>
    </div>
</div>