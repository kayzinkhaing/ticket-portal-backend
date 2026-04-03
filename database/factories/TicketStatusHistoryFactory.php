<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TicketStatusHistory;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;

class TicketStatusHistoryFactory extends Factory
{
    protected $model = TicketStatusHistory::class;

    public function definition(): array
    {
        $ticket = Ticket::inRandomOrder()->first();
        $statuses = TicketStatus::all()->pluck('id');

        if (!$ticket || $statuses->count() < 2) {
            return []; // prevent null errors
        }

        return [
            'ticket_id' => $ticket->id,
            'old_status_id' => $statuses->random(),
            'new_status_id' => $statuses->where('id', '!=', $statuses->random())->random(),
            'changed_by' => User::inRandomOrder()->first()?->id,
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'updated_at' => now(),
        ];
    }
}
