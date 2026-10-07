<div>
    <div class="flex justify-between items-center mb-2">
        <h3 class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Group Members ({{ $members->count() }})</h3>
        <span class="w-2 h-2 bg-green-500 rounded-full" title="Live Collaboration Active"></span>
    </div>
    <div class="flex items-center">
        <div class="flex -space-x-2 overflow-hidden">
            @foreach($members as $member)
                <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white shadow-sm" 
                     src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&color=4f46e5&background=eef2ff&font-size=0.4" 
                     alt="{{ $member->name }}" title="{{ $member->name }}" />
            @endforeach
        </div>
        
        <div class="ml-3 text-[11px] text-gray-500 font-medium">
            + planning together
        </div>
    </div>
</div>