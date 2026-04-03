<?php

namespace Database\Seeders;

use App\Models\ClientProfile;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class ClientProfileSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch all client users (example: users with 'Client' in email)
        $clients = User::where('email', 'like', '%@gmail.com')->get();

        $organization = Organization::first(); // Or create one

        foreach ($clients as $client) {
            // Assign role
            $client->assignRole('Client');

            // Create client profile
            ClientProfile::create([
                'user_id' => $client->id,
                'organization_id' => $organization->id,
            ]);
        }
    }
}
