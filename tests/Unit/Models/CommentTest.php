<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Media;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_comment()
    {
        $comment = Comment::factory()->create([
            'content' => 'Test comment'
        ]);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'content' => 'Test comment'
        ]);
    }

    /** @test */
    public function it_belongs_to_ticket()
    {
        $ticket = Ticket::factory()->create();

        $comment = Comment::factory()->create([
            'ticket_id' => $ticket->id
        ]);

        $this->assertEquals($ticket->id, $comment->ticket->id);
    }

    /** @test */
    public function it_belongs_to_user()
    {
        $user = User::factory()->create();

        $comment = Comment::factory()->create([
            'user_id' => $user->id
        ]);

        $this->assertEquals($user->id, $comment->user->id);
    }

    /** @test */
    public function it_has_many_media()
    {
        $comment = Comment::factory()->create();

        Media::factory()->count(2)->create([
            'mediable_id' => $comment->id,
            'mediable_type' => Comment::class
        ]);

        $this->assertCount(2, $comment->media);
    }

    /** @test */
    public function it_can_have_parent_comment()
    {
        $parent = Comment::factory()->create();

        $child = Comment::factory()->create([
            'parent_id' => $parent->id
        ]);

        $this->assertEquals($parent->id, $child->parent->id);
    }

    /** @test */
    public function it_can_have_replies()
    {
        $parent = Comment::factory()->create();

        Comment::factory()->count(3)->create([
            'parent_id' => $parent->id
        ]);

        $this->assertCount(3, $parent->replies);
    }

    /** @test */
    public function it_can_create_nested_comments_structure()
    {
        $parent = Comment::factory()->create();

        $child = Comment::factory()->create([
            'parent_id' => $parent->id
        ]);

        $reply = Comment::factory()->create([
            'parent_id' => $child->id
        ]);

        $this->assertEquals($child->id, $reply->parent->id);
        $this->assertEquals($parent->id, $child->parent->id);
    }
}
