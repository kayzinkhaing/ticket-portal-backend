<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\BaseRepository;
use App\Models\User;

class BaseRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected BaseRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new BaseRepository('User');
    }

    public function test_create_user()
    {
        $user = $this->repo->create([
            'first_name' => 'Test',
            'middle_name' => 'A',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => bcrypt('secret')
        ]);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
        $this->assertInstanceOf(User::class, $user);
    }

    public function test_find_by_id()
    {
        $user = User::factory()->create();

        $found = $this->repo->findById($user->id);

        $this->assertEquals($user->id, $found->id);
    }

    public function test_find_by_name()
    {
        $user = User::factory()->create([
            'first_name' => 'Unique',
            'middle_name' => 'Test',
            'last_name' => 'Name'
        ]);

        $fullName = "{$user->first_name} {$user->middle_name} {$user->last_name}";

        $found = $this->repo->findByName($fullName);

        $this->assertEquals($fullName, $found->name);
    }

    public function test_update()
    {
        $user = User::factory()->create([
            'first_name' => 'Old',
            'middle_name' => 'M',
            'last_name' => 'Name'
        ]);

        $updated = $this->repo->update($user->id, [
            'first_name' => 'New'
        ]);

        $this->assertEquals('New', $updated->first_name);
    }

    public function test_delete()
    {
        $user = User::factory()->create();

        $deleted = $this->repo->delete($user->id);

        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_all_by_role()
    {
        User::factory()->count(3)->create();

        $result = $this->repo->allByRole('guest');

        // paginator assertions
        $this->assertEquals(3, $result->total());
        $this->assertCount(3, $result->items());
    }
}
