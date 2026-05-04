@extends('layouts.admin')

@section('title', 'Detail Penjualan')
@section('page-title', 'Detail Penjualan')
@section('page-subtitle', 'Informasi lengkap transaksi penjualan')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 bg-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xl ring-1 ring-blue-500/20">
                    <i data-lucide="receipt" style="width:24px;height:24px;"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-800 text-xl tracking-tight">{{ $sale->invoice_number }}</h3>
                    <p class="text-sm text-slate-500 flex items-center gap-1.5 mt-0.5">
                        <i data-lucide="calendar" style="width:14px;height:14px;"></i> {{ $sale->date->format('d M Y') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('sale.print', $sale) }}" target="_blank" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-600 text-sm font-semibold px-4 py-2 rounded-xl flex items-center gap-2 transition-all ring-1 ring-indigo-500/20">
                    <i data-lucide="printer" style="width:16px;height:16px;"></i> Cetak Invoice
                </a>
                <a href="{{ route('sale.edit', $sale) }}" class="bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 text-slate-700 text-sm font-semibold px-4 py-2 rounded-xl flex items-center gap-2 transition-all shadow-sm">
                    <i data-lucide="pencil" style="width:16px;height:16px;"></i> Edit
                </a>
                <a href="{{ route('sale.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold px-4 py-2 rounded-xl flex items-center gap-2 transition-all">
                    <i data-lucide="arrow-left" style="width:16px;height:16px;"></i> Kembali
                </a>
            </div>
        </div>
        
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Informasi Customer</p>
                        <p class="text-slate-800 font-bold text-lg leading-tight">{{ $sale->customer->name }}</p>
                        <p class="text-sm text-slate-500 mt-1">{{ $sale->customer->address }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Salesman</p>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-600 uppercase">
                                {{ substr($sale->salesman->name, 0, 1) }}
                            </div>
                            <p class="text-sm text-slate-700 font-semibold">{{ $sale->salesman->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Metode Pembayaran</p>
                        <p class="text-sm text-slate-700 font-semibold">{{ $sale->payment_term ?: 'Cash / Tunai' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Status Transaksi</p>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider {{ $sale->status == 'paid' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20' : ($sale->status == 'partial' ? 'bg-amber-50 text-amber-700 ring-1 ring-amber-600/20' : 'bg-red-50 text-red-700 ring-1 ring-red-600/20') }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $sale->status == 'paid' ? 'bg-emerald-500' : ($sale->status == 'partial' ? 'bg-amber-500' : 'bg-red-500') }}"></span>
                            {{ $sale->status }}
                        </span>
                    </div>
                </div>

                <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Ringkasan Biaya</p>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500">Subtotal</span>
                            <span class="font-semibold text-slate-700">Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500">Diskon</span>
                            <span class="font-semibold text-red-500">- Rp {{ number_format($sale->discount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500">Pajak</span>
                            <span class="font-semibold text-slate-700">+ Rp {{ number_format($sale->tax, 0, ',', '.') }}</span>
                        </div>
                        <div class="pt-3 border-t border-slate-200 flex justify-between items-center">
                            <span class="font-bold text-slate-800">Total Akhir</span>
                            <span class="text-xl font-black text-indigo-600 tracking-tight">Rp {{ number_format($sale->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-100 bg-white">
            <h4 class="font-bold text-slate-800 flex items-center gap-2">
                <i data-lucide="package" style="width:18px;height:18px;" class="text-slate-400"></i>
                Daftar Barang / Item
            </h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-500 text-xs uppercase tracking-wider">
                        <th class="px-8 py-4 text-left font-semibold">Produk</th>
                        <th class="px-8 py-4 text-center font-semibold">Qty</th>
                        <th class="px-8 py-4 text-center font-semibold">Bonus</th>
                        <th class="px-8 py-4 text-right font-semibold">Harga Satuan</th>
                        <th class="px-8 py-4 text-right font-semibold">Potongan</th>
                        <th class="px-8 py-4 text-right font-semibold">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($sale->items as $item)
                    <tr>
                        <td class="px-8 py-4">
                            <div class="font-semibold text-slate-800">{{ $item->product->name }}</div>
                            <div class="font-mono text-[10px] text-slate-400 mt-0.5 uppercase tracking-tighter">{{ $item->product->code }}</div>
                        </td>
                        <td class="px-8 py-4 text-center font-medium text-slate-700">{{ $item->quantity }}</td>
                        <td class="px-8 py-4 text-center font-medium text-emerald-600">{{ $item->bonus ?: '-' }}</td>
                        <td class="px-8 py-4 text-right font-medium text-slate-700">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="px-8 py-4 text-right font-medium text-red-500">Rp {{ number_format($item->discount, 0, ',', '.') }}</td>
                        <td class="px-8 py-4 text-right font-bold text-slate-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    @if($sale->notes)
    <div class="bg-white rounded-2xl p-6 border border-slate-200/60 shadow-sm">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Catatan Tambahan</p>
        <p class="text-slate-600 text-sm italic">{{ $sale->notes }}</p>
    </div>
    @endif
</div>
@endsection
