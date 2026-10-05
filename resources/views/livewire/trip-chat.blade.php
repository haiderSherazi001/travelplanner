<div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8 flex flex-col h-[85vh]">
    <!-- Header & Navigation -->
    <div class="mb-4 flex justify-between items-end shrink-0">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">{{ $trip->title }} Chat</h1>
            <p class="text-gray-600 mt-2 text-sm">Discuss plans with your travel group.</p>
        </div>
        <a href="{{ route('trips.show', $trip->id) }}" class="text-indigo-600 hover:text-indigo-800 font-medium bg-indigo-50 px-4 py-2 rounded-lg transition">
            ← Back to Itinerary
        </a>
    </div>

    <!-- Chat Box -->
    <div class="flex-1 bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col overflow-hidden">
        
        <!-- Messages Area (Auto-refreshes every 2 seconds) -->
        <div wire:poll.2s class="flex-1 p-6 overflow-y-auto bg-gray-50 flex flex-col gap-4">
            @forelse ($messages as $message)
                @php $isMe = $message->user_id === auth()->id(); @endphp
                <div class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }}">
                    <span class="text-xs text-gray-500 mb-1 px-1">{{ $isMe ? 'You' : $message->user->name }}</span>
                    <div class="max-w-[75%] px-4 py-2 rounded-2xl {{ $isMe ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-white text-gray-900 border border-gray-200 rounded-bl-none shadow-sm' }}">
                        {{ $message->content }}
                    </div>
                    <span class="text-[10px] text-gray-400 mt-1 px-1">{{ $message->created_at->format('g:i A') }}</span>
                </div>
            @empty
                <div class="text-center py-10 text-gray-500 my-auto">
                    <p class="text-lg">No messages yet.</p>
                    <p class="text-sm">Say hello to get the planning started!</p>
                </div>
            @endforelse
        </div>

        <!-- Message Input -->
        <div class="p-4 bg-white border-t border-gray-200 shrink-0">
            <form wire:submit="sendMessage" class="flex gap-4">
                <input type="text" wire:model="content" placeholder="Type your message..." class="flex-1 rounded-full border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-6" required autocomplete="off">
                <button type="submit" class="bg-indigo-600 text-white p-3 rounded-full hover:bg-indigo-700 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
            </form>
        </div>
    </div>
</div>