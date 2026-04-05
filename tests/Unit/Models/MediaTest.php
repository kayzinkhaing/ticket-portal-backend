<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MediaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_it_can_create_media()
    {
        $user = User::factory()->create();

        $media = Media::factory()->create([
            'mediable_id' => $user->id,
            'mediable_type' => User::class,
        ]);

        $this->assertInstanceOf(User::class, $media->mediable);
    }


    /** @test */
    public function it_can_belong_to_a_mediable()
    {
        $user = User::factory()->create();
        $media = Media::factory()->create(['mediable_id' => $user->id, 'mediable_type' => User::class]);

        $this->assertEquals($user->id, $media->mediable->id);
    }
}
