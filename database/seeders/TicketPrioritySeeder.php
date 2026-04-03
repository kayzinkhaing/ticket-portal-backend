<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TicketPriority;

class TicketPrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $priorities = [
            [
                'name' => 'Low',
                'sla_hours' => 72, // 3 days
            ],
            [
                'name' => 'Medium',
                'sla_hours' => 48, // 2 days
            ],
            [
                'name' => 'High',
                'sla_hours' => 24, // 1 day
            ],
            [
                'name' => 'Critical',
                'sla_hours' => 4, // 4 hours
            ],
        ];

        foreach ($priorities as $priority) {
            TicketPriority::updateOrCreate(
                ['name' => $priority['name']],
                $priority
            );
        }
    }
}
