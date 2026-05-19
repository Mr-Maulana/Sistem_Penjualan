<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\Salesman;
use App\Models\Customer;

class RolePermissionHelper
{
    // ===== PERMISSION CONSTANTS =====
    const PERMISSION_VIEW_ONLY = 'view_only';
    const PERMISSION_CREATE = 'create';
    const PERMISSION_EDIT = 'edit';
    const PERMISSION_DELETE = 'delete';
    const PERMISSION_OWN_ONLY = 'own_only';
    const PERMISSION_TEAM = 'team';
    const PERMISSION_ALL = 'all';
    const PERMISSION_NONE = 'none';

    // ===== SUPPLIER PERMISSIONS =====
    /**
     * Cek akses ke Supplier
     * Sales, Supervisor, Manager: view only
     * Admin: full access
     */
    public static function canAccessSupplier(string $action = 'view'): bool
    {
        $role = Auth::user()->role;
        
        $permissions = [
            'admin' => ['view', 'create', 'edit', 'delete'],
            'manager' => ['view'],
            'supervisor' => ['view'],
            'sales' => ['view'],
        ];

        return in_array($action, $permissions[$role] ?? []);
    }

    // ===== CUSTOMER PERMISSIONS =====
    /**
     * Cek akses ke Customer
     * Sales: only customer dengan PIC diri sendiri
     * Supervisor: customer PIC diri + anggota
     * Manager: semua customer (create/edit/delete)
     * Admin: full access
     */
    public static function canAccessCustomer(string $action = 'view', ?Customer $customer = null): bool
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'manager') {
            return in_array($action, ['view', 'create', 'edit', 'delete']);
        }

        // Sales & Supervisor: hanya view dan create
        if (!in_array($action, ['view', 'create'])) {
            return false;
        }

        // Jika customer diberikan, cek ownership
        if ($customer) {
            return self::isCustomerAccessible($customer);
        }

        return true;
    }

    /**
     * Cek apakah customer accessible oleh user
     */
    public static function isCustomerAccessible(Customer $customer): bool
    {
        $user = Auth::user();

        // Admin & Manager bisa akses semua
        if (in_array($user->role, ['admin', 'manager'])) {
            return true;
        }

        // Sales: hanya customer yang PIC diri sendiri
        if ($user->role === 'sales') {
            return $customer->salesman_id === $user->salesman_id;
        }

        // Supervisor: customer PIC diri + anggota tim
        if ($user->role === 'supervisor') {
            $teamIds = self::getTeamMemberIds($user->salesman_id);
            return in_array($customer->salesman_id, $teamIds);
        }

        return false;
    }

    // ===== PRODUCT PERMISSIONS =====
    /**
     * Cek akses ke Product
     * Sales, Supervisor: view only (stock)
     * Manager: full access
     * Admin: full access
     */
    public static function canAccessProduct(string $action = 'view'): bool
    {
        $role = Auth::user()->role;

        if ($role === 'admin') {
            return true;
        }

        if ($role === 'manager') {
            return in_array($action, ['view', 'create', 'edit', 'delete']);
        }

        // Sales & Supervisor: view only
        return $action === 'view';
    }

    // ===== PRICE PERMISSIONS =====
    /**
     * Cek akses ke Price
     * Sales, Supervisor: view only
     * Manager: full access
     * Admin: full access
     */
    public static function canAccessPrice(string $action = 'view'): bool
    {
        $role = Auth::user()->role;

        if ($role === 'admin') {
            return true;
        }

        if ($role === 'manager') {
            return in_array($action, ['view', 'create', 'edit', 'delete']);
        }

        // Sales & Supervisor: view only
        return $action === 'view';
    }

    // ===== SALE PERMISSIONS =====
    /**
     * Cek akses ke Sale
     * Sales: milik sendiri
     * Supervisor: milik sendiri + anggota tim
     * Manager: semua (limited edit/delete)
     * Admin: full access
     */
    public static function canAccessSale(string $action = 'view', $sale = null): bool
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'manager') {
            return in_array($action, ['view', 'create', 'edit', 'delete']);
        }

        if (!in_array($action, ['view', 'create', 'edit', 'delete'])) {
            return false;
        }

        // Sales & Supervisor: hanya milik sendiri/tim
        if ($sale) {
            return self::isSaleAccessible($sale);
        }

        return true;
    }

    /**
     * Cek apakah sale accessible
     */
    public static function isSaleAccessible($sale): bool
    {
        $user = Auth::user();

        if (in_array($user->role, ['admin', 'manager'])) {
            return true;
        }

        if ($user->role === 'sales') {
            return $sale->salesman_id === $user->salesman_id;
        }

        if ($user->role === 'supervisor') {
            $teamIds = self::getTeamMemberIds($user->salesman_id);
            return in_array($sale->salesman_id, $teamIds);
        }

        return false;
    }

    // ===== CASHFLOW / TRANSACTION PERMISSIONS =====
    /**
     * Cek akses ke CashFlow/Transaksi
     * Sales, Supervisor: view only (milik sendiri/tim)
     * Manager: view (semua)
     * Admin: full access
     */
    public static function canAccessCashFlow(string $action = 'view', $cashFlow = null): bool
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return true;
        }

        // View only untuk sales, supervisor, manager
        if (!in_array($action, ['view'])) {
            return $user->role === 'admin';
        }

        if ($user->role === 'manager') {
            return true;
        }

        // Sales & Supervisor: filter by ownership
        if ($cashFlow) {
            if ($user->role === 'sales') {
                return $cashFlow->sale?->salesman_id === $user->salesman_id;
            }

            if ($user->role === 'supervisor') {
                $teamIds = self::getTeamMemberIds($user->salesman_id);
                return in_array($cashFlow->sale?->salesman_id, $teamIds);
            }
        }

        return true;
    }

    // ===== SALESMAN PERMISSIONS =====
    /**
     * Cek akses ke Salesman (struktur team)
     */
    public static function canAccessSalesman(string $action = 'view'): bool
    {
        $role = Auth::user()->role;

        if ($role === 'admin') {
            return true;
        }

        if ($role === 'manager') {
            return in_array($action, ['view', 'create', 'edit', 'delete']);
        }

        // Sales & Supervisor: view only
        return $action === 'view';
    }

    // ===== ASSESSMENT PERMISSIONS =====
    /**
     * Cek akses ke Assessment
     * Sales: milik sendiri
     * Supervisor: milik sendiri + anggota
     * Manager: full access
     * Admin: full access
     */
    public static function canAccessAssessment(string $action = 'view', $assessment = null): bool
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'manager') {
            return in_array($action, ['view', 'create', 'edit', 'delete']);
        }

        if (!in_array($action, ['view', 'create', 'edit', 'delete'])) {
            return false;
        }

        if ($assessment) {
            if ($user->role === 'sales') {
                return $assessment->salesman_id === $user->salesman_id;
            }

            if ($user->role === 'supervisor') {
                $teamIds = self::getTeamMemberIds($user->salesman_id);
                return in_array($assessment->salesman_id, $teamIds);
            }
        }

        return true;
    }

    // ===== REPORT PERMISSIONS =====
    /**
     * Cek akses ke Report
     * Sales: laporan sendiri
     * Supervisor: laporan sendiri + tim
     * Manager: laporan semua
     * Admin: laporan semua
     */
    public static function canAccessReport(string $type = 'sales', $ownerId = null): bool
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'manager') {
            return true;
        }

        if ($user->role === 'sales') {
            return $ownerId === null || $ownerId === $user->salesman_id;
        }

        if ($user->role === 'supervisor') {
            if ($ownerId === null || $ownerId === $user->salesman_id) {
                return true;
            }
            $teamIds = self::getTeamMemberIds($user->salesman_id);
            return in_array($ownerId, $teamIds);
        }

        return false;
    }

    // ===== HELPER METHODS =====

    /**
     * Get team member IDs (includes self)
     */
    public static function getTeamMemberIds($supervisorId): array
    {
        $subordinates = Salesman::where('supervisor_id', $supervisorId)
            ->pluck('id')
            ->toArray();
        return array_merge([$supervisorId], $subordinates);
    }

    /**
     * Get accessible salesman IDs for queries
     */
    public static function getAccessibleSalesmanIds(): ?array
    {
        $user = Auth::user();

        if ($user->role === 'admin' || $user->role === 'manager') {
            return null; // null = all
        }

        if ($user->role === 'supervisor') {
            return self::getTeamMemberIds($user->salesman_id);
        }

        if ($user->role === 'sales') {
            return [$user->salesman_id];
        }

        return [];
    }

    /**
     * Get permission level untuk menu
     */
    public static function getMenuPermission(string $menu): ?string
    {
        $user = Auth::user();
        $role = $user->role;

        $permissions = [
            'admin' => [
                'supplier' => 'full',
                'customer' => 'full',
                'product' => 'full',
                'price' => 'full',
                'sale' => 'full',
                'cashflow' => 'full',
                'salesman' => 'full',
                'assessment' => 'full',
                'report' => 'full',
            ],
            'manager' => [
                'supplier' => 'view',
                'customer' => 'full',
                'product' => 'full',
                'price' => 'full',
                'sale' => 'full',
                'cashflow' => 'view',
                'salesman' => 'full',
                'assessment' => 'full',
                'report' => 'full',
            ],
            'supervisor' => [
                'supplier' => 'view',
                'customer' => 'team',
                'product' => 'view',
                'price' => 'view',
                'sale' => 'team',
                'cashflow' => 'team_view',
                'salesman' => 'view',
                'assessment' => 'team',
                'report' => 'team',
            ],
            'sales' => [
                'supplier' => 'view',
                'customer' => 'own',
                'product' => 'view',
                'price' => 'view',
                'sale' => 'own',
                'cashflow' => 'own_view',
                'salesman' => 'view',
                'assessment' => 'own',
                'report' => 'own',
            ],
        ];

        return $permissions[$role][$menu] ?? null;
    }

    /**
     * Check if user can view menu
     */
    public static function canViewMenu(string $menu): bool
    {
        return self::getMenuPermission($menu) !== null;
    }

    /**
     * Get accessible customer IDs for queries
     */
    public static function getAccessibleCustomerIds(): ?array
    {
        $user = Auth::user();

        if ($user->role === 'admin' || $user->role === 'manager') {
            return null;
        }

        if ($user->role === 'supervisor') {
            $teamIds = self::getTeamMemberIds($user->salesman_id);
            return Customer::whereIn('salesman_id', $teamIds)->pluck('id')->toArray();
        }

        if ($user->role === 'sales') {
            return Customer::where('salesman_id', $user->salesman_id)->pluck('id')->toArray();
        }

        return [];
    }
}
