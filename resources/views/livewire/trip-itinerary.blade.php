<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <!-- Trip Header -->
    <div class="mb-8 flex justify-between items-start">
       <div>
            <div class="flex items-center gap-4">
                <h1 class="text-3xl font-extrabold text-gray-900">{{ $trip->title }}</h1>
                
                <!-- Share Button (Copies Link to Clipboard) -->
                <div x-data="{ copied: false }" class="relative">
                    <button @click="
                                navigator.clipboard.writeText('{{ route('trips.join', $trip->invite_code) }}');
                                copied = true;
                                setTimeout(() => copied = false, 2000);
                            " 
                            class="flex items-center gap-1 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 px-3 py-1.5 rounded-md text-sm font-bold transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                        Share Invite Link
                    </button>
                    <!-- Tooltip -->
                    <div x-show="copied" x-transition class="absolute top-full mt-2 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded shadow-lg whitespace-nowrap">
                        Copied to clipboard!
                    </div>
                </div>
            </div>

            <p class="text-gray-600 mt-2 text-lg">
                📍 {{ $trip->destination }} | 
                📅 {{ \Carbon\Carbon::parse($trip->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($trip->end_date)->format('M d, Y') }}
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('trips.chat', $trip->id) }}" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow-sm hover:bg-gray-50 transition font-medium flex items-center gap-2">
                <span>💬</span> Chat
            </a>
            <a href="{{ route('trips.packing-list', $trip->id) }}" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow-sm hover:bg-gray-50 transition font-medium flex items-center gap-2">
                <span>🎒</span> Packing List
            </a>
            <a href="{{ route('trips.expenses', $trip->id) }}" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow-sm hover:bg-gray-50 transition font-medium flex items-center gap-2">
                <span>💰</span> Finances
            </a>
            <a href="{{ route('trips.export', $trip->id) }}" class="bg-indigo-600 text-white border border-transparent px-4 py-2 rounded-lg shadow-sm hover:bg-indigo-700 transition font-medium flex items-center gap-2">
                <span>📄</span> Export PDF
            </a>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 text-green-700 bg-green-100 p-3 rounded-lg border border-green-200">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Add Activity Form -->
        <div class="lg:col-span-1 bg-white p-6 rounded-xl shadow-sm border border-gray-200 self-start">
            <h3 class="text-lg font-bold mb-4 text-gray-900">Add an Activity</h3>
            <form wire:submit="addActivity">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Activity Title</label>
                    <input type="text" wire:model="title" placeholder="e.g., Dinner at Luigi's" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Type</label>
                    <select wire:model="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="general">General Activity</option>
                        <option value="flight">Flight/Travel</option>
                        <option value="hotel">Accommodation</option>
                        <option value="dining">Dining/Restaurant</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Date & Time</label>
                    <input type="datetime-local" wire:model="scheduled_at" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Location (Optional)</label>
                    <input type="text" wire:model="location" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div class="mb-4 grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Booking URL</label>
                        <input type="url" wire:model="booking_url" placeholder="https://..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @error('booking_url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Reservation #</label>
                        <input type="text" wire:model="reservation_code" placeholder="e.g., XYZ123" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                    <textarea wire:model="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition font-medium">Add to Itinerary</button>
            </form>
        </div>

        <!-- Right Column: Day-by-Day Schedule -->
        <div class="lg:col-span-2">
            <h3 class="text-xl font-bold mb-6 text-gray-900">Itinerary Schedule</h3>
            
            @forelse ($groupedActivities as $date => $activities)
                <div class="mb-8">
                    <div class="sticky top-0 bg-gray-50 py-2 border-b border-gray-200 mb-4 z-10">
                        <h4 class="text-lg font-extrabold text-indigo-700">
                            {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
                        </h4>
                    </div>
                    
                    <div class="space-y-4">
                        @foreach ($activities as $activity)
                            @php 
                                $userVote = $activity->votes->where('user_id', auth()->id())->first()->value ?? 0; 
                            @endphp
                            <div wire:key="activity-{{ $activity->id }}" class="bg-white p-4 rounded-lg shadow-sm border-l-4 @if($activity->type == 'flight') border-blue-500 @elseif($activity->type == 'hotel') border-purple-500 @elseif($activity->type == 'dining') border-orange-500 @else border-green-500 @endif flex justify-between items-center">
                                <div class="flex-1">
                                    <h5 class="font-bold text-gray-900">{{ $activity->title }}</h5>
                                    <div class="text-sm text-gray-500 flex items-center gap-4 mt-1">
                                        <span>⏰ {{ \Carbon\Carbon::parse($activity->scheduled_at)->format('g:i A') }}</span>
                                        @if($activity->location)
                                            <span>📍 {{ $activity->location }}</span>
                                        @endif
                                    </div>
                                    @if($activity->reservation_code || $activity->booking_url)
                                        <div class="mt-2 flex flex-wrap gap-2">
                                            @if($activity->reservation_code)
                                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded border border-gray-200">
                                                    <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                                    {{ $activity->reservation_code }}
                                                </span>
                                            @endif
                                            @if($activity->booking_url)
                                                <a href="{{ $activity->booking_url }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-1 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 hover:text-indigo-800 text-xs rounded border border-indigo-100 transition">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                    View Booking
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                    @if($activity->notes)
                                        <p class="text-sm text-gray-600 mt-2 bg-gray-50 p-2 rounded">{{ $activity->notes }}</p>
                                    @endif
                                </div>
                                
                                <!-- Voting Section -->
                                @php 
                                    $userVote = $activity->votes->where('user_id', auth()->id())->first()->value ?? 0; 
                                @endphp
                                <div class="flex items-center gap-3 ml-4 pl-4 border-l border-gray-100">
                                    <div class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600 uppercase tracking-wide hidden sm:block">
                                        {{ $activity->type }}
                                    </div>
                                    
                                    <div class="flex items-center gap-2">
                                        <!-- Upvote Button & Count -->
                                        <button wire:click="castVote({{ $activity->id }}, 1)" 
                                                wire:loading.attr="disabled"
                                                title="Vote Yes"
                                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg transition-colors focus:outline-none disabled:opacity-50 border
                                                    {{ $userVote === 1 ? 'bg-green-50 border-green-200 text-green-700' : 'bg-white border-gray-200 text-gray-500 hover:bg-gray-50' }}">
                                            
                                            <!-- Up Arrow Icon -->
                                            <svg class="w-4 h-4" fill="{{ $userVote === 1 ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                            
                                            <span class="font-bold text-sm">{{ $activity->upvotes }}</span>
                                        </button>

                                        <!-- Downvote Button & Count -->
                                        <button wire:click="castVote({{ $activity->id }}, -1)" 
                                                wire:loading.attr="disabled"
                                                title="Vote No"
                                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg transition-colors focus:outline-none disabled:opacity-50 border
                                                    {{ $userVote === -1 ? 'bg-red-50 border-red-200 text-red-600' : 'bg-white border-gray-200 text-gray-500 hover:bg-gray-50' }}">
                                            
                                            <!-- Down Arrow Icon -->
                                            <svg class="w-4 h-4" fill="{{ $userVote === -1 ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                            
                                            <span class="font-bold text-sm">{{ $activity->downvotes }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-xl border border-gray-200 shadow-sm">
                    <p class="text-gray-500 text-lg">Your itinerary is empty.</p>
                    <p class="text-gray-400 text-sm mt-1">Use the form on the left to add your first activity.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>