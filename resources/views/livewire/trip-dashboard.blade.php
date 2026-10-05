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
        <div class="md:col-span-2">
            <h3 class="text-lg font-bold mb-4">Your Upcoming Trips</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse ($trips as $trip)
                    <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
                        <h4 class="font-bold text-lg text-gray-900">{{ $trip->title }}</h4>
                        <p class="text-sm text-gray-600 mb-2">{{ $trip->destination }}</p>
                        <p class="text-xs text-gray-500 font-medium">
                            {{ \Carbon\Carbon::parse($trip->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($trip->end_date)->format('M d, Y') }}
                        </p>
                        <div class="mt-4">
                            <span class="inline-block text-xs bg-indigo-100 text-indigo-800 rounded-full px-2 py-1">Role: {{ ucfirst($trip->pivot->role) }}</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 text-center py-10 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        <p class="text-gray-500">You haven't planned any trips yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>