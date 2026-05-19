<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class RoleHelper
{
    const ROLE_ADMIN = 'admin';
    const ROLE_MANAGER = 'manager';
    const ROLE_SUPERVISOR = 'supervisor';
    const ROLE_SALES = 'sales';

    /**
     * Dapatkan role user yang sedang login
     */
    public static function getCurrentRole(): string
    {
        return Auth::user()?->role ?? '';
    }

    /**
     * Check apakah user adalah admin
     */
    public static function isAdmin(): bool
    {
        return self::getCurrentRole() === self::ROLE_ADMIN;
    }

    /**
     * Check apakah user adalah manager
     */
    public static function isManager(): bool
    {
        return self::getCurrentRole() === self::ROLE_MANAGER;
    }

    /**
     * Check apakah user adalah supervisor
     */
    public static function isSupervisor(): bool
    {
        return self::getCurrentRole() === self::ROLE_SUPERVISOR;
    }

    /**
     * Check apakah user adalah sales
     */
    public static function isSales(): bool
    {
        return self::getCurrentRole() === self::ROLE_SALES;
    }

    /**
     * Check apakah user memiliki salah satu dari role yang diberikan
     */
    public static function hasRole(...$roles): bool
    {
        $currentRole = self::getCurrentRole();
        return in_array($currentRole, $roles, true);
    }

    /**
     * Check apakah user bisa mengakses menu
     */
    public static function canAccessMenu(string $menu): bool
    {
        $role = self::getCurrentRole();
        
        $permissions = [
            'dashboard' => [self::ROLE_ADMIN, self::ROLE_MANAGER, self::ROLE_SUPERVISOR, self::ROLE_SALES],
            'sale' => [self::ROLE_ADMIN, self::ROLE_MANAGER, self::ROLE_SUPERVISOR, self::ROLE_SALES],
            'customer' => [self::ROLE_ADMIN, self::ROLE_MANAGER, self::ROLE_SUPERVISOR, self::ROLE_SALES],
            'product' => [self::ROLE_ADMIN, self::ROLE_MANAGER],
            'supplier' => [self::ROLE_ADMIN, self::ROLE_MANAGER],
            'price' => [self::ROLE_ADMIN, self::ROLE_MANAGER],
            'salesman' => [self::ROLE_ADMIN, self::ROLE_MANAGER, self::ROLE_SUPERVISOR],
            'area' => [self::ROLE_ADMIN, self::ROLE_MANAGER],
            'cash-flow' => [self::ROLE_ADMIN, self::ROLE_MANAGER],
            'team' => [self::ROLE_ADMIN, self::ROLE_MANAGER, self::ROLE_SUPERVISOR],
            'report' => [self::ROLE_ADMIN, self::ROLE_MANAGER, self::ROLE_SUPERVISOR, self::ROLE_SALES],
            'user' => [self::ROLE_ADMIN],
        ];

        return in_array($role, $permissions[$menu] ?? [], true);
    }

    /**
     * Get menu yang bisa diakses user
     */
    public static function getAccessibleMenus(): array
    {
        $menus = [
            'dashboard',
            'sale',
            'customer',
            'product',
            'supplier',
            'price',
            'salesman',
            'area',
            'cash-flow',
            'team',
            'report',
            'user',
        ];

        return array_filter($menus, fn($menu) => self::canAccessMenu($menu));
    }

    /**
     * Get role badge color
     */
    public static function getRoleBadgeColor(string $role = null): string
    {
        $role = $role ?? self::getCurrentRole();
        
        $colors = [
            self::ROLE_ADMIN => 'bg-red-100 text-red-800 border-red-200',
            self::ROLE_MANAGER => 'bg-blue-100 text-blue-800 border-blue-200',
            self::ROLE_SUPERVISOR => 'bg-purple-100 text-purple-800 border-purple-200',
            self::ROLE_SALES => 'bg-green-100 text-green-800 border-green-200',
        ];

        return $colors[$role] ?? 'bg-slate-100 text-slate-800 border-slate-200';
    }

    /**
     * Get role label
     */
    public static function getRoleLabel(string $role = null): string
    {
        $role = $role ?? self::getCurrentRole();
        
        $labels = [
            self::ROLE_ADMIN => 'Administrator',
            self::ROLE_MANAGER => 'Manager',
            self::ROLE_SUPERVISOR => 'Supervisor',
            self::ROLE_SALES => 'Penjualan',
        ];

        return $labels[$role] ?? ucfirst($role);
    }

    /**
     * Get role icon
     */
    public static function getRoleIcon(string $role = null): string
    {
        $role = $role ?? self::getCurrentRole();
        
        $icons = [
            self::ROLE_ADMIN => 'shield-alert',
            self::ROLE_MANAGER => 'briefcase',
            self::ROLE_SUPERVISOR => 'users',
            self::ROLE_SALES => 'shopping-bag',
        ];

        return $icons[$role] ?? 'user';
    }
}
