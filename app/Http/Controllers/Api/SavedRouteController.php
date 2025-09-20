<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSavedRouteRequest;
use App\Http\Requests\UpdateSavedRouteRequest;
use App\Models\SavedRoute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedRouteController extends Controller
{
    /**
     * Display a listing of the user's saved routes.
     */
    public function index(Request $request): JsonResponse
    {
        $query = SavedRoute::forUser(Auth::id())
            ->orderBy('created_at', 'desc');

        // Filter by route type if specified
        if ($request->has('type')) {
            $type = $request->get('type');
            if ($type === 'safer') {
                $query->saferRoutes();
            } elseif ($type === 'regular') {
                $query->regularRoutes();
            }
        }

        // Search by name if specified
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $routes = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $routes->items(),
            'pagination' => [
                'current_page' => $routes->currentPage(),
                'last_page' => $routes->lastPage(),
                'per_page' => $routes->perPage(),
                'total' => $routes->total(),
            ],
        ]);
    }

    /**
     * Store a newly created saved route.
     */
    public function store(StoreSavedRouteRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();

            $savedRoute = SavedRoute::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Route saved successfully',
                'data' => $savedRoute,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save route',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified saved route.
     */
    public function show($id): JsonResponse
    {
        try {
            $savedRoute = SavedRoute::forUser(Auth::id())->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $savedRoute,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Route not found',
            ], 404);
        }
    }

    /**
     * Update the specified saved route.
     */
    public function update(UpdateSavedRouteRequest $request, SavedRoute $savedRoute): JsonResponse
    {
        try {
            $savedRoute->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Route updated successfully',
                'data' => $savedRoute->fresh(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update route',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified saved route.
     */
    public function destroy(SavedRoute $savedRoute): JsonResponse
    {
        try {
            // Check if the user owns this route
            if ($savedRoute->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }

            $savedRoute->delete();

            return response()->json([
                'success' => true,
                'message' => 'Route deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete route',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get route statistics for the user.
     */
    public function stats(): JsonResponse
    {
        $userId = Auth::id();

        $stats = [
            'total_routes' => SavedRoute::forUser($userId)->count(),
            'safer_routes' => SavedRoute::forUser($userId)->saferRoutes()->count(),
            'regular_routes' => SavedRoute::forUser($userId)->regularRoutes()->count(),
            'avg_safety_score' => SavedRoute::forUser($userId)
                ->whereNotNull('safety_score')
                ->avg('safety_score'),
        ];

        // Round average safety score to 2 decimal places
        if ($stats['avg_safety_score']) {
            $stats['avg_safety_score'] = round($stats['avg_safety_score'], 2);
        }

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }
}
