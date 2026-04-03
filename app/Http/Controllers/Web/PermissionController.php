<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Permissions;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected Permissions $permissionService;

    public function __construct(Permissions $permissionService)
    {
        parent::__construct($permissionService);
        $this->permissionService = $permissionService;
    }
}
