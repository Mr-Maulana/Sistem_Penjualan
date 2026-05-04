@extends('layouts.admin')

@section('title', isset($sale) ? 'Edit Penjualan' : 'Tambah Penjualan')
@section('page-title', 'Penjualan')
@section('page-subtitle', isset($sale) ? 'Edit transaksi penjualan' : 'Tambah transaksi penjualan')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 max-w-5xl mx-auto overflow-hidden">
    <div class="px-8 py-5 border-b border-slate-100 bg-white">
        <h3 class="font-bold text-slate-800 text-lg">{{ isset($sale) ? 'Edit Transaksi Penjualan' : 'Buat Transaksi Baru' }}</h3>
        <p class="text-xs text-slate-500 mt-1">Input data transaksi dengan teliti untuk akurasi laporan keuangan.</p>
    </div>
    <form method="POST" action="{{ isset($sale) ? route('sale.update', $sale) : route('sale.store') }}" class="p-8" id="sale-form">
        @csrf
        @if(isset($sale))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">No. Invoice</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i data-lucide="hash" style="width:16px;height:16px;" class="text-slate-400"></i>
                        </div>
                        <input type="text" name="invoice_number" value="{{ old('invoice_number', $sale->invoice_number ?? $autoInvoice ?? '') }}" 
                               class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm bg-slate-100 text-blue-600 cursor-not-allowed font-mono font-bold"
                               readonly required placeholder="Otomatis">
                    </div>
                    @error('invoice_number') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
                    <p class="text-[10px] text-slate-400 mt-1 font-medium italic">* No. Invoice digenerate otomatis</p>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Tanggal Transaksi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i data-lucide="calendar" style="width:16px;height:16px;" class="text-slate-400"></i>
                        </div>
                        <input type="date" name="date" value="{{ old('date', isset($sale) ? optional($sale->date)->format('Y-m-d') : now()->format('Y-m-d')) }}" 
                               class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all bg-slate-50/50 hover:bg-slate-50"
                               required>
                    </div>
                    @error('date') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Customer (Pembeli)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i data-lucide="user" style="width:16px;height:16px;" class="text-slate-400"></i>
                        </div>
                        <select name="customer_id" class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all bg-slate-50/50 hover:bg-slate-50 appearance-none">
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" {{ (string)old('customer_id', $sale->customer_id ?? '') === (string)$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('customer_id') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Salesman (Penanggung Jawab)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i data-lucide="briefcase" style="width:16px;height:16px;" class="text-slate-400"></i>
                        </div>
                        <select name="salesman_id" class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all bg-slate-50/50 hover:bg-slate-50 appearance-none">
                            @foreach($salesmen as $s)
                                <option value="{{ $s->id }}" {{ (string)old('salesman_id', $sale->salesman_id ?? '') === (string)$s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('salesman_id') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="bg-slate-50 border border-slate-200 rounded-2xl overflow-hidden mb-8 shadow-inner">
            <div class="px-6 py-4 flex items-center justify-between border-b border-slate-200 bg-white/50 backdrop-blur-sm">
                <div class="flex items-center gap-2">
                    <i data-lucide="shopping-cart" style="width:18px;height:18px;" class="text-blue-500"></i>
                    <span class="font-bold text-slate-800">Daftar Barang / Item</span>
                </div>
                <button type="button" id="add-item" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition-all flex items-center gap-2 shadow-sm hover:shadow">
                    <i data-lucide="plus" style="width:14px;height:14px;"></i> Tambah Item
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm" id="items-table">
                    <thead>
                        <tr class="bg-slate-100/50 text-slate-500 text-[10px] uppercase tracking-widest">
                            <th class="px-4 py-3 text-left font-bold">Produk</th>
                            <th class="px-4 py-3 text-center font-bold w-20">Qty</th>
                            <th class="px-4 py-3 text-center font-bold w-20">Bonus</th>
                            <th class="px-4 py-3 text-right font-bold w-32">Harga Satuan</th>
                            <th class="px-4 py-3 text-right font-bold w-28">Potongan</th>
                            <th class="px-4 py-3 text-right font-bold w-36">Subtotal</th>
                            <th class="px-4 py-3 text-center font-bold w-12"></th>
                        </tr>
                    </thead>
                    <tbody id="items-body" class="bg-white/30 divide-y divide-slate-100">
                        @php
                            $oldItems = old('items');
                            $items = $oldItems ?? (isset($sale) ? $sale->items->map(function($i){
                                return [
                                    'product_id' => $i->product_id,
                                    'quantity' => $i->quantity,
                                    'bonus' => $i->bonus ?? 0,
                                    'price' => $i->price,
                                    'discount' => $i->discount ?? 0,
                                ];
                            })->toArray() : []);
                            if (empty($items)) {
                                $items = [[ 'product_id' => $products->first()?->id, 'quantity' => 1, 'bonus' => 0, 'price' => 0, 'discount' => 0 ]];
                            }
                        @endphp
                        @foreach($items as $idx => $it)
                        <tr class="item-row hover:bg-white/80 transition-colors">
                            <td class="px-4 py-3">
                                <select name="items[{{ $idx }}][product_id]" class="item-product w-full border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/50 bg-white">
                                    @foreach($products as $p)
                                        <option value="{{ $p->id }}" {{ (string)($it['product_id'] ?? '') === (string)$p->id ? 'selected' : '' }} data-default-price="{{ $p->price }}">
                                            {{ $p->name }} ({{ $p->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <input type="number" min="1" name="items[{{ $idx }}][quantity]" value="{{ $it['quantity'] ?? 1 }}" class="item-qty w-full border border-slate-200 rounded-lg px-2 py-2 text-center text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/50 bg-white font-bold">
                            </td>
                            <td class="px-4 py-3 text-center">
                                <input type="number" min="0" name="items[{{ $idx }}][bonus]" value="{{ $it['bonus'] ?? 0 }}" class="item-bonus w-full border border-slate-200 rounded-lg px-2 py-2 text-center text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/50 bg-white text-emerald-600 font-bold">
                            </td>
                            <td class="px-4 py-3 text-right">
                                <input type="number" min="0" name="items[{{ $idx }}][price]" value="{{ $it['price'] ?? 0 }}" class="item-price w-full border border-slate-200 rounded-lg px-2 py-2 text-right text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/50 bg-white font-mono font-bold">
                            </td>
                            <td class="px-4 py-3 text-right">
                                <input type="number" min="0" name="items[{{ $idx }}][discount]" value="{{ $it['discount'] ?? 0 }}" class="item-discount w-full border border-slate-200 rounded-lg px-2 py-2 text-right text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/50 bg-white text-red-500 font-mono">
                            </td>
                            <td class="px-4 py-3 text-right font-bold text-slate-800 item-subtotal font-mono">Rp 0</td>
                            <td class="px-4 py-3 text-center">
                                <button type="button" class="remove-item p-1.5 rounded-lg hover:bg-red-50 text-slate-400 hover:text-red-500 transition-colors">
                                    <i data-lucide="trash-2" style="width:14px;height:14px;"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @error('items') <div class="text-sm text-red-500 mb-6 font-medium p-3 bg-red-50 rounded-xl border border-red-100 flex items-center gap-2"><i data-lucide="alert-circle" class="w-4 h-4"></i> {{ $message }}</div> @enderror

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Catatan Transaksi</label>
                    <textarea name="notes" rows="4" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all bg-slate-50/50 hover:bg-slate-50" 
                              placeholder="Masukkan keterangan tambahan jika ada...">{{ old('notes', $sale->notes ?? '') }}</textarea>
                    @error('notes') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Termin Pembayaran</label>
                        <input name="payment_term" value="{{ old('payment_term', $sale->payment_term ?? '') }}" 
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all bg-white"
                               placeholder="Contoh: COD / 7 Hari">
                        @error('payment_term') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Uang Muka (DP)</label>
                        <input type="number" name="down_payment" value="{{ old('down_payment', $sale->down_payment ?? 0) }}" 
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all bg-white font-mono">
                        @error('down_payment') <div class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="bg-slate-50 rounded-2xl p-8 border border-slate-200 space-y-4">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500 font-semibold uppercase tracking-wider text-[10px]">Pilih Status Bayar</span>
                    <div class="flex bg-white p-1 rounded-xl border border-slate-200 ring-1 ring-black/5">
                        @php($v = old('status', $sale->status ?? 'unpaid'))
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="unpaid" class="hidden peer" {{ $v==='unpaid'?'checked':'' }}>
                            <span class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all inline-block peer-checked:bg-red-500 peer-checked:text-white text-slate-400">UNPAID</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="partial" class="hidden peer" {{ $v==='partial'?'checked':'' }}>
                            <span class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all inline-block peer-checked:bg-amber-500 peer-checked:text-white text-slate-400">PARTIAL</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="paid" class="hidden peer" {{ $v==='paid'?'checked':'' }}>
                            <span class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all inline-block peer-checked:bg-emerald-500 peer-checked:text-white text-slate-400">PAID</span>
                        </label>
                    </div>
                </div>
                @error('status') <div class="text-xs text-red-500 mt-1 font-medium text-right">{{ $message }}</div> @enderror

                <div class="space-y-3 pt-2">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-bold text-slate-500">Potongan Invoice (Rp)</span>
                        <input type="number" min="0" name="discount" id="discount" value="{{ old('discount', $sale->discount ?? 0) }}" 
                               class="w-40 border border-slate-200 rounded-xl px-3 py-2 text-right text-sm focus:outline-none focus:ring-2 focus:ring-red-500/50 bg-white font-mono text-red-600 font-bold">
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-bold text-slate-500">Pajak / PPN (Rp)</span>
                        <input type="number" min="0" name="tax" id="tax" value="{{ old('tax', $sale->tax ?? 0) }}" 
                               class="w-40 border border-slate-200 rounded-xl px-3 py-2 text-right text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/50 bg-white font-mono font-bold">
                    </div>
                    <div class="pt-4 border-t border-slate-200 flex justify-between items-center">
                        <span class="font-black text-slate-800 uppercase tracking-tighter text-lg">Total Akhir</span>
                        <div class="text-right">
                            <input type="number" readonly id="total" value="{{ old('total', $sale->total ?? 0) }}" 
                                   class="bg-transparent border-none text-right text-2xl font-black text-blue-600 focus:outline-none w-56 font-mono tracking-tighter">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Sistem Otomatis Terkalkulasi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 mt-10 pt-6 border-t border-slate-100">
            <button type="submit" class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold py-3 px-10 rounded-xl text-sm transition-all shadow-lg hover:shadow-indigo-500/25 hover:-translate-y-0.5 flex items-center gap-2">
                <i data-lucide="save" style="width:18px;height:18px;"></i> {{ isset($sale) ? 'Update Transaksi' : 'Simpan Transaksi' }}
            </button>
            <a href="{{ route('sale.index') }}" class="bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 text-slate-600 font-bold py-3 px-8 rounded-xl text-sm transition-all flex items-center gap-2">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const itemsBody = document.getElementById('items-body');
    const addBtn = document.getElementById('add-item');
    const discountEl = document.getElementById('discount');
    const taxEl = document.getElementById('tax');
    const totalEl = document.getElementById('total');
    const customerEl = document.querySelector('select[name="customer_id"]');
    const dateEl = document.querySelector('input[name="date"]');

    const customers = @json($customers->map(fn($c) => ['id' => $c->id, 'group' => $c->group])->values());
    const customerGroupById = new Map(customers.map(c => [String(c.id), c.group || '']));

    function formatRp(n) {
        const v = Math.round((Number(n) || 0));
        return 'Rp ' + v.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    async function lookupPrice(productId) {
        const customerId = customerEl?.value;
        const group = customerGroupById.get(String(customerId || '')) || '';
        const date = dateEl?.value || '';
        const url = new URL(@json(route('price.lookup')), window.location.origin);
        url.searchParams.set('product_id', productId);
        if (group) url.searchParams.set('customer_group', group);
        if (date) url.searchParams.set('date', date);
        const res = await fetch(url.toString(), { headers: { 'Accept': 'application/json' } });
        if (!res.ok) return null;
        return await res.json();
    }

    function rowSubtotal(row) {
        const qty = Number(row.querySelector('.item-qty')?.value || 0);
        const price = Number(row.querySelector('.item-price')?.value || 0);
        const disc = Number(row.querySelector('.item-discount')?.value || 0);
        return Math.max(0, (qty * price) - disc);
    }

    function recompute() {
        let subtotal = 0;
        itemsBody.querySelectorAll('.item-row').forEach(row => {
            const st = rowSubtotal(row);
            subtotal += st;
            row.querySelector('.item-subtotal').textContent = formatRp(st);
        });
        const disc = Number(discountEl?.value || 0);
        const tax = Number(taxEl?.value || 0);
        const total = Math.max(0, subtotal - disc + tax);
        totalEl.value = Math.round(total);
    }

    function renumberNames() {
        itemsBody.querySelectorAll('.item-row').forEach((row, idx) => {
            row.querySelectorAll('select, input').forEach(el => {
                const name = el.getAttribute('name');
                if (!name) return;
                el.setAttribute('name', name.replace(/items\[\d+\]/, `items[${idx}]`));
            });
        });
    }

    function bindRow(row) {
        const productSel = row.querySelector('.item-product');
        const priceInput = row.querySelector('.item-price');
        const discountInput = row.querySelector('.item-discount');
        const inputs = row.querySelectorAll('input, select');
        inputs.forEach(i => i.addEventListener('input', recompute));

        if (productSel && priceInput) {
            productSel.addEventListener('change', async () => {
                const opt = productSel.options[productSel.selectedIndex];
                const defaultPrice = opt?.getAttribute('data-default-price');
                // If user hasn't set a price yet, try lookup based on customer group, fallback to product default.
                const current = Number(priceInput.value || 0);
                if (current === 0) {
                    const data = await lookupPrice(productSel.value);
                    if (data?.found && data.price_small != null) {
                        priceInput.value = Number(data.price_small || 0);
                        if (discountInput && Number(discountInput.value || 0) === 0) {
                            discountInput.value = Number(data.discount || 0);
                        }
                        if (taxEl && Number(taxEl.value || 0) === 0) {
                            taxEl.value = Number(data.tax || 0);
                        }
                    } else {
                        priceInput.value = Number(defaultPrice || 0);
                    }
                }
                recompute();
            });
        }

        const removeBtn = row.querySelector('.remove-item');
        if (removeBtn) {
            removeBtn.addEventListener('click', () => {
                const rows = itemsBody.querySelectorAll('.item-row');
                if (rows.length <= 1) return;
                row.remove();
                renumberNames();
                recompute();
                lucide.createIcons();
            });
        }
    }

    addBtn?.addEventListener('click', () => {
        const tpl = itemsBody.querySelector('.item-row');
        if (!tpl) return;
        const clone = tpl.cloneNode(true);
        clone.querySelectorAll('input').forEach(i => {
            if (i.classList.contains('item-qty')) i.value = 1;
            else i.value = 0;
        });
        clone.querySelector('.item-subtotal').textContent = 'Rp 0';
        itemsBody.appendChild(clone);
        renumberNames();
        bindRow(clone);
        recompute();
        lucide.createIcons();
    });

    itemsBody.querySelectorAll('.item-row').forEach(bindRow);
    discountEl?.addEventListener('input', recompute);
    taxEl?.addEventListener('input', recompute);
    customerEl?.addEventListener('change', () => {
        // Re-trigger price lookup on all rows if price is still zero.
        itemsBody.querySelectorAll('.item-row').forEach(row => {
            const productSel = row.querySelector('.item-product');
            const priceInput = row.querySelector('.item-price');
            if (!productSel || !priceInput) return;
            if (Number(priceInput.value || 0) === 0) {
                productSel.dispatchEvent(new Event('change'));
            }
        });
    });
    recompute();
})();
</script>
@endpush

