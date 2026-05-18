<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SalePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Sale $sale): bool
    {
        if (in_array($user->role, ['admin', 'manager'])) {
            return true;
        }

        if ($user->role === 'supervisor') {
            $supervisorSalesmanId = $user->salesman_id;
            $subordinateIds = \App\Models\Salesman::where('supervisor_id', $supervisorSalesmanId)->pluck('id')->toArray();
            $allowedIds = array_merge([$supervisorSalesmanId], $subordinateIds);
            return in_array($sale->salesman_id, $allowedIds);
        }

        return $user->role === 'sales' && $user->salesman_id === $sale->salesman_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Sale $sale): bool
    {
        if (in_array($user->role, ['admin', 'manager'])) {
            return true;
        }

        if ($user->role === 'sales' || $user->role === 'supervisor') {
            
            // Sales & Supervisor can ONLY update their OWN sales.
            // They cannot update subordinates' sales.
            if ($user->salesman_id !== $sale->salesman_id) {
                return false;
            }

            // Lock Logic: Check if current time is more than 24 hours from the time the record was created
            return $sale->created_at->diffInHours(now()) <= 24;
        }

        return false;
    }

    public function delete(User $user, Sale $sale): bool
    {
        return $this->update($user, $sale); // Same logic as update
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Sale $sale): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Sale $sale): bool
    {
        return false;
    }
}
