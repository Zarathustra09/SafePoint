<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CrimeReport;
use GeminiAPI\Client;
use GeminiAPI\Resources\Parts\TextPart;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIController extends Controller
{
    private Client $geminiClient;

    public function __construct()
    {
        $this->geminiClient = new Client(config('services.gemini.api_key'));
    }


    /**
     * Generate safer route without Gemini AI (pure mathematical approach)
     * Uses Google Routes API instead of legacy Directions API
     */
    public function generateSaferRoute(Request $request): JsonResponse
    {
        Log::info('generateSaferRoute called', [
            'start_lat' => $request->start_lat,
            'start_lng' => $request->start_lng,
            'end_lat' => $request->end_lat,
            'end_lng' => $request->end_lng,
            'radius' => $request->input('radius', 5)
        ]);

        $request->validate([
            'start_lat' => 'required|numeric|between:-90,90',
            'start_lng' => 'required|numeric|between:-180,180',
            'end_lat' => 'required|numeric|between:-90,90',
            'end_lng' => 'required|numeric|between:-180,180',
            'radius' => 'numeric|min:1|max:50',
            'avoid_recent_crimes' => 'boolean',
            'time_sensitivity_days' => 'numeric|min:1|max:365'
        ]);

        // Validate that both start and end coordinates are within Tanauan City, Batangas
        if (!$this->isInTanauan((float) $request->start_lat, (float) $request->start_lng)) {
            return response()->json([
                'success' => false,
                'message' => 'Start location must be within Tanauan City, Batangas.'
            ], 422);
        }

        if (!$this->isInTanauan((float) $request->end_lat, (float) $request->end_lng)) {
            return response()->json([
                'success' => false,
                'message' => 'End location must be within Tanauan City, Batangas.'
            ], 422);
        }

        $radius = $request->input('radius', 5);
        $avoidRecentCrimes = $request->input('avoid_recent_crimes', true);
        $timeSensitivityDays = $request->input('time_sensitivity_days', 30);

        // Get crime data with time sensitivity
        $crimeQuery = CrimeReport::nearLocation(
            $request->start_lat,
            $request->start_lng,
            $radius
        );

        if ($avoidRecentCrimes) {
            $crimeQuery->where('incident_date', '>=', now()->subDays($timeSensitivityDays));
        }

        $crimeData = $crimeQuery
            ->orderBy('severity', 'desc')
            ->orderBy('incident_date', 'desc')
            ->limit(50)
            ->get(['id', 'title', 'severity', 'latitude', 'longitude', 'incident_date'])
            ->toArray();

        Log::info('Crime data fetched', ['crime_count' => count($crimeData)]);

        // Check if Google Maps API key is configured
        $mapsKey = env('GOOGLE_MAPS_API_KEY');
        if (!$mapsKey) {
            return response()->json([
                'success' => false,
                'message' => 'Google Maps API key not configured'
            ], 500);
        }

        // Get multiple route options using different preferences
        $routes = $this->getMultipleRouteOptions($request);

        if (empty($routes)) {
            Log::warning('No routes found from Google Routes API with multiple preferences');
            // Fallback to legacy Directions API
            return $this->fallbackToDirectionsAPI($request, $crimeData);
        }

        // Calculate safety scores for each route
        $routesWithScores = $this->calculateRouteSafetyScores($routes, $crimeData, 'routes_api');

        // Sort by safety score (lower = safer)
        usort($routesWithScores, function ($a, $b) {
            return $a['safety_score'] <=> $b['safety_score'];
        });

        $safestRoute = $routesWithScores[0];

        // Define safety threshold
        $SAFETY_THRESHOLD = 5.0; // Adjust this value based on your needs

        if ($safestRoute['safety_score'] > $SAFETY_THRESHOLD) {
            Log::warning('All routes exceed safety threshold', [
                'best_score' => $safestRoute['safety_score'],
                'threshold' => $SAFETY_THRESHOLD
            ]);

            return response()->json([
                'success' => false,
                'message' => 'No safe routes available. All routes pass through high-crime areas.',
                'best_available_score' => $safestRoute['safety_score'],
                'safety_threshold' => $SAFETY_THRESHOLD,
                'recommendation' => 'Consider traveling at a different time or using public transportation.',
                'route_data' => $safestRoute,
                'crime_analysis' => [
                    'total_crimes_in_area' => count($crimeData),
                    'crimes_near_route' => $safestRoute['crimes_near_route'],
                    'high_severity_crimes' => $safestRoute['high_severity_crimes']
                ]
            ], 200); // Use 200 so the app can handle it gracefully
        }

        Log::info('Route safety analysis completed', [
            'routes_analyzed' => count($routesWithScores),
            'safest_route_score' => $safestRoute['safety_score']
        ]);

        return response()->json([
            'success' => true,
            'polyline' => $safestRoute['polyline'],
            'safety_score' => $safestRoute['safety_score'],
            'route_description' => $safestRoute['description'],
            'duration' => $safestRoute['duration'],
            'distance' => $safestRoute['distance'],
            'crime_analysis' => [
                'total_crimes_in_area' => count($crimeData),
                'crimes_near_route' => $safestRoute['crimes_near_route'],
                'high_severity_crimes' => $safestRoute['high_severity_crimes']
            ],
            'alternative_routes' => array_slice($routesWithScores, 1, 2),
            'api_used' => 'routes_api'
        ]);
    }

    /**
     * Get multiple route options using different routing preferences
     */
    private function getMultipleRouteOptions(Request $request): array
    {
        $mapsKey = env('GOOGLE_MAPS_API_KEY');
        $routesUrl = "https://routes.googleapis.com/directions/v2:computeRoutes";

        $baseRequest = [
            'origin' => [
                'location' => [
                    'latLng' => [
                        'latitude' => (float)$request->start_lat,
                        'longitude' => (float)$request->start_lng
                    ]
                ]
            ],
            'destination' => [
                'location' => [
                    'latLng' => [
                        'latitude' => (float)$request->end_lat,
                        'longitude' => (float)$request->end_lng
                    ]
                ]
            ],
            'travelMode' => 'DRIVE',
            'languageCode' => 'en-US',
            'units' => 'METRIC'
        ];

        // Try different routing preferences to get more route options
        $preferences = [
            ['routingPreference' => 'TRAFFIC_AWARE_OPTIMAL', 'computeAlternativeRoutes' => true],
            ['routingPreference' => 'TRAFFIC_AWARE', 'routeModifiers' => ['avoidHighways' => true]],
            ['routingPreference' => 'TRAFFIC_AWARE', 'routeModifiers' => ['avoidTolls' => true]],
            ['routingPreference' => 'TRAFFIC_UNAWARE'],
        ];

        $allRoutes = [];
        foreach ($preferences as $preference) {
            $requestBody = array_merge($baseRequest, $preference);

            Log::info('Making Routes API call with preference', ['preference' => $preference]);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-Goog-Api-Key' => $mapsKey,
                'X-Goog-FieldMask' => 'routes.duration,routes.distanceMeters,routes.polyline.encodedPolyline,routes.legs.steps.navigationInstruction,routes.description'
            ])->timeout(30)->post($routesUrl, $requestBody);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['routes'])) {
                    $allRoutes = array_merge($allRoutes, $data['routes']);
                    Log::info('Added routes from preference', [
                        'preference' => $preference['routingPreference'],
                        'routes_count' => count($data['routes'])
                    ]);
                }
            } else {
                Log::warning('Routes API call failed', [
                    'preference' => $preference,
                    'status' => $response->status(),
                    'error' => $response->body()
                ]);
            }
        }

        // Remove duplicate routes (same polyline)
        $uniqueRoutes = [];
        $seenPolylines = [];
        foreach ($allRoutes as $route) {
            $polyline = $route['polyline']['encodedPolyline'] ?? '';
            if (!empty($polyline) && !in_array($polyline, $seenPolylines)) {
                $uniqueRoutes[] = $route;
                $seenPolylines[] = $polyline;
            }
        }

     Log::info('Route deduplication completed', [
         'total_routes_fetched' => count($allRoutes),
         'unique_routes' => count($uniqueRoutes),
         'unique_polylines' => array_values(array_filter(array_map(function($r) {
             return $r['polyline']['encodedPolyline'] ?? ($r['overview_polyline']['points'] ?? null);
         }, $uniqueRoutes))),
         'seen_polylines' => $seenPolylines
     ]);

        return $uniqueRoutes;
    }

    /**
     * Fallback to legacy Directions API if Routes API is not available
     */
    private function fallbackToDirectionsAPI(Request $request, array $crimeData): JsonResponse
    {
        Log::info('Falling back to Directions API');

        $mapsKey = env('GOOGLE_MAPS_API_KEY');
        $directionsUrl = "https://maps.googleapis.com/maps/api/directions/json";

        $directionsResponse = Http::get($directionsUrl, [
            'origin' => "{$request->start_lat},{$request->start_lng}",
            'destination' => "{$request->end_lat},{$request->end_lng}",
            'alternatives' => 'true',
            'mode' => 'driving',
            'key' => $mapsKey,
        ]);

        if ($directionsResponse->failed()) {
            Log::error('Both Routes API and Directions API failed', [
                'directions_response' => $directionsResponse->body()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to get routes from Google Maps APIs. Please enable Directions API in Google Cloud Console.',
                'directions_api_error' => $directionsResponse->body()
            ], 500);
        }

        $directionsData = $directionsResponse->json();
        $routes = $directionsData['routes'] ?? [];

        if (empty($routes) || ($directionsData['status'] ?? '') !== 'OK') {
            return response()->json([
                'success' => false,
                'message' => 'No routes found from Directions API',
                'api_status' => $directionsData['status'] ?? 'unknown',
                'error_message' => $directionsData['error_message'] ?? 'No error provided'
            ], 404);
        }

        // Calculate safety scores using directions API format
        $routesWithScores = $this->calculateRouteSafetyScores($routes, $crimeData, 'directions_api');

        usort($routesWithScores, function ($a, $b) {
            return $a['safety_score'] <=> $b['safety_score'];
        });

        $safestRoute = $routesWithScores[0];

        return response()->json([
            'success' => true,
            'polyline' => $safestRoute['polyline'],
            'safety_score' => $safestRoute['safety_score'],
            'route_description' => $safestRoute['description'],
            'duration' => $safestRoute['duration'],
            'distance' => $safestRoute['distance'],
            'crime_analysis' => [
                'total_crimes_in_area' => count($crimeData),
                'crimes_near_route' => $safestRoute['crimes_near_route'],
                'high_severity_crimes' => $safestRoute['high_severity_crimes']
            ],
            'alternative_routes' => array_slice($routesWithScores, 1, 2),
            'api_used' => 'directions_api_fallback'
        ]);
    }

    /**
     * Calculate safety scores for routes (works with both APIs)
     */
    private function calculateRouteSafetyScores(array $routes, array $crimeData, string $apiType = 'routes_api'): array
    {
        $routesWithScores = [];

        foreach ($routes as $routeIndex => $route) {
            // Handle different API response formats
            if ($apiType === 'routes_api') {
                $polyline = $route['polyline']['encodedPolyline'] ?? '';
                $duration = $this->formatDuration($route['duration'] ?? '');
                $distance = $this->formatDistance($route['distanceMeters'] ?? 0);
                $description = $route['description'] ?? "Route " . ($routeIndex + 1);
            } else {
                $polyline = $route['overview_polyline']['points'] ?? '';
                $duration = $route['legs'][0]['duration']['text'] ?? 'Unknown';
                $distance = $route['legs'][0]['distance']['text'] ?? 'Unknown';
                $description = $route['summary'] ?? "Route " . ($routeIndex + 1);
            }

            if (empty($polyline)) {
                continue;
            }

            $polylinePoints = $this->decodePolyline($polyline);

            $safetyScore = 0;
            $crimesNearRoute = 0;
            $highSeverityCrimes = 0;

            // Severity weights
            $severityWeights = [
                'low' => 1,
                'medium' => 3,
                'high' => 5,
                'critical' => 10
            ];

            foreach ($crimeData as $crime) {
                $minDistance = $this->getMinimumDistanceToRoute(
                    $crime['latitude'],
                    $crime['longitude'],
                    $polylinePoints
                );

                // Crime affects safety if within 250m (0.25km) of route
                if ($minDistance <= 0.25) {
                    $crimesNearRoute++;

                    $distanceWeight = max(0, 1 - ($minDistance / 0.25));
                    $severityWeight = $severityWeights[strtolower($crime['severity'])] ?? 1;

                    // Recent crimes have higher impact
                    $daysAgo = now()->diffInDays($crime['incident_date']);
                    $recencyWeight = max(0.1, 1 - ($daysAgo / 365));

                    $crimeImpact = $distanceWeight * $severityWeight * $recencyWeight;
                    $safetyScore += $crimeImpact;

                    if (in_array(strtolower($crime['severity']), ['high', 'critical'])) {
                        $highSeverityCrimes++;
                    }
                }
            }

            // Add small penalty for longer routes
            $routeDistanceKm = $apiType === 'routes_api'
                ? ($route['distanceMeters'] ?? 0) / 1000
                : ($route['legs'][0]['distance']['value'] ?? 0) / 1000;
            $lengthPenalty = $routeDistanceKm * 0.01;

            $finalScore = $safetyScore + $lengthPenalty;

            $routesWithScores[] = [
                'polyline' => $polyline,
                'description' => $description,
                'safety_score' => round($finalScore, 2),
                'duration' => $duration,
                'distance' => $distance,
                'crimes_near_route' => $crimesNearRoute,
                'high_severity_crimes' => $highSeverityCrimes
            ];
        }

        return $routesWithScores;
    }

    /**
     * Format duration from Routes API response
     */
    private function formatDuration(string $duration): string
    {
        if (preg_match('/(\d+)s/', $duration, $matches)) {
            $seconds = intval($matches[1]);
            $minutes = round($seconds / 60);
            return $minutes . ' mins';
        }
        return $duration;
    }

    /**
     * Format distance from Routes API response
     */
    private function formatDistance(int $distanceMeters): string
    {
        if ($distanceMeters > 0) {
            $km = round($distanceMeters / 1000, 1);
            return $km . ' km';
        }
        return 'Unknown';
    }

    /**
     * Calculate minimum distance from point to route
     */
    private function getMinimumDistanceToRoute(float $lat, float $lng, array $routePoints): float
    {
        $minDistance = PHP_FLOAT_MAX;
        foreach ($routePoints as $point) {
            $distance = $this->calculateHaversineDistance($lat, $lng, $point['lat'], $point['lng']);
            $minDistance = min($minDistance, $distance);
        }
        return $minDistance;
    }

    /**
     * Decode polyline to coordinate points
     */
    private function decodePolyline(string $encoded): array
    {
        $points = [];
        $index = 0;
        $len = strlen($encoded);
        $lat = 0;
        $lng = 0;

        while ($index < $len) {
            $b = 0;
            $shift = 0;
            $result = 0;
            do {
                $b = ord($encoded[$index++]) - 63;
                $result |= ($b & 0x1f) << $shift;
                $shift += 5;
            } while ($b >= 0x20);
            $dlat = (($result & 1) ? ~($result >> 1) : ($result >> 1));
            $lat += $dlat;

            $shift = 0;
            $result = 0;
            do {
                $b = ord($encoded[$index++]) - 63;
                $result |= ($b & 0x1f) << $shift;
                $shift += 5;
            } while ($b >= 0x20);
            $dlng = (($result & 1) ? ~($result >> 1) : ($result >> 1));
            $lng += $dlng;

            $points[] = [
                'lat' => $lat / 1E5,
                'lng' => $lng / 1E5
            ];
        }
        return $points;
    }

    /**
     * Calculate distance using Haversine formula
     */
    private function calculateHaversineDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) * sin($dLng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
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
