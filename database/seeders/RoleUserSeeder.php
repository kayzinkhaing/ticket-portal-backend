<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Assign Agent role to first user
        $agentUser = User::find(1);
        $agentRole = Role::where('name', 'Agent')->first();
        if ($agentUser && $agentRole) {
            $agentUser->roles()->attach($agentRole);
        }

        // Assign Client role to second user
        $clientUser = User::find(2);
        $clientRole = Role::where('name', 'Client')->first();
        if ($clientUser && $clientRole) {
            $clientUser->roles()->attach($clientRole);
        }
    }
}
