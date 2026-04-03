<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Ticket;
use App\Models\ClientProfile;
use App\Models\User;
use App\Models\TicketStatus;
use App\Models\TicketPriority;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        $clientProfile = ClientProfile::inRandomOrder()->first();
        $createdBy = User::inRandomOrder()->first();
        $status = TicketStatus::inRandomOrder()->first();
        $priority = TicketPriority::inRandomOrder()->first();

        return [
            'client_profile_id' => $clientProfile?->id,
            'created_by'       => $createdBy?->id,
            'assigned_to'      => User::inRandomOrder()->first()?->id, // optional
            'assigned_by'      => $createdBy?->id,                     // optional

            'title'            => $this->faker->sentence(),
            'description'      => $this->faker->paragraph(),

            'status_id'        => $status?->id,
            'priority_id'      => $priority?->id,

            'sla_deadline'     => $this->faker->dateTimeBetween('+1 days', '+7 days'),
        ];
    }
}
