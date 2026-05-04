<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Closing & Assessment</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111827; line-height: 1.5; }
        h1 { font-size: 16px; margin: 0 0 5px; }
        h2 { font-size: 13px; margin: 20px 0 10px; border-bottom: 1px solid #e5e7eb; padding-bottom: 5px; color: #374151; }
        .muted { color: #6b7280; }
        .grid { width: 100%; margin-bottom: 20px; }
        .card { padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 10px; }
        .card-title { font-size: 9px; font-weight: bold; text-transform: uppercase; color: #6b7280; margin-bottom: 4px; }
        .card-value { font-size: 14px; font-weight: bold; color: #111827; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 8px 6px; border-bottom: 1px solid #e5e7eb; vertical-align: middle; }
        th { background: #f9fafb; text-align: left; font-size: 9px; color: #374151; text-transform: uppercase; }
        .right { text-align: right; }
        
        .badge { padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: bold; }
        .badge-a { background: #ecfdf5; color: #059669; }
        .badge-b { background: #eff6ff; color: #2563eb; }
        .badge-c { background: #fffbeb; color: #d97706; }
        .badge-d { background: #fef2f2; color: #dc2626; }
    </style>
</head>
<body>
    <h1>Laporan Closing & Assessment</h1>
    <div class="muted" style="margin-bottom: 20px;">Generated: {{ now()->format('d/m/Y H:i') }}</div>

    <h2>Ringkasan Keuangan</h2>
    <table style="border: none;">
        <tr>
            <td style="border: none; width: 33%;">
                <div class="card">
                    <div class="card-title">Total Penjualan</div>
                    <div class="card-value">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
                </div>
            </td>
            <td style="border: none; width: 33%;">
                <div class="card">
                    <div class="card-title">Penjualan Terbayar</div>
                    <div class="card-value">Rp {{ number_format($paidSales, 0, ',', '.') }}</div>
                </div>
            </td>
            <td style="border: none; width: 33%;">
                <div class="card">
                    <div class="card-title">Piutang (Unpaid)</div>
                    <div class="card-value" style="color: #dc2626;">Rp {{ number_format($unpaidSales, 0, ',', '.') }}</div>
                </div>
            </td>
        </tr>
        <tr>
            <td style="border: none; width: 33%;">
                <div class="card">
                    <div class="card-title">Total Kas Masuk</div>
                    <div class="card-value" style="color: #059669;">Rp {{ number_format($cashIn, 0, ',', '.') }}</div>
                </div>
            </td>
            <td style="border: none; width: 33%;">
                <div class="card">
                    <div class="card-title">Total Kas Keluar</div>
                    <div class="card-value" style="color: #dc2626;">Rp {{ number_format($cashOut, 0, ',', '.') }}</div>
                </div>
            </td>
            <td style="border: none; width: 33%;">
                <div class="card">
                    <div class="card-title">Saldo Akhir</div>
                    <div class="card-value" style="background: #f3f4f6; padding: 2px 4px;">Rp {{ number_format($endingBalance, 0, ',', '.') }}</div>
                </div>
            </td>
        </tr>
    </table>

    <h2>Assessment Salesman</h2>
    <table>
        <thead>
            <tr>
                <th>Nama Salesman</th>
                <th class="right">Achievement</th>
                <th class="right">Target</th>
                <th class="right">Progress</th>
                <th>Grade</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salesmanAssessment as $s)
                <tr>
                    <td>{{ $s['name'] }}</td>
                    <td class="right">Rp {{ number_format($s['achievement'], 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($s['target'], 0, ',', '.') }}</td>
                    <td class="right">{{ $s['percentage'] }}%</td>
                    <td>
                        <span class="badge badge-{{ strtolower($s['grade']) }}">{{ $s['grade'] }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Daftar Piutang (Unpaid Invoices)</h2>
    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Tanggal</th>
                <th>Customer</th>
                <th class="right">Total Tagihan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($unpaidInvoices as $inv)
                <tr>
                    <td>{{ $inv->invoice_number }}</td>
                    <td>{{ optional($inv->date)->format('d/m/Y') }}</td>
                    <td>{{ $inv->customer?->name ?? '-' }}</td>
                    <td class="right">Rp {{ number_format($inv->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
