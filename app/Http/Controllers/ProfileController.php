<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:1000',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->address = $request->address;

        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture) {
                Storage::delete('public/' . $user->profile_picture);
            }

            $profilePicture = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $profilePicture;
        }

        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully!');
    }

    public function destroy()
    {
        $user = Auth::user();

        // Delete profile picture if exists
        if ($user->profile_picture) {
            Storage::delete('public/' . $user->profile_picture);
        }

        $user->delete();

        return redirect('/')->with('success', 'Account deleted successfully!');
    }

    public function uploadImage(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture) {
                Storage::delete('public/' . $user->profile_picture);
            }

            $profilePicture = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $profilePicture;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile picture updated successfully!',
                'image_url' => asset('storage/' . $profilePicture)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No image file found.'
        ], 400);
    }

    public function resetImage(Request $request)
    {
        $user = Auth::user();

        if ($user->profile_picture) {
            // Delete the profile picture file
            Storage::delete('public/' . $user->profile_picture);

            // Update user record
            $user->profile_picture = null;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile picture removed successfully!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No profile picture to remove.'
        ], 400);
    }
}
