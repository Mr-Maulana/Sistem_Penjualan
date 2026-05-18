<?php

namespace App\Policies;

use App\Models\Price;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PricePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Price $price): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function update(User $user, Price $price): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function delete(User $user, Price $price): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function restore(User $user, Price $price): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function forceDelete(User $user, Price $price): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }
}
