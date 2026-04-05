<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Traits\serviceHelper;
use App\Http\Requests\CategoriesRequest;
use Illuminate\Support\Facades\Route;

class DummyService
{
    use serviceHelper;

    public $service;
    public $bladeFolder = 'Categories';

    public function callFetchResource($id) { return $this->fetchResource($id); }
    public function callCreateResource(array $data) { return $this->createResource($data); }
    public function callUpdateResource($model, array $data) { return $this->updateResource($model, $data); }
    public function callDestroyResource($id) { return $this->destroyResource($id); }
    public function callGetValidatedData(Request $request) { return $this->getValidatedData($request); }
    public function callResolveRequestClass() { return $this->resolveRequestClass(); }
    public function callGetResource() { return $this->getResource(); }
}

class ServiceHelperTraitTest extends TestCase
{
    protected $dummy;
    protected $mockService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockService = new class {
            public $deleted = null;
            public $created = null;
            public $updated = null;
            public $foundId = null;

            public function findById($id)
            {
                $this->foundId = $id;
                return (object)['id' => $id];
            }

            public function create(array $data)
            {
                $this->created = $data;
                return $data;
            }

            public function update($id, array $data)
            {
                $this->updated = ['id' => $id, 'data' => $data];
                return $this->updated;
            }

            public function delete($id)
            {
                $this->deleted = $id;
                return true;
            }
        };

        $this->dummy = new DummyService();
        $this->dummy->service = $this->mockService;
    }

    /** @test */
    public function fetch_resource_returns_model()
    {
        $result = $this->dummy->callFetchResource(5);

        $this->assertEquals(5, $result->id);
        $this->assertEquals(5, $this->mockService->foundId);
    }

    /** @test */
    public function create_resource_calls_service_create()
    {
        $data = ['name' => 'Test'];

        $result = $this->dummy->callCreateResource($data);

        $this->assertEquals($data, $this->mockService->created);
        $this->assertEquals($data, $result);
    }

    /** @test */
    public function update_resource_calls_service_update()
    {
        $model = (object)['id' => 7];
        $data = ['name' => 'Updated'];

        $result = $this->dummy->callUpdateResource($model, $data);

        $this->assertEquals(['id' => 7, 'data' => $data], $this->mockService->updated);
        $this->assertEquals(['id' => 7, 'data' => $data], $result);
    }

    /** @test */
    public function destroy_resource_calls_service_delete()
    {
        $this->dummy->callDestroyResource(9);

        $this->assertEquals(9, $this->mockService->deleted);
    }

    /** @test */
    public function resolve_request_class_returns_class_name()
    {
        $result = $this->dummy->callResolveRequestClass();

        $this->assertEquals(CategoriesRequest::class, $result);
    }

    /** @test */
    public function resolve_request_class_returns_null_if_missing()
    {
        $this->dummy->bladeFolder = 'NonExistent';

        $this->assertNull($this->dummy->callResolveRequestClass());
    }

    /** @test */
    public function get_validated_data_throws_exception_if_request_class_missing()
    {
        $this->dummy->bladeFolder = 'NonExistent';

        $this->expectException(\LogicException::class);

        $this->dummy->callGetValidatedData(new Request());
    }

    /** @test */
    public function get_resource_returns_correct_route_segment()
    {
        Route::get('/test', fn () => 'ok')->name('admin.categories.index');

        $this->get('/test');

        $result = $this->dummy->callGetResource();

        $this->assertEquals('categories', $result);
    }
}
