<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CustomerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Customer $customer): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Customer $customer): bool
    {
        if (in_array($user->role, ['admin', 'manager'])) {
            return true;
        }

        if ($user->role === 'sales' || $user->role === 'supervisor') {
            // Can only update their OWN customer
            return $user->salesman_id === $customer->salesman_id;
        }

        return false;
    }

    public function delete(User $user, Customer $customer): bool
    {
        return $this->update($user, $customer);
    }

    public function restore(User $user, Customer $customer): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function forceDelete(User $user, Customer $customer): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }
}
