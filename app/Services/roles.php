<?php

namespace App\Services;

use App\Models\Role;
use App\Contracts\roleInterface;
use App\Models\User;
use App\Repositories\roleRepository;

use Illuminate\Support\Collection;

class roles extends common
{
    protected $roleRepository;

    public function __construct(roleRepository $roleRepository)
    {
        parent::__construct($roleRepository);
        $this->roleRepository = $roleRepository;
    }

    // Role-specific logic, not CRUD
    public function assignRoleToUser($userId, $roleId)
    {
        $user = User::find($userId);
        $role = Role::find($roleId);

        if ($user && $role) {
            $user->roles()->attach($role);
        }

        return $user;
    }


    public function getRoleById($id)
    {
        return $this->roleRepository->getRoleById($id);
    }
}
