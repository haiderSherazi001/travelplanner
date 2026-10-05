<div>
    @if (session()->has('message'))
        <div class="mb-4 text-green-700 bg-green-100 p-3 rounded">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Create Trip Form -->
        <div class="md:col-span-1 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h3 class="text-lg font-bold mb-4">Plan a New Trip</h3>
            <form wire:submit="createTrip">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Trip Title</label>
                    <input type="text" wire:model="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Destination</label>
                    <input type="text" wire:model="destination" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" wire:model="start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" wire:model="end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    @error('end_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition">Create Trip</button>
            </form>
        </div>

        <!-- Trip List -->
        <!-- Trip List -->
        <div class="md:col-span-2">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-extrabold text-gray-900 tracking-tight">Your Upcoming Trips</h3>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @forelse ($trips as $trip)
                    <a href="{{ route('trips.show', $trip->id) }}" class="group block bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden transform hover:-translate-y-1">
                        <!-- Decorative Top Border -->
                        <div class="h-2 bg-gradient-to-r from-blue-500 to-indigo-600 w-full"></div>
                        
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="font-bold text-xl text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $trip->title }}</h4>
                                    <p class="text-sm font-medium text-gray-500 flex items-center mt-1">
                                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        {{ $trip->destination }}
                                    </p>
                                </div>
                                <span class="bg-indigo-50 text-indigo-700 text-xs font-semibold px-2.5 py-1 rounded-md border border-indigo-100">
                                    {{ ucfirst($trip->pivot->role) }}
                                </span>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-3 mt-4 flex items-center justify-between border border-gray-100">
                                <div class="text-xs text-gray-500">
                                    <span class="block font-semibold text-gray-700 mb-1">Dates</span>
                                    {{ \Carbon\Carbon::parse($trip->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($trip->end_date)->format('M d, Y') }}
                                </div>
                                <div class="text-right">
                                    <span class="text-indigo-600 text-sm font-medium group-hover:underline">Plan →</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-2 flex flex-col items-center justify-center py-12 px-4 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h3 class="text-lg font-medium text-gray-900">No trips planned yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Use the form on the left to start your next adventure.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>