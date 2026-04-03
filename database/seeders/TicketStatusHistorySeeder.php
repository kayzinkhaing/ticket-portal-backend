<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TicketStatusHistory;
use App\Models\Ticket;
use App\Models\User;
use App\Models\TicketStatus;

class TicketStatusHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tickets = Ticket::all();
        $statuses = TicketStatus::all();

        foreach ($tickets as $ticket) {
            if ($statuses->count() < 2) continue; // need at least 2 statuses

            $old = $statuses->random();
            $new = $statuses->where('id', '!=', $old->id)->random();

            TicketStatusHistory::create([
                'ticket_id' => $ticket->id,
                'old_status_id' => $old->id,
                'new_status_id' => $new->id,
                'changed_by' => User::inRandomOrder()->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
