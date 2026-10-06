<div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-extrabold text-gray-900">Notifications</h1>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <button wire:click="markAllAsRead" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                Mark all as read
            </button>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <ul class="divide-y divide-gray-200">
            @forelse ($notifications as $notification)
                <li class="p-4 {{ $notification->read_at ? 'bg-white' : 'bg-indigo-50' }} flex justify-between items-center transition">
                    <div>
                        <p class="text-sm font-semibold {{ $notification->read_at ? 'text-gray-700' : 'text-indigo-900' }}">
                            {{ $notification->data['message'] }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $notification->data['trip_title'] }} • {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <a href="{{ route('trips.expenses', $notification->data['trip_id']) }}" class="text-sm font-medium text-indigo-600 hover:underline">
                            View
                        </a>
                        @if(is_null($notification->read_at))
                            <button wire:click="markAsRead('{{ $notification->id }}')" class="w-2.5 h-2.5 bg-indigo-600 rounded-full hover:bg-indigo-800 transition" title="Mark as read"></button>
                        @endif
                    </div>
                </li>
            @empty
                <li class="p-8 text-center text-gray-500">
                    You have no notifications.
                </li>
            @endforelse
        </ul>
    </div>
</div>