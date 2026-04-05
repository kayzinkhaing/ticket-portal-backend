<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_permission()
    {
        $permission = Permission::factory()->create();
        $this->assertDatabaseHas('permissions', [
            'id' => $permission->id,
            'name' => $permission->name,
        ]);
    }

    /** @test */
    public function it_can_belong_to_roles()
    {
        $permission = Permission::factory()->create();
        $role = Role::factory()->create();

        // attach role
        $permission->roles()->attach($role);

        $this->assertTrue($permission->roles->contains($role));
    }
}
