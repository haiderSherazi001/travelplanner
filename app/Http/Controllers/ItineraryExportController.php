<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ItineraryExportController extends Controller
{
    public function download(Request $request, Trip $trip)
    {
        if (!Auth::user()->trips->contains($trip->id)) {
            abort(403, 'Unauthorized');
        }

        // Get requested sections (default to all if none provided)
        $sections = $request->input('sections', ['itinerary', 'finances', 'packing']);

        $data = [
            'trip' => $trip,
            'sections' => $sections,
            'exportUser' => Auth::user(),
        ];

        // 1. Load Itinerary
        if (in_array('itinerary', $sections)) {
            $data['groupedActivities'] = $trip->activities()
                ->orderBy('scheduled_at')
                ->get()
                ->groupBy(function($activity) {
                    return Carbon::parse($activity->scheduled_at)->format('Y-m-d');
                });
        }

        // 2. Load Finances
        if (in_array('finances', $sections)) {
            $expenses = $trip->expenses()->with('payer')->orderBy('date')->get();
            $data['expenses'] = $expenses;
            $data['sharedTotal'] = $expenses->where('is_personal', false)->sum('amount');
            $data['memberCount'] = max(1, $trip->users()->count());
        }

        // 3. Load Packing List (Using the new Pivot relationship)
        if (in_array('packing', $sections)) {
            $data['packingList'] = $trip->packingListItems()->with('packedBy')->get();
        }

        $pdf = Pdf::loadView('pdf.itinerary', $data);
        
        // Optional: Make it A4 size for printing
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download(str_replace(' ', '_', $trip->title) . '_Trip_Report.pdf');
    }
}