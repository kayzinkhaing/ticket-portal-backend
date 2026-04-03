<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommonTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $commonEntries = [
            // Ticket Statuses
            ['type' => 'status', 'key' => 'open', 'value' => 'Open', 'label' => 'Open', 'description' => 'Ticket is open', 'sort_order' => 1],
            ['type' => 'status', 'key' => 'in_progress', 'value' => 'In Progress', 'label' => 'In Progress', 'description' => 'Ticket is being worked on', 'sort_order' => 2],
            ['type' => 'status', 'key' => 'closed', 'value' => 'Closed', 'label' => 'Closed', 'description' => 'Ticket has been resolved', 'sort_order' => 3],

            // Ticket Priorities
            ['type' => 'priority', 'key' => 'low', 'value' => 'Low', 'label' => 'Low', 'description' => 'Low priority ticket', 'sort_order' => 1],
            ['type' => 'priority', 'key' => 'medium', 'value' => 'Medium', 'label' => 'Medium', 'description' => 'Medium priority ticket', 'sort_order' => 2],
            ['type' => 'priority', 'key' => 'high', 'value' => 'High', 'label' => 'High', 'description' => 'High priority ticket', 'sort_order' => 3],
            ['type' => 'priority', 'key' => 'urgent', 'value' => 'Urgent', 'label' => 'Urgent', 'description' => 'Urgent priority ticket', 'sort_order' => 4],
        ];

        DB::table('common_tables')->insert($commonEntries);
    }
}
