<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $sale->invoice_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111827; }
        .muted { color: #6b7280; }
        .title { font-size: 16px; font-weight: 700; margin: 0 0 4px; }
        .row { width: 100%; }
        .right { text-align: right; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px 6px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
        th { background: #f9fafb; text-align: left; font-size: 11px; color: #374151; }
        .totals td { border-bottom: none; }
        .box { border: 1px solid #e5e7eb; padding: 10px; border-radius: 8px; }
    </style>
</head>
<body>
    <table class="row" style="margin-bottom: 12px;">
        <tr>
            <td>
                <div class="title">Faktur Penjualan</div>
                <div class="muted">Sistem Penjualan</div>
            </td>
            <td class="right">
                <div><strong>No:</strong> {{ $sale->invoice_number }}</div>
                <div><strong>Tanggal:</strong> {{ optional($sale->date)->format('d/m/Y') }}</div>
                <div><strong>Status:</strong> {{ strtoupper($sale->status) }}</div>
            </td>
        </tr>
    </table>

    <table class="row" style="margin-bottom: 12px;">
        <tr>
            <td class="box" style="width: 50%;">
                <div style="font-weight:700; margin-bottom:6px;">Customer</div>
                <div>{{ $sale->customer?->name }}</div>
                <div class="muted">{{ $sale->customer?->address }}</div>
                @if(!empty($sale->customer?->city))
                    <div class="muted">{{ $sale->customer?->city }}</div>
                @endif
                <div class="muted">{{ $sale->customer?->phone }}</div>
                @if(!empty($sale->customer?->group))
                    <div class="muted">Grup: {{ $sale->customer?->group }}</div>
                @endif
            </td>
            <td style="width: 12px;"></td>
            <td class="box" style="width: 50%;">
                <div style="font-weight:700; margin-bottom:6px;">Info</div>
                <div><strong>Salesman:</strong> {{ $sale->salesman?->name }}</div>
                <div><strong>Termin:</strong> {{ $sale->payment_term ?: '-' }}</div>
                <div><strong>Uang Muka:</strong> Rp {{ number_format($sale->down_payment ?? 0, 0, ',', '.') }}</div>
                @if(!empty($sale->notes))
                    <div style="margin-top:6px;"><strong>Catatan:</strong> {{ $sale->notes }}</div>
                @endif
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th style="width: 34px;">No</th>
                <th>Produk</th>
                <th style="width: 70px;" class="right">Qty</th>
                <th style="width: 70px;" class="right">Bonus</th>
                <th style="width: 110px;" class="right">Harga</th>
                <th style="width: 110px;" class="right">Diskon</th>
                <th style="width: 120px;" class="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $i => $it)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>
                        <div style="font-weight:700;">{{ $it->product?->name }}</div>
                        <div class="muted">{{ $it->product?->code }}</div>
                    </td>
                    <td class="right">{{ number_format($it->quantity, 0, ',', '.') }}</td>
                    <td class="right">{{ number_format($it->bonus ?? 0, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($it->price, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($it->discount ?? 0, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($it->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="row" style="margin-top: 10px;">
        <tr>
            <td style="width: 60%;"></td>
            <td style="width: 40%;">
                <table>
                    <tr class="totals">
                        <td class="right muted">Subtotal</td>
                        <td class="right" style="width:140px;">Rp {{ number_format($sale->subtotal ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="totals">
                        <td class="right muted">Diskon</td>
                        <td class="right">Rp {{ number_format($sale->discount ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="totals">
                        <td class="right muted">Pajak</td>
                        <td class="right">Rp {{ number_format($sale->tax ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="totals">
                        <td class="right" style="font-weight:700;">Total</td>
                        <td class="right" style="font-weight:700;">Rp {{ number_format($sale->total ?? 0, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

