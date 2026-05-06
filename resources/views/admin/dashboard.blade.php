@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan data penjualan')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/60 relative overflow-hidden group animate-slide-up stagger-1">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-500/5 rounded-full blur-2xl group-hover:bg-blue-500/10 transition-colors"></div>
        <div class="relative z-10">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                <i data-lucide="bar-chart-3" style="width:20px;height:20px;"></i>
            </div>
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Penjualan</div>
            <div class="text-2xl font-black text-slate-800 tracking-tight">Rp {{ number_format($totalSales ?? 0, 0, ',', '.') }}</div>
            <div class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-tighter">{{ number_format($totalTransactions ?? 0) }} TRANSAKSI</div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/60 relative overflow-hidden group animate-slide-up stagger-2">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl group-hover:bg-emerald-500/10 transition-colors"></div>
        <div class="relative z-10">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                <i data-lucide="check-circle" style="width:20px;height:20px;"></i>
            </div>
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Lunas</div>
            <div class="text-2xl font-black text-emerald-600 tracking-tight">Rp {{ number_format($paidSales ?? 0, 0, ',', '.') }}</div>
            <div class="text-[10px] font-bold text-emerald-500/70 mt-1 uppercase tracking-tighter">SUDAH TERBAYAR</div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/60 relative overflow-hidden group animate-slide-up stagger-3">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-500/5 rounded-full blur-2xl group-hover:bg-amber-500/10 transition-colors"></div>
        <div class="relative z-10">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4">
                <i data-lucide="clock" style="width:20px;height:20px;"></i>
            </div>
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Tagihan Pending</div>
            <div class="text-2xl font-black text-amber-600 tracking-tight">Rp {{ number_format($unpaidSales ?? 0, 0, ',', '.') }}</div>
            <div class="text-[10px] font-bold text-amber-500/70 mt-1 uppercase tracking-tighter">BELUM TERBAYAR</div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/60 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-500/5 rounded-full blur-2xl group-hover:bg-indigo-500/10 transition-colors"></div>
        <div class="relative z-10">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4">
                <i data-lucide="landmark" style="width:20px;height:20px;"></i>
            </div>
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Saldo Kas/Bank</div>
            <div class="text-2xl font-black text-slate-800 tracking-tight">Rp {{ number_format($currentBalance ?? 0, 0, ',', '.') }}</div>
            <div class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-tighter">SALDO AKTIF</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i data-lucide="trending-up" class="w-5 h-5 text-blue-600"></i> Tren Penjualan
            </h3>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">7 Hari Terakhir</span>
        </div>
        <div class="h-[300px]">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i data-lucide="landmark" class="w-5 h-5 text-emerald-600"></i> Arus Kas (In vs Out)
            </h3>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">7 Hari Terakhir</span>
        </div>
        <div class="h-[300px]">
            <canvas id="cashFlowChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-white">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i data-lucide="shopping-bag" style="width:18px;height:18px;"></i>
                </div>
                <h3 class="font-bold text-slate-800">Transaksi Terbaru</h3>
            </div>
            <a href="{{ route('sale.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 uppercase tracking-wider">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-500 text-[10px] uppercase tracking-widest">
                        <th class="px-6 py-4 text-left font-bold">Invoice</th>
                        <th class="px-6 py-4 text-left font-bold">Customer</th>
                        <th class="px-6 py-4 text-right font-bold">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentTransactions as $t)
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-4 font-mono text-xs font-bold text-blue-600">{{ $t->invoice_number }}</td>
                            <td class="px-6 py-4 font-semibold text-slate-700">{{ $t->customer?->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-right font-black text-slate-800">Rp {{ number_format($t->total ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-6 py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center">
                                <i data-lucide="database" class="w-8 h-8 text-slate-200 mb-2"></i>
                                <p class="text-xs font-medium uppercase tracking-widest">Belum ada transaksi</p>
                            </div>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i data-lucide="award" class="w-5 h-5 text-amber-500"></i> Performa Salesman
            </h3>
        </div>
        <div class="h-[300px]">
            <canvas id="salesmanChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 gap-8">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-white">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center">
                    <i data-lucide="package-search" style="width:18px;height:18px;"></i>
                </div>
                <h3 class="font-bold text-slate-800">Stok Hampir Habis</h3>
            </div>
            <a href="{{ route('product.index') }}" class="text-xs font-bold text-red-600 hover:text-red-700 uppercase tracking-wider">Kelola Produk</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-500 text-[10px] uppercase tracking-widest">
                        <th class="px-6 py-4 text-left font-bold">Produk</th>
                        <th class="px-6 py-4 text-right font-bold">Stok Sisa</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($lowStockProducts as $p)
                        <tr class="hover:bg-red-50/30 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $p->name }}</div>
                                <div class="font-mono text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ $p->code }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-black {{ ($p->stock ?? 0) <= 5 ? 'bg-red-100 text-red-700 ring-1 ring-red-600/20' : 'bg-amber-100 text-amber-700 ring-1 ring-amber-600/20' }}">
                                    {{ $p->stock ?? 0 }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="px-6 py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center">
                                <i data-lucide="package" class="w-8 h-8 text-slate-200 mb-2"></i>
                                <p class="text-xs font-medium uppercase tracking-widest">Stok aman terkendali</p>
                            </div>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData->pluck('date')) !!},
            datasets: [{
                label: 'Penjualan',
                data: {!! json_encode($chartData->pluck('total')) !!},
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { display: false } },
                x: { grid: { display: false } }
            }
        }
    });

    // Cash Flow Chart
    const cashFlowCtx = document.getElementById('cashFlowChart').getContext('2d');
    new Chart(cashFlowCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($cashFlowChart->pluck('date')) !!},
            datasets: [
                {
                    label: 'Kas Masuk',
                    data: {!! json_encode($cashFlowChart->pluck('cash_in')) !!},
                    borderColor: '#10b981',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.4
                },
                {
                    label: 'Kas Keluar',
                    data: {!! json_encode($cashFlowChart->pluck('cash_out')) !!},
                    borderColor: '#ef4444',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, grid: { display: false } },
                x: { grid: { display: false } }
            }
        }
    });

    // Salesman Performance Chart
    const salesmanCtx = document.getElementById('salesmanChart').getContext('2d');
    new Chart(salesmanCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($salesmanPerformance->pluck('name')) !!},
            datasets: [{
                label: '% Pencapaian',
                data: {!! json_encode($salesmanPerformance->pluck('percentage')) !!},
                backgroundColor: '#f59e0b',
                borderRadius: 8
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { max: 100, beginAtZero: true, grid: { display: false } },
                y: { grid: { display: false } }
            }
        }
    });
});
</script>
@endpush
@endsection

