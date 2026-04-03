<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Services\Organizations;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Organizations",
 *     description="API Endpoints for Organizations"
 * )
 */
class OrganizationController extends Controller
{
    protected $organizations;

    public function __construct(Organizations $organizations)
    {
        $this->organizations = $organizations;
        parent::__construct($this->organizations);
    }

    public function index()
    {
        $organizations = Organization::all();
        return response()->json(['data' => ['organizations' => $organizations]]);
    }


    public function store(Request $request)
    {
        return parent::store($request);
    }

    public function show($id)
    {
        // Find the organization and load its users
        $organization = Organization::with('users')->findOrFail($id);

        return response()->json([
            'data' => [
                'organization' => $organization,
                'clients' => $organization->users // This gets all related User models
            ]
        ]);
    }

}
