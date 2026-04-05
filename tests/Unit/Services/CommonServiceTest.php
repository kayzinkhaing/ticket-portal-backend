<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Common;
use App\Repositories\BaseRepository;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CommonServiceTest extends TestCase
{
    use RefreshDatabase;

    protected Common $service;
    protected BaseRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repo = new BaseRepository('User');

        // Anonymous concrete class
        $this->service = new class($this->repo) extends Common {};
    }

    public function test_create_user_through_service()
    {
        $user = $this->service->create([
            'first_name' => 'Service',
            'middle_name' => 'Test',
            'last_name' => 'User',
            'email' => 'service@example.com',
            'password' => bcrypt('secret')
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', [
            'email' => 'service@example.com'
        ]);
    }

    public function test_update_user_through_service()
    {
        $user = User::factory()->create([
            'first_name' => 'Old',
            'middle_name' => 'M',
            'last_name' => 'Name'
        ]);

        $updated = $this->service->update($user->id, [
            'first_name' => 'Updated'
        ]);

        $this->assertEquals('Updated', $updated->first_name);
    }

    public function test_delete_user_through_service()
    {
        $user = User::factory()->create();

        $deleted = $this->service->delete($user->id);

        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_all_by_role_through_service()
    {
        // simulate logged-in user
        $authUser = User::factory()->create();
        Auth::login($authUser);

        User::factory()->count(2)->create();

        $result = $this->service->allByRole();

        // paginator assertions
        $this->assertEquals(3, $result->total()); // includes auth user
        $this->assertCount(3, $result->items());
    }
}
