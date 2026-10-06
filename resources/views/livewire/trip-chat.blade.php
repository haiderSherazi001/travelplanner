<div class="flex flex-col h-full w-full bg-gray-50/50">
    <!-- Compact Sidebar Header -->
    <div class="bg-white px-3 py-2.5 border-b border-gray-200 shrink-0 flex justify-between items-center">
        <h3 class="font-bold text-gray-800 text-sm flex items-center gap-1.5">
            <span class="text-base">💬</span> Group Chat
        </h3>
    </div>

    <!-- Messages Area -->
    <div wire:poll.2s class="flex-1 p-3 overflow-y-auto flex flex-col gap-2.5">
        @forelse ($messages as $message)
            @php $isMe = $message->user_id === auth()->id(); @endphp
            <div class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }}">
                <span class="text-[9px] font-bold text-gray-400 mb-0.5 px-1 uppercase tracking-wide">{{ $isMe ? 'You' : $message->user->name }}</span>
                <div class="max-w-[85%] px-2.5 py-1.5 text-xs rounded-xl shadow-sm {{ $isMe ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-white text-gray-800 border border-gray-100 rounded-bl-none' }}">
                    {{ $message->content }}
                </div>
                <span class="text-[9px] text-gray-400 mt-0.5 px-1">{{ $message->created_at->format('g:i A') }}</span>
            </div>
        @empty
            <div class="text-center py-8 text-gray-400 my-auto">
                <p class="text-xs font-semibold">No messages yet</p>
                <p class="text-[10px] mt-0.5">Say hello to the group!</p>
            </div>
        @endforelse
    </div>

    <!-- Compact Message Input -->
    <div class="p-2 bg-white border-t border-gray-200 shrink-0">
        <form wire:submit="sendMessage" class="flex gap-1.5">
            <input type="text" wire:model="content" placeholder="Type a message..." class="flex-1 text-xs rounded-full border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-1.5" required autocomplete="off">
            <button type="submit" class="bg-indigo-600 text-white p-1.5 rounded-full hover:bg-indigo-700 transition shadow-sm focus:outline-none flex-shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
            </button>
        </form>
    </div>
</div>