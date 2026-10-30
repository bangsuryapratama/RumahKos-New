<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/webp" href="{{ asset('favicon.webp') }}">
    <link rel="icon" type="image/webp" sizes="64x64" href="{{ asset('favicon.webp') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.webp') }}">
    <link rel="preload" as="image" href="{{ asset('images/auth-login.webp') }}" type="image/webp" fetchpriority="high">
    <title>Cari &amp; Daftar Kos Lengkap - RumahKos</title>

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

        @keyframes rkShimmer {
            0%   { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .shimmer-bg {
            background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 37%, #f1f5f9 63%);
            background-size: 200% 100%;
            animation: rkShimmer 1.3s ease-in-out infinite;
        }
        .shimmer-dark {
            background: linear-gradient(90deg, #1e293b 25%, #334155 37%, #1e293b 63%);
            background-size: 200% 100%;
            animation: rkShimmer 1.3s ease-in-out infinite;
        }

        .search-tab.active {
            background-color: #eff6ff;
            color: #1d4ed8;
            border-color: #bfdbfe;
            box-shadow: 0 1px 2px rgba(37, 99, 235, 0.08);
        }
        .mobile-cat-btn.active .mobile-cat-box {
            background-color: #eff6ff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }
        .mobile-cat-btn.active .mobile-cat-label {
            color: #1d4ed8;
            font-weight: 800;
        }
        .city-banner-chip.active {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.25);
        }
        .view-btn.active {
            background-color: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
        }
        .page-btn.active {
            background-color: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
        }
        .hidden-item {
            display: none !important;
        }
    </style>
</head>
<body class="bg-slate-50 antialiased pb-20 md:pb-0">

@include('landing.navbar')

{{-- ══════════════════════════════════════════════════════════════════
     HERO BANNER PENCARIAN (DENGAN BACKGROUND FOTO WEBP & FLOATING SEARCH)
══════════════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-slate-900 pt-24 sm:pt-28 pb-16 sm:pb-24">
    <img src="{{ asset('images/auth-login.webp') }}"
         alt="Cari Kos RumahKos"
         fetchpriority="high"
         decoding="sync"
         onerror="this.onerror=null;this.src='{{ asset('images/hero-kos.webp') }}';"
         class="absolute inset-0 w-full h-full object-cover object-center pointer-events-none">

    {{-- Dark Gradient Overlay supaya Navbar Transparan & Judul Kontras --}}
    <div class="absolute inset-0 bg-gradient-to-b from-slate-950/85 via-slate-900/55 to-slate-950/85 pointer-events-none"></div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 relative z-10">
        {{-- Top Breadcrumb & Quick View Mode Shortcut --}}
        <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
            <nav class="flex items-center gap-1.5 text-xs font-semibold text-white/80">
                <a href="{{ route('landing') }}" class="hover:text-white transition-colors">Beranda</a>
                <span>/</span>
                <span class="text-sky-300 font-bold">Cari &amp; Daftar Kos Lengkap</span>
            </nav>

            <button type="button" onclick="setViewMode('property'); document.getElementById('resultToolbarAnchor')?.scrollIntoView({ behavior: 'smooth' })"
                    class="inline-flex items-center gap-1.5 text-xs font-bold text-white bg-white/15 hover:bg-white/25 backdrop-blur-md border border-white/20 px-3 py-1.5 rounded-xl transition-all">
                <img src="{{ asset('images/icons/ic-search-building.webp') }}" alt="" class="w-4 h-4 object-contain">
                <span>Lihat per Gedung Properti ({{ $properties->count() }} Gedung) →</span>
            </button>
        </div>

        {{-- Headline --}}
        <div class="text-center max-w-2xl mx-auto mb-2 md:mb-6">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-white tracking-tight leading-tight drop-shadow-md">
                Cari &amp; Daftar Kos Lengkap di Seluruh Kota
            </h1>
            <p class="text-white/90 text-xs sm:text-sm md:text-base mt-2 leading-relaxed drop-shadow">
                Filter berdasarkan kota tujuan, kategori kos, budget bulanan, atau jelajahi langsung per gedung properti.
            </p>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════════
     FLOATING SEARCH WIDGET (MOBILE APP CARD vs DESKTOP OTA DOCK)
══════════════════════════════════════════════════════════════════ --}}
<div class="max-w-5xl mx-auto px-4 sm:px-6 -mt-10 sm:-mt-14 relative z-20">

    {{-- A. TAMPILAN KHUSUS MOBILE (< md): 4-Icon Grid + Filter Drawer Button --}}
    <div class="md:hidden bg-white rounded-2xl shadow-xl border border-slate-200/90 p-3.5">
        <div class="grid grid-cols-4 gap-2 mb-3 pb-3 border-b border-slate-100">
            <button type="button" onclick="setTypeFilter('all')" data-mob-type="all" class="mobile-cat-btn active flex flex-col items-center gap-1">
                <div class="mobile-cat-box w-11 h-11 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center transition-all">
                    <img src="{{ asset('images/icons/cat-semua.webp') }}" alt="Semua" class="w-6 h-6 object-contain">
                </div>
                <span class="mobile-cat-label text-[10px] font-semibold text-slate-600">Semua Kos</span>
            </button>
            <button type="button" onclick="setTypeFilter('campur')" data-mob-type="campur" class="mobile-cat-btn flex flex-col items-center gap-1">
                <div class="mobile-cat-box w-11 h-11 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center transition-all">
                    <img src="{{ asset('images/icons/cat-campur.webp') }}" alt="Campur" class="w-6 h-6 object-contain">
                </div>
                <span class="mobile-cat-label text-[10px] font-semibold text-slate-600">Kos Campur</span>
            </button>
            <button type="button" onclick="setTypeFilter('putri')" data-mob-type="putri" class="mobile-cat-btn flex flex-col items-center gap-1">
                <div class="mobile-cat-box w-11 h-11 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center transition-all">
                    <img src="{{ asset('images/icons/cat-putri.webp') }}" alt="Putri" class="w-6 h-6 object-contain">
                </div>
                <span class="mobile-cat-label text-[10px] font-semibold text-slate-600">Kos Putri</span>
            </button>
            <button type="button" onclick="setTypeFilter('putra')" data-mob-type="putra" class="mobile-cat-btn flex flex-col items-center gap-1">
                <div class="mobile-cat-box w-11 h-11 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center transition-all">
                    <img src="{{ asset('images/icons/cat-putra.webp') }}" alt="Putra" class="w-6 h-6 object-contain">
                </div>
                <span class="mobile-cat-label text-[10px] font-semibold text-slate-600">Kos Putra</span>
            </button>
        </div>

        <div class="space-y-2">
            <div class="grid grid-cols-2 gap-2">
                <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-2">
                    <img src="{{ asset('images/icons/ic-location.webp') }}" alt="" class="w-4 h-4 object-contain mr-2 shrink-0">
                    <select id="mobSearchCity" onchange="syncFromMobileSearch()"
                            class="w-full bg-transparent border-0 p-0 text-xs font-bold text-slate-800 focus:ring-0 focus:outline-none truncate">
                        <option value="all">Semua Kota</option>
                        @foreach($availableCities ?? ['Bandung', 'Jakarta', 'Yogyakarta', 'Surabaya'] as $city)
                            <option value="{{ strtolower($city) }}">{{ $city }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-2">
                    <img src="{{ asset('images/icons/ic-price.webp') }}" alt="" class="w-4 h-4 object-contain mr-2 shrink-0">
                    <select id="mobSearchBudget" onchange="syncFromMobileSearch()"
                            class="w-full bg-transparent border-0 p-0 text-xs font-bold text-slate-800 focus:ring-0 focus:outline-none truncate">
                        <option value="all">Semua Harga</option>
                        <option value="under-15">&lt; Rp 1,5 Jt</option>
                        <option value="15-25">Rp 1,5 - 2,5 Jt</option>
                        <option value="above-25">&gt; Rp 2,5 Jt</option>
                    </select>
                </div>
            </div>

            <div class="flex items-stretch gap-2">
                <div class="flex-1 flex items-center bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-2">
                    <img src="{{ asset('images/icons/ic-search-building.webp') }}" alt="" class="w-4 h-4 object-contain mr-2 shrink-0">
                    <input id="mobSearchKeyword" type="text"
                           value="{{ $initialFilters['q'] ?? '' }}"
                           oninput="syncFromMobileKeyword(this.value)"
                           placeholder="Ketik nama kos atau area..."
                           class="w-full bg-transparent border-0 p-0 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:ring-0 focus:outline-none">
                </div>
                <button type="button" onclick="toggleMobileFilterDrawer(true)"
                        class="px-3.5 py-2 rounded-xl bg-blue-600 text-white font-bold text-xs flex items-center gap-1.5 shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <span>Filter</span>
                </button>
            </div>
        </div>
    </div>

    {{-- B. TAMPILAN KHUSUS DESKTOP (md+): Floating Category Dock + Connected Search Bar --}}
    <div class="hidden md:block bg-white rounded-2xl shadow-xl border border-slate-200/90 px-6 pb-5 pt-4 relative">
        <div class="-mt-10 mb-5 flex justify-center">
            <div class="inline-flex items-center gap-2 bg-white rounded-full shadow-lg border border-slate-200/90 p-1.5">
                <button type="button" onclick="setTypeFilter('all')" data-type-tab="all"
                        class="search-tab active flex items-center gap-2 px-4 py-2 rounded-full text-xs sm:text-sm font-bold border border-transparent text-slate-700 hover:bg-slate-50 transition-all">
                    <img src="{{ asset('images/icons/cat-semua.webp') }}" alt="" class="w-5 h-5 object-contain shrink-0">
                    <span>Semua Kos</span>
                </button>
                <button type="button" onclick="setTypeFilter('campur')" data-type-tab="campur"
                        class="search-tab flex items-center gap-2 px-4 py-2 rounded-full text-xs sm:text-sm font-bold border border-transparent text-slate-700 hover:bg-slate-50 transition-all">
                    <img src="{{ asset('images/icons/cat-campur.webp') }}" alt="" class="w-5 h-5 object-contain shrink-0">
                    <span>Kos Campur</span>
                </button>
                <button type="button" onclick="setTypeFilter('putri')" data-type-tab="putri"
                        class="search-tab flex items-center gap-2 px-4 py-2 rounded-full text-xs sm:text-sm font-bold border border-transparent text-slate-700 hover:bg-slate-50 transition-all">
                    <img src="{{ asset('images/icons/cat-putri.webp') }}" alt="" class="w-5 h-5 object-contain shrink-0">
                    <span>Kos Putri</span>
                </button>
                <button type="button" onclick="setTypeFilter('putra')" data-type-tab="putra"
                        class="search-tab flex items-center gap-2 px-4 py-2 rounded-full text-xs sm:text-sm font-bold border border-transparent text-slate-700 hover:bg-slate-50 transition-all">
                    <img src="{{ asset('images/icons/cat-putra.webp') }}" alt="" class="w-5 h-5 object-contain shrink-0">
                    <span>Kos Putra</span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-0 mb-2 px-1">
            <div class="col-span-4 text-xs font-semibold text-slate-500">Kota, tujuan, atau lokasi</div>
            <div class="col-span-4 text-xs font-semibold text-slate-500 pl-3.5">Rentang Harga Bulanan</div>
            <div class="col-span-4 text-xs font-semibold text-slate-500 pl-3.5">Nama Kos atau Area</div>
        </div>

        <div class="border border-slate-200 rounded-xl overflow-hidden grid grid-cols-12 divide-x divide-slate-200 bg-slate-50/50">
            <div class="col-span-4 flex items-center px-3.5 py-3 hover:bg-white transition-colors">
                <img src="{{ asset('images/icons/ic-location.webp') }}" alt="" class="w-5 h-5 object-contain shrink-0 mr-2.5">
                <select id="searchCity" onchange="runSearch(true)"
                        class="w-full bg-transparent border-0 p-0 text-xs sm:text-sm font-bold text-slate-800 focus:ring-0 focus:outline-none cursor-pointer truncate">
                    <option value="all">Semua Kota di Indonesia</option>
                    @foreach($availableCities ?? ['Bandung', 'Jakarta', 'Yogyakarta', 'Surabaya'] as $city)
                        <option value="{{ strtolower($city) }}">{{ $city }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-span-4 flex items-center px-3.5 py-3 hover:bg-white transition-colors">
                <img src="{{ asset('images/icons/ic-price.webp') }}" alt="" class="w-5 h-5 object-contain shrink-0 mr-2.5">
                <select id="searchBudget" onchange="runSearch(true)"
                        class="w-full bg-transparent border-0 p-0 text-xs sm:text-sm font-bold text-slate-800 focus:ring-0 focus:outline-none cursor-pointer truncate">
                    <option value="all">Semua Rentang Harga</option>
                    <option value="under-15">Di bawah Rp 1.500.000 / bln</option>
                    <option value="15-25">Rp 1.500.000 - Rp 2.500.000 / bln</option>
                    <option value="above-25">Di atas Rp 2.500.000 / bln</option>
                </select>
            </div>

            <div class="col-span-4 flex items-stretch">
                <div class="flex-1 flex items-center px-3.5 py-3 hover:bg-white transition-colors">
                    <img src="{{ asset('images/icons/ic-search-building.webp') }}" alt="" class="w-5 h-5 object-contain shrink-0 mr-2.5">
                    <input id="searchKeyword" type="text"
                           value="{{ $initialFilters['q'] ?? '' }}"
                           oninput="runSearch(true)"
                           placeholder="Ketik nama kos atau kota..."
                           class="w-full bg-transparent border-0 p-0 text-xs sm:text-sm font-bold text-slate-800 placeholder-slate-400 focus:ring-0 focus:outline-none">
                    <button type="button" id="clearSearchKeywordBtn" onclick="clearSearchKeyword()"
                            class="hidden text-xs font-bold text-slate-400 hover:text-slate-600 ml-1.5">
                        ✕
                    </button>
                </div>
                <button type="button" onclick="runSearch(true)"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 flex items-center justify-center gap-1.5 transition-colors shrink-0 text-xs sm:text-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span>Cari</span>
                </button>
            </div>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════════════
     QUICK CITY VISUAL BANNER STRIP + MAIN RESULT CONTENT
══════════════════════════════════════════════════════════════════ --}}
<main class="max-w-6xl mx-auto px-4 sm:px-6 pt-6 pb-10">

    {{-- Quick Visual City Cards Strip --}}
    <div class="mb-6">
        <div class="flex items-center justify-between mb-2.5">
            <h2 class="text-xs sm:text-sm font-extrabold text-slate-700 uppercase tracking-wider">Pilih Cepat Kota Populer</h2>
            <button type="button" onclick="setSidebarCity('all')" class="text-xs font-bold text-blue-600 hover:underline">
                Tampilkan Semua Kota
            </button>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-3.5">
            @foreach($popularCities as $c)
                <button type="button"
                        onclick="setSidebarCity('{{ strtolower($c['name']) }}')"
                        data-city-card="{{ strtolower($c['name']) }}"
                        class="city-banner-chip group relative h-20 sm:h-24 rounded-2xl overflow-hidden border-2 border-transparent text-left shadow-xs hover:shadow-md transition-all bg-slate-900 shimmer-dark">
                    <img src="{{ $c['image'] }}"
                         alt="{{ $c['name'] }}"
                         decoding="sync"
                         onload="this.parentElement.classList.remove('shimmer-dark')"
                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-950/85 via-slate-950/50 to-transparent"></div>
                    <div class="relative z-10 h-full p-3 flex flex-col justify-center">
                        <div class="text-sm sm:text-base font-extrabold text-white leading-tight">{{ $c['name'] }}</div>
                        <div class="text-[11px] text-sky-300 font-semibold mt-0.5">{{ $c['available_count'] }} kamar tersedia</div>
                    </div>
                </button>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        {{-- ═══ PANEL FILTER KIRI (DESKTOP SIDEBAR & MOBILE DRAWER) ═══ --}}
        <div id="filterDrawerBackdrop" onclick="toggleMobileFilterDrawer(false)"
             class="fixed inset-0 bg-slate-950/50 z-40 hidden lg:hidden"></div>

        <aside id="filterSidebar"
               class="fixed inset-x-0 bottom-0 z-50 max-h-[85vh] overflow-y-auto rounded-t-2xl bg-white p-5 shadow-2xl transition-transform duration-300 translate-y-full lg:static lg:translate-y-0 lg:z-auto lg:max-h-none lg:rounded-2xl lg:border lg:border-slate-200/90 lg:shadow-2xs lg:col-span-3 space-y-5">

            {{-- Header Filter --}}
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <h2 class="text-sm font-extrabold text-slate-900">Filter Pencarian</h2>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="resetSearchFilters()" class="text-xs font-bold text-blue-600 hover:underline">
                        Reset
                    </button>
                    <button type="button" onclick="toggleMobileFilterDrawer(false)" class="lg:hidden text-slate-400 hover:text-slate-600 text-sm font-bold">
                        ✕
                    </button>
                </div>
            </div>

            {{-- 1. Status Ketersediaan --}}
            <div>
                <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2.5">Ketersediaan Kamar</h3>
                <div class="space-y-2">
                    <label class="flex items-center justify-between text-xs font-semibold text-slate-700 cursor-pointer">
                        <span class="flex items-center gap-2">
                            <input type="radio" name="statusRadio" value="all" checked onchange="setStatusFilter('all')"
                                   class="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500">
                            <span>Semua Kamar</span>
                        </span>
                        <span class="text-slate-400 font-bold">{{ $rooms->count() }}</span>
                    </label>
                    <label class="flex items-center justify-between text-xs font-semibold text-slate-700 cursor-pointer">
                        <span class="flex items-center gap-2">
                            <input type="radio" name="statusRadio" value="available" onchange="setStatusFilter('available')"
                                   class="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500">
                            <span>Hanya Kamar Tersedia</span>
                        </span>
                        <span class="text-emerald-600 font-bold">{{ $rooms->where('status', 'available')->count() }}</span>
                    </label>
                </div>
            </div>

            {{-- 2. Kategori Kos --}}
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2.5">Kategori Kos</h3>
                <div class="space-y-2">
                    @php
                        $typesList = [
                            ['key' => 'all', 'label' => 'Semua Kategori', 'icon' => 'cat-semua.webp'],
                            ['key' => 'campur', 'label' => 'Kos Campur', 'icon' => 'cat-campur.webp'],
                            ['key' => 'putri', 'label' => 'Kos Putri', 'icon' => 'cat-putri.webp'],
                            ['key' => 'putra', 'label' => 'Kos Putra', 'icon' => 'cat-putra.webp'],
                        ];
                    @endphp
                    @foreach($typesList as $tItem)
                        <label class="flex items-center justify-between text-xs font-semibold text-slate-700 cursor-pointer">
                            <span class="flex items-center gap-2">
                                <input type="radio" name="sidebarTypeRadio" value="{{ $tItem['key'] }}"
                                       {{ ($initialFilters['type'] ?? 'all') === $tItem['key'] ? 'checked' : '' }}
                                       onchange="setTypeFilter('{{ $tItem['key'] }}')"
                                       class="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500">
                                <img src="{{ asset('images/icons/' . $tItem['icon']) }}" alt="" class="w-4 h-4 object-contain">
                                <span>{{ $tItem['label'] }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- 3. Kota Populer --}}
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2.5">Lokasi Kota</h3>
                <div class="space-y-2">
                    <label class="flex items-center justify-between text-xs font-semibold text-slate-700 cursor-pointer">
                        <span class="flex items-center gap-2">
                            <input type="radio" name="sidebarCityRadio" value="all" checked onchange="setSidebarCity('all')"
                                   class="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500">
                            <span>Semua Kota</span>
                        </span>
                        <span class="text-slate-400 font-bold">{{ $rooms->count() }}</span>
                    </label>
                    @foreach($popularCities as $cItem)
                        <label class="flex items-center justify-between text-xs font-semibold text-slate-700 cursor-pointer">
                            <span class="flex items-center gap-2">
                                <input type="radio" name="sidebarCityRadio" value="{{ strtolower($cItem['name']) }}"
                                       onchange="setSidebarCity('{{ strtolower($cItem['name']) }}')"
                                       class="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500">
                                <span>{{ $cItem['name'] }}</span>
                            </span>
                            <span class="text-slate-400 font-bold">{{ $cItem['rooms_count'] }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- 4. Fasilitas Kamar --}}
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2.5">Fasilitas Unggulan</h3>
                <div class="space-y-2">
                    @php
                        $facFilterItems = [
                            ['key' => 'wifi', 'label' => 'WiFi Cepat', 'icon' => 'fac-wifi.webp'],
                            ['key' => 'ac', 'label' => 'AC Dingin', 'icon' => 'fac-ac.webp'],
                            ['key' => 'mandi', 'label' => 'K. Mandi Dalam', 'icon' => 'fac-bath.webp'],
                            ['key' => 'kasur', 'label' => 'Kasur Springbed', 'icon' => 'fac-bed.webp'],
                            ['key' => 'parkir', 'label' => 'Parkir Luas', 'icon' => 'fac-parking.webp'],
                            ['key' => 'cctv', 'label' => 'CCTV 24 Jam', 'icon' => 'fac-cctv.webp'],
                        ];
                    @endphp
                    @foreach($facFilterItems as $fItem)
                        <label class="flex items-center gap-2.5 text-xs font-semibold text-slate-700 cursor-pointer">
                            <input type="checkbox" value="{{ $fItem['key'] }}" onchange="runSearch(true)"
                                   class="fac-checkbox w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <img src="{{ asset('images/icons/' . $fItem['icon']) }}" alt="" class="w-4 h-4 object-contain">
                            <span>{{ $fItem['label'] }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="pt-3 lg:hidden">
                <button type="button" onclick="toggleMobileFilterDrawer(false)"
                        class="w-full py-2.5 bg-blue-600 text-white font-bold text-xs rounded-xl">
                    Terapkan Filter
                </button>
            </div>
        </aside>

        {{-- ═══ AREA KANAN: TOOLBAR + HASIL PENCARIAN (GRID & LIST VIEW) ═══ --}}
        <div class="lg:col-span-9">

            {{-- Result Toolbar: Count + Sort + View Mode Switcher --}}
            <div id="resultToolbarAnchor" class="bg-white rounded-2xl border border-slate-200/90 p-3.5 sm:p-4 mb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-2xs">
                <div>
                    <h2 class="text-sm sm:text-base font-extrabold text-slate-900">
                        Menampilkan <span id="resultCount" class="text-blue-600">{{ $rooms->count() }}</span> Kamar Kos
                    </h2>
                    <p id="activeFilterSummary" class="text-xs text-slate-500 mt-0.5">
                        Semua Kota • Semua Kategori Kos
                    </p>
                </div>

                <div class="flex flex-wrap items-center justify-between sm:justify-end gap-2">
                    <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5">
                        <span class="text-[11px] font-semibold text-slate-400 shrink-0">Urutkan:</span>
                        <select id="sortSelect" onchange="runSearch(false)"
                                class="bg-transparent border-0 p-0 text-xs font-bold text-slate-800 focus:ring-0 focus:outline-none cursor-pointer">
                            <option value="recommended">Rekomendasi</option>
                            <option value="price-asc">Harga Terendah</option>
                            <option value="price-desc">Harga Tertinggi</option>
                            <option value="rating-desc">Rating Tertinggi</option>
                        </select>
                    </div>

                    <div class="inline-flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200/80 shrink-0">
                        <button type="button" id="btnViewGrid" onclick="setViewMode('grid')"
                                class="view-btn active px-2.5 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all"
                                title="Tampilan Grid">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                            <span>Grid</span>
                        </button>
                        <button type="button" id="btnViewList" onclick="setViewMode('list')"
                                class="view-btn px-2.5 py-1.5 rounded-lg text-xs font-bold text-slate-600 flex items-center gap-1.5 transition-all"
                                title="Tampilan List Detail">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                            <span>List</span>
                        </button>
                        <button type="button" id="btnViewProperty" onclick="setViewMode('property')"
                                class="view-btn px-2.5 py-1.5 rounded-lg text-xs font-bold text-slate-600 flex items-center gap-1.5 transition-all"
                                title="Tampilan Per Gedung Properti">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span>Per Gedung</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- ═══ 1. TAMPILAN GRID (COMPACT CARDS) ═══ --}}
            <div id="gridViewContainer" class="grid grid-cols-2 md:grid-cols-3 gap-3.5 sm:gap-4">
                @foreach($rooms as $room)
                    @php
                        $avgRating = round($room->reviews->avg('rating') ?? 4.9, 1);
                        $cycle = match($room->billing_cycle ?? 'monthly') {
                            'daily'   => 'hr',
                            'weekly'  => 'mgg',
                            'monthly' => 'bln',
                            default   => 'thn'
                        };
                        $cityName = $room->property?->city ?? 'Bandung';
                        $propType = $room->property?->type ?? 'campur';
                        $propName = $room->property?->name ?? 'RumahKos';
                        $facNames = strtolower($room->facilities->pluck('name')->implode(' ') . ' wifi ac mandi kasur parkir cctv');
                        $roomImgUrl = (!empty($room->image) && file_exists(public_path('storage/' . $room->image)))
                            ? asset('storage/' . $room->image)
                            : asset('images/default-room.webp');
                    @endphp

                    <a href="{{ route('rooms.detail', $room) }}"
                       class="search-room-item grid-item bg-white rounded-xl border border-slate-200/90 hover:border-blue-300 hover:shadow-md transition-all overflow-hidden flex flex-col group"
                       data-id="{{ $room->id }}"
                       data-name="{{ strtolower($room->name . ' ' . $propName . ' ' . ($room->property?->address ?? '')) }}"
                       data-city="{{ strtolower($cityName) }}"
                       data-type="{{ strtolower($propType) }}"
                       data-status="{{ $room->status }}"
                       data-price="{{ $room->price }}"
                       data-rating="{{ $avgRating }}"
                       data-facilities="{{ $facNames }}">

                        <div class="relative h-32 sm:h-36 overflow-hidden bg-slate-100 shimmer-bg">
                            <img src="{{ $roomImgUrl }}"
                                 alt="{{ $room->name }}"
                                 decoding="sync"
                                 onload="this.parentElement.classList.remove('shimmer-bg')"
                                 onerror="this.onerror=null;this.src='{{ asset('images/default-room.webp') }}';this.parentElement.classList.remove('shimmer-bg');"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>

                        <div class="p-3 flex-1 flex flex-col justify-between gap-2.5">
                            <div>
                                <div class="flex items-center justify-between gap-1 text-[10px] sm:text-[11px] text-slate-500 mb-0.5">
                                    <span class="font-bold text-blue-600 truncate">{{ $cityName }} • Kos {{ ucfirst($propType) }}</span>
                                    <span class="font-bold text-slate-700 shrink-0">★ {{ number_format($avgRating, 1) }}</span>
                                </div>
                                <h3 class="font-extrabold text-slate-900 text-xs sm:text-sm group-hover:text-blue-600 transition-colors line-clamp-1">
                                    {{ $room->name }}
                                </h3>
                                <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">
                                    {{ $propName }} • Lantai {{ $room->floor }}
                                </p>
                                <p class="text-[10px] sm:text-[11px] text-slate-400 mt-1 line-clamp-1">
                                    {{ $room->size ? $room->size . ' • ' : '' }}AC • WiFi • K. Mandi Dalam
                                </p>
                            </div>

                            <div class="pt-2.5 border-t border-slate-100 flex items-baseline justify-between">
                                <div>
                                    <span class="text-blue-600 font-extrabold text-xs sm:text-sm">
                                        Rp {{ number_format($room->price, 0, ',', '.') }}
                                    </span>
                                    <span class="text-[10px] text-slate-400">/{{ $cycle }}</span>
                                </div>
                                <span class="text-[11px] font-bold {{ $room->status === 'available' ? 'text-emerald-600' : 'text-slate-400' }}">
                                    {{ $room->status === 'available' ? 'Tersedia' : 'Penuh' }}
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- ═══ 2. TAMPILAN LIST HORIZONTAL (TRAVELOKA / TIKET.COM STYLE) ═══ --}}
            <div id="listViewContainer" class="hidden space-y-3.5">
                @foreach($rooms as $room)
                    @php
                        $avgRating = round($room->reviews->avg('rating') ?? 4.9, 1);
                        $reviewCount = $room->reviews->count() ?: 12;
                        $cycle = match($room->billing_cycle ?? 'monthly') {
                            'daily'   => 'hari',
                            'weekly'  => 'minggu',
                            'monthly' => 'bulan',
                            default   => 'tahun'
                        };
                        $cityName = $room->property?->city ?? 'Bandung';
                        $propType = $room->property?->type ?? 'campur';
                        $propName = $room->property?->name ?? 'RumahKos';
                        $propAddr = $room->property?->address ?? $cityName;
                        $facNames = strtolower($room->facilities->pluck('name')->implode(' ') . ' wifi ac mandi kasur parkir cctv');
                        $roomImgUrl = (!empty($room->image) && file_exists(public_path('storage/' . $room->image)))
                            ? asset('storage/' . $room->image)
                            : asset('images/default-room.webp');
                    @endphp

                    <a href="{{ route('rooms.detail', $room) }}"
                       class="search-room-item list-item bg-white rounded-2xl border border-slate-200/90 hover:border-blue-300 hover:shadow-md transition-all overflow-hidden flex flex-col sm:flex-row group"
                       data-id="{{ $room->id }}"
                       data-name="{{ strtolower($room->name . ' ' . $propName . ' ' . $propAddr) }}"
                       data-city="{{ strtolower($cityName) }}"
                       data-type="{{ strtolower($propType) }}"
                       data-status="{{ $room->status }}"
                       data-price="{{ $room->price }}"
                       data-rating="{{ $avgRating }}"
                       data-facilities="{{ $facNames }}">

                        <div class="sm:w-56 md:w-64 h-44 sm:h-auto relative overflow-hidden bg-slate-100 shimmer-bg shrink-0">
                            <img src="{{ $roomImgUrl }}"
                                 alt="{{ $room->name }}"
                                 decoding="sync"
                                 onload="this.parentElement.classList.remove('shimmer-bg')"
                                 onerror="this.onerror=null;this.src='{{ asset('images/default-room.webp') }}';this.parentElement.classList.remove('shimmer-bg');"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>

                        <div class="p-4 sm:p-5 flex-1 flex flex-col sm:flex-row justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 text-xs font-bold text-blue-600 mb-1">
                                    <span>{{ $cityName }}</span>
                                    <span>•</span>
                                    <span>Kos {{ ucfirst($propType) }}</span>
                                    <span>•</span>
                                    <span class="{{ $room->status === 'available' ? 'text-emerald-600' : 'text-slate-400' }}">
                                        {{ $room->status === 'available' ? 'Tersedia' : 'Sedang Penuh' }}
                                    </span>
                                </div>

                                <h3 class="text-base sm:text-lg font-extrabold text-slate-900 group-hover:text-blue-600 transition-colors truncate">
                                    {{ $room->name }} — {{ $propName }}
                                </h3>

                                <p class="text-xs text-slate-500 mt-0.5 line-clamp-1">
                                    {{ $propAddr }} • Lantai {{ $room->floor }} {{ $room->size ? '• Ukuran ' . $room->size : '' }}
                                </p>

                                <div class="flex flex-wrap items-center gap-3 mt-3 pt-3 border-t border-slate-100 text-xs text-slate-600 font-semibold">
                                    <span class="inline-flex items-center gap-1.5">
                                        <img src="{{ asset('images/icons/fac-wifi.webp') }}" alt="" class="w-4 h-4 object-contain">
                                        <span>WiFi Cepat</span>
                                    </span>
                                    <span class="inline-flex items-center gap-1.5">
                                        <img src="{{ asset('images/icons/fac-ac.webp') }}" alt="" class="w-4 h-4 object-contain">
                                        <span>AC</span>
                                    </span>
                                    <span class="inline-flex items-center gap-1.5">
                                        <img src="{{ asset('images/icons/fac-bath.webp') }}" alt="" class="w-4 h-4 object-contain">
                                        <span>K. Mandi Dalam</span>
                                    </span>
                                    <span class="inline-flex items-center gap-1.5">
                                        <img src="{{ asset('images/icons/fac-bed.webp') }}" alt="" class="w-4 h-4 object-contain">
                                        <span>Full Furnished</span>
                                    </span>
                                </div>
                            </div>

                            <div class="sm:text-right flex sm:flex-col justify-between items-end sm:pl-4 sm:border-l sm:border-slate-100 shrink-0">
                                <div class="flex items-center gap-1.5 text-xs font-bold text-slate-700">
                                    <span class="text-amber-500">★</span>
                                    <span>{{ number_format($avgRating, 1) }}</span>
                                    <span class="text-slate-400 font-medium">({{ $reviewCount }} ulasan)</span>
                                </div>

                                <div>
                                    <div class="text-[11px] text-slate-400">Harga sewa mulai</div>
                                    <div class="text-base sm:text-lg font-extrabold text-blue-600">
                                        Rp {{ number_format($room->price, 0, ',', '.') }}
                                        <span class="text-xs font-normal text-slate-400">/{{ $cycle }}</span>
                                    </div>
                                    <span class="mt-2 inline-flex items-center gap-1 text-xs font-bold text-white bg-blue-600 group-hover:bg-blue-700 px-3.5 py-1.5 rounded-lg transition-colors">
                                        <span>Pilih Kamar</span>
                                        <span>→</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- ═══ 3. TAMPILAN PER GEDUNG PROPERTI (PROPERTY GROUP VIEW) ═══ --}}
            <div id="propertyViewContainer" class="hidden space-y-5">
                @php
                    $cityImgMap = [
                        'bandung'    => asset('images/cities/bandung.webp'),
                        'jakarta'    => asset('images/cities/jakarta.webp'),
                        'yogyakarta' => asset('images/cities/yogyakarta.webp'),
                        'surabaya'   => asset('images/cities/surabaya.webp'),
                    ];
                @endphp

                @foreach($properties as $property)
                    @php
                        $propCity = $property->city ?? 'Bandung';
                        $propType = $property->type ?? 'campur';
                        $propRooms = $property->rooms;
                        $availRooms = $propRooms->where('status', 'available')->count();
                        $minPrice = $propRooms->min('price') ?? 0;
                        $maxPrice = $propRooms->max('price') ?? 0;
                        $propBanner = $cityImgMap[strtolower($propCity)] ?? asset('images/hero-kos.webp');
                    @endphp

                    <div class="property-group-card bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden"
                         data-city="{{ strtolower($propCity) }}"
                         data-type="{{ strtolower($propType) }}"
                         data-name="{{ strtolower($property->name . ' ' . ($property->address ?? '') . ' ' . $propCity) }}">

                        {{-- Visual Property Header Banner --}}
                        <div class="relative p-4 sm:p-5 border-b border-slate-100 overflow-hidden bg-slate-900">
                            <img src="{{ $propBanner }}"
                                 alt="{{ $property->name }}"
                                 decoding="sync"
                                 class="absolute inset-0 w-full h-full object-cover opacity-35 pointer-events-none">
                            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-900/75 to-slate-900/60 pointer-events-none"></div>

                            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2 text-xs font-bold text-sky-300 mb-1">
                                        <span>{{ $propCity }}</span>
                                        <span>•</span>
                                        <span>Kos {{ ucfirst($propType) }}</span>
                                        <span>•</span>
                                        <span class="text-emerald-400">{{ $availRooms }} dari {{ $propRooms->count() }} kamar tersedia</span>
                                    </div>
                                    <h3 class="text-base sm:text-lg font-extrabold text-white">
                                        {{ $property->name }}
                                    </h3>
                                    <p class="text-xs text-white/80 mt-0.5">
                                        {{ $property->address ?? 'Lokasi strategis dekat kampus & pusat bisnis ' . $propCity }}
                                    </p>
                                </div>

                                <div class="sm:text-right shrink-0">
                                    <div class="text-[11px] text-white/70">Rentang harga kamar</div>
                                    <div class="text-sm sm:text-base font-extrabold text-white">
                                        @if($minPrice === $maxPrice)
                                            Rp {{ number_format($minPrice, 0, ',', '.') }}
                                        @else
                                            Rp {{ number_format($minPrice, 0, ',', '.') }} - Rp {{ number_format($maxPrice, 0, ',', '.') }}
                                        @endif
                                        <span class="text-xs font-normal text-white/70">/bln</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Daftar Kamar di Gedung Ini --}}
                        <div class="p-4 sm:p-5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3.5">
                            @foreach($propRooms as $room)
                                @php
                                    $avgRating = round($room->reviews->avg('rating') ?? 4.9, 1);
                                    $facNames = strtolower($room->facilities->pluck('name')->implode(' ') . ' wifi ac mandi kasur parkir cctv');
                                    $roomImgUrl = (!empty($room->image) && file_exists(public_path('storage/' . $room->image)))
                                        ? asset('storage/' . $room->image)
                                        : asset('images/default-room.webp');
                                @endphp
                                <a href="{{ route('rooms.detail', $room) }}"
                                   class="prop-room-item rounded-xl border border-slate-200/90 hover:border-blue-300 hover:shadow-md transition-all overflow-hidden flex flex-col group bg-slate-50/40"
                                   data-id="{{ $room->id }}"
                                   data-name="{{ strtolower($room->name . ' ' . $property->name . ' ' . ($property->address ?? '')) }}"
                                   data-city="{{ strtolower($propCity) }}"
                                   data-type="{{ strtolower($propType) }}"
                                   data-status="{{ $room->status }}"
                                   data-price="{{ $room->price }}"
                                   data-rating="{{ $avgRating }}"
                                   data-facilities="{{ $facNames }}">
                                    <div class="h-32 overflow-hidden bg-slate-100 shimmer-bg relative">
                                        <img src="{{ $roomImgUrl }}"
                                             alt="{{ $room->name }}"
                                             decoding="sync"
                                             onload="this.parentElement.classList.remove('shimmer-bg')"
                                             onerror="this.onerror=null;this.src='{{ asset('images/default-room.webp') }}';this.parentElement.classList.remove('shimmer-bg');"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    </div>
                                    <div class="p-3 flex-1 flex flex-col justify-between gap-2 bg-white">
                                        <div>
                                            <div class="flex items-center justify-between text-[11px] text-slate-500 mb-0.5">
                                                <span>Lantai {{ $room->floor }} {{ $room->size ? '• ' . $room->size : '' }}</span>
                                                <span class="font-bold text-slate-700">★ {{ number_format($avgRating, 1) }}</span>
                                            </div>
                                            <h4 class="text-xs sm:text-sm font-extrabold text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-1">
                                                {{ $room->name }}
                                            </h4>
                                        </div>
                                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                                            <span class="text-xs sm:text-sm font-extrabold text-blue-600">
                                                Rp {{ number_format($room->price, 0, ',', '.') }}<span class="text-[10px] font-normal text-slate-400">/bln</span>
                                            </span>
                                            <span class="text-[11px] font-bold {{ $room->status === 'available' ? 'text-emerald-600' : 'text-slate-400' }}">
                                                {{ $room->status === 'available' ? 'Tersedia' : 'Penuh' }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Empty State --}}
            <div id="noSearchResults" class="hidden bg-white rounded-2xl border border-slate-200/90 p-10 text-center my-4">
                <img src="{{ asset('images/icons/ic-search-building.webp') }}" alt="" class="w-12 h-12 mx-auto mb-3 object-contain opacity-80">
                <h3 class="text-base font-extrabold text-slate-900 mb-1">Kamar kos tidak ditemukan</h3>
                <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto mb-4">
                    Coba ubah filter kota, rentang harga, atau kata kunci pencarianmu untuk melihat pilihan kamar lainnya.
                </p>
                <button type="button" onclick="resetSearchFilters()"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-colors">
                    Reset Semua Filter
                </button>
            </div>

            {{-- Pagination --}}
            <div id="searchPagination" class="mt-6 flex items-center justify-center gap-1.5"></div>

        </div>
    </div>
