<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/webp" href="{{ asset('favicon.webp') }}">
    <link rel="icon" type="image/webp" sizes="64x64" href="{{ asset('favicon.webp') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.webp') }}">
    <link rel="preload" as="image" href="{{ asset('images/auth-login.webp') }}" type="image/webp" fetchpriority="high">
    <title>Dashboard Penghuni - RumahKos</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            color: #0f172a;
            background-color: #f8fafc;
            overflow-x: hidden;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        .nav-item.active {
            background-color: #eff6ff;
            color: #1d4ed8;
            border-color: #bfdbfe;
            font-weight: 800;
        }
        .mob-tab-btn.active {
            color: #2563eb;
            font-weight: 800;
        }

        .doc-canvas-wrap {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            background: #0f172a;
            line-height: 0;
            user-select: none;
            -webkit-user-select: none;
            border: 1px solid #e2e8f0;
        }
        .doc-canvas-wrap canvas {
            width: 100%;
            height: auto;
            display: block;
            pointer-events: none;
        }
        .doc-canvas-wrap::after {
            content: '';
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(
                45deg,
                rgba(255,255,255,0.02) 0px,
                rgba(255,255,255,0.02) 1px,
                transparent 1px,
                transparent 8px
            );
            pointer-events: none;
        }
        .doc-secure-label {
            position: absolute;
            bottom: 8px;
            right: 8px;
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(4px);
            color: #ffffff;
            font-size: 10px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 8px;
            pointer-events: none;
        }
        .doc-canvas-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 28px 16px;
            color: rgba(255,255,255,0.55);
            font-size: 12px;
            font-weight: 600;
        }
        .upload-dropzone {
            border: 2px dashed #cbd5e1;
            border-radius: 14px;
            padding: 20px 14px;
            text-align: center;
            cursor: pointer;
            transition: all .2s ease;
            background: #f8fafc;
        }
        .upload-dropzone:hover,
        .upload-dropzone.dragover {
            border-color: #2563eb;
            background: #eff6ff;
        }
        .upload-dropzone input[type="file"] { display: none; }
        @keyframes rkFieldPulse {
            0%   { background-color: #dbeafe; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.25); }
            100% { background-color: #ffffff; border-color: #cbd5e1; box-shadow: none; }
        }
        .autofill-highlight {
            animation: rkFieldPulse 2.4s ease-out;
        }
    </style>
</head>
<body class="bg-slate-50 antialiased pb-24 lg:pb-10">

@include('landing.navbar')

@php
    $overduePayments = collect();
    $unpaidPaymentsCount = 0;
    $nextPayment = null;

    if ($resident) {
        $overduePayments = $resident->payments
            ->filter(fn($p) => $p->status === 'pending' && $p->due_date->isPast())
            ->sortBy('due_date');
        $unpaidPaymentsCount = $resident->payments->where('status', 'pending')->count();
        $nextPayment = $resident->payments->where('status', 'pending')->sortBy('due_date')->first();
    }

    $profile = $user->profile;
    $hasName  = !empty($user->name);
    $hasPhone = !empty($profile?->phone);
    $hasNik   = !empty($profile?->identity_number);
    $hasKtp   = !empty($profile?->ktp_photo);
    $profileComplete = $hasPhone && $hasNik && $hasKtp;

    $completionPercent = 0;
    if ($profile) {
        $fields = ['phone', 'identity_number', 'address', 'date_of_birth', 'gender', 'occupation', 'emergency_contact', 'ktp_photo'];
        $filled = 0;
        foreach ($fields as $field) {
            if ($profile->$field) $filled++;
        }
        $completionPercent = round(($filled / count($fields)) * 100);
    }

    $allPayments = $allResidents->flatMap->payments->sortByDesc('billing_month');
    $words = explode(' ', trim($user->name ?? 'Penghuni'));
    $initials = strtoupper(substr($words[0], 0, 1)) . (count($words) > 1 ? strtoupper(substr(end($words), 0, 1)) : '');
@endphp

{{-- ══════════════════════════════════════════════════════════════════
     HERO BANNER PENGHUNI (DENGAN BACKGROUND WEBP & STATS CARDS)
══════════════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-slate-900 pt-24 sm:pt-28 pb-16 sm:pb-20">
    <img src="{{ asset('images/auth-login.webp') }}"
         alt="Dashboard RumahKos"
         fetchpriority="high"
         decoding="sync"
         class="absolute inset-0 w-full h-full object-cover object-center opacity-35 pointer-events-none">
    <div class="absolute inset-0 bg-gradient-to-b from-slate-950/90 via-slate-900/80 to-slate-950/95 pointer-events-none"></div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 relative z-10">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5 sm:gap-4">
                <div class="w-13 h-13 sm:w-16 sm:h-16 rounded-2xl bg-blue-600 text-white font-extrabold text-lg sm:text-xl flex items-center justify-center shadow-lg border-2 border-white/20 shrink-0 overflow-hidden">
                    @if($user->avatar)
                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-full h-full object-cover"
                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                        <span style="display:none">{{ $initials }}</span>
                    @else
                        {{ $initials }}
                    @endif
                </div>
                <div>
                    <div class="text-xs font-bold text-sky-300">Portal Penghuni RumahKos</div>
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-white tracking-tight">
                        Halo, {{ $user->name }}
                    </h1>
                    <p class="text-xs sm:text-sm text-white/75 mt-0.5">
                        {{ $user->email }}
                        @if($profile?->phone)
                            • {{ $profile->phone }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('rooms.search') }}"
                   class="px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 border border-white/15 text-white text-xs font-bold flex items-center gap-1.5 transition-all">
                    <img src="{{ asset('images/icons/ic-search-building.webp') }}" alt="" class="w-4 h-4 object-contain">
                    <span>Cari Kamar Kos</span>
                </a>
                <a href="{{ route('tenant.bookings.index') }}"
                   class="px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold flex items-center gap-1.5 shadow-sm transition-all">
                    <img src="{{ asset('images/icons/ic-price.webp') }}" alt="" class="w-4 h-4 object-contain">
                    <span>Riwayat Booking &amp; Tagihan</span>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════════
     FLOATING SUMMARY CARDS & WORKSPACE UTAMA
══════════════════════════════════════════════════════════════════ --}}
<main class="max-w-6xl mx-auto px-4 sm:px-6 -mt-10 sm:-mt-12 relative z-20">

    {{-- 4 Quick Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        {{-- Card 1: Status Hunian --}}
        <button type="button" onclick="showTab('kos')"
                class="bg-white rounded-2xl border border-slate-200/90 p-4 text-left shadow-sm hover:border-blue-300 transition-all">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Hunian Kos</span>
                <img src="{{ asset('images/icons/ic-search-building.webp') }}" alt="" class="w-5 h-5 object-contain">
            </div>
            @if($resident)
                <div class="text-sm sm:text-base font-extrabold text-slate-900 truncate">{{ $resident->room->name }}</div>
                <div class="text-xs font-semibold {{ $resident->status === 'active' ? 'text-emerald-600' : 'text-amber-600' }} mt-0.5">
                    {{ $resident->status === 'active' ? 'Aktif Menghuni' : 'Menunggu Pembayaran' }}
                </div>
            @else
                <div class="text-sm sm:text-base font-extrabold text-slate-900">Belum Ngekos</div>
                <div class="text-xs font-bold text-blue-600 mt-0.5">Pilih kamar sekarang →</div>
            @endif
        </button>

        {{-- Card 2: Tagihan & Pembayaran --}}
        <button type="button" onclick="showTab('payment')"
                class="bg-white rounded-2xl border {{ $overduePayments->count() > 0 ? 'border-rose-300 bg-rose-50/40' : 'border-slate-200/90' }} p-4 text-left shadow-sm hover:border-blue-300 transition-all">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tagihan Sewa</span>
                <img src="{{ asset('images/icons/ic-price.webp') }}" alt="" class="w-5 h-5 object-contain">
            </div>
            @if($resident)
                @if($overduePayments->count() > 0)
                    <div class="text-sm sm:text-base font-extrabold text-rose-600">{{ $overduePayments->count() }} Tagihan Jatuh Tempo</div>
                    <div class="text-xs font-bold text-rose-500 mt-0.5">Bayar sekarang →</div>
                @elseif($unpaidPaymentsCount > 0)
                    <div class="text-sm sm:text-base font-extrabold text-amber-600">{{ $unpaidPaymentsCount }} Menunggu Bayar</div>
                    <div class="text-xs font-semibold text-slate-500 mt-0.5">Lihat rincian tagihan →</div>
                @else
                    <div class="text-sm sm:text-base font-extrabold text-emerald-600">Semua Lunas</div>
                    <div class="text-xs font-semibold text-slate-500 mt-0.5">Tidak ada tunggakan</div>
                @endif
            @else
                <div class="text-sm sm:text-base font-extrabold text-slate-900">Rp 0</div>
                <div class="text-xs text-slate-400 mt-0.5">Belum ada tagihan</div>
            @endif
        </button>

        {{-- Card 3: Durasi Kontrak --}}
        <button type="button" onclick="showTab('kos')"
                class="bg-white rounded-2xl border border-slate-200/90 p-4 text-left shadow-sm hover:border-blue-300 transition-all">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Masa Sewa</span>
                <img src="{{ asset('images/icons/cat-semua.webp') }}" alt="" class="w-5 h-5 object-contain">
            </div>
            @if($resident)
                <div class="text-sm sm:text-base font-extrabold text-slate-900">{{ $resident->getDurationInMonths() }} Bulan</div>
                <div class="text-xs text-slate-500 mt-0.5 truncate">
                    s/d {{ $resident->end_date->format('d M Y') }}
                </div>
            @else
                <div class="text-sm sm:text-base font-extrabold text-slate-900">-</div>
                <div class="text-xs text-slate-400 mt-0.5">Kontrak belum aktif</div>
            @endif
        </button>

        {{-- Card 4: Kelengkapan Profil & KTP --}}
        <button type="button" onclick="showTab('profile')"
                class="bg-white rounded-2xl border {{ !$profileComplete ? 'border-amber-300' : 'border-slate-200/90' }} p-4 text-left shadow-sm hover:border-blue-300 transition-all">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Verifikasi Data</span>
                <img src="{{ asset('images/icons/cat-putra.webp') }}" alt="" class="w-5 h-5 object-contain">
            </div>
            <div class="text-sm sm:text-base font-extrabold {{ $profileComplete ? 'text-emerald-600' : 'text-amber-600' }}">
                {{ $profileComplete ? 'Siap Booking (100%)' : 'Data ' . $completionPercent . '% Lengkap' }}
            </div>
            <div class="text-xs text-slate-500 mt-0.5">
                {{ $profileComplete ? 'E-KTP & Telepon terverifikasi' : 'Lengkapi KTP & No. HP →' }}
            </div>
        </button>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-5 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between gap-3 text-xs sm:text-sm font-semibold">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 font-bold">✕</button>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center justify-between gap-3 text-xs sm:text-sm font-semibold">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 font-bold">✕</button>
        </div>
    @endif

    {{-- Alert Tunggakan Mendesak --}}
    @if($overduePayments->count() > 0)
        <div class="mb-6 bg-rose-50 border border-rose-200 rounded-2xl p-4 sm:p-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                <div>
                    <h2 class="text-sm sm:text-base font-extrabold text-rose-800">
                        Perhatian: Ada {{ $overduePayments->count() }} Tagihan Sewa yang Melewati Jatuh Tempo
                    </h2>
                    <p class="text-xs text-rose-600">
                        Total tunggakan: <span class="font-extrabold">Rp {{ number_format($overduePayments->sum('amount'), 0, ',', '.') }}</span> — Segera selesaikan pembayaran agar status hunian tetap aktif.
                    </p>
                </div>
                <a href="{{ route('tenant.bookings.index') }}" class="text-xs font-extrabold text-rose-700 hover:underline shrink-0">
                    Lihat Semua Tagihan →
                </a>
            </div>
            <div class="space-y-2">
                @foreach($overduePayments as $op)
                    <div class="flex flex-wrap items-center justify-between gap-2 bg-white border border-rose-200/80 rounded-xl px-3.5 py-2.5 text-xs">
                        <div>
                            <span class="font-extrabold text-slate-900">{{ $op->billing_month->format('F Y') }}</span>
                            <span class="text-slate-400 mx-1">•</span>
                            <span class="text-slate-500">Jatuh tempo {{ $op->due_date->format('d M Y') }} ({{ $op->due_date->diffForHumans() }})</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="font-extrabold text-slate-900">Rp {{ number_format($op->amount, 0, ',', '.') }}</span>
                            <a href="{{ route('tenant.payment.midtrans', $op->id) }}"
                               class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-lg transition-colors">
                                Bayar Sekarang
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Checklist Langkah Cepat jika Profil Belum Lengkap --}}
    @if(!$profileComplete)
        <div class="mb-6 bg-white border border-amber-200 rounded-2xl p-4 sm:p-5 shadow-2xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                <div>
                    <h2 class="text-sm sm:text-base font-extrabold text-slate-900">
                        3 Langkah Cepat Agar Akun Siap Menyewa Kamar
                    </h2>
                    <p class="text-xs text-slate-500">
                        Lengkapi syarat wajib di bawah ini supaya kamu bisa langsung booking kamar kos pilihanmu.
                    </p>
                </div>
                <span class="text-xs font-extrabold text-blue-600">{{ $completionPercent }}% Selesai</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                <div class="p-3 rounded-xl border {{ $hasPhone ? 'border-emerald-200 bg-emerald-50/50' : 'border-slate-200 bg-slate-50' }} flex items-center justify-between">
                    <div>
                        <div class="text-xs font-bold text-slate-800">1. Nomor WhatsApp</div>
                        <div class="text-[11px] {{ $hasPhone ? 'text-emerald-600 font-semibold' : 'text-slate-400' }}">
                            {{ $hasPhone ? $profile->phone : 'Belum diisi' }}
                        </div>
                    </div>
                    <span class="text-xs font-extrabold {{ $hasPhone ? 'text-emerald-600' : 'text-amber-600' }}">
                        {{ $hasPhone ? '✓ Lengkap' : 'Wajib' }}
                    </span>
                </div>

                <div id="chk-nik-card" class="p-3 rounded-xl border {{ $hasNik ? 'border-emerald-200 bg-emerald-50/50' : 'border-slate-200 bg-slate-50' }} flex items-center justify-between transition-all">
                    <div>
                        <div class="text-xs font-bold text-slate-800">2. Nomor NIK KTP</div>
                        <div id="chk-nik-sub" class="text-[11px] {{ $hasNik ? 'text-emerald-600 font-semibold' : 'text-slate-400' }}">
                            {{ $hasNik ? '16 Digit Tersimpan' : 'Belum diisi' }}
                        </div>
                    </div>
                    <span id="chk-nik-status" class="text-xs font-extrabold {{ $hasNik ? 'text-emerald-600' : 'text-amber-600' }}">
                        {{ $hasNik ? '✓ Lengkap' : 'Wajib' }}
                    </span>
                </div>

                <div id="chk-ktp-card" class="p-3 rounded-xl border {{ $hasKtp ? 'border-emerald-200 bg-emerald-50/50' : 'border-slate-200 bg-slate-50' }} flex items-center justify-between transition-all">
                    <div>
                        <div class="text-xs font-bold text-slate-800">3. Upload Foto e-KTP</div>
                        <div id="chk-ktp-sub" class="text-[11px] {{ $hasKtp ? 'text-emerald-600 font-semibold' : 'text-slate-400' }}">
                            {{ $hasKtp ? 'Dokumen Tersimpan' : 'Belum diupload' }}
                        </div>
                    </div>
                    <span id="chk-ktp-status" class="text-xs font-extrabold {{ $hasKtp ? 'text-emerald-600' : 'text-amber-600' }}">
                        {{ $hasKtp ? '✓ Lengkap' : 'Wajib' }}
                    </span>
                </div>
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════════
         MAIN WORKSPACE: SIDEBAR MENU (KIRI) + ACTIVE CONTENT PANEL (KANAN)
    ══════════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        {{-- SIDEBAR MENU DESKTOP & TABLET --}}
        <aside class="lg:col-span-3 space-y-4">
            <div class="bg-white rounded-2xl border border-slate-200/90 p-3 shadow-2xs">
                <div class="px-3 py-2 text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">
                    Menu Penghuni
                </div>
                <nav class="space-y-1">
                    <button type="button" onclick="showTab('kos')" id="tab-kos"
                            class="nav-item w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-slate-700 hover:bg-slate-50 border border-transparent transition-all">
                        <span class="flex items-center gap-2.5">
                            <img src="{{ asset('images/icons/ic-search-building.webp') }}" alt="" class="w-4 h-4 object-contain">
                            <span>Kos &amp; Kamar Saya</span>
                        </span>
                        <span>→</span>
                    </button>

                    <button type="button" onclick="showTab('payment')" id="tab-payment"
                            class="nav-item w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-slate-700 hover:bg-slate-50 border border-transparent transition-all">
                        <span class="flex items-center gap-2.5">
                            <img src="{{ asset('images/icons/ic-price.webp') }}" alt="" class="w-4 h-4 object-contain">
                            <span>Tagihan &amp; Pembayaran</span>
                        </span>
                        @if($overduePayments->count() > 0)
                            <span class="text-xs font-extrabold text-rose-600">{{ $overduePayments->count() }}</span>
                        @else
                            <span>→</span>
                        @endif
                    </button>

                    <button type="button" onclick="showTab('profile')" id="tab-profile"
                            class="nav-item active w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-slate-700 hover:bg-slate-50 border border-transparent transition-all">
                        <span class="flex items-center gap-2.5">
                            <img src="{{ asset('images/icons/cat-putra.webp') }}" alt="" class="w-4 h-4 object-contain">
                            <span>Profil &amp; Dokumen KTP</span>
                        </span>
                        <span>→</span>
                    </button>
                </nav>

                <div class="my-2.5 border-t border-slate-100"></div>

                <a href="{{ route('tenant.bookings.index') }}"
                   class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-600 hover:text-blue-600 hover:bg-slate-50 transition-all">
                    <span>Halaman Semua Booking</span>
                    <span>↗</span>
                </a>
                <a href="https://wa.me/6283841806357?text={{ urlencode('Halo Admin RumahKos, saya ' . $user->name . ' ingin bertanya terkait hunian kos.') }}"
                   target="_blank" rel="noopener"
                   class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-emerald-700 hover:bg-emerald-50 transition-all">
                    <span>Bantuan CS WhatsApp</span>
                    <span>↗</span>
                </a>

                <form method="POST" action="{{ route('tenant.logout') }}" class="mt-1">
                    @csrf
                    <button type="submit"
                            class="w-full text-left px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-rose-600 hover:bg-rose-50 transition-all">
                        Keluar Akun
                    </button>
                </form>
            </div>
        </aside>

        {{-- PANEL KONTEN UTAMA (9 KOLOM) --}}
        <div class="lg:col-span-9">

            {{-- ══════════════════════════════════════════════════════════
                 TAB 1: PROFIL & DOKUMEN IDENTITAS (DEFAULT)
            ══════════════════════════════════════════════════════════ --}}
            <div id="content-profile" class="tab-content bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-2xs">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 mb-5 border-b border-slate-100">
                    <div>
                        <h2 class="text-base sm:text-lg font-extrabold text-slate-900">Data Diri &amp; Dokumen Identitas</h2>
                        <p class="text-xs text-slate-500">Pastikan data sesuai dengan e-KTP untuk mempermudah proses verifikasi sewa kos.</p>
                    </div>
                    <button type="submit" form="profileForm"
                            class="hidden sm:inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold rounded-xl shadow-xs transition-all">
                        Simpan Perubahan
                    </button>
                </div>

                <form method="POST" action="{{ route('tenant.profile.update') }}"
                      enctype="multipart/form-data" class="space-y-6" id="profileForm">
                    @csrf
                    @method('PUT')

                    {{-- Bagian 1: Informasi Utama --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap (Sesuai KTP) *</label>
                            <input type="text" id="input_name" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-xs sm:text-sm font-semibold text-slate-800">
                            @error('name')<p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Email Akun</label>
                            <input type="email" value="{{ $user->email }}" disabled
                                   class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl bg-slate-100 text-slate-500 text-xs sm:text-sm font-semibold cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Telepon / WhatsApp Aktif *</label>
                            <input type="tel" id="input_phone" name="phone"
                                   value="{{ old('phone', $profile?->phone ?? '') }}"
                                   placeholder="Contoh: 081234567890"
                                   class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-xs sm:text-sm font-semibold text-slate-800">
                            @error('phone')<p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="flex items-center justify-between text-xs font-bold text-slate-700 mb-1.5">
                                <span>Nomor Induk Kependudukan (NIK KTP) *</span>
                                <span id="nikAutoHint" class="text-[11px] font-semibold text-blue-600">Bisa otomatis dari foto KTP</span>
                            </label>
                            <input type="text" id="input_identity_number" name="identity_number"
                                   value="{{ old('identity_number', $profile?->identity_number ?? '') }}"
                                   placeholder="16 digit angka NIK" maxlength="16"
                                   oninput="handleNikInput(this.value)"
                                   class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono tracking-wider text-xs sm:text-sm font-bold text-slate-800">
                            @error('identity_number')<p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Tanggal Lahir <span class="font-normal text-slate-400">(Min. 17 tahun)</span>
                            </label>
                            <input type="date" id="input_date_of_birth" name="date_of_birth"
                                   value="{{ old('date_of_birth', $profile?->date_of_birth?->format('Y-m-d') ?? '') }}"
                                   max="{{ \Carbon\Carbon::now()->subYears(17)->format('Y-m-d') }}"
                                   class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-xs sm:text-sm font-semibold text-slate-800">
                            @error('date_of_birth')<p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Kelamin</label>
                            <select id="input_gender" name="gender"
                                    class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-xs sm:text-sm font-semibold text-slate-800 bg-white">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="male"   {{ old('gender', $profile?->gender ?? '') == 'male'   ? 'selected' : '' }}>Laki-laki</option>
                                <option value="female" {{ old('gender', $profile?->gender ?? '') == 'female' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Pekerjaan / Kampus</label>
                            <input type="text" id="input_occupation" name="occupation"
                                   value="{{ old('occupation', $profile?->occupation ?? '') }}"
                                   placeholder="Contoh: Mahasiswa ITB / Karyawan Swasta"
                                   class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-xs sm:text-sm font-semibold text-slate-800">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Kontak Darurat (Nama &amp; Hubungan)</label>
                            <input type="text" id="input_emergency_contact_name" name="emergency_contact_name"
                                   value="{{ old('emergency_contact_name', $profile?->emergency_contact_name ?? '') }}"
                                   placeholder="Contoh: Budi Santoso (Orang Tua)"
                                   class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-xs sm:text-sm font-semibold text-slate-800">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Telepon Kontak Darurat</label>
                            <input type="tel" id="input_emergency_contact" name="emergency_contact"
                                   value="{{ old('emergency_contact', $profile?->emergency_contact ?? '') }}"
                                   placeholder="Contoh: 081298765432"
                                   class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-xs sm:text-sm font-semibold text-slate-800">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Asal Lengkap</label>
                            <input type="text" id="input_address" name="address"
                                   value="{{ old('address', $profile?->address ?? '') }}"
                                   placeholder="Jalan, Kecamatan, Kota/Kabupaten"
                                   class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-xs sm:text-sm font-semibold text-slate-800">
                        </div>
                    </div>

                    {{-- Bagian 2: Upload Dokumen Identitas Terenkripsi --}}
                    <div class="pt-5 border-t border-slate-100">
                        <div class="mb-4">
                            <h3 class="text-sm sm:text-base font-extrabold text-slate-900">Dokumen Identitas Resmi</h3>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Dokumen dienkripsi dan dilindungi watermark keamanan. Mendukung format JPG, PNG, atau WebP (Maksimal 2 MB).
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- KTP Card --}}
                            <div class="p-4 rounded-2xl border border-slate-200/90 bg-slate-50/50 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <h4 class="text-xs sm:text-sm font-extrabold text-slate-900">Foto e-KTP (Wajib)</h4>
                                            <p class="text-[11px] text-slate-500">Syarat utama verifikasi sewa kamar</p>
                                        </div>
                                        <span class="text-xs font-extrabold {{ $profile?->ktp_photo ? 'text-emerald-600' : 'text-amber-600' }}">
                                            {{ $profile?->ktp_photo ? '✓ Terupload' : 'Belum Ada' }}
                                        </span>
                                    </div>

                                    @if($profile?->ktp_photo)
                                        <div class="doc-canvas-wrap mb-2.5" id="ktp-existing-wrap">
                                            <canvas id="ktp-existing-canvas"></canvas>
                                            <div class="doc-secure-label">Dokumen Terenkripsi</div>
                                        </div>
                                        <script>
                                            (function () {
                                                var _r = @json(route('tenant.document.global.ktp'));
                                                document.addEventListener('DOMContentLoaded', function () {
                                                    fetchAndRenderSecure(_r, 'ktp-existing-canvas', 'ktp-existing-wrap');
                                                });
                                            })();
                                        </script>
                                        <label class="inline-flex items-center gap-2 text-xs font-semibold text-rose-600 cursor-pointer mb-2">
                                            <input type="checkbox" name="delete_ktp" value="1" class="rounded border-slate-300 text-rose-600">
                                            <span>Hapus dokumen KTP lama</span>
                                        </label>
                                    @endif

                                    <div class="upload-dropzone"
                                         onclick="document.getElementById('ktp_photo').click()"
                                         ondragover="event.preventDefault();this.classList.add('dragover')"
                                         ondragleave="this.classList.remove('dragover')"
                                         ondrop="handleDrop(event,'ktp_photo','ktp-new-wrap')">
                                        <input type="file" id="ktp_photo" name="ktp_photo"
                                               accept="image/jpeg,image/png,image/jpg,image/webp"
                                               onchange="handleFileChange(this,'ktp-new-wrap')">
                                        <div class="text-xs sm:text-sm font-bold text-blue-600">Klik atau seret foto e-KTP ke sini</div>
                                        <div class="text-[11px] text-slate-400 mt-0.5">Otomatis deteksi kartu e-KTP &amp; isi NIK, Tgl Lahir, Gender</div>
                                    </div>
                                    <div id="ktp-ocr-status" class="hidden mt-3"></div>
                                    <div id="ktp-new-wrap" class="mt-3"></div>
                                    @error('ktp_photo')<p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>@enderror
                                </div>

                                <div class="mt-3 pt-3 border-t border-slate-200/70 text-[11px] text-slate-500">
                                    Pastikan NIK dan nama pada KTP terlihat tajam dan tidak tertutup pantulan cahaya.
                                </div>
                            </div>

                            {{-- SIM Card --}}
                            <div class="p-4 rounded-2xl border border-slate-200/90 bg-slate-50/50 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <h4 class="text-xs sm:text-sm font-extrabold text-slate-900">Foto SIM (Opsional)</h4>
                                            <p class="text-[11px] text-slate-500">Identitas pendukung bagi pengguna kendaraan</p>
                                        </div>
                                        <span class="text-xs font-extrabold {{ $profile?->sim_photo ? 'text-emerald-600' : 'text-slate-400' }}">
                                            {{ $profile?->sim_photo ? '✓ Terupload' : 'Opsional' }}
                                        </span>
                                    </div>

                                    @if($profile?->sim_photo)
                                        <div class="doc-canvas-wrap mb-2.5" id="sim-existing-wrap">
                                            <canvas id="sim-existing-canvas"></canvas>
                                            <div class="doc-secure-label">Dokumen Terenkripsi</div>
                                        </div>
                                        <script>
                                            (function () {
                                                var _r = @json(route('tenant.document.global.sim'));
                                                document.addEventListener('DOMContentLoaded', function () {
                                                    fetchAndRenderSecure(_r, 'sim-existing-canvas', 'sim-existing-wrap');
                                                });
                                            })();
                                        </script>
                                        <label class="inline-flex items-center gap-2 text-xs font-semibold text-rose-600 cursor-pointer mb-2">
                                            <input type="checkbox" name="delete_sim" value="1" class="rounded border-slate-300 text-rose-600">
                                            <span>Hapus dokumen SIM lama</span>
                                        </label>
                                    @endif

                                    <div class="upload-dropzone"
                                         onclick="document.getElementById('sim_photo').click()"
                                         ondragover="event.preventDefault();this.classList.add('dragover')"
                                         ondragleave="this.classList.remove('dragover')"
                                         ondrop="handleDrop(event,'sim_photo','sim-new-wrap')">
                                        <input type="file" id="sim_photo" name="sim_photo"
                                               accept="image/jpeg,image/png,image/jpg,image/webp"
                                               onchange="handleFileChange(this,'sim-new-wrap')">
                                        <div class="text-xs sm:text-sm font-bold text-blue-600">Klik atau seret foto SIM ke sini</div>
                                        <div class="text-[11px] text-slate-400 mt-0.5">JPG, PNG, WebP • Maksimal 2 MB</div>
                                    </div>
                                    <div id="sim-new-wrap" class="mt-3"></div>
                                    @error('sim_photo')<p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>@enderror
                                </div>

                                <div class="mt-3 pt-3 border-t border-slate-200/70 text-[11px] text-slate-500">
                                    Memudahkan pendataan parkir kendaraan bermotor di area properti kos.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end">
                        <button type="submit"
                                class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-extrabold text-xs sm:text-sm shadow-md shadow-blue-600/20 transition-all">
                            Simpan Profil &amp; Dokumen
                        </button>
                    </div>
                </form>
            </div>

            {{-- ══════════════════════════════════════════════════════════
                 TAB 2: KOS & KAMAR SAYA
            ══════════════════════════════════════════════════════════ --}}
            <div id="content-kos" class="tab-content hidden bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-2xs">
                <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
                    <div>
                        <h2 class="text-base sm:text-lg font-extrabold text-slate-900">Informasi Kos &amp; Kamar Saya</h2>
                        <p class="text-xs text-slate-500">Detail kontrak hunian kos yang sedang berjalan</p>
                    </div>
                    <a href="{{ route('rooms.search') }}" class="text-xs font-bold text-blue-600 hover:underline">
                        Jelajahi Kamar Lain →
                    </a>
                </div>

                @if($resident)
                    @php
                        $resRoom = $resident->room;
                        $resImg = (!empty($resRoom->image) && file_exists(public_path('storage/' . $resRoom->image)))
                            ? asset('storage/' . $resRoom->image)
                            : asset('images/default-room.webp');
                    @endphp
                    <div class="rounded-2xl border border-slate-200/90 overflow-hidden bg-slate-50/50 mb-5">
                        <div class="flex flex-col sm:flex-row">
                            <div class="sm:w-56 h-44 sm:h-auto shrink-0 bg-slate-200 overflow-hidden">
                                <img src="{{ $resImg }}" alt="{{ $resRoom->name }}" decoding="sync" class="w-full h-full object-cover">
                            </div>
                            <div class="p-4 sm:p-5 flex-1 flex flex-col justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2 text-xs font-bold text-blue-600 mb-1">
                                        <span>{{ $resRoom->property?->city ?? 'Bandung' }}</span>
                                        <span>•</span>
                                        <span>Lantai {{ $resRoom->floor ?? 1 }}</span>
                                        <span>•</span>
                                        <span class="{{ $resident->status === 'active' ? 'text-emerald-600' : 'text-amber-600' }}">
                                            {{ $resident->status === 'active' ? 'Kontrak Aktif' : 'Menunggu Pembayaran' }}
                                        </span>
                                    </div>
                                    <h3 class="text-base sm:text-lg font-extrabold text-slate-900">
                                        {{ $resRoom->name }} — {{ $resRoom->property?->name ?? 'RumahKos' }}
                                    </h3>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        {{ $resRoom->property?->address ?? '' }}
                                    </p>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-3 border-t border-slate-200/80 text-xs">
                                    <div>
                                        <span class="text-slate-400 block">Mulai Sewa</span>
                                        <span class="font-extrabold text-slate-800">{{ $resident->start_date->format('d M Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block">Berakhir Pada</span>
                                        <span class="font-extrabold text-slate-800">{{ $resident->end_date->format('d M Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block">Biaya Bulanan</span>
                                        <span class="font-extrabold text-blue-600">Rp {{ number_format($resRoom->price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2.5">
                        <a href="{{ route('tenant.bookings.index') }}"
                           class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold transition-colors">
                            Kelola Tagihan &amp; Kontrak Sewa
                        </a>
                        <a href="{{ route('rooms.detail', $resRoom) }}"
                           class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold transition-colors">
                            Lihat Halaman Kamar
                        </a>
                    </div>
                @else
                    <div class="text-center py-10 px-4 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                        <img src="{{ asset('images/icons/ic-search-building.webp') }}" alt="" class="w-12 h-12 mx-auto mb-3 object-contain opacity-80">
                        <h3 class="text-sm sm:text-base font-extrabold text-slate-900 mb-1">Kamu Belum Memiliki Kamar Kos Aktif</h3>
                        <p class="text-xs text-slate-500 max-w-md mx-auto mb-4">
                            Yuk pilih kamar kos &amp; co-living favoritmu di Bandung, Jakarta, Yogyakarta, atau Surabaya sekarang!
                        </p>
                        <a href="{{ route('rooms.search') }}"
                           class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-extrabold shadow-sm transition-all">
                            <span>Cari &amp; Pilih Kamar Kos</span>
                            <span>→</span>
                        </a>
                    </div>
                @endif
            </div>

            {{-- ══════════════════════════════════════════════════════════
                 TAB 3: TAGIHAN & RIWAYAT PEMBAYARAN
            ══════════════════════════════════════════════════════════ --}}
            <div id="content-payment" class="tab-content hidden bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-2xs">
                <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
                    <div>
                        <h2 class="text-base sm:text-lg font-extrabold text-slate-900">Tagihan &amp; Riwayat Pembayaran</h2>
                        <p class="text-xs text-slate-500">Daftar tagihan bulanan dan invoice pembayaran resmi</p>
                    </div>
                    <a href="{{ route('tenant.bookings.index') }}" class="text-xs font-bold text-blue-600 hover:underline">
                        Buka Halaman Semua Tagihan →
                    </a>
                </div>

                @if($allPayments->isNotEmpty())
                    <div class="space-y-3">
                        @foreach($allPayments->take(12) as $payment)
                            @php
                                $resForPayment = $allResidents->firstWhere('id', $payment->resident_id);
                                $isOverdue = $payment->status === 'pending' && $payment->due_date->isPast();
                            @endphp
                            <div class="rounded-xl border {{ $isOverdue ? 'border-rose-200 bg-rose-50/50' : 'border-slate-200/90 bg-white' }} p-3.5 sm:p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs sm:text-sm font-extrabold text-slate-900">
                                            Tagihan {{ $payment->billing_month->format('F Y') }}
                                        </span>
                                        <span class="text-xs font-bold
                                            {{ $payment->status === 'paid' ? 'text-emerald-600' : ($isOverdue ? 'text-rose-600' : ($payment->status === 'pending' ? 'text-amber-600' : 'text-slate-400')) }}">
                                            •
                                            @if($payment->status === 'paid') Lunas
                                            @elseif($isOverdue) Terlambat ({{ $payment->due_date->diffForHumans() }})
                                            @elseif($payment->status === 'pending') Menunggu Pembayaran
                                            @elseif($payment->status === 'failed') Gagal
                                            @else Dibatalkan
                                            @endif
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        {{ $resForPayment?->room?->name ?? 'Kamar Kos' }} • Jatuh tempo: {{ $payment->due_date->format('d M Y') }}
                                    </p>
                                </div>

                                <div class="flex items-center justify-between sm:justify-end gap-3 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                                    <div class="text-sm sm:text-base font-extrabold text-slate-900">
                                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                    </div>

                                    @if($payment->status === 'paid')
                                        <a href="{{ route('tenant.payment.invoice', $payment->id) }}"
                                           class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 text-xs font-bold text-slate-700 transition-colors">
                                            Unduh Invoice
                                        </a>
                                    @elseif($payment->status === 'pending')
                                        <a href="{{ route('tenant.payment.midtrans', $payment->id) }}"
                                           class="px-4 py-2 rounded-xl {{ $isOverdue ? 'bg-rose-600 hover:bg-rose-700' : 'bg-blue-600 hover:bg-blue-700' }} text-white text-xs font-extrabold transition-colors">
                                            Bayar Sekarang
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10 px-4 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                        <img src="{{ asset('images/icons/ic-price.webp') }}" alt="" class="w-12 h-12 mx-auto mb-3 object-contain opacity-80">
                        <h3 class="text-sm sm:text-base font-extrabold text-slate-900 mb-1">Belum Ada Tagihan Pembayaran</h3>
                        <p class="text-xs text-slate-500">Tagihan sewa bulanan akan muncul otomatis setelah kamu memesan kamar kos.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

</main>

{{-- ══════════════════════════════════════════════════════════════════
     STICKY BOTTOM BAR KHUSUS MOBILE (< lg) UNTUK PINDAH TAB CEPAT
══════════════════════════════════════════════════════════════════ --}}
<nav class="lg:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 px-2 py-2 grid grid-cols-4 text-center shadow-[0_-4px_12px_rgba(15,23,42,0.05)]">
    <button type="button" onclick="showTab('profile')" id="mob-tab-profile" class="mob-tab-btn active flex flex-col items-center justify-center gap-0.5 text-slate-500">
        <img src="{{ asset('images/icons/cat-putra.webp') }}" alt="" class="w-5 h-5 object-contain">
        <span class="text-[10px]">Profil &amp; KTP</span>
    </button>
    <button type="button" onclick="showTab('kos')" id="mob-tab-kos" class="mob-tab-btn flex flex-col items-center justify-center gap-0.5 text-slate-500">
        <img src="{{ asset('images/icons/ic-search-building.webp') }}" alt="" class="w-5 h-5 object-contain">
        <span class="text-[10px]">Kos Saya</span>
    </button>
    <button type="button" onclick="showTab('payment')" id="mob-tab-payment" class="mob-tab-btn flex flex-col items-center justify-center gap-0.5 text-slate-500">
        <img src="{{ asset('images/icons/ic-price.webp') }}" alt="" class="w-5 h-5 object-contain">
        <span class="text-[10px]">Tagihan</span>
    </button>
    <a href="{{ route('rooms.search') }}" class="flex flex-col items-center justify-center gap-0.5 text-slate-500 hover:text-blue-600">
        <img src="{{ asset('images/icons/cat-semua.webp') }}" alt="" class="w-5 h-5 object-contain">
        <span class="text-[10px] font-semibold">Cari Kos</span>
    </a>
</nav>

<script>
    function showTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.mob-tab-btn').forEach(el => el.classList.remove('active'));

        document.getElementById('content-' + tabName)?.classList.remove('hidden');
        document.getElementById('tab-' + tabName)?.classList.add('active');
        document.getElementById('mob-tab-profile')?.classList.toggle('active', tabName === 'profile');
        document.getElementById('mob-tab-kos')?.classList.toggle('active', tabName === 'kos');
        document.getElementById('mob-tab-payment')?.classList.toggle('active', tabName === 'payment');
    }

    function fetchAndRenderSecure(route, canvasId, wrapId) {
        const canvas = document.getElementById(canvasId);
        const wrap   = document.getElementById(wrapId);
        if (!canvas || !wrap) return;

        fetch(route, {
            method: 'GET',
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
        .then(res => {
            if (!res.ok) throw new Error('Akses ditolak.');
            return res.blob();
        })
        .then(blob => {
            var blobUrl = URL.createObjectURL(blob);
            var img = new Image();
            img.onload = function () {
                canvas.width  = img.naturalWidth;
                canvas.height = img.naturalHeight;
                canvas.getContext('2d').drawImage(img, 0, 0);
                URL.revokeObjectURL(blobUrl);
                img.src = '';
            };
            img.onerror = function () {
                URL.revokeObjectURL(blobUrl);
                showDocPlaceholder(wrap);
            };
            img.src = blobUrl;
        })
        .catch(() => showDocPlaceholder(wrap));

        canvas.addEventListener('contextmenu', e => e.preventDefault());
        canvas.addEventListener('dragstart',   e => e.preventDefault());
    }

    function showDocPlaceholder(wrap) {
        wrap.innerHTML =
            '<div class="doc-canvas-placeholder">' +
                '<span>Dokumen tersimpan aman di server</span>' +
            '</div>';
    }

    var MAX_BYTES = 2 * 1024 * 1024;
    var OK_TYPES  = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

    function handleDrop(event, inputId, wrapId) {
        event.preventDefault();
        event.currentTarget.classList.remove('dragover');
        var file = event.dataTransfer && event.dataTransfer.files && event.dataTransfer.files[0];
        if (!file) return;
        try {
            var dt = new DataTransfer();
            dt.items.add(file);
            document.getElementById(inputId).files = dt.files;
        } catch (e) {}
        processFile(file, wrapId, null);
    }

    function handleFileChange(input, wrapId) {
        var file = input.files && input.files[0];
        if (!file) return;
        processFile(file, wrapId, input);
    }

    function processFile(file, wrapId, inputEl) {
        var wrap = document.getElementById(wrapId);
        if (!wrap) return;
        wrap.innerHTML = '';

        if (OK_TYPES.indexOf(file.type) === -1) {
            wrap.innerHTML = fileErr('Format tidak valid. Gunakan JPG, PNG, atau WebP.');
            if (inputEl) inputEl.value = '';
            return;
        }
        if (file.size > MAX_BYTES) {
            wrap.innerHTML = fileErr('Ukuran ' + (file.size / 1024 / 1024).toFixed(2) + ' MB melebihi batas 2 MB.');
            if (inputEl) inputEl.value = '';
            return;
        }

        var reader = new FileReader();
        reader.onload = function (ev) {
            var img = new Image();
            img.onload = function () {
                var outer  = document.createElement('div');
                outer.className = 'doc-canvas-wrap';

                var canvas = document.createElement('canvas');
                canvas.width  = img.naturalWidth;
                canvas.height = img.naturalHeight;
                canvas.getContext('2d').drawImage(img, 0, 0);

                var label = document.createElement('div');
                label.className = 'doc-secure-label';
                label.textContent = 'Pratinjau Dokumen';

                outer.appendChild(canvas);
                outer.appendChild(label);

                var info = document.createElement('p');
                info.className = 'text-xs font-semibold text-emerald-600 mt-2';
                info.textContent = '✓ ' + file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';

                wrap.appendChild(outer);
                wrap.appendChild(info);

                canvas.addEventListener('contextmenu', e => e.preventDefault());
                canvas.addEventListener('dragstart',   e => e.preventDefault());

                // Jika dokumen yang dipilih adalah KTP, jalankan deteksi kartu e-KTP & OCR Auto-Fill
                if (wrapId === 'ktp-new-wrap') {
                    markChecklistStep('ktp', 'Siap Disimpan');
                    scanKtpAndAutofill(img);
                } else {
                    img.src = '';
                }
            };
            img.src = ev.target.result;
        };
        reader.readAsDataURL(file);
    }

    function fileErr(msg) {
        return '<p class="text-xs font-semibold text-rose-600 mt-1">✕ ' + msg + '</p>';
    }

    /* ══════════════════════════════════════════════════════════════════
       SMART INDONESIAN e-KTP AUTO-DETECTOR & OCR AUTO-FILL (Tesseract.js + NIK Decoder)
    ══════════════════════════════════════════════════════════════════ */
    let tesseractPromise = null;

    function loadTesseractJs() {
        if (window.Tesseract) return Promise.resolve(window.Tesseract);
        if (tesseractPromise) return tesseractPromise;

        tesseractPromise = new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js';
            script.async = true;
            script.onload = () => resolve(window.Tesseract);
            script.onerror = () => reject(new Error('Gagal memuat modul OCR'));
            document.head.appendChild(script);
        });
        return tesseractPromise;
    }

    function highlightField(el) {
        if (!el) return;
        el.classList.remove('autofill-highlight');
        void el.offsetWidth;
        el.classList.add('autofill-highlight');
    }

    function markChecklistStep(step, subText) {
        const card = document.getElementById('chk-' + step + '-card');
        const sub  = document.getElementById('chk-' + step + '-sub');
        const st   = document.getElementById('chk-' + step + '-status');
        if (card) {
            card.classList.remove('border-slate-200', 'bg-slate-50');
            card.classList.add('border-emerald-200', 'bg-emerald-50/50');
        }
        if (sub) {
            sub.className = 'text-[11px] text-emerald-600 font-semibold';
            sub.textContent = subText;
        }
        if (st) {
            st.className = 'text-xs font-extrabold text-emerald-600';
            st.textContent = '✓ Lengkap';
        }
    }

    /**
     * Dekoder Standar 16 Digit NIK KTP Indonesia (Dukcapil):
     * Digit 7-8 = Tanggal Lahir (untuk Perempuan ditambah 40, misal 01 -> 41)
     * Digit 9-10 = Bulan Lahir (01 - 12)
     * Digit 11-12 = Tahun Lahir (YY)
     */
    function decodeIndonesianNik(nik) {
        const clean = (nik || '').replace(/\D/g, '');
        if (clean.length !== 16) return null;

        let day = parseInt(clean.substring(6, 8), 10);
        const month = parseInt(clean.substring(8, 10), 10);
        const year2 = parseInt(clean.substring(10, 12), 10);

        let gender = 'male';
        if (day > 40) {
            gender = 'female';
            day -= 40;
        }

        if (day < 1 || day > 31 || month < 1 || month > 12) {
            return null;
        }

        const currentYear2 = new Date().getFullYear() % 100;
        const fullYear = (year2 <= currentYear2) ? (2000 + year2) : (1900 + year2);
        const dob = `${fullYear}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

        return { nik: clean, dob, gender };
    }

    function handleNikInput(rawVal) {
        const nikEl = document.getElementById('input_identity_number');
        const clean = (rawVal || '').replace(/\D/g, '').slice(0, 16);
        if (nikEl) nikEl.value = clean;

        if (clean.length === 16) {
            markChecklistStep('nik', '16 Digit Terisi');
            const decoded = decodeIndonesianNik(clean);
            if (decoded) {
                const dobEl = document.getElementById('input_date_of_birth');
                const genEl = document.getElementById('input_gender');
                const hintEl = document.getElementById('nikAutoHint');

                if (dobEl && !dobEl.value) {
                    dobEl.value = decoded.dob;
                    highlightField(dobEl);
                }
                if (genEl && !genEl.value) {
                    genEl.value = decoded.gender;
                    highlightField(genEl);
                }
                if (hintEl) {
                    hintEl.textContent = '✓ Tgl lahir & gender terdeteksi dari NIK';
                    hintEl.className = 'text-[11px] font-bold text-emerald-600';
                }
            }
        }
    }

    function preprocessKtpImageForOcr(img) {
        const maxDim = 1400;
        let w = img.naturalWidth || img.width;
        let h = img.naturalHeight || img.height;
        if (w > maxDim) {
            h = Math.round(h * (maxDim / w));
            w = maxDim;
        }

        const offCanvas = document.createElement('canvas');
        offCanvas.width = w;
        offCanvas.height = h;
        const ctx = offCanvas.getContext('2d');
        ctx.drawImage(img, 0, 0, w, h);

        const imgData = ctx.getImageData(0, 0, w, h);
        const data = imgData.data;

        // Ubah ke high-contrast grayscale supaya teks hitam di atas background biru e-KTP terbaca tajam
        for (let i = 0; i < data.length; i += 4) {
            const r = data[i];
            const g = data[i + 1];
            const b = data[i + 2];
            const lum = 0.299 * r + 0.587 * g + 0.114 * b;
            const contrasted = lum < 145 ? Math.max(0, (lum - 35) * 0.7) : Math.min(255, lum * 1.15);
            data[i] = data[i + 1] = data[i + 2] = contrasted;
        }
        ctx.putImageData(imgData, 0, 0);
        return offCanvas;
    }

    function extractKtpFieldsFromText(rawText) {
        const text = (rawText || '').replace(/\r/g, '');
        const upper = text.toUpperCase();

        // 1. Deteksi kata kunci khas e-KTP Indonesia
        const ktpKeywords = [
            'PROVINSI', 'KABUPATEN', 'KOTA', 'NIK', 'NAMA', 'LAHIR',
            'KELAMIN', 'ALAMAT', 'AGAMA', 'KAWIN', 'PEKERJAAN',
            'KEWARGANEGARAAN', 'BERLAKU', 'SEUMUR HIDUP', 'GOL. DARAH', 'RT/RW'
        ];
        const matchedKeywords = ktpKeywords.filter(kw => upper.includes(kw));

        // 2. Cari pola 16 digit NIK (normalisasi huruf yang sering terbaca mirip angka pada baris NIK)
        let detectedNik = null;
        const lines = text.split('\n').map(l => l.trim()).filter(Boolean);

        for (const line of lines) {
            const normalizedDigits = line
                .replace(/[OoQD]/g, '0')
                .replace(/[Iil|!]/g, '1')
                .replace(/[Zz]/g, '2')
                .replace(/[Ss]/g, '5')
                .replace(/[Bb]/g, '8');

            const match16 = normalizedDigits.match(/\b(\d{16})\b/) || normalizedDigits.replace(/\s+/g, '').match(/(\d{16})/);
            if (match16 && decodeIndonesianNik(match16[1])) {
                detectedNik = match16[1];
                break;
            }
        }

        if (!detectedNik) {
            const rawDigitsMatch = upper.match(/\b\d{16}\b/);
            if (rawDigitsMatch) detectedNik = rawDigitsMatch[0];
        }

        // 3. Ekstrak Nama, Pekerjaan, Alamat, Tgl Lahir, Gender dari baris teks
        let detectedName = null;
        let detectedDob = null;
        let detectedGender = null;
        let detectedOccupation = null;
        let detectedAddress = null;

        if (upper.includes('PEREMPUAN') || upper.includes('WANITA')) {
            detectedGender = 'female';
        } else if (upper.includes('LAKI-LAKI') || upper.includes('LAKI LAKI') || upper.includes('PRIA')) {
            detectedGender = 'male';
        }

        const dobMatch = upper.match(/(\d{2})\s*[-/.]\s*(\d{2})\s*[-/.]\s*(19\d{2}|20\d{2})/);
        if (dobMatch) {
            const d = parseInt(dobMatch[1], 10);
            const m = parseInt(dobMatch[2], 10);
            const y = parseInt(dobMatch[3], 10);
            if (d >= 1 && d <= 31 && m >= 1 && m <= 12) {
                detectedDob = `${y}-${String(m).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
            }
        }

        for (const line of lines) {
            const upLine = line.toUpperCase();
            if (!detectedName && /^NAMA\b/.test(upLine)) {
                const val = line.replace(/^NAMA\s*[:.;-]?\s*/i, '').replace(/[^a-zA-Z\s.,']/g, '').trim();
                if (val.length >= 3) detectedName = val;
            }
            if (!detectedAddress && /^ALAMAT\b/.test(upLine)) {
                const val = line.replace(/^ALAMAT\s*[:.;-]?\s*/i, '').trim();
                if (val.length >= 4) detectedAddress = val;
            }
            if (!detectedOccupation && /^PEKERJAAN\b/.test(upLine)) {
                const val = line.replace(/^PEKERJAAN\s*[:.;-]?\s*/i, '').replace(/[^a-zA-Z\s/.-]/g, '').trim();
                if (val.length >= 3) detectedOccupation = val;
            }
        }

        if (!detectedOccupation) {
            if (upper.includes('PELAJAR') || upper.includes('MAHASISWA')) detectedOccupation = 'Pelajar / Mahasiswa';
            else if (upper.includes('KARYAWAN SWASTA')) detectedOccupation = 'Karyawan Swasta';
            else if (upper.includes('WIRASWASTA')) detectedOccupation = 'Wiraswasta';
            else if (upper.includes('PEGAWAI NEGERI') || upper.includes('PNS')) detectedOccupation = 'PNS';
        }

        // Gabungkan dengan hasil dekoder 16 digit NIK Dukcapil
        if (detectedNik) {
            const nikInfo = decodeIndonesianNik(detectedNik);
            if (nikInfo) {
                if (!detectedDob) detectedDob = nikInfo.dob;
                if (!detectedGender) detectedGender = nikInfo.gender;
            }
        }

        const isValidKtp = Boolean(detectedNik) || matchedKeywords.length >= 2;

        return {
            isValidKtp,
            matchedKeywordsCount: matchedKeywords.length,
            nik: detectedNik,
            name: detectedName,
            dob: detectedDob,
            gender: detectedGender,
            occupation: detectedOccupation,
            address: detectedAddress
        };
    }

    async function scanKtpAndAutofill(imgEl) {
        const statusBox = document.getElementById('ktp-ocr-status');
        if (!statusBox) return;

        statusBox.classList.remove('hidden');
        statusBox.className = 'mt-3 p-3 rounded-xl border border-blue-200 bg-blue-50/80 text-xs text-blue-900';
        statusBox.innerHTML = `
            <div class="flex items-center justify-between mb-1.5">
                <span class="font-extrabold">Memindai kartu identitas e-KTP...</span>
                <span id="ocrProgressPct" class="font-bold text-blue-600">15%</span>
            </div>
            <div class="w-full h-1.5 bg-blue-200/70 rounded-full overflow-hidden">
                <div id="ocrProgressBar" class="h-full bg-blue-600 transition-all duration-300" style="width: 15%"></div>
            </div>
        `;

        try {
            const processedCanvas = preprocessKtpImageForOcr(imgEl);
            const Tesseract = await loadTesseractJs();

            const result = await Tesseract.recognize(processedCanvas, 'ind+eng', {
                logger: m => {
                    if (m.status === 'recognizing text' && typeof m.progress === 'number') {
                        const pct = Math.max(20, Math.min(99, Math.round(m.progress * 100)));
                        const bar = document.getElementById('ocrProgressBar');
                        const txt = document.getElementById('ocrProgressPct');
                        if (bar) bar.style.width = pct + '%';
                        if (txt) txt.textContent = pct + '%';
                    }
                }
            });

            const parsed = extractKtpFieldsFromText(result?.data?.text || '');
            const filledLabels = [];

            if (parsed.nik) {
                const nikEl = document.getElementById('input_identity_number');
                if (nikEl) {
                    nikEl.value = parsed.nik;
                    highlightField(nikEl);
                    markChecklistStep('nik', '16 Digit Terdeteksi');
                    filledLabels.push('NIK 16 Digit');
                }
            }

            if (parsed.dob) {
                const dobEl = document.getElementById('input_date_of_birth');
                if (dobEl) {
                    dobEl.value = parsed.dob;
                    highlightField(dobEl);
                    filledLabels.push('Tanggal Lahir');
                }
            }

            if (parsed.gender) {
                const genEl = document.getElementById('input_gender');
                if (genEl) {
                    genEl.value = parsed.gender;
                    highlightField(genEl);
                    filledLabels.push('Jenis Kelamin');
                }
            }

            if (parsed.occupation) {
                const occEl = document.getElementById('input_occupation');
                if (occEl && !occEl.value) {
                    occEl.value = parsed.occupation;
                    highlightField(occEl);
                    filledLabels.push('Pekerjaan');
                }
            }

            if (parsed.address) {
                const addrEl = document.getElementById('input_address');
                if (addrEl && !addrEl.value) {
                    addrEl.value = parsed.address;
                    highlightField(addrEl);
                    filledLabels.push('Alamat');
                }
            }

            if (parsed.isValidKtp) {
                const summary = filledLabels.length > 0
                    ? `Data otomatis terisi: <strong>${filledLabels.join(', ')}</strong>.`
                    : `Struktur kartu e-KTP terverifikasi. Silakan periksa kembali kolom data diri di atas.`;
                statusBox.className = 'mt-3 p-3 rounded-xl border border-emerald-200 bg-emerald-50 text-xs text-emerald-900';
                statusBox.innerHTML = `
                    <div class="font-extrabold text-emerald-800 mb-0.5">✓ Kartu Identitas e-KTP Terdeteksi</div>
                    <p class="text-[11px] text-emerald-700 leading-relaxed">${summary}</p>
                `;
            } else {
                statusBox.className = 'mt-3 p-3 rounded-xl border border-amber-300 bg-amber-50 text-xs text-amber-900';
                statusBox.innerHTML = `
                    <div class="font-extrabold text-amber-800 mb-0.5">⚠ Gambar Belum Terdeteksi Sebagai e-KTP yang Jelas</div>
                    <p class="text-[11px] text-amber-800 leading-relaxed">
                        Sistem tidak menemukan teks NIK 16 digit atau atribut KTP pada foto ini. Pastikan kamu mengupload foto e-KTP asli yang terang &amp; tidak blur, atau lengkapi kolom NIK secara manual.
                    </p>
                `;
            }
        } catch (err) {
            statusBox.className = 'mt-3 p-3 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-600';
            statusBox.innerHTML = `
                <div class="font-bold text-slate-700">Foto KTP siap diupload</div>
                <p class="text-[11px] text-slate-500 mt-0.5">Ketik 16 digit NIK di atas untuk mengisi Tanggal Lahir &amp; Jenis Kelamin secara otomatis.</p>
            `;
        }
    }
</script>

</body>
</html>
