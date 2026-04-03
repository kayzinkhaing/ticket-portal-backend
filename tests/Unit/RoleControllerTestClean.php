<?php

namespace Tests\Unit\Http\Controllers\Web;

use App\Http\Controllers\Web\RoleController;
use App\Services\roles;
use App\Http\Requests\rolesRequest;
use App\Models\Role;
use App\Models\User;
use App\Repositories\roleRepository;
use App\Services\commonDropdown;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;
use App\Services\configFiles;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Collection;

class RoleControllerTestClean extends TestCase
{
    use RefreshDatabase;

    protected $roleService;
    protected $configService;
    protected $roleRequest;
    protected $controller;

    public function setUp(): void
    {
        parent::setUp();

        // Create the roles (reuse in multiple tests)
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Editor']);

        // Common mock services
        $this->roleService = Mockery::mock(roles::class);
        $this->configService = Mockery::mock(configFiles::class);
        $this->roleRequest = Mockery::mock(rolesRequest::class);

        $this->roleRequest->shouldReceive('validated')->andReturn([
            'name' => 'Editor' // The expected data passed to the create method
        ]);
        $this->app->instance(rolesRequest::class, $this->roleRequest);

        $commDropdown = Mockery::mock(commonDropdown::class); // Mock the dropdown dependency

        // Initialize controller with mocks
        $this->controller = new RoleController(
            $this->roleService,
            false,
            $commDropdown,
            $this->configService
        );
    }

    // Utility method for common assertions
    protected function assertRedirectToRolesIndex($response)
    {
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertEquals(route('roles.index'), $response->headers->get('Location'));
    }

    // Helper method to call store, update, or delete
    protected function callControllerMethod($method, $data = null, $id = null)
    {
        $roleRepository = new roleRepository();
        $roleService = new roles($roleRepository);
        $controller = new RoleController($roleService);

        if ($data) {
            $mockRequest = Mockery::mock(RolesRequest::class);
            $mockRequest->shouldReceive('validated')->andReturn($data);
            $this->app->instance(RolesRequest::class, $mockRequest);
            return $controller->$method($mockRequest, $id);
        }

        return $controller->$method($id);
    }

    public function test_environment_is_testing()
    {
        $this->assertTrue(app()->environment('testing'));
    }

    public function test_index()
    {
        Route::shouldReceive('currentRouteName')->once()->andReturn('roles.index');
        // Mock the repository
        $roleRepository = Mockery::mock(roleRepository::class);
        $roleRepository->shouldReceive('all')->once()->andReturn(new Collection([
            new Role(['id' => 1, 'name' => 'Admin']),
            new Role(['id' => 2, 'name' => 'User']),
        ]));

        $roleService = new roles($roleRepository);
        $controller = new RoleController($roleService);

        $response = $controller->index();

        $this->assertInstanceOf(\Illuminate\View\View::class, $response);

        $data = $response->getData();
        $expectedRoles = new Collection([
            new Role(['id' => 1, 'name' => 'Admin']),
            new Role(['id' => 2, 'name' => 'User']),
        ]);

        $this->assertEquals($expectedRoles, $data['roles']);
    }

    // Refactored store test using helper method
    public function test_store()
    {
        $requestData = ['name' => 'User'];
        $response = $this->callControllerMethod('store', $requestData);

        $this->assertRedirectToRolesIndex($response);
    }

    // Refactored update test using helper method
    public function test_update()
    {
        $requestData = ['name' => 'Updated Role'];
        $id = Role::first()->id;
        $response = $this->callControllerMethod('update', $requestData, $id);

        $this->assertRedirectToRolesIndex($response);
    }

    // Refactored delete test using helper method
    public function test_delete()
    {
        $id = Role::first()->id;
        $response = $this->callControllerMethod('destroy', null, $id);
        $this->assertRedirectToRolesIndex($response);
    }
}
