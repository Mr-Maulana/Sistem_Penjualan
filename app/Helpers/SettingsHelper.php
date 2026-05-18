<?php

namespace App\Helpers;

class SettingsHelper
{
    public static function get($key, $default = null)
    {
        $path = storage_path('app/settings.json');
        if (!file_exists($path)) {
            return $default;
        }
        $settings = json_decode(file_get_contents($path), true);
        return $settings[$key] ?? $default;
    }

    public static function set($key, $value)
    {
        $path = storage_path('app/settings.json');
        $settings = [];
        if (file_exists($path)) {
            $settings = json_decode(file_get_contents($path), true) ?? [];
        }
        $settings[$key] = $value;
        file_put_contents($path, json_encode($settings, JSON_PRETTY_PRINT));
    }

    public static function all()
    {
        $path = storage_path('app/settings.json');
        if (!file_exists($path)) {
            return [];
        }
        return json_decode(file_get_contents($path), true) ?? [];
    }

    public static function getThemeDetails()
    {
        $theme = self::get('theme', 'indigo');
        $themes = [
            'indigo' => [
                'primary' => '#4f46e5',
                'hover' => '#4338ca',
                'light' => '#f5f3ff',
                'gradient_from' => '#2563eb',
                'gradient_to' => '#4f46e5',
            ],
            'emerald' => [
                'primary' => '#059669',
                'hover' => '#047857',
                'light' => '#ecfdf5',
                'gradient_from' => '#059669',
                'gradient_to' => '#0d9488',
            ],
            'blue' => [
                'primary' => '#2563eb',
                'hover' => '#1d4ed8',
                'light' => '#eff6ff',
                'gradient_from' => '#3b82f6',
                'gradient_to' => '#1d4ed8',
            ],
            'amber' => [
                'primary' => '#d97706',
                'hover' => '#b45309',
                'light' => '#fffbeb',
                'gradient_from' => '#f59e0b',
                'gradient_to' => '#d97706',
            ],
            'rose' => [
                'primary' => '#e11d48',
                'hover' => '#be123c',
                'light' => '#fff1f2',
                'gradient_from' => '#f43f5e',
                'gradient_to' => '#be123c',
            ],
            'slate' => [
                'primary' => '#475569',
                'hover' => '#334155',
                'light' => '#f8fafc',
                'gradient_from' => '#64748b',
                'gradient_to' => '#334155',
            ],
        ];

        return $themes[$theme] ?? $themes['indigo'];
    }

    public static function logoUrl()
    {
        $path = self::get('logo_path');
        return $path ? asset($path) : asset('images/logo.png');
    }

    public static function loginBgUrl()
    {
        $path = self::get('login_bg_path');
        return $path ? asset($path) : asset('images/login-bg.png');
    }
}
