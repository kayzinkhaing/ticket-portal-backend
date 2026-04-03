<?php

namespace Tests\Unit\Http\Controllers\Web;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;
    protected $adminRole;
    protected $editorRole;
    protected $permission1;
    protected $permission2;

    public function setUp(): void
    {
        parent::setUp();

        // Create roles
        $this->adminRole = Role::create(['name' => 'Admin']);
        $this->editorRole = Role::create(['name' => 'Editor']);

        // Create permissions
        $this->permission1 = Permission::create(['name' => 'view_dashboard']);
        $this->permission2 = Permission::create(['name' => 'edit_user']);

        // Create admin user
        $this->admin = User::factory()->create([
            'name' => 'Admin',
            // Assuming 'is_admin' attribute determines admin
        ]);

        // Create regular user
        $this->user = User::factory()->create([
            'name' => 'User',
        ]);

        // Assign some roles and permissions to regular user
        $this->user->roles()->attach($this->editorRole);
        $this->user->permissions()->attach($this->permission1);

        // Assign the admin role to the admin user
        $this->admin->roles()->attach($this->adminRole);

        // Log in as admin user
        $this->actingAs($this->admin);
    }

    public function test_admin_can_assign_roles_and_permissions_to_user()
    {
        // Data to be assigned to the user
        $data = [
            'user_id' => $this->user->id,
            'roles' => [$this->adminRole->id], // Assign admin role
            'permissions' => [$this->permission2->id] // Assign "edit_user" permission
        ];

        // Make the POST request to the assign route
        $response = $this->post(route('admin.assign'), $data);

        // Assert that the response is a redirect (302) back to the admin index
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.index'));

        // Reload the user from the database to check if roles and permissions were assigned
        $user = $this->user->fresh();

        // Assert the user now has the new role and permission
        $this->assertTrue($user->roles->contains($this->adminRole));
        $this->assertTrue($user->permissions->contains($this->permission2));

        // Optionally, you can assert if the session contains a success message
        //$response->assertSessionHas('success', 'Updated successfully');
    }

    public function test_admin_user_is_denied_access_if_not_admin()
    {
        // Log in as a non-admin user
        $nonAdminUser = User::factory()->create(['name' => "User"]);
        $this->actingAs($nonAdminUser);

        // Try to access the admin route
        $response = $this->post(route('admin.assign'), [
            'user_id' => $this->user->id,
            'roles' => [$this->adminRole->id],
            'permissions' => [$this->permission2->id]
        ]);

        // Assert that the response is a redirect (302) with a redirect to login or another non-admin route
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));  // Adjust this based on your middleware setup
    }
}
