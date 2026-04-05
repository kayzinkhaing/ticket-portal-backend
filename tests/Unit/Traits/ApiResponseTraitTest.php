<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Models\User;

class ApiResponseTraitTest extends TestCase
{
    protected $dummy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dummy = new class {
            use \App\Traits\ApiResponse;

            // Required by generateResponse()
            public function getIndexRoute()
            {
                return 'home';
            }

            // Simulate request path
            public function setRequestPath(string $path)
            {
                request()->server->set('REQUEST_URI', '/' . ltrim($path, '/'));
            }

            // Wrappers
            public function callGenerateResponse(...$args)
            {
                return $this->generateResponse(...$args);
            }

            public function callApiErrorResponse(...$args)
            {
                return $this->apiErrorResponse(...$args);
            }

            public function callApiResponse(...$args)
            {
                return $this->apiResponse(...$args);
            }

            public function callIsApiRequest()
            {
                return $this->isApiRequest();
            }
        };
    }

    /** @test */
    public function it_returns_json_response_for_api_request()
    {
        $this->dummy->setRequestPath('api/test');

        $response = $this->dummy->callGenerateResponse(['foo' => 'bar']);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['data' => ['foo' => 'bar']], $response->getData(true));
    }

    /** @test */
    public function it_returns_redirect_response_for_web_request()
    {
        $this->dummy->setRequestPath('web/test');

        $response = $this->dummy->callGenerateResponse(['foo' => 'bar']);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('home'), $response->getTargetUrl());
    }

    /** @test */
    public function api_response_formats_model_correctly()
    {
        $user = User::factory()->create();

        $response = $this->dummy->callApiResponse($user);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $data = $response->getData(true);

        $this->assertArrayHasKey('data', $data);
        $this->assertEquals($user->id, $data['data']['id']);
    }

    /** @test */
    public function api_response_formats_collection_correctly()
    {
        $users = User::factory()->count(2)->create();

        $response = $this->dummy->callApiResponse($users);

        $data = $response->getData(true);

        $this->assertCount(2, $data['data']);
    }

    /** @test */
    public function api_error_response_returns_json_with_error()
    {
        $response = $this->dummy->callApiErrorResponse('Something went wrong', 422);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals(['error' => 'Something went wrong'], $response->getData(true));
    }

    /** @test */
    public function it_detects_api_request_correctly()
    {
        $this->dummy->setRequestPath('api/test');
        $this->assertTrue($this->dummy->callIsApiRequest());

        $this->dummy->setRequestPath('web/test');
        $this->assertFalse($this->dummy->callIsApiRequest());
    }
}
