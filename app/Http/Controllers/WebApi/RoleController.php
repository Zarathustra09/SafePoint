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
}
