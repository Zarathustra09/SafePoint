<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements.
     */
    public function index(Request $request)
    {
        $announcements = Announcement::with('user')
            ->where('is_active', true)
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $announcements
        ]);
    }

    /**
     * Store a newly created announcement.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imageData = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('announcements', 'public');

                $imageData[] = [
                    'id' => uniqid(),
                    'path' => $path,
                    'is_featured' => $index === 0, // First image is featured
                    'order' => $index,
                ];
            }
        }

        $announcement = Auth::user()->announcements()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'images' => $imageData,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'data' => $announcement->load('user')
        ], 201);
    }

    /**
     * Display the specified announcement.
     */
    public function show(Announcement $announcement)
    {
        return response()->json([
            'success' => true,
            'data' => $announcement->load('user')
        ]);
    }

    /**
     * Update the specified announcement.
     */
    public function update(Request $request, Announcement $announcement)
    {
        // Check if user owns the announcement
        if ($announcement->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'is_active' => 'sometimes|boolean',
            'images' => 'nullable|array',
        ]);

        $announcement->update($validated);

        return response()->json([
            'success' => true,
            'data' => $announcement->fresh('user')
        ]);
    }

    /**
     * Remove the specified announcement.
     */
    public function destroy(Announcement $announcement)
    {
        // Check if user owns the announcement
        if ($announcement->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Delete image files from storage
        if (!empty($announcement->images)) {
            foreach ($announcement->images as $image) {
                if (Storage::disk('public')->exists($image['path'])) {
                    Storage::disk('public')->delete($image['path']);
                }
            }
        }

        $announcement->delete();

        return response()->json([
            'success' => true,
            'message' => 'Announcement deleted successfully'
        ]);
    }
}
