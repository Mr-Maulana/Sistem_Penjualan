<?php

namespace App\Policies;

use App\Models\Salesman;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SalesmanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Salesman $salesman): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function update(User $user, Salesman $salesman): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function delete(User $user, Salesman $salesman): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function restore(User $user, Salesman $salesman): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function forceDelete(User $user, Salesman $salesman): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }
}
