<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class ProperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the Admin role if it doesn't exist
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => '22-65615@g.batstate-u.edu.ph'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('AdminPassword123!'),
                'email_verified_at' => now(),
            ]
        );

        // Assign Admin role to the user
        if (!$admin->hasRole('Admin')) {
            $admin->assignRole($adminRole);
        }
    }
}
