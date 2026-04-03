<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1️⃣ Roles & Permissions
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RoleUserSeeder::class,
        ]);

        // 2️⃣ Users
        User::factory()->create([
            'first_name' => 'Support',
            'middle_name' => null,
            'last_name' => 'Agent',
            'email' => 'kayzinkhaing1331@gmail.com',
            'password' => Hash::make('Ticket@Support997'),
        ]);

        User::factory()->create([
            'first_name' => 'Client',
            'middle_name' => null,
            'last_name' => 'User',
            'email' => 'kayzinkhaing1332001@gmail.com',
            'password' => Hash::make('Ticket@Support997'),
        ]);

        // 3️⃣ Organizations & Client Profiles
        $this->call([
            OrganizationSeeder::class,
            ClientProfileSeeder::class, // optional
        ]);

        // 4️⃣ Ticket Statuses & Priorities
        $this->call([
            TicketStatusSeeder::class,
            TicketPrioritySeeder::class,
        ]);

        // 5️⃣ Tickets (clients, statuses, priorities must exist first)
        Ticket::factory(50)->create();

        // 6️⃣ Ticket Status Histories (tickets must exist)
        $this->call(\Database\Seeders\TicketStatusHistorySeeder::class);

        // 7️⃣ Comments (tickets & users must exist first)
        $this->call(CommentSeeder::class);

        // 8️⃣ Optional: Messages & Common Table
        $this->call([
            MessageSeeder::class,     // optional
            CommonTableSeeder::class, // optional
        ]);
    }
}
