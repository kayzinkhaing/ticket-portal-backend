<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\TicketPriority;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketPriorityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_ticket_priority()
    {
        $priority = TicketPriority::factory()->create([
            'name' => 'High',
            'sla_hours' => 24
        ]);

        $this->assertDatabaseHas('ticket_priorities', [
            'id' => $priority->id,
            'name' => 'High',
            'sla_hours' => 24
        ]);
    }

    /** @test */
    public function it_has_many_tickets()
    {
        $priority = TicketPriority::factory()->create();

        Ticket::factory()->count(4)->create([
            'priority_id' => $priority->id
        ]);

        $this->assertCount(4, $priority->tickets);
        $this->assertInstanceOf(Ticket::class, $priority->tickets->first());
    }
}
