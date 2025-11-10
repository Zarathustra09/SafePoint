<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\AccountRejected;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    public function index()
    {
        $users = User::where('is_verified', false)->get();

        return view('approval.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('approval.show', compact('user'));
    }

    public function approve(User $user)
    {
        $user->is_verified = true;
        $user->save();

        return redirect()->route('approval.index')->with('status', 'User approved.');
    }

    public function reject(User $user)
    {
        // Store user details before deleting
        $userEmail = $user->email;
        $userName = $user->name;

        // Send notification immediately (not queued) before deleting
        $user->notifyNow(new AccountRejected(
            'Your account application did not meet our verification requirements.',
            Auth::user()->name
        ));

        // Delete the user
        $user->delete();

        return redirect()->route('approval.index')->with('status', 'User rejected and notified via email.');
    }
}
