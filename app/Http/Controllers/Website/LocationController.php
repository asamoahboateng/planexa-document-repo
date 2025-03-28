<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\General\Application;
use App\Models\General\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LocationController extends Controller
{
    //
    public function searchtest(Request $request)
    {
        $lat = '43.8175';
        $lng = '-79.31222';

        // Search locations within 10km radius using Haversine formula
        $locations = DB::table('locations')
            ->select(
                'id',
                'location',
                'lat',
                DB::raw('`long` as lng'), // Rename to lng for consistency
                DB::raw('(
                    6371 * acos(
                        cos(radians(?)) * cos(radians(lat)) *
                        cos(radians(`long`) - radians(?)) +
                        sin(radians(?)) * sin(radians(lat))
                    )
                ) AS distance')
            )
            ->having('distance', '<=', 10)
            ->orderBy('distance')
            ->setBindings([$lat, $lng, $lat])
            ->get();

        return response()->json($locations);
    }

    public function search(Request $request)
    {
        $lat = $request->input('lat');
        $lng = $request->input('lng');

        // Search locations within 10km radius using Haversine formula
        $locations = DB::table('locations')
            ->select(
                'id',
                'location',
                'lat',
                DB::raw('`long` as lng'), // Rename to lng for consistency
                DB::raw('(
                    6371 * acos(
                        cos(radians(?)) * cos(radians(lat)) *
                        cos(radians(`long`) - radians(?)) +
                        sin(radians(?)) * sin(radians(lat))
                    )
                ) AS distance')
            )
            ->having('distance', '<=', 2.5)
            ->orderBy('distance')
            ->setBindings([$lat, $lng, $lat])
            ->get();

        return response()->json($locations);
    }

    public function show($id): View
    {
        $location = Location::findOrFail($id);
//        dd($location);
        return view('website.location-search-result', compact('location'));
    }

    public function application($locationID, $applicationID): View
    {
        $application = Application::with(['location', 'meeting'])->find($applicationID);
        $location = $application->location;
        return view('website.location-application', compact('application', 'location'));

    }
}
