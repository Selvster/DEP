<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // User management
            'view_user',
            'create_user',
            'edit_user',
            'delete_user',
            // Centers management
            'view_center',
            'create_center',
            'edit_center',
            'delete_center',
            // Request Types management
            'view_request_type',
            'create_request_type',
            'edit_request_type',
            'delete_request_type',
            // Request Statuses management
            'view_request_status',
            'create_request_status',
            'edit_request_status',
            'delete_request_status',
            // Requests management
            'view_request',
            'create_request',
            'edit_request',
            'delete_request',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create super_admin role
        $role = Role::firstOrCreate(['name' => 'super_admin']);
        
        // Assign all permissions to super_admin role
        $role->syncPermissions($permissions);

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'superadmin@mail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // Assign super_admin role to admin user
        $admin->assignRole('super_admin');
    }
}
