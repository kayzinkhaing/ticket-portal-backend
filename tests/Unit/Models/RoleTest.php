<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_role()
    {
        $role = Role::factory()->create();

        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => $role->name,
        ]);
    }

    /** @test */
    public function it_can_have_users()
    {
        $role = Role::factory()->create();
        $user = User::factory()->create();

        // Attach the user to the role
        $role->users()->attach($user);

        // Reload relationship
        $role->load('users');

        $this->assertTrue($role->users->contains($user));
        $this->assertEquals($user->id, $role->users->first()->id);
    }

    /** @test */
    public function it_can_have_permissions()
    {
        $role = Role::factory()->create();
        $permission = Permission::factory()->create();

        // Attach the permission to the role
        $role->permissions()->attach($permission);

        // Reload relationship
        $role->load('permissions');

        $this->assertTrue($role->permissions->contains($permission));
        $this->assertEquals($permission->id, $role->permissions->first()->id);
    }

    /** @test */
    public function it_can_have_users_and_permissions_together()
    {
        $role = Role::factory()->create();
        $user = User::factory()->create();
        $permission = Permission::factory()->create();

        $role->users()->attach($user);
        $role->permissions()->attach($permission);

        $role->load(['users', 'permissions']);

        $this->assertTrue($role->users->contains($user));
        $this->assertTrue($role->permissions->contains($permission));
    }
}
