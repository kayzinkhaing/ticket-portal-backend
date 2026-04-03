<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\Role;
use App\Repositories\PermissionRepository;
use Illuminate\Support\Collection;

class Permissions extends Common
{
    protected PermissionRepository $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        parent::__construct($permissionRepository);
        $this->permissionRepository = $permissionRepository;
    }

    // Permission-specific logic
    public function assignPermissionToRole($roleId, $permissionId)
    {
        $role = Role::find($roleId);
        $permission = Permission::find($permissionId);

        if ($role && $permission) {
            $role->permissions()->attach($permission);
        }

        return $role;
    }

    public function getPermissionById($id)
    {
        return $this->permissionRepository->findById($id);
    }
}
