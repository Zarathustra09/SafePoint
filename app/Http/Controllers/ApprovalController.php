<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
        // Optionally, delete or flag the user as rejected
        $user->delete();

        return redirect()->route('approval.index')->with('status', 'User rejected.');
    }
}
