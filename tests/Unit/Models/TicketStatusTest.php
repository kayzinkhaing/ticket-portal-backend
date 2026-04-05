<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\TicketStatus;
use App\Models\Ticket;
use App\Models\TicketStatusHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_ticket_status()
    {
        $status = TicketStatus::factory()->create([
            'name' => 'Open'
        ]);

        $this->assertDatabaseHas('ticket_statuses', [
            'id' => $status->id,
            'name' => 'Open'
        ]);
    }

    /** @test */
    public function it_has_many_tickets()
    {
        $status = TicketStatus::factory()->create();

        $tickets = Ticket::factory()->count(3)->create([
            'status_id' => $status->id
        ]);

        $this->assertCount(3, $status->tickets);
        $this->assertInstanceOf(Ticket::class, $status->tickets->first());
    }

    /** @test */
    public function it_has_many_old_status_histories()
    {
        $status = TicketStatus::factory()->create();

        TicketStatusHistory::factory()->count(2)->create([
            'old_status_id' => $status->id
        ]);

        $this->assertCount(2, $status->oldStatusHistories);
    }

    /** @test */
    public function it_has_many_new_status_histories()
    {
        $status = TicketStatus::factory()->create();

        TicketStatusHistory::factory()->count(2)->create([
            'new_status_id' => $status->id
        ]);

        $this->assertCount(2, $status->newStatusHistories);
    }
}
