<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Arus Kas</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111827; }
        h1 { font-size: 14px; margin: 0 0 8px; }
        .muted { color: #6b7280; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px 6px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
        th { background: #f9fafb; text-align: left; font-size: 10px; color: #374151; text-transform: uppercase; }
        .right { text-align: right; }
        .text-in { color: #059669; font-weight: bold; }
        .text-out { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Laporan Arus Kas</h1>
    <div class="muted" style="margin-bottom: 15px;">Generated: {{ now()->format('d/m/Y H:i') }}</div>

    <table>
        <thead>
            <tr>
                <th>No Ref</th>
                <th>Tanggal</th>
                <th>Tipe</th>
                <th>Deskripsi</th>
                <th class="right">Jumlah</th>
                <th class="right">Saldo Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cashFlows as $cf)
                <tr>
                    <td>{{ $cf->code }}</td>
                    <td>{{ optional($cf->date)->format('d/m/Y') }}</td>
                    <td class="{{ $cf->type == 'in' ? 'text-in' : 'text-out' }}">
                        {{ strtoupper($cf->type) }}
                    </td>
                    <td>{{ $cf->description }}</td>
                    <td class="right {{ $cf->type == 'in' ? 'text-in' : 'text-out' }}">
                        Rp {{ number_format($cf->amount, 0, ',', '.') }}
                    </td>
                    <td class="right">Rp {{ number_format($cf->balance, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
