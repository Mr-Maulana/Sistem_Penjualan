<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Product $product): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function update(User $user, Product $product): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function delete(User $user, Product $product): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function restore(User $user, Product $product): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function forceDelete(User $user, Product $product): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }
}
