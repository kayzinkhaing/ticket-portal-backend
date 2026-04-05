<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Ticket;
use App\Models\User;
use App\Models\ClientProfile;
use App\Models\TicketStatus;
use App\Models\TicketPriority;
use App\Models\Comment;
use App\Models\Media;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_ticket()
    {
        $ticket = Ticket::factory()->create();

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id
        ]);
    }

    /** @test */
    public function it_belongs_to_client_profile()
    {
        $profile = ClientProfile::factory()->create();

        $ticket = Ticket::factory()->create([
            'client_profile_id' => $profile->id
        ]);

        $this->assertEquals($profile->id, $ticket->clientProfile->id);
    }

    /** @test */
    public function client_method_returns_client_profile_relation()
    {
        $profile = ClientProfile::factory()->create();

        $ticket = Ticket::factory()->create([
            'client_profile_id' => $profile->id
        ]);

        $this->assertEquals($profile->id, $ticket->client->id);
    }

    /** @test */
    public function it_belongs_to_creator()
    {
        $user = User::factory()->create();

        $ticket = Ticket::factory()->create([
            'created_by' => $user->id
        ]);

        $this->assertEquals($user->id, $ticket->creator->id);
    }

    /** @test */
    public function it_belongs_to_assignee()
    {
        $user = User::factory()->create();

        $ticket = Ticket::factory()->create([
            'assigned_to' => $user->id
        ]);

        $this->assertEquals($user->id, $ticket->assignee->id);
    }

    /** @test */
    public function it_belongs_to_status_and_priority()
    {
        $status = TicketStatus::factory()->create();
        $priority = TicketPriority::factory()->create();

        $ticket = Ticket::factory()->create([
            'status_id' => $status->id,
            'priority_id' => $priority->id
        ]);

        $this->assertEquals($status->id, $ticket->status->id);
        $this->assertEquals($priority->id, $ticket->priority->id);
    }

    /** @test */
    public function it_has_many_comments()
    {
        $ticket = Ticket::factory()->create();

        Comment::factory()->count(2)->create([
            'ticket_id' => $ticket->id
        ]);

        $this->assertCount(2, $ticket->comments);
    }

    /** @test */
    public function it_has_many_media()
    {
        $ticket = Ticket::factory()->create();

        Media::factory()->count(2)->create([
            'mediable_id' => $ticket->id,
            'mediable_type' => Ticket::class
        ]);

        $this->assertCount(2, $ticket->media);
    }

    /** @test */
    public function scope_filter_calls_filter_class()
    {
        $ticket = Ticket::factory()->create();

        $mockFilter = new class {
            public $called = false;

            public function apply($query)
            {
                $this->called = true;
                return $query;
            }
        };

        Ticket::filter($mockFilter)->get();

        $this->assertTrue($mockFilter->called);
    }
}
