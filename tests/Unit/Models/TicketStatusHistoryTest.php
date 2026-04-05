<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\TicketStatusHistory;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketStatusHistoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_ticket_status_history()
    {
        $history = TicketStatusHistory::factory()->create();

        $this->assertDatabaseHas('ticket_status_histories', [
            'id' => $history->id
        ]);
    }

    /** @test */
    public function it_does_not_use_updated_at_timestamp()
    {
        $history = TicketStatusHistory::factory()->create();

        $this->assertNull($history->updated_at);
        $this->assertNotNull($history->created_at);
    }

    /** @test */
    public function it_belongs_to_ticket()
    {
        $ticket = Ticket::factory()->create();

        $history = TicketStatusHistory::factory()->create([
            'ticket_id' => $ticket->id
        ]);

        $this->assertEquals($ticket->id, $history->ticket->id);
    }

    /** @test */
    public function it_belongs_to_old_status()
    {
        $status = TicketStatus::factory()->create();

        $history = TicketStatusHistory::factory()->create([
            'old_status_id' => $status->id
        ]);

        $this->assertEquals($status->id, $history->oldStatus->id);
    }

    /** @test */
    public function it_belongs_to_new_status()
    {
        $status = TicketStatus::factory()->create();

        $history = TicketStatusHistory::factory()->create([
            'new_status_id' => $status->id
        ]);

        $this->assertEquals($status->id, $history->newStatus->id);
    }

    /** @test */
    public function it_belongs_to_changed_by_user()
    {
        $user = User::factory()->create();

        $history = TicketStatusHistory::factory()->create([
            'changed_by' => $user->id
        ]);

        $this->assertEquals($user->id, $history->changedBy->id);
    }
}
