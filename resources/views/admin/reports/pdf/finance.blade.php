<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9px; color: #374151; }
        .header { border-bottom: 2px solid #2563eb; padding-bottom: 10px; margin-bottom: 14px; }
        .header h1 { font-size: 15px; font-weight: bold; color: #1e40af; }
        .header p { color: #6b7280; font-size: 9px; margin-top: 2px; }
        .meta { float: right; text-align: right; }
        .meta p { font-size: 8px; color: #6b7280; }
        .clearfix::after { content: ''; display: table; clear: both; }
        .stats { display: flex; gap: 8px; margin-bottom: 14px; }
        .stat-box { flex: 1; border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px; }
        .stat-box .label { font-size: 7px; text-transform: uppercase; letter-spacing: 0.05em; color: #9ca3af; }
        .stat-box .value { font-size: 11px; font-weight: bold; margin-top: 2px; }
        .stat-blue .value { color: #1d4ed8; }
        .stat-green .value { color: #15803d; }
        .stat-yellow .value { color: #a16207; }
        .stat-red .value { color: #b91c1c; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #f3f4f6; }
        th { padding: 7px 8px; text-align: left; font-size: 7px; text-transform: uppercase;
             letter-spacing: 0.05em; color: #6b7280; border-bottom: 1px solid #e5e7eb; }
        th.right { text-align: right; }
        td { padding: 7px 8px; border-bottom: 1px solid #f3f4f6; vertical-align: top; }
        td.right { text-align: right; font-weight: 600; color: #111827; }
        tr:last-child td { border-bottom: none; }
        .name { font-weight: 600; color: #111827; }
        .sub  { color: #9ca3af; font-size: 8px; }
        .badge { display: inline-block; padding: 2px 7px; border-radius: 999px; font-size: 7.5px; font-weight: 600; }
        .badge-paid      { background: #dcfce7; color: #15803d; }
        .badge-pending   { background: #fef9c3; color: #a16207; }
        .badge-failed    { background: #fee2e2; color: #b91c1c; }
        .badge-cancelled { background: #f3f4f6; color: #4b5563; }
        .overdue { color: #dc2626; font-weight: 600; }
        .footer { margin-top: 16px; border-top: 1px solid #e5e7eb; padding-top: 8px;
                  font-size: 8px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>

<div class="header clearfix">
    <div class="meta">
        <p>Dicetak: {{ $generatedAt }}</p>
        <p>Total: {{ $payments->count() }} tagihan</p>
    </div>
    <h1>Laporan Keuangan</h1>
    <p>RumahKos — Rekap pembayaran sewa kos</p>
</div>

{{-- Stats --}}
<div class="stats">
    <div class="stat-box stat-blue">
        <div class="label">Total Tagihan</div>
        <div class="value">Rp {{ number_format($stats['total_tagihan'], 0, ',', '.') }}</div>
    </div>
    <div class="stat-box stat-green">
        <div class="label">Sudah Lunas</div>
        <div class="value">Rp {{ number_format($stats['total_lunas'], 0, ',', '.') }}</div>
    </div>
    <div class="stat-box stat-yellow">
        <div class="label">Pending</div>
        <div class="value">{{ $stats['total_pending'] }} tagihan</div>
    </div>
    <div class="stat-box stat-red">
        <div class="label">Gagal / Batal</div>
        <div class="value">{{ $stats['total_failed'] }} tagihan</div>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Penghuni</th>
            <th>Kamar</th>
            <th>Bulan Tagih</th>
            <th>Jatuh Tempo</th>
            <th class="right">Nominal</th>
            <th>Tgl Bayar</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @php
            $badgeClass = [
                'paid'      => 'badge-paid',
                'pending'   => 'badge-pending',
                'failed'    => 'badge-failed',
                'cancelled' => 'badge-cancelled',
            ];
            $badgeLabel = [
                'paid'      => 'Lunas',
                'pending'   => 'Pending',
                'failed'    => 'Gagal',
                'cancelled' => 'Batal',
            ];
        @endphp
        @foreach ($payments as $i => $p)
            @php
                $isOverdue = $p->status === 'pending'
                    && $p->due_date
                    && \Carbon\Carbon::parse($p->due_date)->isPast();
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                    <div class="name">{{ $p->resident->user->name }}</div>
                    <div class="sub">{{ $p->resident->user->email }}</div>
                </td>
                <td>
                    <div>{{ $p->resident->room->name ?? '-' }}</div>
                    <div class="sub">{{ $p->resident->room->property->name ?? '' }}</div>
                </td>
                <td>{{ \Carbon\Carbon::parse($p->billing_month)->format('M Y') }}</td>
                <td class="{{ $isOverdue ? 'overdue' : '' }}">
                    {{ $p->due_date ? \Carbon\Carbon::parse($p->due_date)->format('d/m/Y') : '-' }}
                </td>
                <td class="right">Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                <td>{{ $p->paid_at ? \Carbon\Carbon::parse($p->paid_at)->format('d/m/Y') : '-' }}</td>
                <td><span class="badge {{ $badgeClass[$p->status] ?? 'badge-cancelled' }}">
                    {{ $badgeLabel[$p->status] ?? $p->status }}
                </span></td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    Laporan ini dibuat secara otomatis oleh sistem RumahKos pada {{ $generatedAt }}
</div>

</body>
</html>