</main>

@include('landing.footer')

{{-- ══════════════════════════════════════════════════════════════════
     KHUSUS MOBILE (< md): STICKY BOTTOM APP NAVIGATION BAR
══════════════════════════════════════════════════════════════════ --}}
<nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 px-2 py-1.5 grid grid-cols-4 text-center shadow-[0_-2px_10px_rgba(0,0,0,0.05)]">
    <a href="{{ route('landing') }}" class="flex flex-col items-center justify-center gap-0.5 text-slate-500 hover:text-blue-600">
        <img src="{{ asset('images/icons/logo-rumahkos.webp') }}" alt="" class="w-5 h-5 object-contain">
        <span class="text-[10px] font-semibold">Beranda</span>
    </a>
    <a href="{{ route('rooms.search') }}" class="flex flex-col items-center justify-center gap-0.5 text-blue-600">
        <img src="{{ asset('images/icons/ic-search-building.webp') }}" alt="" class="w-5 h-5 object-contain">
        <span class="text-[10px] font-bold">Cari Kos</span>
    </a>
    <a href="/#promo" class="flex flex-col items-center justify-center gap-0.5 text-slate-500 hover:text-blue-600">
        <img src="{{ asset('images/icons/ic-price.webp') }}" alt="" class="w-5 h-5 object-contain">
        <span class="text-[10px] font-semibold">Promo</span>
    </a>
    @auth('tenant')
        <a href="{{ route('tenant.dashboard') }}" class="flex flex-col items-center justify-center gap-0.5 text-slate-500 hover:text-blue-600">
            <img src="{{ asset('images/icons/cat-putra.webp') }}" alt="" class="w-5 h-5 object-contain">
            <span class="text-[10px] font-semibold">Akun</span>
        </a>
    @else
        <a href="{{ route('tenant.login') }}" class="flex flex-col items-center justify-center gap-0.5 text-slate-500 hover:text-blue-600">
            <img src="{{ asset('images/icons/cat-putra.webp') }}" alt="" class="w-5 h-5 object-contain">
            <span class="text-[10px] font-semibold">Masuk</span>
        </a>
    @endauth
