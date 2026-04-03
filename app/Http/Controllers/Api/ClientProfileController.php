<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ClientProfiles;

class ClientProfileController extends Controller
{
    protected $organizations;

    public function __construct(ClientProfiles $organizations)
    {
        $this->organizations = $organizations;

        // Parent Controller Constructor
        parent::__construct(
            $this->organizations
        );
    }
}
