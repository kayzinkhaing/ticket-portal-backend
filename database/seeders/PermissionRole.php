<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionRole extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Retrieve the roles
        $adminRole = Role::where('name', 'Admin')->first();
        $userRole = Role::where('name', 'User')->first();

        // Retrieve the permissions
        $manageRolesPermission = Permission::where('name', 'manage roles')->first();
        $managePermission = Permission::where('name', 'manage permissions')->first();
        $viewDashboardPermission = Permission::where('name', 'view dashboard')->first();
        $editPostsPermission = Permission::where('name', 'edit posts')->first();

        // Assign permissions to the Admin role
        $adminRole->permissions()->attach([
            $manageRolesPermission->id,
            $managePermission->id,
            $viewDashboardPermission->id,
            $editPostsPermission->id,
        ]);

        // Assign permissions to the User role
        $userRole->permissions()->attach([
            $viewDashboardPermission->id, // User can only view the dashboard
        ]);
    }
}
