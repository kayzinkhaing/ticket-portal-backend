<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TicketStatus;

class TicketStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'Open'],
            ['name' => 'In Progress'],
            ['name' => 'Resolved'],
            ['name' => 'Closed'],
        ];

        foreach ($statuses as $status) {
            TicketStatus::updateOrCreate(
                ['name' => $status['name']],
                $status
            );
        }
    }
}
