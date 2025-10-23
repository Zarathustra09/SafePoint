<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CrimeReport;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class CrimeReportController extends Controller
{
    public function index(Request $request)
    {
        $query = CrimeReport::with('reporter');

        if ($request->has('severity')) {
            $query->bySeverity($request->severity);
        }

        if ($request->has('status')) {
            $query->byStatus($request->status);
        }

        if ($request->has(['lat', 'lng', 'radius'])) {
            $query->nearLocation(
                $request->lat,
                $request->lng,
                $request->radius ?? 10
            );
        }

        // apply search parameter if provided
        if ($request->filled('search')) {
            $term = trim($request->search);
            if ($term !== '') {
                $like = "%{$term}%";
                $query->where(function ($q) use ($like) {
                    $q->where('title', 'like', $like)
                        ->orWhere('description', 'like', $like)
                        ->orWhere('address', 'like', $like)
                        ->orWhereHas('reporter', function ($q2) use ($like) {
                            $q2->where('name', 'like', $like);
                        });
                });
            }
        }

        $crimeReports = $query->latest()->paginate(15);

        return $crimeReports;
    }


    // app/Http/Controllers/Api/CrimeReportController.php

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'severity' => 'required|in:low,medium,high,critical',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string|max:255',
            'incident_date' => 'required|date',
            'reported_by' => 'nullable|exists:users,id',
            'report_image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Verify location is within Tanauan City, Batangas
        if (!$this->isInTanauan((float) $validated['latitude'], (float) $validated['longitude'])) {
            return response()->json([
                'success' => false,
                'message' => 'Reports must be located within Tanauan City, Batangas.'
            ], 422);
        }

        if ($request->hasFile('report_image')) {
            $validated['report_image'] = $request->file('report_image')->store('report_images', 'public');
        }

        $crimeReport = CrimeReport::create($validated);

        return $crimeReport->load('reporter');
    }

    public function update(Request $request, CrimeReport $crimeReport)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'severity' => 'sometimes|in:low,medium,high,critical',
            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180',
            'address' => 'nullable|string|max:255',
            'status' => 'sometimes|in:pending,under_investigation,resolved,closed',
            'incident_date' => 'sometimes|date',
            'report_image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Determine effective coordinates after update
        $effectiveLat = array_key_exists('latitude', $validated) ? (float) $validated['latitude'] : (float) $crimeReport->latitude;
        $effectiveLng = array_key_exists('longitude', $validated) ? (float) $validated['longitude'] : (float) $crimeReport->longitude;

        if (!$this->isInTanauan($effectiveLat, $effectiveLng)) {
            return response()->json([
                'success' => false,
                'message' => 'Updated location must be within Tanauan City, Batangas.'
            ], 422);
        }

        if ($request->hasFile('report_image')) {
            $validated['report_image'] = $request->file('report_image')->store('report_images', 'public');
        }

        $crimeReport->update($validated);

        return $crimeReport->load('reporter');
    }



    public function show(CrimeReport $crimeReport)
    {
        return $crimeReport->load('reporter');
    }



    public function destroy(CrimeReport $crimeReport)
    {
        $crimeReport->delete();

        return response()->noContent();
    }


    public function myReports(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        $query = $user->crimeReports()->with('reporter');

        if ($request->has('severity')) {
            $query->bySeverity($request->severity);
        }

        if ($request->has('status')) {
            $query->byStatus($request->status);
        }

        $crimeReports = $query->latest()->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $crimeReports,
            'message' => 'Crime reports retrieved successfully'
        ]);
    }

    /**
     * Check if coordinates are within Tanauan City, Batangas using Google Maps API
     */
    protected function isInTanauan(float $lat, float $lng): bool
    {
        $apiKey = env('GOOGLE_MAPS_API_KEY');

        if (empty($apiKey)) {
            return false;
        }

        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lng}&key={$apiKey}";
        $response = Http::get($url);

        if (!$response->successful()) {
            return false;
        }

        $data = $response->json();

        if (empty($data['results'])) {
            return false;
        }

        foreach ($data['results'] as $result) {
            if (!empty($result['address_components'])) {
                $components = $result['address_components'];
                $hasTanauan = false;
                $hasBatangas = false;

                foreach ($components as $component) {
                    $types = $component['types'] ?? [];
                    $name = strtolower($component['long_name'] ?? '');

                    // Check for Tanauan in locality or administrative area
                    if ((in_array('locality', $types) || in_array('administrative_area_level_2', $types)) &&
                        strpos($name, 'tanauan') !== false) {
                        $hasTanauan = true;
                    }

                    // Check for Batangas in administrative area level 1 (province)
                    if (in_array('administrative_area_level_1', $types) &&
                        strpos($name, 'batangas') !== false) {
                        $hasBatangas = true;
                    }
                }

                if ($hasTanauan && $hasBatangas) {
                    return true;
                }
            }

            // Fallback: check formatted address
            if (!empty($result['formatted_address'])) {
                $formattedAddress = strtolower($result['formatted_address']);
                if (strpos($formattedAddress, 'tanauan') !== false &&
                    strpos($formattedAddress, 'batangas') !== false) {
                    return true;
                }
            }
        }

        return false;
    }
}
