@extends('layouts.admin')

@section('title', isset($sale) ? 'Edit Penjualan' : 'Tambah Penjualan')
@section('page-title', 'Penjualan')
@section('page-subtitle', isset($sale) ? 'Edit transaksi penjualan' : 'Tambah transaksi penjualan')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800">{{ isset($sale) ? 'Edit Penjualan' : 'Tambah Penjualan' }}</h3>
        </div>
        <form method="POST" action="{{ isset($sale) ? route('sale.update', $sale) : route('sale.store') }}" class="p-5 space-y-4" id="sale-form">
            @csrf
            @if(isset($sale))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">No. Invoice</label>
                    <input name="invoice_number" value="{{ old('invoice_number', $sale->invoice_number ?? '') }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('invoice_number') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Tanggal</label>
                    <input type="date" name="date" value="{{ old('date', isset($sale) ? optional($sale->date)->format('Y-m-d') : now()->format('Y-m-d')) }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('date') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Customer</label>
                    <select name="customer_id" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ (string)old('customer_id', $sale->customer_id ?? '') === (string)$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('customer_id') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Salesman</label>
                    <select name="salesman_id" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        @foreach($salesmen as $s)
                            <option value="{{ $s->id }}" {{ (string)old('salesman_id', $sale->salesman_id ?? '') === (string)$s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                    @error('salesman_id') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Termin</label>
                    <input name="payment_term" value="{{ old('payment_term', $sale->payment_term ?? '') }}" placeholder="Contoh: COD / 7 Hari / 14 Hari" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('payment_term') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Uang Muka (Rp)</label>
                    <input type="number" name="down_payment" value="{{ old('down_payment', $sale->down_payment ?? 0) }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('down_payment') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Status</label>
                    @php($v = old('status', $sale->status ?? 'unpaid'))
                    <select name="status" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="paid" {{ $v==='paid'?'selected':'' }}>Lunas</option>
                        <option value="partial" {{ $v==='partial'?'selected':'' }}>Sebagian</option>
                        <option value="unpaid" {{ $v==='unpaid'?'selected':'' }}>Belum Lunas</option>
                    </select>
                    @error('status') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="bg-slate-50 border border-slate-200 rounded-xl overflow-hidden">
                <div class="px-4 py-3 flex items-center justify-between border-b border-slate-200">
                    <div class="font-semibold text-slate-800">Detail Item</div>
                    <button type="button" id="add-item" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-3 py-2 rounded-lg transition flex items-center gap-1.5">
                        <i data-lucide="plus" style="width:14px;height:14px;"></i> Tambah Item
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm" id="items-table">
                        <thead>
                            <tr class="bg-white text-slate-500 text-xs uppercase tracking-wide">
                                <th class="px-4 py-3 text-left font-semibold">Produk</th>
                                <th class="px-4 py-3 text-left font-semibold w-20">Qty</th>
                                <th class="px-4 py-3 text-left font-semibold w-24">Bonus</th>
                                <th class="px-4 py-3 text-left font-semibold w-32">Harga</th>
                                <th class="px-4 py-3 text-left font-semibold w-32">Diskon</th>
                                <th class="px-4 py-3 text-left font-semibold w-36">Subtotal</th>
                                <th class="px-4 py-3 text-left font-semibold w-12"></th>
                            </tr>
                        </thead>
                        <tbody id="items-body" class="bg-white">
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
                            <tr class="border-b border-slate-100 item-row">
                                <td class="px-4 py-3">
                                    <select name="items[{{ $idx }}][product_id]" class="item-product w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                        @foreach($products as $p)
                                            <option value="{{ $p->id }}" {{ (string)($it['product_id'] ?? '') === (string)$p->id ? 'selected' : '' }} data-default-price="{{ $p->price }}">
                                                {{ $p->name }} ({{ $p->code }}) - stok: {{ $p->stock }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" min="1" name="items[{{ $idx }}][quantity]" value="{{ $it['quantity'] ?? 1 }}" class="item-qty w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" min="0" name="items[{{ $idx }}][bonus]" value="{{ $it['bonus'] ?? 0 }}" class="item-bonus w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" min="0" name="items[{{ $idx }}][price]" value="{{ $it['price'] ?? 0 }}" class="item-price w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" min="0" name="items[{{ $idx }}][discount]" value="{{ $it['discount'] ?? 0 }}" class="item-discount w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                </td>
                                <td class="px-4 py-3 font-semibold text-slate-800 item-subtotal">Rp 0</td>
                                <td class="px-4 py-3 text-right">
                                    <button type="button" class="remove-item p-1.5 rounded hover:bg-red-50 text-red-400">
                                        <i data-lucide="x" style="width:16px;height:16px;"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @error('items') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Catatan</label>
                    <input name="notes" value="{{ old('notes', $sale->notes ?? '') }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('notes') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1">Diskon (Rp)</label>
                            <input type="number" min="0" name="discount" id="discount" value="{{ old('discount', $sale->discount ?? 0) }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            @error('discount') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1">Pajak (Rp)</label>
                            <input type="number" min="0" name="tax" id="tax" value="{{ old('tax', $sale->tax ?? 0) }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            @error('tax') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1">Total (Rp)</label>
                            <input type="number" readonly id="total" value="{{ old('total', $sale->total ?? 0) }}" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-2 pt-2">
                <button class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition">
                    Simpan
                </button>
                <a href="{{ route('sale.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold px-4 py-2.5 rounded-lg transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
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

