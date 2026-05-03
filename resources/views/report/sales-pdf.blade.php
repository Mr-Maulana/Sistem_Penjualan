<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111827; }
        h1 { font-size: 14px; margin: 0 0 8px; }
        .muted { color: #6b7280; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 6px 6px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
        th { background: #f9fafb; text-align: left; font-size: 10px; color: #374151; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <h1>Laporan Penjualan</h1>
    <div class="muted" style="margin-bottom: 10px;">Generated: {{ now()->format('d/m/Y H:i') }}</div>

    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Tanggal</th>
                <th>Customer</th>
                <th>Salesman</th>
                <th class="right">Subtotal</th>
                <th class="right">Diskon</th>
                <th class="right">Pajak</th>
                <th class="right">Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $s)
                <tr>
                    <td>{{ $s->invoice_number }}</td>
                    <td>{{ optional($s->date)->format('d/m/Y') }}</td>
                    <td>{{ $s->customer?->name ?? '-' }}</td>
                    <td>{{ $s->salesman?->name ?? '-' }}</td>
                    <td class="right">Rp {{ number_format($s->subtotal ?? 0, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($s->discount ?? 0, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($s->tax ?? 0, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($s->total ?? 0, 0, ',', '.') }}</td>
                    <td>{{ strtoupper($s->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

