<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'profile_picture' => $user->profile_picture ? asset('storage/'.$user->profile_picture) : null,
                'address' => $user->address,
                'is_verified' => $user->is_verified ?? false,
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'address' => 'nullable|string|max:1000',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->address = $request->address;

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::delete('public/'.$user->profile_picture);
            }

            $profilePicture = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $profilePicture;
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully!',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'address' => $user->address,
                'profile_picture' => $user->profile_picture ? asset('storage/'.$user->profile_picture) : null,
                'is_verified' => $user->is_verified ?? false,
            ],
        ]);
    }

    public function uploadImage(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::delete('public/'.$user->profile_picture);
            }

            $profilePicture = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $profilePicture;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile picture updated successfully!',
                'image_url' => asset('storage/'.$profilePicture),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No image file found.',
        ], 400);
    }

    public function resetImage()
    {
        $user = Auth::user();

        if ($user->profile_picture) {
            Storage::delete('public/'.$user->profile_picture);
            $user->profile_picture = null;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile picture removed successfully!',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No profile picture to remove.',
        ], 400);
    }

    public function destroy()
    {
        $user = Auth::user();

        if ($user->profile_picture) {
            Storage::delete('public/'.$user->profile_picture);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully!',
        ]);
    }
}
