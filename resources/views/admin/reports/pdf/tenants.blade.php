<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9px; color: #374151; background: #fff; }

        /* ── Header ── */
        .header-table { width: 100%; border-collapse: collapse; border-bottom: 2px solid #2563eb; padding-bottom: 10px; margin-bottom: 14px; }
        .header-title { font-size: 15px; font-weight: bold; color: #1e40af; }
        .header-sub   { color: #6b7280; font-size: 9px; margin-top: 2px; }
        .header-meta  { font-size: 8px; color: #6b7280; text-align: right; line-height: 1.7; }

        /* ── Stats ── */
        .stats-table { width: 100%; border-collapse: separate; border-spacing: 6px 0; margin-bottom: 14px; }
        .stats-table td { border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px 10px; vertical-align: top; }
        .stat-label { font-size: 7px; text-transform: uppercase; letter-spacing: 0.05em; color: #9ca3af; }
        .stat-value { font-size: 13px; font-weight: bold; margin-top: 2px; }
        .s-blue   .stat-value { color: #1d4ed8; }
        .s-green  .stat-value { color: #15803d; }
        .s-yellow .stat-value { color: #a16207; }
        .s-gray   .stat-value { color: #4b5563; }
        .s-red    .stat-value { color: #b91c1c; }

        /* ── Main table ── */
        .main-table { width: 100%; border-collapse: collapse; }
        .main-table thead { background: #f3f4f6; }
        .main-table th { padding: 7px 8px; text-align: left; font-size: 7px; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; border-bottom: 1px solid #e5e7eb; font-weight: bold; }
        .main-table td { padding: 7px 8px; border-bottom: 1px solid #f3f4f6; vertical-align: middle; font-size: 8.5px; }
        .main-table tbody tr:nth-child(even) td { background: #fafafa; }

        .name-main { font-weight: 600; color: #111827; }
        .name-sub  { color: #9ca3af; font-size: 8px; }

        /* ── Badges ── */
        .badge        { display: inline-block; padding: 2px 7px; border-radius: 999px; font-size: 7.5px; font-weight: 600; }
        .badge-green  { background: #dcfce7; color: #15803d; }
        .badge-yellow { background: #fef9c3; color: #a16207; }
        .badge-gray   { background: #f3f4f6; color: #4b5563; }
        .badge-red    { background: #fee2e2; color: #b91c1c; }

        /* ── Progress bar ── */
        .progress-wrap { background: #e5e7eb; border-radius: 999px; height: 5px; width: 48px; display: inline-block; vertical-align: middle; }
        .progress-fill { background: #22c55e; border-radius: 999px; height: 5px; }

        /* ── Footer ── */
        .footer { margin-top: 16px; border-top: 1px solid #e5e7eb; padding-top: 8px; font-size: 8px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>

{{-- ── HEADER ── --}}
<table class="header-table" style="margin-bottom:14px;">
    <tr>
        <td style="vertical-align:middle;">
            <div class="header-title">Laporan Penghuni</div>
            <div class="header-sub">RumahKos — Rekap data seluruh penghuni kos</div>
        </td>
        <td style="vertical-align:middle;">
            <div class="header-meta">
                Dicetak: {{ $generatedAt }}<br>
                Total: {{ $residents->count() }} penghuni
            </div>
        </td>
    </tr>
</table>

{{-- ── STATS ── --}}
@php
    $total     = $residents->count();
    $active    = $residents->where('status', 'active')->count();
    $inactive  = $residents->where('status', 'inactive')->count();
    $expired   = $residents->where('status', 'expired')->count();
    $cancelled = $residents->where('status', 'cancelled')->count();
@endphp

<table class="stats-table">
    <tr>
        <td class="s-blue">
            <div class="stat-label">Total</div>
            <div class="stat-value">{{ $total }}</div>
        </td>
        <td class="s-green">
            <div class="stat-label">Aktif</div>
            <div class="stat-value">{{ $active }}</div>
        </td>
        <td class="s-yellow">
            <div class="stat-label">Inactive</div>
            <div class="stat-value">{{ $inactive }}</div>
        </td>
        <td class="s-gray">
            <div class="stat-label">Expired</div>
            <div class="stat-value">{{ $expired }}</div>
        </td>
        <td class="s-red">
            <div class="stat-label">Cancelled</div>
            <div class="stat-value">{{ $cancelled }}</div>
        </td>
    </tr>
</table>

{{-- ── TABLE ── --}}
<table class="main-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Kamar</th>
            <th>Properti</th>
            <th>Tgl Masuk</th>
            <th>Tgl Keluar</th>
            <th>Durasi</th>
            <th>Bayar</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($residents as $i => $r)
            @php
                $paid     = $r->payments->where('status', 'paid')->count();
                $total    = $r->payments->count();
                $duration = \Carbon\Carbon::parse($r->start_date)->diffInMonths(\Carbon\Carbon::parse($r->end_date));
                $badge = match($r->status) {
                    'active'    => 'badge-green',
                    'inactive'  => 'badge-yellow',
                    'expired'   => 'badge-gray',
                    'cancelled' => 'badge-red',
                    default     => 'badge-gray',
                };
                $label = match($r->status) {
                    'active'    => 'Aktif',
                    'inactive'  => 'Inactive',
                    'expired'   => 'Expired',
                    'cancelled' => 'Cancelled',
                    default     => $r->status,
                };
                $pct = $total > 0 ? round($paid / $total * 100) : 0;
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                    <div class="name-main">{{ $r->user->name }}</div>
                    <div class="name-sub">{{ $r->user->email }}</div>
                </td>
                <td>{{ $r->room->name ?? '-' }}</td>
                <td>{{ $r->room->property->name ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($r->start_date)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($r->end_date)->format('d/m/Y') }}</td>
                <td>{{ $duration }} bln</td>
                <td>
                    <table style="border-collapse:collapse; display:inline-table; vertical-align:middle;">
                        <tr>
                            <td style="padding:0; vertical-align:middle;">
                                <div class="progress-wrap">
                                    <div class="progress-fill" style="width:{{ $pct }}%;"></div>
                                </div>
                            </td>
                            <td style="padding:0 0 0 4px; vertical-align:middle; font-size:8px; color:#6b7280; white-space:nowrap;">
                                {{ $paid }}/{{ $total }}
                            </td>
                        </tr>
                    </table>
                </td>
                <td><span class="badge {{ $badge }}">{{ $label }}</span></td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- ── FOOTER ── --}}
<div class="footer">
    Laporan ini dibuat secara otomatis oleh sistem RumahKos pada {{ $generatedAt }}
</div>

</body>
</html>