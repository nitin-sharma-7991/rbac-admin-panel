<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Permission;

class PermissionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Admin');
    }

    public function view(User $user, Permission $permission): bool
    {
        return $user->hasRole('Admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Admin');
    }

    public function update(User $user, Permission $permission): bool
    {
        return $user->hasRole('Admin');
    }

    public function delete(User $user, Permission $permission): bool
    {
        return $user->hasRole('Admin');
    }
}
