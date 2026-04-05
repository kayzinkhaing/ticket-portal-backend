<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Organization;
use App\Models\ClientProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrganizationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_organization()
    {
        $org = Organization::factory()->create([
            'name' => 'Test Org'
        ]);

        $this->assertDatabaseHas('organizations', [
            'id' => $org->id,
            'name' => 'Test Org'
        ]);
    }

    /** @test */
    public function it_has_many_client_profiles()
    {
        $org = Organization::factory()->create();

        ClientProfile::factory()->count(3)->create([
            'organization_id' => $org->id
        ]);

        $this->assertCount(3, $org->clientProfiles);
    }

    /** @test */
    public function it_has_many_users_through_client_profiles()
    {
        $org = Organization::factory()->create();

        $users = User::factory()->count(2)->create();

        foreach ($users as $user) {
            ClientProfile::factory()->create([
                'user_id' => $user->id,
                'organization_id' => $org->id
            ]);
        }

        $this->assertCount(2, $org->users);
        $this->assertInstanceOf(User::class, $org->users->first());
    }
}
