<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CrimeReport;
use GeminiAPI\Client;
use GeminiAPI\Resources\Parts\TextPart;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class AIController extends Controller
{
    private Client $geminiClient;

    public function __construct()
    {
        $this->geminiClient = new Client(config('services.gemini.api_key'));
    }

    public function generateSaferRoute(Request $request): JsonResponse
    {
        $request->validate([
            'start_lat' => 'required|numeric',
            'start_lng' => 'required|numeric',
            'end_lat'   => 'required|numeric',
            'end_lng'   => 'required|numeric',
            'radius'    => 'numeric|min:1|max:50'
        ]);

        $radius = $request->input('radius', 5);

        // ✅ 1. Get top 10 crime reports near start location
        $crimeData = CrimeReport::nearLocation(
            $request->start_lat,
            $request->start_lng,
            $radius
        )
            ->orderBy('severity', 'desc')
            ->limit(10)
            ->get(['id','title','severity','latitude','longitude'])
            ->toArray();

        // ✅ 2. Fetch candidate routes from Google Maps Directions API
        $mapsKey = env('GOOGLE_MAPS_API_KEY');
        $directionsUrl = "https://maps.googleapis.com/maps/api/directions/json";
        $directionsResponse = Http::get($directionsUrl, [
            'origin' => "{$request->start_lat},{$request->start_lng}",
            'destination' => "{$request->end_lat},{$request->end_lng}",
            'alternatives' => 'true', // multiple route options
            'key' => $mapsKey,
        ]);

        if ($directionsResponse->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get routes from Google Maps'
            ], 500);
        }

        $routes = $directionsResponse->json('routes', []);

        if (empty($routes)) {
            return response()->json([
                'success' => false,
                'message' => 'No routes found'
            ], 404);
        }

        // ✅ 3. Ask Gemini to pick the safest route
        $apiKey = env('GEMINI_API_KEY');
        $prompt = "
        You are a navigation safety AI.
        Given:
        - Crime reports: " . json_encode($crimeData) . "
        - Candidate routes: " . json_encode(array_map(function($r) {
                        return [
                            'summary' => $r['summary'] ?? '',
                            'legs' => $r['legs'] ?? [],
                            'polyline' => $r['overview_polyline']['points'] ?? ''
                        ];
                    }, $routes)) . "

        Task:
        Select the safest route (avoid high severity crime locations if possible).
        Return ONLY the Google Maps polyline string of the safest route.
        ";

        $geminiResponse = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}", [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ]);

        if ($geminiResponse->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get safer route from Gemini',
                'crime_data' => $crimeData,
                'routes' => $routes
            ], 500);
        }

        $geminiData = $geminiResponse->json();
        $aiText = $geminiData['candidates'][0]['content']['parts'][0]['text'] ?? null;

        // Extract polyline string (strip quotes or extra text)
        preg_match('/"([^"]+)"/', $aiText, $matches);
        $polyline = $matches[1] ?? trim($aiText);

        return response()->json([
            'success' => true,
            'polyline' => $polyline,
            'crime_data' => $crimeData,
            'routes' => $routes // optional: return all Google Maps routes for debugging
        ]);
    }

    private function getCrimeDataAlongRoute($startLat, $startLng, $endLat, $endLng, $radius)
    {
        return CrimeReport::select(['latitude', 'longitude', 'severity', 'title', 'incident_date'])
            ->where(function($query) use ($startLat, $startLng, $endLat, $endLng, $radius) {
                $query->nearLocation($startLat, $startLng, $radius)
                      ->orWhere(function($q) use ($endLat, $endLng, $radius) {
                          $q->nearLocation($endLat, $endLng, $radius);
                      });
            })
            ->where('status', 'verified')
            ->orderBy('incident_date', 'desc')
            ->limit(50)
            ->get();
    }

    private function generateRouteWithAI($crimeData, $routeParams)
    {
        $prompt = $this->buildPrompt($crimeData, $routeParams);

        try {
            $response = $this->geminiClient
                ->generativeModel('gemini-2.0-flash-exp')
                ->generateContent(new TextPart($prompt));

            return [
                'ai_suggestion' => $response->text(),
                'alternative_waypoints' => $this->extractWaypoints($response->text()),
                'safety_score' => $this->calculateSafetyScore($crimeData)
            ];
        } catch (\Exception $e) {
            // Check if it's a rate limit error
            if (str_contains($e->getMessage(), '429') || str_contains($e->getMessage(), 'Quota exceeded')) {
                return [
                    'error' => 'API rate limit exceeded. Please try again later.',
                    'fallback' => $this->generateFallbackRoute($crimeData, $routeParams),
                    'safety_score' => $this->calculateSafetyScore($crimeData)
                ];
            }

            return [
                'error' => 'Failed to generate AI route: ' . $e->getMessage(),
                'fallback' => $this->generateFallbackRoute($crimeData, $routeParams),
                'safety_score' => $this->calculateSafetyScore($crimeData)
            ];
        }
    }

    private function generateFallbackRoute($crimeData, $routeParams): string
    {
        if ($crimeData->isEmpty()) {
            return 'No crime reports found in the area. Route appears safe to use.';
        }

        $highCrimeAreas = $crimeData->where('severity', 'high')->count();
        $mediumCrimeAreas = $crimeData->where('severity', 'medium')->count();

        if ($highCrimeAreas > 0) {
            return "Warning: {$highCrimeAreas} high-severity crime reports found. Consider using main roads, avoid isolated areas, and travel during daylight hours.";
        } elseif ($mediumCrimeAreas > 0) {
            return "Caution: {$mediumCrimeAreas} medium-severity crime reports found. Stay on well-lit main roads and remain alert.";
        }

        return 'Low crime activity detected. Exercise normal precautions.';
    }

    private function buildPrompt($crimeData, $routeParams): string
    {
        $crimeInfo = $crimeData->map(function($crime) {
            return "Severity: {$crime->severity}, Type: {$crime->title}, Location: {$crime->latitude},{$crime->longitude}";
        })->implode('; ');

        return "Given the following crime data along a route from ({$routeParams['start_lat']},{$routeParams['start_lng']}) to ({$routeParams['end_lat']},{$routeParams['end_lng']}):

Crime Reports: {$crimeInfo}

Please suggest alternative waypoints or route modifications to avoid high-crime areas. Provide specific latitude/longitude coordinates for safer waypoints and explain the reasoning. Focus on well-lit main roads and populated areas.";
    }

    private function extractWaypoints($aiResponse): array
    {
        preg_match_all('/(-?\d+\.\d+),\s*(-?\d+\.\d+)/', $aiResponse, $matches, PREG_SET_ORDER);

        $waypoints = [];
        foreach ($matches as $match) {
            $waypoints[] = [
                'lat' => (float)$match[1],
                'lng' => (float)$match[2]
            ];
        }

        return $waypoints;
    }

    private function calculateSafetyScore($crimeData): int
    {
        if ($crimeData->isEmpty()) return 100;

        $severityWeights = ['low' => 1, 'medium' => 3, 'high' => 5];
        $totalScore = $crimeData->sum(function($crime) use ($severityWeights) {
            return $severityWeights[strtolower($crime->severity)] ?? 2;
        });

        return max(0, 100 - ($totalScore * 2));
    }
}
