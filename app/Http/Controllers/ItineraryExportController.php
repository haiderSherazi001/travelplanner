<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ItineraryExportController extends Controller
{
    public function download(Trip $trip)
    {
        // Security check
        if (!Auth::user()->trips->contains($trip->id)) {
            abort(403, 'Unauthorized');
        }

        // Group activities by date
        $groupedActivities = $trip->activities()
            ->orderBy('scheduled_at')
            ->get()
            ->groupBy(function($activity) {
                return Carbon::parse($activity->scheduled_at)->format('Y-m-d');
            });
            
        // Fetch the packing list
        $packingList = $trip->packingListItems()->get();

        // Generate the PDF
        $pdf = Pdf::loadView('pdf.itinerary', [
            'trip' => $trip,
            'groupedActivities' => $groupedActivities,
            'packingList' => $packingList
        ]);

        return $pdf->download(str_replace(' ', '_', $trip->title) . '_Itinerary.pdf');
    }
}