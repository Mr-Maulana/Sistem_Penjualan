@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan data penjualan')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200 card-hover">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i data-lucide="shopping-cart" style="width:20px;height:20px;"></i>
            </div>
            <span class="text-xs font-medium text-slate-400 uppercase tracking-wide">Total Penjualan</span>
        </div>
        <div class="text-2xl font-extrabold text-slate-800">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
        <div class="text-xs text-slate-400 mt-1">{{ $totalTransactions }} transaksi</div>
    </div>
    
    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200 card-hover">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                <i data-lucide="users" style="width:20px;height:20px;"></i>
            </div>
            <span class="text-xs font-medium text-slate-400 uppercase tracking-wide">Total Customer</span>
        </div>
        <div class="text-2xl font-extrabold text-slate-800">{{ $totalCustomers }}</div>
        <div class="text-xs text-slate-400 mt-1">{{ $activeCustomers }} aktif</div>
    </div>
    
    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200 card-hover">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-lg bg-violet-50 text-violet-600 flex items-center justify-center">
                <i data-lucide="user-check" style="width:20px;height:20px;"></i>
            </div>
            <span class="text-xs font-medium text-slate-400 uppercase tracking-wide">Total Salesman</span>
        </div>
        <div class="text-2xl font-extrabold text-slate-800">{{ $totalSalesmen }}</div>
        <div class="text-xs text-slate-400 mt-1">Rp {{ number_format($totalTarget, 0, ',', '.') }} target</div>
    </div>
    
    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200 card-hover">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                <i data-lucide="landmark" style="width:20px;height:20px;"></i>
            </div>
            <span class="text-xs font-medium text-slate-400 uppercase tracking-wide">Saldo Kas/Bank</span>
        </div>
        <div class="text-2xl font-extrabold text-slate-800">Rp {{ number_format($currentBalance, 0, ',', '.') }}</div>
        <div class="text-xs text-slate-400 mt-1">Masuk: Rp {{ number_format($totalCashIn, 0, ',', '.') }}</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
        <h3 class="font-bold text-slate-800 mb-3 text-sm">Transaksi Terbaru</h3>
        <div class="space-y-2.5">
            @forelse($recentTransactions as $transaction)
            <div class="flex items-center justify-between py-2 border-b border-slate-100 last:border-0">
                <div>
                    <div class="text-sm font-semibold text-slate-700">{{ $transaction->invoice_number }}</div>
                    <div class="text-xs text-slate-400">{{ $transaction->customer->name }} · {{ $transaction->date->format('d/m/Y') }}</div>
                </div>
                <div class="text-right">
                    <div class="text-sm font-bold text-slate-800">Rp {{ number_format($transaction->total, 0, ',', '.') }}</div>
                    <span class="badge {{ $transaction->status == 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $transaction->status == 'paid' ? 'Lunas' : 'Belum Lunas' }}
                    </span>
                </div>
            </div>
            @empty
            <p class="text-slate-400 text-sm">Belum ada transaksi hari ini</p>
            @endforelse
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
        <h3 class="font-bold text-slate-800 mb-3 text-sm">Performa Salesman</h3>
        <div class="space-y-3">
            @foreach($salesmanPerformance as $salesman)
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="font-semibold text-slate-700">{{ $salesman['name'] }}</span>
                    <span class="text-slate-500">{{ number_format($salesman['percentage'], 0) }}% (Rp {{ number_format($salesman['achievement'], 0, ',', '.') }})</span>
                </div>
                <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full {{ $salesman['percentage'] >= 80 ? 'bg-emerald-500' : ($salesman['percentage'] >= 50 ? 'bg-amber-500' : 'bg-red-400') }}" 
                         style="width: {{ $salesman['percentage'] }}%; transition: width 0.5s"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection