<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $announcements = Announcement::latest()->paginate(10);

        return view('announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('announcements.create');
    }

    /**
     * Store a newly created resource in storage.
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
        ]);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Announcement $announcement)
    {
        return view('announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement)
    {

        return view('announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imageData = $announcement->images ?? [];
        $maxOrder = 0;

        // Find maximum order value
        if (!empty($imageData)) {
            $maxOrder = max(array_column($imageData, 'order'));
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('announcements', 'public');
                $maxOrder++;

                $imageData[] = [
                    'id' => uniqid(),
                    'path' => $path,
                    'is_featured' => false,
                    'order' => $maxOrder,
                ];
            }
        }

        $announcement->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'images' => $imageData,
        ]);

        return redirect()->route('announcements.show', $announcement)
            ->with('success', 'Announcement updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {

        // Delete image files from storage
        if (!empty($announcement->images)) {
            foreach ($announcement->images as $image) {
                if (Storage::disk('public')->exists($image['path'])) {
                    Storage::disk('public')->delete($image['path']);
                }
            }
        }

        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }

    /**
     * Update image properties.
     */
    public function updateImage(Request $request, Announcement $announcement, string $imageId)
    {

        $validated = $request->validate([
            'is_featured' => 'nullable|boolean',
            'order' => 'nullable|integer',
        ]);

        $images = $announcement->images ?? [];
        $updated = false;

        foreach ($images as &$image) {
            if ($image['id'] === $imageId) {
                if (isset($validated['is_featured']) && $validated['is_featured']) {
                    // Unset any previously featured image
                    foreach ($images as &$img) {
                        $img['is_featured'] = false;
                    }
                }

                if (isset($validated['is_featured'])) {
                    $image['is_featured'] = $validated['is_featured'];
                }

                if (isset($validated['order'])) {
                    $image['order'] = $validated['order'];
                }

                $updated = true;
                break;
            }
        }

        if ($updated) {
            $announcement->images = $images;
            $announcement->save();

            return response()->json([
                'success' => true,
                'images' => $announcement->images
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Image not found'
        ], 404);
    }

    /**
     * Delete an image.
     */
    public function deleteImage(Announcement $announcement, string $imageId)
    {
        $images = $announcement->images ?? [];
        $imageToDelete = null;
        $updatedImages = [];

        foreach ($images as $image) {
            if ($image['id'] === $imageId) {
                $imageToDelete = $image;
            } else {
                $updatedImages[] = $image;
            }
        }

        if ($imageToDelete) {
            // Delete the file from storage
            if (Storage::disk('public')->exists($imageToDelete['path'])) {
                Storage::disk('public')->delete($imageToDelete['path']);
            }

            $announcement->images = $updatedImages;
            $announcement->save();

            return response()->json([
                'success' => true
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Image not found'
        ], 404);
    }
}
