<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9px; color: #1f2937; background: #fff; padding: 30px 36px; }

        /* ── Header ── */
        .header-table { width: 100%; border-collapse: collapse; border-bottom: 2px solid #2563eb; padding-bottom: 12px; margin-bottom: 16px; }
        .brand-name { font-size: 18px; font-weight: bold; color: #1e3a8a; }
        .brand-tagline { font-size: 8px; color: #9ca3af; margin-top: 2px; }
        .doc-title { font-size: 13px; font-weight: bold; color: #111827; text-align: right; }
        .doc-meta { font-size: 7.5px; color: #9ca3af; text-align: right; line-height: 1.7; margin-top: 3px; }

        /* ── Stats ── */
        .stats-table { width: 100%; border-collapse: separate; border-spacing: 6px 0; margin-bottom: 16px; }
        .stats-table td { width: 25%; border: 1px solid #e5e7eb; border-top: 3px solid #e5e7eb; border-radius: 4px; padding: 9px 10px; vertical-align: top; }
        .stat-label { font-size: 7px; text-transform: uppercase; letter-spacing: 0.07em; color: #9ca3af; font-weight: bold; }
        .stat-value { font-size: 11px; font-weight: bold; margin-top: 4px; line-height: 1.2; }
        .s-blue   { border-top: 3px solid #2563eb !important; }
        .s-blue   .stat-value { color: #1d4ed8; }
        .s-green  { border-top: 3px solid #16a34a !important; }
        .s-green  .stat-value { color: #15803d; }
        .s-yellow { border-top: 3px solid #ca8a04 !important; }
        .s-yellow .stat-value { color: #92400e; }
        .s-red    { border-top: 3px solid #dc2626 !important; }
        .s-red    .stat-value { color: #b91c1c; }

        /* ── Section label ── */
        .section-label { font-size: 8px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.08em; color: #6b7280; margin-bottom: 8px; }

        /* ── Main table ── */
        .main-table { width: 100%; border-collapse: collapse; }
        .main-table thead th { background: #f3f4f6; padding: 7px 8px; text-align: left; font-size: 7.5px; text-transform: uppercase; letter-spacing: 0.06em; color: #374151; font-weight: bold; border-bottom: 1px solid #d1d5db; border-top: 1px solid #e5e7eb; }
        .main-table thead th.r { text-align: right; }
        .main-table tbody td { padding: 7px 8px; font-size: 8.5px; color: #374151; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
        .main-table tbody tr:nth-child(even) td { background: #fafafa; }

        .td-index  { color: #d1d5db; font-size: 7.5px; width: 18px; }
        .td-amount { text-align: right; font-weight: 700; color: #111827; }
        .name-main { font-weight: 600; color: #111827; }
        .name-sub  { color: #9ca3af; font-size: 7.5px; margin-top: 1px; }

        /* ── Badges ── */
        .badge { display: inline-block; padding: 2px 8px; border-radius: 999px; font-size: 7.5px; font-weight: 700; }
        .b-paid      { background: #dcfce7; color: #166534; }
        .b-pending   { background: #fef9c3; color: #854d0e; }
        .b-failed    { background: #fee2e2; color: #991b1b; }
        .b-cancelled { background: #f3f4f6; color: #4b5563; }

        .overdue     { color: #dc2626; font-weight: 700; }
        .overdue-tag { font-size: 6.5px; font-weight: 700; color: #dc2626; background: #fee2e2; padding: 1px 3px; border-radius: 2px; }

        /* ── Total row ── */
        .total-row td { background: #f8fafc; border-top: 2px solid #e5e7eb; font-weight: 700; font-size: 9px; padding: 8px 8px; color: #6b7280; }
        .total-amount { text-align: right; color: #1d4ed8; font-size: 11px; font-weight: 700; }

        /* ── Footer ── */
        .footer-table { width: 100%; border-collapse: collapse; margin-top: 18px; border-top: 1px solid #f3f4f6; padding-top: 10px; }
        .footer-brand { font-size: 8.5px; font-weight: bold; color: #2563eb; }
        .footer-note  { font-size: 7.5px; color: #d1d5db; text-align: right; }
    </style>
</head>
<body>

{{-- ── HEADER ── --}}
<table class="header-table" style="margin-bottom:16px;">
    <tr>
        <td style="vertical-align:middle; width:60%;">
            <span class="brand-name">RumahKos</span><br>
            <span class="brand-tagline">Sistem Manajemen Properti Kos</span>
        </td>
        <td style="vertical-align:middle; text-align:right;">
            <div class="doc-title">Laporan Keuangan</div>
            <div class="doc-meta">
                Dicetak: {{ $generatedAt }}<br>
                {{ $payments->count() }} tagihan
                @if(request('month')) &bull; {{ \Carbon\Carbon::parse(request('month').'-01')->format('M Y') }}@endif
            </div>
        </td>
    </tr>
</table>

{{-- ── STATS ── --}}
<table class="stats-table">
    <tr>
        <td class="s-blue">
            <div class="stat-label">Total Tagihan</div>
            <div class="stat-value">Rp {{ number_format($stats['total_tagihan'], 0, ',', '.') }}</div>
        </td>
        <td class="s-green">
            <div class="stat-label">Sudah Lunas</div>
            <div class="stat-value">Rp {{ number_format($stats['total_lunas'], 0, ',', '.') }}</div>
        </td>
        <td class="s-yellow">
            <div class="stat-label">Pending</div>
            <div class="stat-value">{{ $stats['total_pending'] }} tagihan</div>
        </td>
        <td class="s-red">
            <div class="stat-label">Gagal / Batal</div>
            <div class="stat-value">{{ $stats['total_failed'] }} tagihan</div>
        </td>
    </tr>
</table>

{{-- ── TABLE ── --}}
<div class="section-label">Detail Pembayaran</div>

@php
    $badgeClass = ['paid'=>'b-paid','pending'=>'b-pending','failed'=>'b-failed','cancelled'=>'b-cancelled'];
    $badgeLabel = ['paid'=>'Lunas','pending'=>'Pending','failed'=>'Gagal','cancelled'=>'Batal'];
    $grandTotal = 0;
@endphp

<table class="main-table">
    <thead>
        <tr>
            <th style="width:18px;">#</th>
            <th>Penghuni</th>
            <th>Kamar</th>
            <th>Bulan Tagih</th>
            <th>Jatuh Tempo</th>
            <th class="r">Nominal</th>
            <th>Tgl Bayar</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($payments as $i => $p)
            @php
                $isOverdue = $p->status === 'pending' && $p->due_date && \Carbon\Carbon::parse($p->due_date)->isPast();
                $grandTotal += $p->amount;
            @endphp
            <tr>
                <td class="td-index">{{ $i + 1 }}</td>
                <td>
                    <div class="name-main">{{ $p->resident->user->name }}</div>
                    <div class="name-sub">{{ $p->resident->user->email }}</div>
                </td>
                <td>
                    <div class="name-main">{{ $p->resident->room->name ?? '-' }}</div>
                    <div class="name-sub">{{ $p->resident->room->property->name ?? '' }}</div>
                </td>
                <td>{{ \Carbon\Carbon::parse($p->billing_month)->format('M Y') }}</td>
                <td class="{{ $isOverdue ? 'overdue' : '' }}">
                    {{ $p->due_date ? \Carbon\Carbon::parse($p->due_date)->format('d/m/Y') : '-' }}
                    @if($isOverdue) <span class="overdue-tag">LEWAT</span>@endif
                </td>
                <td class="td-amount">Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                <td>{{ $p->paid_at ? \Carbon\Carbon::parse($p->paid_at)->format('d/m/Y') : '-' }}</td>
                <td>
                    <span class="badge {{ $badgeClass[$p->status] ?? 'b-cancelled' }}">
                        {{ $badgeLabel[$p->status] ?? $p->status }}
                    </span>
                </td>
            </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="5" style="text-align:right; color:#9ca3af;">Total Keseluruhan</td>
            <td class="total-amount">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
            <td colspan="2"></td>
        </tr>
    </tbody>
</table>

{{-- ── FOOTER ── --}}
<table class="footer-table" style="margin-top:18px;">
    <tr>
        <td style="vertical-align:middle;">
            <span class="footer-brand">RumahKos</span>
        </td>
        <td style="text-align:right; vertical-align:middle;">
            <span class="footer-note">Dibuat otomatis oleh sistem pada {{ $generatedAt }}</span>
        </td>
    </tr>
</table>

</body>
</html>