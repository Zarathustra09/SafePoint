<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MapController extends Controller
{
    /**
     * POST /api/directions
     * Replicates the Flutter _getDirections using Google Routes API v2.
     */
    public function getDirections(Request $request): JsonResponse
    {
        $request->validate([
            'from_lat' => 'required|numeric|between:-90,90',
            'from_lng' => 'required|numeric|between:-180,180',
            'to_lat'   => 'required|numeric|between:-90,90',
            'to_lng'   => 'required|numeric|between:-180,180',
            'travel_mode' => 'nullable|string|in:DRIVE,WALK,BICYCLE,TWO_WHEELER',
            'routing_preference' => 'nullable|string|in:TRAFFIC_AWARE,TRAFFIC_AWARE_OPTIMAL,TRAFFIC_UNAWARE',
            'avoid_tolls' => 'nullable|boolean',
            'avoid_highways' => 'nullable|boolean',
            'avoid_ferries' => 'nullable|boolean',
            'units' => 'nullable|string|in:IMPERIAL,METRIC',
        ]);

        Log::info('Getting directions', [
            'from' => "{$request->from_lat},{$request->from_lng}",
            'to'   => "{$request->to_lat},{$request->to_lng}",
        ]);

        $apiKey = env('GOOGLE_MAPS_API_KEY');
        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'Google Maps API key not configured',
            ], 500);
        }

        $url = 'https://routes.googleapis.com/directions/v2:computeRoutes';

        $requestBody = [
            "origin" => [
                "location" => [
                    "latLng" => [
                        "latitude" => (float)$request->from_lat,
                        "longitude" => (float)$request->from_lng,
                    ]
                ]
            ],
            "destination" => [
                "location" => [
                    "latLng" => [
                        "latitude" => (float)$request->to_lat,
                        "longitude" => (float)$request->to_lng,
                    ]
                ]
            ],
            "travelMode" => $request->input('travel_mode', 'DRIVE'),
            "routingPreference" => $request->input('routing_preference', 'TRAFFIC_AWARE'),
            "computeAlternativeRoutes" => false,
            "routeModifiers" => [
                "avoidTolls" => $request->boolean('avoid_tolls', false),
                "avoidHighways" => $request->boolean('avoid_highways', false),
                "avoidFerries" => $request->boolean('avoid_ferries', false),
            ],
            "languageCode" => "en-US",
            "units" => $request->input('units', 'IMPERIAL'),
        ];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-Goog-Api-Key' => $apiKey,
                'X-Goog-FieldMask' => 'routes.duration,routes.distanceMeters,routes.polyline.encodedPolyline',
            ])->timeout(30)->post($url, $requestBody);

            Log::info('Routes API response', ['status' => $response->status(), 'body' => $response->body()]);

            if ($response->successful()) {
                $data = $response->json();

                if (!empty($data['routes'])) {
                    $route = $data['routes'][0];
                    $polylinePoints = $route['polyline']['encodedPolyline'] ?? '';
                    $duration = $route['duration'] ?? '';
                    $distanceMeters = (int)($route['distanceMeters'] ?? 0);
                    $distance = number_format($distanceMeters / 1609.34, 1) . ' mi';

                    return response()->json([
                        'success' => true,
                        'polyline' => $polylinePoints,
                        'duration' => $duration,
                        'distance' => $distance,
                        'distance_meters' => $distanceMeters,
                    ]);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'No route found',
                ], 404);
            }

            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $response->status(),
                'error' => $response->body(),
            ], $response->status());
        } catch (\Throwable $e) {
            Log::error('Error getting directions', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error getting directions: ' . $e->getMessage(),
            ], 500);
        }
    }
}