</nav>

<script>
    let activeType = @json($initialFilters['type'] ?? 'all');
    let activeStatus = @json($initialFilters['status'] ?? 'all');
    let activeView = @json($initialFilters['view'] ?? 'grid');
    let currentPage = 1;
    const perPage = 9;

    document.addEventListener('DOMContentLoaded', () => {
        const initCity = @json($initialFilters['city'] ?? 'all');
        const initBudget = @json($initialFilters['budget'] ?? 'all');
        const initSort = @json($initialFilters['sort'] ?? 'recommended');

        const cityEl = document.getElementById('searchCity');
        const mobCityEl = document.getElementById('mobSearchCity');
        const budgetEl = document.getElementById('searchBudget');
        const mobBudgetEl = document.getElementById('mobSearchBudget');
        const sortEl = document.getElementById('sortSelect');

        if (cityEl && initCity) cityEl.value = initCity;
        if (mobCityEl && initCity) mobCityEl.value = initCity;
        if (budgetEl && initBudget) budgetEl.value = initBudget;
        if (mobBudgetEl && initBudget) mobBudgetEl.value = initBudget;
        if (sortEl && initSort) sortEl.value = initSort;

        document.querySelectorAll('input[name="sidebarCityRadio"]').forEach(r => {
            r.checked = (r.value === initCity);
        });
        document.querySelectorAll('input[name="statusRadio"]').forEach(r => {
            r.checked = (r.value === activeStatus);
        });

        document.querySelectorAll('img').forEach(img => {
            if (img.complete && img.parentElement) {
                img.parentElement.classList.remove('shimmer-bg', 'shimmer-dark');
            }
        });

        setTypeFilter(activeType, false);
        setViewMode(activeView, false);
        runSearch(false);
    });

    function syncFromMobileSearch() {
        const mobCity = document.getElementById('mobSearchCity')?.value || 'all';
        const mobBudget = document.getElementById('mobSearchBudget')?.value || 'all';
        const cityEl = document.getElementById('searchCity');
        const budgetEl = document.getElementById('searchBudget');
        if (cityEl) cityEl.value = mobCity;
        if (budgetEl) budgetEl.value = mobBudget;
        runSearch(true);
    }

    function syncFromMobileKeyword(val) {
        const kw = document.getElementById('searchKeyword');
        if (kw) kw.value = val;
        runSearch(true);
    }

    function setViewMode(mode, shouldRun = true) {
        activeView = ['grid', 'list', 'property'].includes(mode) ? mode : 'grid';
        document.getElementById('btnViewGrid')?.classList.toggle('active', activeView === 'grid');
        document.getElementById('btnViewList')?.classList.toggle('active', activeView === 'list');
        document.getElementById('btnViewProperty')?.classList.toggle('active', activeView === 'property');
        document.getElementById('gridViewContainer')?.classList.toggle('hidden', activeView !== 'grid');
        document.getElementById('listViewContainer')?.classList.toggle('hidden', activeView !== 'list');
        document.getElementById('propertyViewContainer')?.classList.toggle('hidden', activeView !== 'property');
        if (shouldRun) runSearch(false);
    }

    function setTypeFilter(type, shouldRun = true) {
        activeType = type;
        document.querySelectorAll('[data-type-tab]').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.typeTab === type);
        });
        document.querySelectorAll('[data-mob-type]').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.mobType === type);
        });
        document.querySelectorAll('input[name="sidebarTypeRadio"]').forEach(r => {
            r.checked = (r.value === type);
        });
        if (shouldRun) runSearch(true);
    }

    function setStatusFilter(status) {
        activeStatus = status;
        runSearch(true);
    }

    function setSidebarCity(cityVal) {
        const cityEl = document.getElementById('searchCity');
        const mobCityEl = document.getElementById('mobSearchCity');
        if (cityEl) cityEl.value = cityVal;
        if (mobCityEl) mobCityEl.value = cityVal;
        runSearch(true);
    }

    function clearSearchKeyword() {
        const kw = document.getElementById('searchKeyword');
        const mobKw = document.getElementById('mobSearchKeyword');
        if (kw) kw.value = '';
        if (mobKw) mobKw.value = '';
        runSearch(true);
    }

    function toggleMobileFilterDrawer(open) {
        const sidebar = document.getElementById('filterSidebar');
        const backdrop = document.getElementById('filterDrawerBackdrop');
        if (!sidebar || !backdrop) return;
        sidebar.classList.toggle('translate-y-full', !open);
        backdrop.classList.toggle('hidden', !open);
    }

    function resetSearchFilters() {
        document.getElementById('searchCity').value = 'all';
        document.getElementById('mobSearchCity').value = 'all';
        document.getElementById('searchBudget').value = 'all';
        document.getElementById('mobSearchBudget').value = 'all';
        document.getElementById('searchKeyword').value = '';
        document.getElementById('mobSearchKeyword').value = '';
        document.getElementById('sortSelect').value = 'recommended';
        activeStatus = 'all';

        document.querySelectorAll('input[name="statusRadio"]').forEach(r => r.checked = (r.value === 'all'));
        document.querySelectorAll('input[name="sidebarCityRadio"]').forEach(r => r.checked = (r.value === 'all'));
        document.querySelectorAll('.fac-checkbox').forEach(cb => cb.checked = false);

        setTypeFilter('all', false);
        runSearch(true);
    }

    function goToSearchPage(page) {
        currentPage = page;
        runSearch(false);
        window.scrollTo({ top: 280, behavior: 'smooth' });
    }

    function runSearch(resetPage = false) {
        if (resetPage) currentPage = 1;

        const cityVal = (document.getElementById('searchCity')?.value || 'all').toLowerCase();
        const budgetVal = document.getElementById('searchBudget')?.value || 'all';
        const kwVal = (document.getElementById('searchKeyword')?.value || '').toLowerCase().trim();
        const sortVal = document.getElementById('sortSelect')?.value || 'recommended';
        const checkedFacs = Array.from(document.querySelectorAll('.fac-checkbox:checked')).map(cb => cb.value);

        const mobCityEl = document.getElementById('mobSearchCity');
        const mobBudgetEl = document.getElementById('mobSearchBudget');
        if (mobCityEl && mobCityEl.value !== cityVal) mobCityEl.value = cityVal;
        if (mobBudgetEl && mobBudgetEl.value !== budgetVal) mobBudgetEl.value = budgetVal;

        document.getElementById('clearSearchKeywordBtn')?.classList.toggle('hidden', kwVal.length === 0);
        document.querySelectorAll('input[name="sidebarCityRadio"]').forEach(r => {
            r.checked = (r.value === cityVal);
        });
        document.querySelectorAll('[data-city-card]').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.cityCard === cityVal);
        });

        const gridContainer = document.getElementById('gridViewContainer');
        const listContainer = document.getElementById('listViewContainer');
        const propContainer = document.getElementById('propertyViewContainer');
        const gridItems = Array.from(document.querySelectorAll('.grid-item'));
        const listItems = Array.from(document.querySelectorAll('.list-item'));
        const propCards = Array.from(document.querySelectorAll('.property-group-card'));

        const sortComparator = (a, b) => {
            const priceA = parseFloat(a.dataset.price) || 0;
            const priceB = parseFloat(b.dataset.price) || 0;
            const ratingA = parseFloat(a.dataset.rating) || 0;
            const ratingB = parseFloat(b.dataset.rating) || 0;
            if (sortVal === 'price-asc') return priceA - priceB;
            if (sortVal === 'price-desc') return priceB - priceA;
            if (sortVal === 'rating-desc') return ratingB - ratingA;
            return (parseInt(a.dataset.id) || 0) - (parseInt(b.dataset.id) || 0);
        };

        gridItems.sort(sortComparator).forEach(el => gridContainer?.appendChild(el));
        listItems.sort(sortComparator).forEach(el => listContainer?.appendChild(el));

        const filterFn = (el) => {
            const c = el.dataset.city || '';
            const t = el.dataset.type || '';
            const s = el.dataset.status || '';
            const p = parseFloat(el.dataset.price) || 0;
            const n = el.dataset.name || '';
            const f = el.dataset.facilities || '';

            const matchCity = (cityVal === 'all') || (c === cityVal);
            const matchType = (activeType === 'all') || (t === activeType);
            const matchStatus = (activeStatus === 'all') || (s === 'available');

            let matchBudget = true;
            if (budgetVal === 'under-15') matchBudget = p < 1500000;
            else if (budgetVal === '15-25') matchBudget = p >= 1500000 && p <= 2500000;
            else if (budgetVal === 'above-25') matchBudget = p > 2500000;

            const matchKw = !kwVal || n.includes(kwVal) || c.includes(kwVal) || t.includes(kwVal);
            const matchFacs = checkedFacs.every(fac => f.includes(fac));

            return matchCity && matchType && matchStatus && matchBudget && matchKw && matchFacs;
        };

        const matchingGrid = gridItems.filter(filterFn);
        const matchingList = listItems.filter(filterFn);

        // Filter rooms inside each property card
        propCards.forEach(card => {
            const innerRooms = Array.from(card.querySelectorAll('.prop-room-item'));
            let visibleInnerCount = 0;
            innerRooms.forEach(roomEl => {
                const match = filterFn(roomEl);
                roomEl.classList.toggle('hidden-item', !match);
                if (match) visibleInnerCount++;
            });
            card.classList.toggle('hidden-item', visibleInnerCount === 0);
        });

        const totalMatching = matchingGrid.length;
        const totalPages = Math.max(1, Math.ceil(totalMatching / perPage));
        if (currentPage > totalPages) currentPage = 1;

        const startIdx = (currentPage - 1) * perPage;
        const endIdx = startIdx + perPage;

        const visibleGridSet = new Set(matchingGrid.slice(startIdx, endIdx));
        const visibleListSet = new Set(matchingList.slice(startIdx, endIdx));

        gridItems.forEach(el => el.classList.toggle('hidden-item', !visibleGridSet.has(el)));
        listItems.forEach(el => el.classList.toggle('hidden-item', !visibleListSet.has(el)));

        document.getElementById('resultCount').textContent = totalMatching;

        const cityLabel = cityVal === 'all' ? 'Semua Kota' : cityVal.charAt(0).toUpperCase() + cityVal.slice(1);
        const typeLabel = activeType === 'all' ? 'Semua Kategori Kos' : 'Kos ' + activeType.charAt(0).toUpperCase() + activeType.slice(1);
        const kwLabel = kwVal ? ` • Kata kunci "${kwVal}"` : '';
        document.getElementById('activeFilterSummary').textContent = `${cityLabel} • ${typeLabel}${kwLabel}`;

        const noResultsEl = document.getElementById('noSearchResults');
        if (totalMatching === 0) {
            noResultsEl?.classList.remove('hidden');
            gridContainer?.classList.add('hidden');
            listContainer?.classList.add('hidden');
            propContainer?.classList.add('hidden');
        } else {
            noResultsEl?.classList.add('hidden');
            gridContainer?.classList.toggle('hidden', activeView !== 'grid');
            listContainer?.classList.toggle('hidden', activeView !== 'list');
            propContainer?.classList.toggle('hidden', activeView !== 'property');
        }

        const pagEl = document.getElementById('searchPagination');
        if (pagEl) {
            if (totalPages <= 1 || activeView === 'property') {
                pagEl.innerHTML = '';
            } else {
                let html = '';
                for (let i = 1; i <= totalPages; i++) {
                    const cls = (i === currentPage) ? 'active' : 'bg-white text-slate-700 hover:bg-slate-100';
                    html += `<button type="button" onclick="goToSearchPage(${i})" class="page-btn ${cls} w-8 h-8 rounded-lg text-xs font-bold border border-slate-200 transition-colors">${i}</button>`;
                }
                pagEl.innerHTML = html;
            }
        }

        const params = new URLSearchParams();
        if (cityVal !== 'all') params.set('city', cityVal);
        if (activeType !== 'all') params.set('type', activeType);
        if (budgetVal !== 'all') params.set('budget', budgetVal);
        if (activeStatus !== 'all') params.set('status', activeStatus);
        if (kwVal) params.set('q', kwVal);
        if (activeView !== 'grid') params.set('view', activeView);
        const qs = params.toString();
        window.history.replaceState({}, '', window.location.pathname + (qs ? '?' + qs : ''));
    }
</script>

</body>
</html>
