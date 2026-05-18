<?php

namespace App\Policies;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SupplierPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Supplier $supplier): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function update(User $user, Supplier $supplier): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function delete(User $user, Supplier $supplier): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function restore(User $user, Supplier $supplier): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function forceDelete(User $user, Supplier $supplier): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }
}
