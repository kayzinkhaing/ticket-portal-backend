<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\roles;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $roleService;
    protected $requestType;
    public function __construct(roles $roleService)
    {
        parent::__construct($roleService);
        $this->roleService = $roleService;
    }
}
