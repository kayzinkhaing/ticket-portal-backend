<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $agentRole = Role::create(['name' => 'Agent']);
        $clientRole = Role::create(['name' => 'Client']);

        // Assign "manage roles" permission to Agent role
        $manageRolesPermission = Permission::where('name', 'manage roles')->first();
        if ($manageRolesPermission) {
            $agentRole->permissions()->attach($manageRolesPermission);
        }
    }
}
