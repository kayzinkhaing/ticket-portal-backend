<?php

namespace Tests\Unit\Http\Controllers\Web;

use App\Http\Controllers\Web\RoleController;
use App\Services\roles;
use App\Http\Requests\rolesRequest;
use App\Models\Role;
use App\Repositories\roleRepository;
use App\Services\commonDropdown;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;
use App\Services\configFiles;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Collection;

class RoleControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @var \App\Services\roles|Mockery\MockInterface */
    protected $roleService;

    /** @var \App\Services\configFiles|Mockery\MockInterface */
    protected $configService;

    /** @var \App\Http\Requests\roleRequest|Mockery\MockInterface */
    protected $roleRequest;

    protected $controller;
    protected $roleRepository;

    public function setUp(): void
    {
        parent::setUp();

        // Create the roles
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Editor']);

        // Mock the role service
        $this->roleService = Mockery::mock(roles::class);

        // Mock the config service
        $this->configService = Mockery::mock(configFiles::class);

        // Mock the dynamic roleRequest
        $this->roleRequest = Mockery::mock(rolesRequest::class);

        // We want the validated() method to return the correct data
        $this->roleRequest->shouldReceive('validated')->andReturn([
            'name' => 'Editor' // The expected data passed to the create method
        ]);

        // Mock the request resolution
        $this->app->instance(rolesRequest::class, $this->roleRequest);

        // Create the controller, passing all the required dependencies
        $commDropdown = Mockery::mock(commonDropdown::class); // Mock the dropdown dependency if needed

        $this->controller = new RoleController(
            $this->roleService,
            false, // Passing `false` for $admin (for example)
            $commDropdown, // Passing the mocked $commDropdown
            $this->configService // Passing the mocked $configService
        );
    }

    public function test_environment_is_testing()
    {
        $this->assertTrue(app()->environment('testing'));
    }


    public function test_index()
    {

        // Simulate the route being used in the controller
        Route::shouldReceive('currentRouteName')->once()->andReturn('roles.index');
        // Mock the roleRepository class
        // Mock the roleRepository class
        $roleRepository = Mockery::mock(roleRepository::class);
        // Mock the 'all' method to return an Eloquent Collection
        $roleRepository->shouldReceive('all')->once()->andReturn(new Collection([
            new Role(['id' => 1, 'name' => 'Admin']),
            new Role(['id' => 2, 'name' => 'User']),
        ]));
        // Now pass the mock repository into the controller
        $roleService = new roles($roleRepository);
        $controller = new RoleController($roleService);

        // Call the index method on the controller
        $response = $controller->index();

        // Assert that the response is a view
        $this->assertInstanceOf(\Illuminate\View\View::class, $response);

        // Manually check the view data
        $data = $response->getData();
        // Create the expected roles collection using Eloquent model instances
        $expectedRoles = new Collection([
            new Role(['id' => 1, 'name' => 'Admin']),
            new Role(['id' => 2, 'name' => 'User']),
        ]);

        // Assert that the 'roles' collection objects are the same (comparison of collections)
        $this->assertEquals($expectedRoles, $data['roles']);
    }



    /**
     * Test store method in RoleController
     */
    public function test_store()
    {
        // Create a mock for the role repository
        $roleRepository = new roleRepository();

        // Create a real instance of the roles service but partially mock it
        $roleService = new roles($roleRepository);

        // Now pass the mock service into the controller
        $controller = new RoleController($roleService);

        // Simulate request data (e.g., from a form submission)
        $requestData = [
            'name' => 'User',
        ];

        // Create a mock for the request class that will simulate the validated data
        $mockRequest = Mockery::mock(RolesRequest::class);
        $mockRequest->shouldReceive('validated')->andReturn($requestData);

        // Bind the mock request class into the application container
        $this->app->instance(RolesRequest::class, $mockRequest);

        // Call the store method on the controller, passing the mock request
        $response = $controller->store($mockRequest);

        // Assert that the response is a redirect (you may adjust the route name as needed)
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertEquals(route('roles.index'), $response->headers->get('Location'));  // Check the redirect URL
    }

    public function test_update()
    {
        // Create a mock for the role repository
        $roleRepository = new roleRepository();

        // Create a real instance of the roles service but partially mock it
        $roleService = new roles($roleRepository);

        // Now pass the mock service into the controller
        $controller = new RoleController($roleService);
        // Simulate the updated request data
        $requestData = [
            'name' => 'Updated Role',
        ];

        // Create a mock for the request class that will simulate the validated data
        $mockRequest = Mockery::mock(RolesRequest::class);
        $mockRequest->shouldReceive('validated')->andReturn($requestData);

        // Bind the mock request class into the application container
        $this->app->instance(RolesRequest::class, $mockRequest);

        $id = Role::first()->id;
        $response = $controller->update($mockRequest, $id);

        // Assert that the response is a redirect
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);

        // Assert that the redirect URL is correct (usually `roles.index`)
        $this->assertEquals(route('roles.index'), $response->headers->get('Location'));
    }

    public function test_delete()
    {
        // Create a mock for the role repository
        $roleRepository = new roleRepository();
        // Create a real instance of the roles service but partially mock it
        $roleService = new roles($roleRepository);
        // Now pass the mock service into the controller
        $controller = new RoleController($roleService);
        // Call the delete method on the controller, passing a role ID (e.g., 1)
        $id = Role::first()->id;
        $response = $controller->destroy($id);
        // Assert that the response is a redirect
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);

        // Assert that the redirect URL is correct (usually `roles.index`)
        $this->assertEquals(route('roles.index'), $response->headers->get('Location'));
    }
}
