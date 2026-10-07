<div class="max-w-[1400px] mx-auto py-6 sm:px-6 lg:px-8 flex flex-col h-[calc(100vh-65px)] min-h-0">
    
    <!-- Unified Trip Header (Sticky at top) -->
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 shrink-0 px-4 sm:px-0">
        <div>
            <div class="flex items-center gap-4 mb-1">
                <h1 class="text-3xl font-extrabold text-gray-900">{{ $trip->title }}</h1>
                <div x-data="{ copied: false }" class="relative">
                    <button @click="navigator.clipboard.writeText('{{ route('trips.join', $trip->invite_code) }}'); copied = true; setTimeout(() => copied = false, 2000);" class="flex items-center gap-1 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 px-3 py-1.5 rounded-md text-sm font-bold transition border border-indigo-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                        Invite Friends
                    </button>
                    <div x-show="copied" x-transition class="absolute top-full mt-2 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded shadow-lg whitespace-nowrap z-50">Copied!</div>
                </div>
            </div>
            <p class="text-gray-600 text-sm md:text-base font-medium">
                📍 {{ $trip->destination }} &nbsp;|&nbsp; 📅 {{ \Carbon\Carbon::parse($trip->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($trip->end_date)->format('M d, Y') }}
            </p>
        </div>
        <!-- Export Button with Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center gap-2 bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg font-bold text-sm hover:bg-gray-50 shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export Report
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" @click.outside="open = false" x-cloak 
                 class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-lg border border-gray-200 z-50 p-4"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100">
                 
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Include Sections</h4>
                
                <form action="{{ route('trips.export', $trip->id) }}" method="GET">
                    <div class="space-y-2 mb-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="sections[]" value="itinerary" checked class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700">Official Itinerary</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="sections[]" value="finances" checked class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700">Financial Summary</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="sections[]" value="packing" checked class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700">Group Packing List</span>
                        </label>
                    </div>
                    
                    <button type="submit" @click="open = false" class="w-full bg-indigo-600 text-white py-2 rounded-lg text-sm font-bold hover:bg-indigo-700 transition">
                        Download PDF
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Workspace Grid -->
    <div class="flex-1 min-h-0 flex flex-col lg:flex-row gap-6 overflow-hidden px-4 sm:px-0">
        
        <!-- LEFT CANVAS: Core Action Tabs (70% width) -->
        <div class="w-full lg:w-2/3 min-h-0 flex flex-col bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Tabs -->
            <div class="bg-gray-50 border-b border-gray-200 flex overflow-x-auto hide-scrollbar px-4 shrink-0">
                @php
                    $tabs = [
                        'itinerary' => '🗓️ Itinerary',
                        'finances' => '💰 Finances',
                        'packing' => '🎒 Packing List'
                    ];
                @endphp
                @foreach($tabs as $key => $label)
                    <button wire:click="switchTab('{{ $key }}')" 
                            class="relative whitespace-nowrap px-6 py-4 font-bold text-sm transition-colors border-b-2 {{ $activeTab === $key ? 'border-indigo-600 text-indigo-700 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100' }}">
                        {{ $label }}
                        
                        <!-- Notification Dot -->
                        @if(in_array($key, $unreadTabs))
                            <span class="absolute top-3 right-3 flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                            </span>
                        @endif
                    </button>
                @endforeach
            </div>

            <!-- Active Tab Content -->
            <div class="flex-1 min-h-0 overflow-y-auto p-6 bg-white">
                @if($activeTab === 'itinerary')
                    <livewire:trip-itinerary :trip="$trip" key="tab-itinerary" />
                @elseif($activeTab === 'finances')
                    <livewire:trip-expenses :trip="$trip" key="tab-finances" />
                @elseif($activeTab === 'packing')
                    <livewire:trip-packing-list :trip="$trip" key="tab-packing" />
                @endif
            </div>
        </div>

        <!-- RIGHT CANVAS: Collaboration Sidebar (30%) -->
        <div class="w-full lg:w-[30%] bg-white rounded-2xl shadow-sm border border-gray-200 flex flex-col h-full overflow-hidden">            
            <!-- Collapsible Group Members Section -->
            <div x-data="{ showMembers: false }" class="border-b border-gray-200 shrink-0">
                <!-- Toggle Button -->
                <button @click="showMembers = !showMembers" class="w-full flex justify-between items-center px-4 py-3 bg-gray-50 hover:bg-gray-100 transition focus:outline-none">
                    <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Trip Members ({{ $trip->users->count() }})
                    </h3>
                    
                    <!-- Chevron Arrow -->
                    <svg :class="showMembers ? 'rotate-180' : ''" class="w-4 h-4 text-gray-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                
                <!-- Expanded Members List -->
                <div x-show="showMembers" 
                     x-collapse 
                     x-cloak 
                     class="bg-white border-t border-gray-100 max-h-48 overflow-y-auto shrink-0">
                    <ul class="flex flex-col py-2">
                        @foreach($trip->users as $member)
                            <li class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50 transition">
                                <img class="h-8 w-8 rounded-full ring-2 ring-white shadow-sm object-cover" 
                                    src="{{ $member->avatar_url }}" 
                                    alt="{{ $member->name }}" />
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-900 leading-tight">
                                        {{ $member->name }}
                                    </span>
                                    @if($member->id === $trip->user_id)
                                        <span class="text-[10px] uppercase tracking-wide text-indigo-500 font-bold">Trip Admin</span>
                                    @elseif($member->id === auth()->id())
                                        <span class="text-[10px] uppercase tracking-wide text-gray-500 font-bold">You</span>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Chat Component Area -->
            <!-- Keep the chat region constrained so the message list can scroll and the input stays visible. -->
            <div class="flex-1 min-h-0 overflow-hidden flex flex-col bg-white">
                <div class="flex-1 min-h-0 overflow-hidden flex flex-col">
                    <livewire:trip-chat :trip="$trip" />
                </div>
            </div>
        </div>
    </div>
</div>