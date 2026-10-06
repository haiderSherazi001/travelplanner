<div class="flex flex-col lg:flex-row gap-6">
    <!-- Left Column: Compact Add Activity Form -->
    <div class="w-full lg:w-1/3 bg-gray-50 p-4 rounded-xl border border-gray-200 self-start">
        <h3 class="text-sm font-bold mb-3 text-gray-900 uppercase tracking-wide">Add New Activity</h3>
        <form wire:submit="addActivity">
            <div class="mb-3">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Title</label>
                <input type="text" wire:model="title" placeholder="e.g., Flight to Paris" class="block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1.5" required>
                @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            
            <!-- Replaced grid with full-width stacked inputs -->
            <div class="mb-3">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Type</label>
                <select wire:model="type" class="block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1.5">
                    <option value="flight">Flight</option>
                    <option value="hotel">Hotel</option>
                    <option value="dining">Dining</option>
                    <option value="activity">Activity</option>
                    <option value="general">General</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Date & Time</label>
                <input type="datetime-local" wire:model="scheduled_at" class="block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1.5" required>
            </div>

            <div class="mb-3">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Location</label>
                <input type="text" wire:model="location" placeholder="e.g., Charles de Gaulle Airport" class="block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1.5">
            </div>

            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Booking URL</label>
                    <input type="url" wire:model="booking_url" placeholder="https://..." class="block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1.5">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Res. #</label>
                    <input type="text" wire:model="reservation_code" placeholder="XYZ123" class="block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1.5">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Notes</label>
                <textarea wire:model="notes" rows="2" class="block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1.5"></textarea>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition text-sm font-bold shadow-sm">Add to Schedule</button>
        </form>
    </div>

    <!-- Right Column: Itinerary Timeline -->
    <div class="w-full lg:w-2/3">
        @forelse ($groupedActivities as $date => $activities)
            <div class="mb-6">
                <h3 class="text-sm font-extrabold text-indigo-700 bg-indigo-50 px-3 py-1.5 rounded-lg inline-block mb-3 border border-indigo-100">
                    {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
                </h3>
                <div class="space-y-3 pl-2 border-l-2 border-indigo-100 ml-2">
                    @foreach ($activities as $activity)
                        <div class="bg-white p-3 rounded-xl shadow-sm border border-gray-200 ml-4 relative hover:border-indigo-300 transition">
                            <div class="absolute w-3 h-3 bg-indigo-500 rounded-full -left-[23px] top-4 border-2 border-white"></div>
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-indigo-600 font-bold text-sm">{{ \Carbon\Carbon::parse($activity->scheduled_at)->format('g:i A') }}</span>
                                        <h4 class="text-base font-bold text-gray-900">{{ $activity->title }}</h4>
                                    </div>
                                    @if($activity->location)
                                        <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">📍 {{ $activity->location }}</p>
                                    @endif
                                    
                                    @if($activity->reservation_code || $activity->booking_url)
                                        <div class="mt-2 flex flex-wrap gap-2">
                                            @if($activity->reservation_code)
                                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-gray-100 text-gray-700 text-[10px] uppercase font-bold rounded border border-gray-200">
                                                    Res: {{ $activity->reservation_code }}
                                                </span>
                                            @endif
                                            @if($activity->booking_url)
                                                <a href="{{ $activity->booking_url }}" target="_blank" class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 text-[10px] uppercase font-bold rounded border border-indigo-100 transition">
                                                    Link ↗
                                                </a>
                                            @endif
                                        </div>
                                    @endif

                                    @if($activity->notes)
                                        <p class="text-xs text-gray-600 mt-2 bg-gray-50 p-2 rounded border border-gray-100">{{ $activity->notes }}</p>
                                    @endif
                                </div>

                                <!-- Compact Voting Section (Restored) -->
                                @php $userVote = $activity->votes->where('user_id', auth()->id())->first()->value ?? 0; @endphp
                                <div class="flex flex-col items-end gap-1.5 ml-3">
                                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $activity->type }}</div>
                                    <div class="flex items-center gap-1.5">
                                        <!-- Upvote -->
                                        <button wire:click="castVote({{ $activity->id }}, 1)" 
                                                class="flex items-center gap-1 px-1.5 py-1 rounded border text-xs font-bold transition-colors
                                                       {{ $userVote === 1 ? 'bg-green-50 border-green-200 text-green-700' : 'bg-white border-gray-200 text-gray-500 hover:bg-gray-50' }}">
                                            <svg class="w-3 h-3" fill="{{ $userVote === 1 ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                            <span>{{ $activity->upvotes }}</span>
                                        </button>
                                        <!-- Downvote -->
                                        <button wire:click="castVote({{ $activity->id }}, -1)" 
                                                class="flex items-center gap-1 px-1.5 py-1 rounded border text-xs font-bold transition-colors
                                                       {{ $userVote === -1 ? 'bg-red-50 border-red-200 text-red-600' : 'bg-white border-gray-200 text-gray-500 hover:bg-gray-50' }}">
                                            <svg class="w-3 h-3" fill="{{ $userVote === -1 ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            <span>{{ $activity->downvotes }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-center py-10 text-gray-500">
                <p class="text-sm font-medium">Your itinerary is empty.</p>
                <p class="text-xs mt-1">Use the form on the left to add your first activity.</p>
            </div>
        @endforelse
    </div>
</div>