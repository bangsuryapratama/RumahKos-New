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
        .stat-box .value { font-size: 13px; font-weight: bold; margin-top: 2px; }
        .stat-blue .value { color: #1d4ed8; }
        .stat-green .value { color: #15803d; }
        .stat-yellow .value { color: #a16207; }
        .stat-gray .value { color: #4b5563; }
        .stat-red .value { color: #b91c1c; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #f3f4f6; }
        th { padding: 7px 8px; text-align: left; font-size: 7px; text-transform: uppercase;
             letter-spacing: 0.05em; color: #6b7280; border-bottom: 1px solid #e5e7eb; }
        td { padding: 7px 8px; border-bottom: 1px solid #f3f4f6; vertical-align: top; }
        tr:last-child td { border-bottom: none; }
        .name { font-weight: 600; color: #111827; }
        .email { color: #9ca3af; font-size: 8px; }
        .badge { display: inline-block; padding: 2px 7px; border-radius: 999px; font-size: 7.5px; font-weight: 600; }
        .badge-green  { background: #dcfce7; color: #15803d; }
        .badge-yellow { background: #fef9c3; color: #a16207; }
        .badge-gray   { background: #f3f4f6; color: #4b5563; }
        .badge-red    { background: #fee2e2; color: #b91c1c; }
        .progress-bar { background: #e5e7eb; border-radius: 999px; height: 4px; width: 48px; display: inline-block; vertical-align: middle; }
        .progress-fill { background: #22c55e; border-radius: 999px; height: 4px; }
        .footer { margin-top: 16px; border-top: 1px solid #e5e7eb; padding-top: 8px;
                  font-size: 8px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>

<div class="header clearfix">
    <div class="meta">
        <p>Dicetak: {{ $generatedAt }}</p>
        <p>Total: {{ $residents->count() }} penghuni</p>
    </div>
    <h1>Laporan Penghuni</h1>
    <p>RumahKos — Rekap data seluruh penghuni kos</p>
</div>

{{-- Stats --}}
<div class="stats">
    @php
        $total     = $residents->count();
        $active    = $residents->where('status', 'active')->count();
        $inactive  = $residents->where('status', 'inactive')->count();
        $expired   = $residents->where('status', 'expired')->count();
        $cancelled = $residents->where('status', 'cancelled')->count();
    @endphp
    <div class="stat-box stat-blue">
        <div class="label">Total</div>
        <div class="value">{{ $total }}</div>
    </div>
    <div class="stat-box stat-green">
        <div class="label">Aktif</div>
        <div class="value">{{ $active }}</div>
    </div>
    <div class="stat-box stat-yellow">
        <div class="label">Inactive</div>
        <div class="value">{{ $inactive }}</div>
    </div>
    <div class="stat-box stat-gray">
        <div class="label">Expired</div>
        <div class="value">{{ $expired }}</div>
    </div>
    <div class="stat-box stat-red">
        <div class="label">Cancelled</div>
        <div class="value">{{ $cancelled }}</div>
    </div>
</div>

<table>
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
                $pct = $total > 0 ? round($paid/$total*100) : 0;
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
                <td>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $pct }}%"></div>
                    </div>
                    <span style="font-size:8px; color:#6b7280; margin-left:4px;">{{ $paid }}/{{ $total }}</span>
                </td>
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