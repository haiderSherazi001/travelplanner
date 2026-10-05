<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <!-- Trip Header -->
    <div class="mb-8 flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">{{ $trip->title }}</h1>
            <p class="text-gray-600 mt-2 text-lg">
                📍 {{ $trip->destination }} | 
                📅 {{ \Carbon\Carbon::parse($trip->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($trip->end_date)->format('M d, Y') }}
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('trips.packing-list', $trip->id) }}" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow-sm hover:bg-gray-50 transition font-medium flex items-center gap-2">
                <span>🎒</span> Packing List
            </a>
            <a href="{{ route('trips.expenses', $trip->id) }}" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow-sm hover:bg-gray-50 transition font-medium flex items-center gap-2">
                <span>💰</span> Finances
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
                                    @if($activity->notes)
                                        <p class="text-sm text-gray-600 mt-2 bg-gray-50 p-2 rounded">{{ $activity->notes }}</p>
                                    @endif
                                </div>
                                
                                <!-- Voting Section -->
                                @php 
                                    $userVote = $activity->votes->where('user_id', auth()->id())->first()->value ?? 0; 
                                @endphp
                                <div class="flex items-center gap-4 ml-4 pl-4 border-l border-gray-100">
                                    <div class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600 uppercase tracking-wide hidden sm:block">
                                        {{ $activity->type }}
                                    </div>
                                    
                                    <div class="flex flex-col items-center bg-white rounded-xl shadow-sm p-1 border border-gray-200">
                                        <!-- Upvote Button -->
                                        <button wire:click="castVote({{ $activity->id }}, 1)" 
                                                wire:loading.attr="disabled"
                                                class="p-1.5 rounded-lg transition-colors focus:outline-none disabled:opacity-50
                                                    {{ $userVote === 1 ? 'text-indigo-600 bg-indigo-50' : 'text-gray-400 hover:bg-gray-50 hover:text-indigo-600' }}">
                                            <svg class="w-5 h-5" fill="{{ $userVote === 1 ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                        </button>

                                        <!-- Score -->
                                        <span class="font-bold text-sm py-0.5 min-w-[1.5rem] text-center transition-colors 
                                                    {{ $userVote === 1 ? 'text-indigo-600' : ($userVote === -1 ? 'text-red-500' : 'text-gray-700') }}">
                                            {{ $activity->score }}
                                        </span>

                                        <!-- Downvote Button -->
                                        <button wire:click="castVote({{ $activity->id }}, -1)" 
                                                wire:loading.attr="disabled"
                                                class="p-1.5 rounded-lg transition-colors focus:outline-none disabled:opacity-50
                                                    {{ $userVote === -1 ? 'text-red-500 bg-red-50' : 'text-gray-400 hover:bg-gray-50 hover:text-red-500' }}">
                                            <svg class="w-5 h-5" fill="{{ $userVote === -1 ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
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