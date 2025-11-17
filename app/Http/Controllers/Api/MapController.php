<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MapController extends Controller
{
    // Tanauan City, Batangas bounds (precise bounding box)
    // Top left: 14.153878, 121.042177
    // Top right: 14.153850, 121.153121
    // Bottom right: 14.026624, 121.157168
    // Bottom left: 14.031997, 121.055551
    private const TANAUAN_NORTH = 14.153878;  // Top edge (top left & top right)
    private const TANAUAN_SOUTH = 14.026624;  // Bottom edge (bottom left & bottom right)
    private const TANAUAN_EAST = 121.157168;  // Right edge (top right & bottom right)
    private const TANAUAN_WEST = 121.042177;  // Left edge (top left & bottom left)

    /**
     * Check if coordinates are within Tanauan City bounds
     */
    private function isWithinTanauanBounds(float $lat, float $lng): bool
    {
        return $lat >= self::TANAUAN_SOUTH
            && $lat <= self::TANAUAN_NORTH
            && $lng >= self::TANAUAN_WEST
            && $lng <= self::TANAUAN_EAST;
    }

    /**
     * POST /api/directions
     * Replicates the Flutter _getDirections using Google Routes API v2.
     */
    public function getDirections(Request $request): JsonResponse
    {
        Log::info('=== DIRECTIONS REQUEST STARTED ===', [
            'request_id' => uniqid('dir_'),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        try {
            $validated = $request->validate([
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

            Log::info('Directions request validated', [
                'from' => "{$validated['from_lat']},{$validated['from_lng']}",
                'to'   => "{$validated['to_lat']},{$validated['to_lng']}",
                'travel_mode' => $request->input('travel_mode', 'DRIVE'),
                'routing_preference' => $request->input('routing_preference', 'TRAFFIC_AWARE'),
            ]);

            // Validate that both origin and destination are within Tanauan bounds
            $fromLat = (float)$validated['from_lat'];
            $fromLng = (float)$validated['from_lng'];
            $toLat = (float)$validated['to_lat'];
            $toLng = (float)$validated['to_lng'];

            $originInBounds = $this->isWithinTanauanBounds($fromLat, $fromLng);
            $destinationInBounds = $this->isWithinTanauanBounds($toLat, $toLng);

            Log::info('Tanauan bounds check', [
                'origin_in_bounds' => $originInBounds,
                'destination_in_bounds' => $destinationInBounds,
                'bounds' => [
                    'north' => self::TANAUAN_NORTH,
                    'south' => self::TANAUAN_SOUTH,
                    'east' => self::TANAUAN_EAST,
                    'west' => self::TANAUAN_WEST,
                ],
            ]);

            if (!$originInBounds) {
                Log::warning('Origin coordinates outside Tanauan City bounds', [
                    'origin' => ['lat' => $fromLat, 'lng' => $fromLng],
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Origin location must be within Tanauan City, Batangas',
                    'error_type' => 'origin_out_of_bounds',
                ], 422);
            }

            if (!$destinationInBounds) {
                Log::warning('Destination coordinates outside Tanauan City bounds', [
                    'destination' => ['lat' => $toLat, 'lng' => $toLng],
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Destination location must be within Tanauan City, Batangas',
                    'error_type' => 'destination_out_of_bounds',
                ], 422);
            }

            Log::info('Both origin and destination are within Tanauan bounds - proceeding with route request');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Directions request validation failed', [
                'errors' => $e->errors(),
                'input' => $request->all(),
            ]);
            throw $e;
        }

        $apiKey = env('GOOGLE_MAPS_API_KEY');
        if (!$apiKey) {
            Log::error('Google Maps API key not configured in .env');
            return response()->json([
                'success' => false,
                'message' => 'Google Maps API key not configured',
            ], 500);
        }

        Log::debug('API key retrieved', ['key_length' => strlen($apiKey)]);

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

        Log::info('Sending request to Google Routes API', [
            'url' => $url,
            'request_body' => $requestBody,
            'headers' => [
                'Content-Type' => 'application/json',
                'X-Goog-FieldMask' => 'routes.duration,routes.distanceMeters,routes.polyline.encodedPolyline',
            ],
        ]);

        $startTime = microtime(true);

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-Goog-Api-Key' => $apiKey,
                'X-Goog-FieldMask' => 'routes.duration,routes.distanceMeters,routes.polyline.encodedPolyline',
            ])->timeout(30)->post($url, $requestBody);

            $elapsed = round((microtime(true) - $startTime) * 1000, 2);

            Log::info('Google Routes API response received', [
                'status' => $response->status(),
                'elapsed_ms' => $elapsed,
                'response_size' => strlen($response->body()),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                Log::debug('Routes API response data', [
                    'routes_count' => isset($data['routes']) ? count($data['routes']) : 0,
                    'data' => $data,
                ]);

                if (!empty($data['routes'])) {
                    $route = $data['routes'][0];
                    $polylinePoints = $route['polyline']['encodedPolyline'] ?? '';
                    $duration = $route['duration'] ?? '';
                    $distanceMeters = (int)($route['distanceMeters'] ?? 0);
                    $distance = number_format($distanceMeters / 1609.34, 1) . ' mi';

                    Log::info('Route found successfully', [
                        'duration' => $duration,
                        'distance_meters' => $distanceMeters,
                        'distance_display' => $distance,
                        'polyline_length' => strlen($polylinePoints),
                    ]);

                    Log::info('=== DIRECTIONS REQUEST COMPLETED SUCCESSFULLY ===');

                    return response()->json([
                        'success' => true,
                        'polyline' => $polylinePoints,
                        'duration' => $duration,
                        'distance' => $distance,
                        'distance_meters' => $distanceMeters,
                    ]);
                }

                Log::warning('No routes found in API response', ['response_data' => $data]);

                return response()->json([
                    'success' => false,
                    'message' => 'No route found',
                ], 404);
            }

            Log::error('Google Routes API request failed', [
                'status' => $response->status(),
                'response_body' => $response->body(),
                'elapsed_ms' => $elapsed,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $response->status(),
                'error' => $response->body(),
            ], $response->status());

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Connection error to Google Routes API', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Connection error: Unable to reach Google Routes API',
            ], 503);
        } catch (\Throwable $e) {
            Log::error('Unexpected error getting directions', [
                'error' => $e->getMessage(),
                'type' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error getting directions: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/geocode-text
     * Geocodes text to coordinates, restricted to Tanauan City, Batangas
     */
    public function getCoordinatesFromText(Request $request): JsonResponse
    {
        Log::info('=== GEOCODING REQUEST STARTED ===', [
            'request_id' => uniqid('geo_'),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        try {
            $validated = $request->validate([
                'text' => 'required|string|max:500',
            ]);

            Log::info('Geocoding request validated', [
                'text' => $validated['text'],
                'text_length' => strlen($validated['text']),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Geocoding request validation failed', [
                'errors' => $e->errors(),
                'input' => $request->all(),
            ]);
            throw $e;
        }

        $text = $request->input('text');

        $apiKey = env('GOOGLE_MAPS_API_KEY');
        if (!$apiKey) {
            Log::error('Google Maps API key not configured in .env');
            return response()->json([
                'success' => false,
                'message' => 'Google Maps API key not configured',
            ], 500);
        }

        Log::debug('API key retrieved for geocoding', ['key_length' => strlen($apiKey)]);

        // Build geocoding URL with Tanauan bounds and PH country restriction
        $address = urlencode("$text, Tanauan, Batangas, Philippines");
        $bounds = self::TANAUAN_SOUTH . ',' . self::TANAUAN_WEST . '|' .
                  self::TANAUAN_NORTH . ',' . self::TANAUAN_EAST;

        $url = "https://maps.googleapis.com/maps/api/geocode/json?" .
               "address=$address&" .
               "key=$apiKey&" .
               "components=country:PH&" .
               "bounds=$bounds";

        Log::info('Sending request to Google Geocoding API', [
            'address' => "$text, Tanauan, Batangas, Philippines",
            'bounds' => [
                'south' => self::TANAUAN_SOUTH,
                'west' => self::TANAUAN_WEST,
                'north' => self::TANAUAN_NORTH,
                'east' => self::TANAUAN_EAST,
            ],
            'components' => 'country:PH',
        ]);

        $startTime = microtime(true);

        try {
            $response = Http::timeout(10)->get($url);

            $elapsed = round((microtime(true) - $startTime) * 1000, 2);

            Log::info('Google Geocoding API response received', [
                'status' => $response->status(),
                'elapsed_ms' => $elapsed,
                'response_size' => strlen($response->body()),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                Log::debug('Geocoding API response data', [
                    'status' => $data['status'] ?? 'UNKNOWN',
                    'results_count' => isset($data['results']) ? count($data['results']) : 0,
                    'data' => $data,
                ]);

                if (!empty($data['results']) && is_array($data['results'])) {
                    $location = $data['results'][0]['geometry']['location'];
                    $lat = (float)$location['lat'];
                    $lng = (float)$location['lng'];
                    $formattedAddress = $data['results'][0]['formatted_address'] ?? null;

                    Log::info('Geocoding result found', [
                        'original_lat' => $lat,
                        'original_lng' => $lng,
                        'formatted_address' => $formattedAddress,
                    ]);

                    // Clamp to Tanauan bounds
                    $clampedLat = max(self::TANAUAN_SOUTH, min(self::TANAUAN_NORTH, $lat));
                    $clampedLng = max(self::TANAUAN_WEST, min(self::TANAUAN_EAST, $lng));

                    $wasClamped = ($lat !== $clampedLat || $lng !== $clampedLng);

                    if ($wasClamped) {
                        Log::warning('Coordinates clamped to Tanauan bounds', [
                            'original' => ['lat' => $lat, 'lng' => $lng],
                            'clamped' => ['lat' => $clampedLat, 'lng' => $clampedLng],
                            'bounds' => [
                                'north' => self::TANAUAN_NORTH,
                                'south' => self::TANAUAN_SOUTH,
                                'east' => self::TANAUAN_EAST,
                                'west' => self::TANAUAN_WEST,
                            ],
                        ]);
                    }

                    Log::info('=== GEOCODING REQUEST COMPLETED SUCCESSFULLY ===', [
                        'final_coordinates' => ['lat' => $clampedLat, 'lng' => $clampedLng],
                        'clamped' => $wasClamped,
                    ]);

                    return response()->json([
                        'success' => true,
                        'latitude' => $clampedLat,
                        'longitude' => $clampedLng,
                        'formatted_address' => $formattedAddress,
                        'clamped' => $wasClamped,
                    ]);
                }

                Log::warning('No location found for text', [
                    'text' => $text,
                    'api_status' => $data['status'] ?? 'UNKNOWN',
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'No location found for the given text',
                ], 404);
            }

            Log::error('Google Geocoding API request failed', [
                'status' => $response->status(),
                'response_body' => $response->body(),
                'elapsed_ms' => $elapsed,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Geocoding request failed',
                'error' => $response->body(),
            ], $response->status());

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Connection error to Google Geocoding API', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Connection error: Unable to reach Google Geocoding API',
            ], 503);
        } catch (\Throwable $e) {
            Log::error('Unexpected error geocoding text', [
                'text' => $text,
                'error' => $e->getMessage(),
                'type' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error geocoding text: ' . $e->getMessage(),
            ], 500);
        }
    }
}
