<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $trip->title }} - Itinerary</title>
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.6; font-size: 14px; }
        .header { text-align: center; border-bottom: 2px solid #4f46e5; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #111; }
        .header p { margin: 5px 0 0; color: #666; }
        .section-header { background: #f3f4f6; padding: 10px; font-weight: bold; margin-top: 20px; border-left: 4px solid #4f46e5; }
        .activity { padding: 12px 10px; border-bottom: 1px solid #eee; }
        .time { font-weight: bold; width: 100px; display: inline-block; vertical-align: top; }
        .content { display: inline-block; width: calc(100% - 120px); }
        .title { font-weight: bold; font-size: 15px; color: #111; }
        .details { color: #555; font-size: 13px; margin-top: 4px; }
        .checklist { list-style-type: none; padding: 0; }
        .checklist li { padding: 8px 10px; border-bottom: 1px solid #eee; }
        .checkbox { font-family: monospace; font-weight: bold; margin-right: 8px; color: #4f46e5; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $trip->title }}</h1>
        <p>Destination: {{ $trip->destination }}</p>
        <p>Dates: {{ \Carbon\Carbon::parse($trip->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($trip->end_date)->format('M d, Y') }}</p>
    </div>

    <!-- Day-by-Day Schedule -->
    @forelse ($groupedActivities as $date => $activities)
        <div class="section-header">
            {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
        </div>
        
        @foreach ($activities as $activity)
            <div class="activity">
                <div class="time">{{ \Carbon\Carbon::parse($activity->scheduled_at)->format('g:i A') }}</div>
                <div class="content">
                    <div class="title">{{ $activity->title }} <span style="font-weight: normal; color: #666;">({{ ucfirst($activity->type) }})</span></div>
                    @if($activity->location)
                        <div class="details"><strong>Location:</strong> {{ $activity->location }}</div>
                    @endif
                    @if($activity->notes)
                        <div class="details"><strong>Notes:</strong> {{ $activity->notes }}</div>
                    @endif
                </div>
            </div>
        @endforeach
    @empty
        <p style="text-align: center; margin-top: 30px; color: #666;">No activities planned yet.</p>
    @endforelse

    <!-- Packing List -->
    @if($packingList->count() > 0)
        <div class="section-header" style="border-left-color: #10b981; margin-top: 40px;">
            Packing List
        </div>
        <ul class="checklist">
            @foreach($packingList as $item)
                <li>
                    <span class="checkbox">[{{ $item->is_packed ? 'X' : ' ' }}]</span> {{ $item->item }}
                </li>
            @endforeach
        </ul>
    @endif
</body>
</html>