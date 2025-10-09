<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('roles.index', compact('users'));
    }

    public function assignAdmin(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $user->assignRole($adminRole);
        return Redirect::back()->with('success', 'Admin role assigned successfully.');
    }

    public function demoteAdmin(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $adminRole = Role::where('name', 'Admin')->first();

        // Count current admins
        $adminCount = User::role('Admin')->count();

        if ($adminRole && $user->hasRole($adminRole)) {
            if ($adminCount <= 1) {
                return Redirect::back()->with('info', 'At least one Admin is required.');
            }
            $user->removeRole($adminRole);
            return Redirect::back()->with('success', 'Admin role removed successfully.');
        }
        return Redirect::back()->with('success', 'User is not an Admin.');
    }
}
