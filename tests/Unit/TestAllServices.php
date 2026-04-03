<?php

namespace Tests\Unit\Http\Controllers\Web;

use App\Http\Controllers\Web\adminController;
use App\Http\Controllers\Web\RoleController;
use App\Http\Controllers\Web\PermissionController;
use App\Http\Controllers\Web\UserController;
use App\Http\Requests\permissionsRequest;
use App\Services\roles;
use App\Services\permissions;
use App\Services\users;
use App\Http\Requests\rolesRequest;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Repositories\roleRepository;
use App\Repositories\permissionRepository;
use App\Repositories\userRepository;
use App\Services\commonDropdown;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;
use App\Services\configFiles;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Collection;

class TestAllServices extends TestCase
{
    use RefreshDatabase;

    protected $service;
    protected $configService;
    protected $serviceRequest;
    protected $controller;
    protected $repository;

    /**
     * Data provider for service names and their respective controllers and services
     */
    public function serviceProvider()
    {
        return [
            // Service: Roles
            'roles' => [
                'service' => roles::class,
                'controller' => RoleController::class,
                'request' => rolesRequest::class,
                'repository' => roleRepository::class,
                'model' => Role::class,
            ],
            // Service: Permissions
            /* 'permissions' => [
                'service' => permissions::class,
                'controller' => PermissionController::class,
                'request' => permissionsRequest::class, // Assume permission request is similar, adjust as needed
                'repository' => permissionRepository::class,
                'model' => Permission::class,
            ],
            // Service: Users
             'users' => [
                'service' => users::class,
                'controller' => adminController::class,
                'request' => rolesRequest::class, // Assume user request is similar, adjust as needed
                'repository' => userRepository::class,
                'model' => User::class,
            ],*/
        ];
    }

    /**
     * Set up each test case, dynamically load service, repository, and controller
     */
    public function setUp(): void
    {
        parent::setUp();

        // Load all services from the data provider
        foreach ($this->serviceProvider() as $serviceName => $config) {
            // Create service and repository
            $this->service = Mockery::mock($config['service']);
            $this->repository = Mockery::mock($config['repository']);
            $this->serviceRequest = $config['request'];

            $this->app->instance($config['request'], $this->serviceRequest);

            // Initialize the controller with the mock service
            $commDropdown = Mockery::mock(commonDropdown::class);
            $this->controller = new $config['controller'](
                $this->service,
                false,
                $commDropdown,
                Mockery::mock(configFiles::class)
            );
        }
    }

    /**
     * Clean up Mockery mocks after each test
     */
    public function tearDown(): void
    {
        Mockery::close();  // Ensure Mockery is cleaned up after each test
        parent::tearDown();
    }

    // Helper method to call controller actions dynamically (store, update, delete)
    protected function callControllerMethod($method, $data = null, $id = null, $config = null)
    {
        if ($method == "index") {
            Route::shouldReceive('currentRouteName')->once()->andReturn('roles.index');
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
        } else {
            $currentRepository = new $config['repository']();
            $currentService = new $config['service']($currentRepository);
            $currentController = new $config['controller']($currentService);

            if ($data) {
                $mockRequest = Mockery::mock($this->serviceRequest);
                $mockRequest->shouldReceive('validated')->andReturn($data);
                $this->app->instance($this->serviceRequest, $mockRequest);
                return $currentController->$method($mockRequest, $id);
            }

            return $currentController->$method($id);
        }
    }

    // Utility method for common assertions
    protected function assertRedirectToIndex($response, $route = 'roles.index')
    {
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertEquals(route($route), $response->headers->get('Location'));
    }

    // Example test for CRUD operations dynamically
    public function test_CRUD_operations()
    {
        // Loop through the services and dynamically run tests for each
        foreach ($this->serviceProvider() as $serviceName => $config) {
            // Mock data for the current service
            $requestData = ['name' => $serviceName . ' Name'];
            // Test index method
            $response = $this->callControllerMethod('index', null, null, $config);

            // Test store method
            $response = $this->callControllerMethod('store', $requestData, null, $config);
            $this->assertRedirectToIndex($response, $serviceName . '.index');

            // Test update method
            /* $id = $config['model']::first()->id;
            $response = $this->callControllerMethod('update', $requestData, $id, $config);
            $this->assertRedirectToIndex($response, $serviceName . '.index');

            // Test delete method
            $id = $config['model']::first()->id;
            $response = $this->callControllerMethod('destroy', null, $id, $config);
            $this->assertRedirectToIndex($response, $serviceName . '.index'); */
        }
    }

    /**
     * Test if environment is testing (remains common)
     */
    public function test_environment_is_testing()
    {
        $this->assertTrue(app()->environment('testing'));
    }
}
