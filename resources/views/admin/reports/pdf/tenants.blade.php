<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9px; color: #374151; background: #fff; padding: 30px 36px; }

        /* ── Header ── */
        .header-table { width: 100%; margin-bottom: 12px; border-bottom: 2px solid #2563eb; padding-bottom: 8px; }
        .title { font-size: 16px; font-weight: bold; color: #1e40af; }
        .subtitle { font-size: 8px; color: #6b7280; margin-top: 2px; }
        .meta { font-size: 8px; color: #6b7280; line-height: 1.8; text-align: right; }

        /* ── Stats ── */
        .stats-table { width: 100%; margin-bottom: 14px; border-collapse: collapse; }
        .stats-table td { border: 1px solid #e5e7eb; padding: 8px 12px; width: 20%; }
        .stat-label { font-size: 7px; text-transform: uppercase; letter-spacing: 0.05em; color: #9ca3af; }
        .stat-value { font-size: 15px; font-weight: bold; margin-top: 2px; }

        /* ── Main Table ── */
        .main-table { width: 100%; border-collapse: collapse; }
        .main-table thead td { background: #f3f4f6; padding: 6px 8px; font-size: 7px; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; border-bottom: 1px solid #e5e7eb; font-weight: bold; }
        .main-table tbody td { padding: 6px 8px; border-bottom: 1px solid #f3f4f6; font-size: 8.5px; vertical-align: middle; }

        .name { font-weight: bold; color: #111827; }
        .email { color: #9ca3af; font-size: 7.5px; }

        .badge { padding: 2px 8px; border-radius: 999px; font-size: 7.5px; font-weight: bold; }
        .b-active    { background: #dcfce7; color: #15803d; }
        .b-inactive  { background: #fef9c3; color: #a16207; }
        .b-expired   { background: #f3f4f6; color: #4b5563; }
        .b-cancelled { background: #fee2e2; color: #b91c1c; }

        .footer { margin-top: 14px; border-top: 1px solid #e5e7eb; padding-top: 8px; font-size: 7.5px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>

{{-- Header --}}
<table class="header-table">
    <tr>
        <td>
            <div class="title">Laporan Penghuni</div>
            <div class="subtitle">RumahKos — Rekap data seluruh penghuni kos</div>
        </td>
        <td>
            <div class="meta">
                Dicetak: {{ $generatedAt }}<br>
                Total: {{ $residents->count() }} penghuni
            </div>
        </td>
    </tr>
</table>

{{-- Stats --}}
<table class="stats-table">
    <tr>
        <td>
            <div class="stat-label">Total</div>
            <div class="stat-value" style="color:#1d4ed8;">{{ $stats['total'] }}</div>
        </td>
        <td>
            <div class="stat-label">Aktif</div>
            <div class="stat-value" style="color:#15803d;">{{ $stats['active'] }}</div>
        </td>
        <td>
            <div class="stat-label">Inactive</div>
            <div class="stat-value" style="color:#a16207;">{{ $stats['inactive'] }}</div>
        </td>
        <td>
            <div class="stat-label">Expired</div>
            <div class="stat-value" style="color:#4b5563;">{{ $stats['expired'] }}</div>
        </td>
        <td>
            <div class="stat-label">Cancelled</div>
            <div class="stat-value" style="color:#b91c1c;">{{ $stats['cancelled'] }}</div>
        </td>
    </tr>
</table>

{{-- Main Table --}}
<table class="main-table">
    <thead>
        <tr>
            <td>#</td>
            <td>Nama</td>
            <td>Kamar</td>
            <td>Properti</td>
            <td>Tgl Masuk</td>
            <td>Tgl Keluar</td>
            <td>Durasi</td>
            <td>Bayar</td>
            <td>Status</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($residents as $i => $r)
            @php
                $paid     = $r->payments->where('status', 'paid')->count();
                $total    = $r->payments->count();
                $duration = \Carbon\Carbon::parse($r->start_date)->diffInMonths(\Carbon\Carbon::parse($r->end_date));
                $pct      = $total > 0 ? round($paid / $total * 100) : 0;
                $badge    = match($r->status) {
                    'active'    => 'b-active',
                    'inactive'  => 'b-inactive',
                    'expired'   => 'b-expired',
                    'cancelled' => 'b-cancelled',
                    default     => 'b-expired',
                };
                $label = match($r->status) {
                    'active'    => 'Aktif',
                    'inactive'  => 'Inactive',
                    'expired'   => 'Expired',
                    'cancelled' => 'Cancelled',
                    default     => $r->status,
                };
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                    <div class="name">{{ $r->user->name }}</div>
                    <div class="email">{{ $r->user->email }}</div>
                </td>
                <td>{{ $r->room->name ?? '-' }}</td>
                <td>{{ $r->room->property->name ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($r->start_date)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($r->end_date)->format('d/m/Y') }}</td>
                <td>{{ $duration }} bln</td>
                <td>{{ $paid }}/{{ $total }}</td>
                <td><span class="badge {{ $badge }}">{{ $label }}</span></td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    Laporan ini dibuat secara otomatis oleh sistem RumahKos pada {{ $generatedAt }}
</div>

</body>
</html>