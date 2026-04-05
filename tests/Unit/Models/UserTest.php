<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_user_using_factory()
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $user->email,
        ]);
    }

    /** @test */
    public function it_can_assign_and_check_roles()
    {
        $user = User::factory()->create();
        Role::factory()->create(['name' => 'editor']);

        $user->assignRole('editor');

        $this->assertTrue($user->hasRole('editor'));
        $this->assertFalse($user->hasRole('admin'));
    }

    /** @test */
    public function it_returns_full_name_accessor()
    {
        $user = User::factory()->create([
            'first_name' => 'John',
            'middle_name' => 'K',
            'last_name' => 'Doe'
        ]);

        $this->assertEquals('John K Doe', $user->name);
    }

    /** @test */
    public function it_can_return_profile_image_url()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        Media::factory()->for($user, 'mediable')->create([
            'url' => 'avatars/test.png',
        ]);

        Storage::disk('public')->put('avatars/test.png', 'dummy');

        $this->assertStringContainsString(
            'avatars/test.png',
            $user->profileImage()->url
        );
    }

    /** @test */
    public function it_can_check_permissions_through_roles()
    {
        $user = User::factory()->create();

        $role = Role::factory()->create(['name' => 'editor']);
        $permission = \App\Models\Permission::factory()->create([
            'name' => 'edit-post'
        ]);

        // attach permission to role
        $role->permissions()->attach($permission);

        // assign role to user
        $user->roles()->attach($role);

        $this->assertTrue($user->hasPermissionTo('edit-post'));
    }

    /** @test */
    public function it_returns_guest_role_if_no_roles()
    {
        $user = User::factory()->create();

        $this->assertEquals('guest', $user->currentRole());
    }
}
