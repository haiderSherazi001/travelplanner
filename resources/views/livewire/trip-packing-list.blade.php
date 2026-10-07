<div class="flex flex-col gap-4">
    <!-- Top Row: Add Item & MY Progress -->
    <div class="flex flex-col md:flex-row gap-4 items-end">
        <form wire:submit="addItem" class="flex-1 flex gap-2 w-full">
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Add Packing Item</label>
                <input type="text" wire:model="newItem" placeholder="e.g., Passports, Sunscreen..." class="block w-full text-sm rounded-md border-gray-300 shadow-sm py-1.5 px-3 focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>
            <button type="submit" class="bg-indigo-600 text-white py-1.5 px-4 mt-5 rounded-md hover:bg-indigo-700 transition text-sm font-bold shadow-sm h-[34px] self-end">
                Add Item
            </button>
        </form>

        <!-- Progress Bar (Now shows YOUR progress) -->
        <div class="w-full md:w-1/3 bg-gray-50 p-2.5 rounded-md border border-gray-200 h-[56px] flex flex-col justify-center self-end relative overflow-hidden">
            <div class="flex justify-between items-center mb-1.5 relative z-10">
                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">My Progress</span>
                <span class="text-xs font-bold {{ $progress == 100 ? 'text-green-600' : 'text-indigo-600' }}">{{ $progress }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-1.5 relative z-10">
                <div class="h-1.5 rounded-full transition-all duration-500 {{ $progress == 100 ? 'bg-green-500' : 'bg-indigo-600' }}" style="width: {{ $progress }}%"></div>
            </div>
        </div>
    </div>

    <!-- Individual Tracking Checklist -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mt-1">
        <ul class="divide-y divide-gray-100">
            @forelse ($items as $item)
                @php 
                    $isPackedByMe = $item->packedBy->contains(auth()->id()); 
                @endphp
                <li class="p-3 hover:bg-gray-50 transition flex justify-between items-center group">
                    <!-- My Personal Checkbox -->
                    <label class="flex items-center gap-3 cursor-pointer flex-1">
                        <input type="checkbox" wire:click="togglePacked({{ $item->id }})" 
                               class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer" 
                               {{ $isPackedByMe ? 'checked' : '' }}>
                        <span class="text-sm {{ $isPackedByMe ? 'line-through text-gray-400' : 'text-gray-900 font-medium' }}">
                            {{ $item->item }}
                        </span>
                    </label>

                    <!-- Group Status Avatars -->
                    <div class="flex items-center gap-3">
                        <div class="flex -space-x-1.5">
                            @foreach($tripMembers as $member)
                                @php 
                                    $memberPacked = $item->packedBy->contains($member->id);
                                @endphp
                                <img class="inline-block h-6 w-6 rounded-full ring-2 ring-white transition-all duration-300 {{ $memberPacked ? 'opacity-100 shadow-sm' : 'opacity-30 grayscale' }}" 
                                     src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&color={{ $memberPacked ? '4f46e5' : '9ca3af' }}&background={{ $memberPacked ? 'eef2ff' : 'f3f4f6' }}&font-size=0.4" 
                                     alt="{{ $member->name }}" 
                                     title="{{ $member->name }} {{ $memberPacked ? 'has packed this' : 'has NOT packed this' }}" />
                            @endforeach
                        </div>

                        <!-- Delete Button -->
                        <button wire:click="deleteItem({{ $item->id }})" class="text-gray-300 hover:text-red-500 transition opacity-0 group-hover:opacity-100 p-1 rounded hover:bg-red-50">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </li>
            @empty
                <li class="p-6 text-center text-gray-500 text-sm">
                    Your packing list is empty. Start adding items above!
                </li>
            @endforelse
        </ul>
    </div>
</div>