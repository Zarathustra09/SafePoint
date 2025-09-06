<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CrimeReport;
use GeminiAPI\Client;
use GeminiAPI\Resources\Parts\TextPart;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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
            'end_lat' => 'required|numeric',
            'end_lng' => 'required|numeric',
            'radius' => 'numeric|min:1|max:50'
        ]);

        $radius = $request->input('radius', 5);

        // Get crime reports near the route
        $crimeData = $this->getCrimeDataAlongRoute(
            $request->start_lat,
            $request->start_lng,
            $request->end_lat,
            $request->end_lng,
            $radius
        );

        // Generate safer route using Gemini AI
        $saferRoute = $this->generateRouteWithAI($crimeData, $request->all());

        return response()->json([
            'success' => true,
            'route' => $saferRoute,
            'crime_data' => $crimeData
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
