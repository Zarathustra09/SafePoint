<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\AccountStatusChanged;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::with('roles')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Toggle the blocked status of a user.
     */
    public function toggleBlocked(User $user): RedirectResponse
    {
        // Prevent admin from blocking themselves
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot block yourself.');
        }

        $user->update(['is_blocked' => !$user->is_blocked]);

        $status = $user->is_blocked ? 'blocked' : 'unblocked';

        // Send notification to the user
        $user->notify(new AccountStatusChanged($status, null, auth()->user()->name));

        return back()->with('success', "User {$user->name} has been {$status} successfully.");
    }

    /**
     * Toggle the restricted status of a user.
     */
    public function toggleRestricted(User $user): RedirectResponse
    {
        // Prevent admin from restricting themselves
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot restrict yourself.');
        }

        $user->update(['is_restricted' => !$user->is_restricted]);

        $status = $user->is_restricted ? 'restricted' : 'unrestricted';

        // Send notification to the user
        $user->notify(new AccountStatusChanged($status, null, auth()->user()->name));

        return back()->with('success', "User {$user->name} has been {$status} successfully.");
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'is_blocked' => 'boolean',
            'is_restricted' => 'boolean',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }
}
