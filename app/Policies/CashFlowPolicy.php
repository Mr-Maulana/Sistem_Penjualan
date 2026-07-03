<?php

namespace App\Policies;

use App\Models\CashFlow;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CashFlowPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager', 'sales']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CashFlow $cashFlow): bool
    {
        return in_array($user->role, ['admin', 'manager', 'sales']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * CashFlow yang berasal dari transaksi lain (auto-generated)
     * akan dikunci sehingga tidak bisa diedit manual.
     */
    public function update(User $user, CashFlow $cashFlow): bool
    {
        // Jika terhubung ke modul sumber (contoh: Sale), maka terkunci.
        if (!empty($cashFlow->reference_type) && !empty($cashFlow->reference_id)) {
            return false;
        }

        return in_array($user->role, ['admin', 'manager']);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * CashFlow auto-generated (terkunci) tidak boleh dihapus di sini.
     * Penghapusan hanya boleh terjadi melalui modul sumber.
     */
    public function delete(User $user, CashFlow $cashFlow): bool
    {
        // Jika terhubung ke modul sumber, maka terkunci.
        if (!empty($cashFlow->reference_type) && !empty($cashFlow->reference_id)) {
            return false;
        }

        return in_array($user->role, ['admin', 'manager']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CashFlow $cashFlow): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CashFlow $cashFlow): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }
}
