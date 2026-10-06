<div class="max-w-[1400px] mx-auto py-6 sm:px-6 lg:px-8 flex flex-col h-[calc(100vh-65px)]">
    
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
        <div>
             <a href="{{ route('trips.export', $trip->id) }}" target="_blank" class="bg-gray-900 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-gray-800 transition font-medium flex items-center gap-2">
                <span>📄</span> Export Report
            </a>
        </div>
    </div>

    <!-- Workspace Grid -->
    <div class="flex-1 flex flex-col lg:flex-row gap-6 overflow-hidden px-4 sm:px-0">
        
        <!-- LEFT CANVAS: Core Action Tabs (70% width) -->
        <div class="w-full lg:w-2/3 flex flex-col bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
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
                    <button wire:click="$set('activeTab', '{{ $key }}')" 
                            class="whitespace-nowrap px-6 py-4 font-bold text-sm transition-colors border-b-2 {{ $activeTab === $key ? 'border-indigo-600 text-indigo-700 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <!-- Active Tab Content -->
            <div class="flex-1 overflow-y-auto p-6 bg-white">
                @if($activeTab === 'itinerary')
                    <livewire:trip-itinerary :trip="$trip" key="tab-itinerary" />
                @elseif($activeTab === 'finances')
                    <livewire:trip-expenses :trip="$trip" key="tab-finances" />
                @elseif($activeTab === 'packing')
                    <livewire:trip-packing-list :trip="$trip" key="tab-packing" />
                @endif
            </div>
        </div>

        <!-- RIGHT SIDEBAR: Social & Chat (30% width) -->
        <div class="w-full lg:w-1/3 lg:flex flex-col gap-4 hidden h-full">
            
            <!-- Members Widget -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 shrink-0">
                <livewire:trip-members :trip="$trip" />
            </div>

            <!-- Chat Widget -->
            <div class="flex-1 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden flex flex-col h-full">
                <!-- We pass a smaller profile to chat so it styles itself for a sidebar -->
                <livewire:trip-chat :trip="$trip" />
            </div>
        </div>
    </div>
</div>