<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Role;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Admin');
    }

    public function view(User $user, Role $role): bool
    {
        return $user->hasRole('Admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Admin');
    }

    public function update(User $user, Role $role): bool
    {
        return $user->hasRole('Admin');
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->hasRole('Admin');
    }

    public function assignPermissions(User $user): bool
    {
        return $user->hasRole('Admin');
    }
}
