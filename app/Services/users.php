<?php

namespace App\Services;

use App\Models\Role;
use App\Contracts\roleInterface;
use App\Models\User;
use App\Repositories\userRepository;

use Illuminate\Support\Collection;

class users extends common
{
    protected $userRepository;

    public function __construct(userRepository $userRepository)
    {
        parent::__construct($userRepository);
        $this->userRepository = $userRepository;
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


    public function getByEmail($email)
    {
        return $this->userRepository->getByEmail($email);
    }
}
