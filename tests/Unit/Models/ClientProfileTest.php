<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\ClientProfile;
use App\Models\User;
use App\Models\Organization;
use App\Models\Media;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_client_profile()
    {
        $profile = ClientProfile::factory()->create();

        $this->assertDatabaseHas('client_profiles', [
            'id' => $profile->id
        ]);
    }

    /** @test */
    public function it_belongs_to_user()
    {
        $user = User::factory()->create();

        $profile = ClientProfile::factory()->create([
            'user_id' => $user->id
        ]);

        $this->assertEquals($user->id, $profile->user->id);
    }

    /** @test */
    public function it_belongs_to_organization()
    {
        $org = Organization::factory()->create();

        $profile = ClientProfile::factory()->create([
            'organization_id' => $org->id
        ]);

        $this->assertEquals($org->id, $profile->organization->id);
    }

    /** @test */
    public function it_has_many_media()
    {
        $profile = ClientProfile::factory()->create();

        Media::factory()->count(2)->create([
            'mediable_id' => $profile->id,
            'mediable_type' => ClientProfile::class
        ]);

        $this->assertCount(2, $profile->media);
    }
}
