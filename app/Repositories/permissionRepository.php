<?php

namespace App\Repositories;

use App\Contracts\permissionInterface;
use App\Models\Role;
use Illuminate\Support\Collection;

class permissionRepository extends baseRepository implements permissionInterface
{
    public function __construct()
    {
        parent::__construct(class_basename("Permission"));
    }
    public function getPermissionbyId($permissionId)
    {
        return $this->currentModel->find($permissionId);
    }
}
