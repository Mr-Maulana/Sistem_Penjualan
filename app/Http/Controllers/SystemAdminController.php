<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\Salesman;
use App\Models\Sale;
use App\Models\CashFlow;
use App\Models\Area;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SystemAdminController extends Controller
{
    public function settings()
    {
        return view('admin.settings');
    }

    public function saveSettings(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email|max:255',
            'company_phone' => 'required|string|max:50',
            'company_address' => 'required|string',
            'app_locale' => 'required|in:id,en',
        ]);

        return redirect()->route('admin.settings')->with('success', 'Pengaturan sistem berhasil diperbarui (Simulasi)');
    }

    public function records()
    {
        $counts = [
            'products' => Product::count(),
            'suppliers' => Supplier::count(),
            'customers' => Customer::count(),
            'salesmen' => Salesman::count(),
            'sales' => Sale::count(),
            'cash_flows' => CashFlow::count(),
            'areas' => Area::count(),
            'users' => User::count(),
        ];

        // MySQL DB Size
        try {
            $dbName = config('database.connections.mysql.database', 'sistem_penjualan');
            $sizeResult = DB::select("SELECT SUM(data_length + index_length) / 1024 / 1024 AS size FROM information_schema.TABLES WHERE table_schema = ?", [$dbName]);
            $dbSize = isset($sizeResult[0]->size) ? round($sizeResult[0]->size, 2) . ' MB' : '0.00 MB';
        } catch (\Exception $e) {
            $dbSize = '0.00 MB';
        }

        return view('admin.records', compact('counts', 'dbSize'));
    }

    public function activity()
    {
        $activities = [];

        // 1. Sales
        $recentSales = Sale::with('customer', 'salesman')->orderBy('created_at', 'desc')->limit(5)->get();
        foreach ($recentSales as $sale) {
            $activities[] = [
                'time' => $sale->created_at,
                'user' => $sale->salesman->name ?? 'Salesman',
                'action' => 'Membuat Penjualan Baru',
                'detail' => "Invoice: {$sale->invoice_number} | Pelanggan: {$sale->customer->name} | Total: Rp " . number_format($sale->grand_total, 0, ',', '.'),
                'icon' => 'shopping-cart',
                'color' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
            ];
        }

        // 2. Cash flows
        $recentCFs = CashFlow::orderBy('created_at', 'desc')->limit(5)->get();
        foreach ($recentCFs as $cf) {
            $activities[] = [
                'time' => $cf->created_at,
                'user' => 'Admin System',
                'action' => $cf->type === 'in' ? 'Mencatat Kas Masuk' : 'Mencatat Kas Keluar',
                'detail' => "[{$cf->code}] {$cf->description} | Rp " . number_format($cf->amount, 0, ',', '.'),
                'icon' => $cf->type === 'in' ? 'trending-up' : 'trending-down',
                'color' => $cf->type === 'in' ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-rose-50 text-rose-600 border-rose-100',
            ];
        }

        // 3. Areas
        $recentAreas = Area::orderBy('created_at', 'desc')->limit(3)->get();
        foreach ($recentAreas as $area) {
            $activities[] = [
                'time' => $area->created_at,
                'user' => 'Administrator',
                'action' => 'Mengelola Master Wilayah',
                'detail' => "Kecamatan: {$area->name} | Kota: {$area->city} | Kode: {$area->code}",
                'icon' => 'map',
                'color' => 'bg-orange-50 text-orange-600 border-orange-100',
            ];
        }

        // Sort activities by time descending
        usort($activities, function($a, $b) {
            return $b['time']->timestamp <=> $a['time']->timestamp;
        });

        // Slice to top 10
        $activities = array_slice($activities, 0, 10);

        return view('admin.activity', compact('activities'));
    }

    public function health()
    {
        $health = [
            'db_connection' => 'Connected',
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
            'memory_limit' => ini_get('memory_limit'),
            'upload_max' => ini_get('upload_max_filesize'),
            'post_max' => ini_get('post_max_size'),
            'max_execution_time' => ini_get('max_execution_time') . 's',
            'disk_free' => 'N/A',
            'disk_total' => 'N/A',
        ];

        try {
            $free = disk_free_space("C:");
            $total = disk_total_space("C:");
            if ($free && $total) {
                $health['disk_free'] = round($free / 1024 / 1024 / 1024, 2) . ' GB';
                $health['disk_total'] = round($total / 1024 / 1024 / 1024, 2) . ' GB';
            }
        } catch (\Exception $e) {}

        return view('admin.health', compact('health'));
    }
}
