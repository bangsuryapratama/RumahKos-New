<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/webp" href="{{ asset('favicon.webp') }}">
    <link rel="icon" type="image/webp" sizes="64x64" href="{{ asset('favicon.webp') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.webp') }}">
    <link rel="preload" as="image" href="{{ asset('images/hero-kos.webp') }}" type="image/webp" fetchpriority="high">

    @php
        $schemaJson = \App\Services\SeoService::getLodgingBusinessSchema($globalProperty ?? null, $rooms ?? null, 4.9, 120);
    @endphp
    @include('components.seo-head', [
        'title' => 'RumahKos - Sewa Kos & Co-Living di Berbagai Kota',
        'description' => 'Cari dan sewa kamar kos aesthetic buat kuliah, kerja, atau keluarga di berbagai kota di Indonesia.',
        'schemaJson' => $schemaJson
    ])
    @include('components.meta-pixel')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            color: #0f172a;
            overflow-x: hidden;
            background-color: #ffffff;
        }
        html {
            scroll-behavior: smooth;
            scroll-padding-top: 4.5rem;
        }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        .first-screen-section {
            min-height: 100vh;
            min-height: 100svh;
        }
        @media (min-width: 1024px) {
            .full-screen-section {
                min-height: calc(100vh - 4.5rem);
                min-height: calc(100svh - 4.5rem);
            }
        }

        /* Lightweight Pure-CSS Shimmer Skeleton */
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
        .img-fade {
            opacity: 0;
            transition: opacity 0.35s ease-out;
        }
        .img-fade.loaded {
            opacity: 1;
        }

        /* Exact 50% width per banner slide on desktop so it never clips */
        .banner-slide {
            flex: 0 0 100%;
            max-width: 100%;
        }
        @media (min-width: 768px) {
            .banner-slide {
                flex: 0 0 50%;
                max-width: 50%;
            }
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
        .filter-chip.active {
            background-color: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
        }
        .page-btn.active {
            background-color: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
        }
        .hidden-room {
            display: none !important;
        }
        .banner-dot.active {
            width: 24px;
            background-color: #2563eb;
        }
        #backToTop {
            position: fixed;
            bottom: 84px;
            right: 20px;
            z-index: 40;
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.2s ease, transform 0.2s ease;
            pointer-events: none;
        }
        @media (min-width: 768px) {
            #backToTop { bottom: 30px; right: 24px; }
        }
        #backToTop.show {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }
    </style>
</head>
<body class="bg-white antialiased pb-16 md:pb-0">

@include('landing.navbar')

{{-- ══════════════════════════════════════════════════════════════════
     LAYAR 1: HERO SEARCH + BANNER PROMO BERJALAN (PAS 1 LAYAR PENUH, KOTA TIDAK KE-SPILL)
══════════════════════════════════════════════════════════════════ --}}
<div class="first-screen-section flex flex-col bg-white border-b border-slate-200/80">

    {{-- Bagian Atas Layar 1: Hero & Search --}}
    <section id="search-section" class="relative flex-1 flex flex-col justify-center overflow-hidden bg-slate-900 pt-24 sm:pt-28 pb-8 sm:pb-10">
        <img src="{{ asset('images/hero-kos.webp') }}"
             alt="RumahKos"
             fetchpriority="high"
             decoding="sync"
             onerror="this.onerror=null;this.src='{{ asset('images/default-room.webp') }}';"
             class="
             absolute inset-0 w-full h-full object-cover object-center pointer-events-none">

        {{-- Gradasi atas & bawah agar navbar putih dan judul sangat jelas terbaca --}}
        <div class="absolute inset-0 bg-gradient-to-b from-slate-950/80 via-slate-900/30 to-slate-950/70 pointer-events-none"></div>

        <div class="max-w-5xl w-full mx-auto px-4 sm:px-6 relative z-10">
            {{-- Headline --}}
            <div class="text-center mb-6 md:mb-12">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-white tracking-tight mb-3 drop-shadow-md leading-tight">
                    Rantau kuliah, kerja, atau berkeluarga?
                </h1>
                <p class="text-white/95 text-xs sm:text-sm md:text-base max-w-xl mx-auto drop-shadow font-medium leading-relaxed">
                    Temukan kos & co-living aesthetic yang siap huni di berbagai kota. Dekat kampus & pusat kantor, harga transparan tanpa ribet.
                </p>
            </div>

            {{-- ═══ A. TAMPILAN KHUSUS MOBILE (< md): App-Style 4 Icon Grid + Compact Search Card ═══ --}}
            <div class="md:hidden bg-white rounded-2xl shadow-2xl border border-slate-200/90 p-3.5">
                <div class="grid grid-cols-4 gap-2 mb-3 pb-3 border-b border-slate-100">
                    <button type="button" onclick="setSearchTypeTab('all')" id="mobTabAll" class="mobile-cat-btn active flex flex-col items-center gap-1">
                        <div class="mobile-cat-box w-11 h-11 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center transition-all">
                            <img src="{{ asset('images/icons/cat-semua.webp') }}" alt="Semua" class="w-6 h-6 object-contain">
                        </div>
                        <span class="mobile-cat-label text-[10px] font-semibold text-slate-600">Semua Kos</span>
                    </button>
                    <button type="button" onclick="setSearchTypeTab('campur')" id="mobTabCampur" class="mobile-cat-btn flex flex-col items-center gap-1">
                        <div class="mobile-cat-box w-11 h-11 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center transition-all">
                            <img src="{{ asset('images/icons/cat-campur.webp') }}" alt="Campur" class="w-6 h-6 object-contain">
                        </div>
                        <span class="mobile-cat-label text-[10px] font-semibold text-slate-600">Kos Campur</span>
                    </button>
                    <button type="button" onclick="setSearchTypeTab('putri')" id="mobTabPutri" class="mobile-cat-btn flex flex-col items-center gap-1">
                        <div class="mobile-cat-box w-11 h-11 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center transition-all">
                            <img src="{{ asset('images/icons/cat-putri.webp') }}" alt="Putri" class="w-6 h-6 object-contain">
                        </div>
                        <span class="mobile-cat-label text-[10px] font-semibold text-slate-600">Kos Putri</span>
                    </button>
                    <button type="button" onclick="setSearchTypeTab('putra')" id="mobTabPutra" class="mobile-cat-btn flex flex-col items-center gap-1">
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
                            <select onchange="syncMobileCity(this.value)" id="mobileFilterCity"
                                    class="w-full bg-transparent border-0 p-0 text-xs font-bold text-slate-800 focus:ring-0 focus:outline-none truncate">
                                <option value="all">Semua Kota</option>
                                @foreach($availableCities ?? ['Bandung', 'Jakarta', 'Yogyakarta', 'Surabaya'] as $city)
                                    <option value="{{ strtolower($city) }}">{{ $city }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-2">
                            <img src="{{ asset('images/icons/ic-price.webp') }}" alt="" class="w-4 h-4 object-contain mr-2 shrink-0">
                            <select onchange="syncMobileBudget(this.value)" id="mobileFilterBudget"
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
                            <input type="text" placeholder="Cari kosan..."
                                   oninput="syncHeroKeyword(this.value)"
                                   class="w-full bg-transparent border-0 p-0 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:ring-0 focus:outline-none">
                        </div>
                        <button type="button" onclick="scrollToRooms()"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 rounded-xl text-xs flex items-center justify-center gap-1.5 shrink-0">
                            <span>Cari</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- ═══ B. TAMPILAN KHUSUS DESKTOP (md+): Floating Category Dock + Connected Search Bar ═══ --}}
            <div class="hidden md:block bg-white rounded-2xl shadow-2xl border border-slate-200/90 px-6 pb-6 pt-4 max-w-4xl mx-auto relative">

                {{-- Floating OTA Category Bar --}}
                <div class="-mt-10 mb-5 flex justify-center">
                    <div class="inline-flex items-center gap-2 bg-white rounded-full shadow-lg border border-slate-200/90 p-1.5">
                        <button type="button" onclick="setSearchTypeTab('all')" id="tabAll"
                                class="search-tab active flex items-center gap-2 px-4 py-2 rounded-full text-xs sm:text-sm font-bold border border-transparent text-slate-700 hover:bg-slate-50 transition-all">
                            <img src="{{ asset('images/icons/cat-semua.webp') }}" alt="" class="w-5 h-5 object-contain shrink-0">
                            <span>Semua Kos</span>
                        </button>
                        <button type="button" onclick="setSearchTypeTab('campur')" id="tabCampur"
                                class="search-tab flex items-center gap-2 px-4 py-2 rounded-full text-xs sm:text-sm font-bold border border-transparent text-slate-700 hover:bg-slate-50 transition-all">
                            <img src="{{ asset('images/icons/cat-campur.webp') }}" alt="" class="w-5 h-5 object-contain shrink-0">
                            <span>Kos Campur</span>
                        </button>
                        <button type="button" onclick="setSearchTypeTab('putri')" id="tabPutri"
                                class="search-tab flex items-center gap-2 px-4 py-2 rounded-full text-xs sm:text-sm font-bold border border-transparent text-slate-700 hover:bg-slate-50 transition-all">
                            <img src="{{ asset('images/icons/cat-putri.webp') }}" alt="" class="w-5 h-5 object-contain shrink-0">
                            <span>Kos Putri</span>
                        </button>
                        <button type="button" onclick="setSearchTypeTab('putra')" id="tabPutra"
                                class="search-tab flex items-center gap-2 px-4 py-2 rounded-full text-xs sm:text-sm font-bold border border-transparent text-slate-700 hover:bg-slate-50 transition-all">
                            <img src="{{ asset('images/icons/cat-putra.webp') }}" alt="" class="w-5 h-5 object-contain shrink-0">
                            <span>Kos Putra</span>
                        </button>
                    </div>
                </div>

                {{-- Desktop Column Labels --}}
                <div class="grid grid-cols-12 gap-0 mb-2 px-1">
                    <div class="col-span-4 text-xs font-semibold text-slate-500">Kota, tujuan, atau lokasi</div>
                    <div class="col-span-4 text-xs font-semibold text-slate-500 pl-3.5">Rentang Harga Bulanan</div>
                    <div class="col-span-4 text-xs font-semibold text-slate-500 pl-3.5">Nama Kos atau Area</div>
                </div>

                {{-- Connected Input Bar --}}
                <div class="border border-slate-200 rounded-xl overflow-hidden grid grid-cols-12 divide-x divide-slate-200 bg-slate-50/50">

                    <div class="col-span-4 flex items-center px-3.5 py-3 hover:bg-white transition-colors">
                        <img src="{{ asset('images/icons/ic-location.webp') }}" alt="" class="w-5 h-5 object-contain shrink-0 mr-2.5">
                        <select id="filterCity" onchange="applyFilters(true)"
                                class="w-full bg-transparent border-0 p-0 text-xs sm:text-sm font-bold text-slate-800 focus:ring-0 focus:outline-none cursor-pointer truncate">
                            <option value="all">Semua Kota di Indonesia</option>
                            @foreach($availableCities ?? ['Bandung', 'Jakarta', 'Yogyakarta', 'Surabaya'] as $city)
                                <option value="{{ strtolower($city) }}">{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-4 flex items-center px-3.5 py-3 hover:bg-white transition-colors">
                        <img src="{{ asset('images/icons/ic-price.webp') }}" alt="" class="w-5 h-5 object-contain shrink-0 mr-2.5">
                        <select id="filterBudget" onchange="applyFilters(true)"
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
                            <input id="heroKeywordInput" type="text" placeholder="Cari kosan ..."
                                   oninput="syncHeroKeyword(this.value)"
                                   class="w-full bg-transparent border-0 p-0 text-xs sm:text-sm font-bold text-slate-800 placeholder-slate-400 focus:ring-0 focus:outline-none">
                        </div>
                        <button type="button" onclick="scrollToRooms()" aria-label="Cari Kos"
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
    </section>

    {{-- Bagian Bawah Layar 1: Banner Promo Berjalan Otomatis --}}
    <section id="promo" class="bg-white py-4 sm:py-5">
        <div class="max-w-6xl w-full mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h2 class="text-base sm:text-lg font-extrabold text-slate-900">Penawaran Spesial</h2>
                    <p class="text-slate-500 text-xs mt-0.5">Klik banner untuk melihat pilihan kamar kos yang sesuai.</p>
                </div>

                {{-- Slider Controls (Prev / Next Buttons) --}}
                <div class="flex items-center gap-2">
                    <button type="button" onclick="moveBannerSlide(-1)" aria-label="Banner Sebelumnya"
                            class="w-8 h-8 rounded-full border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 flex items-center justify-center shadow-sm transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <button type="button" onclick="moveBannerSlide(1)" aria-label="Banner Berikutnya"
                            class="w-8 h-8 rounded-full border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 flex items-center justify-center shadow-sm transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Auto-Sliding Banner Viewport --}}
            <div class="relative overflow-hidden rounded-2xl"
                 onmouseenter="pauseBannerAutoPlay()"
                 onmouseleave="startBannerAutoPlay()"
                 ontouchstart="pauseBannerAutoPlay()"
                 ontouchend="startBannerAutoPlay()">

                <div id="bannerTrack" class="flex -mx-1.5 transition-transform duration-700 ease-out">
                    {{-- Slide 1 --}}
                    <div class="banner-slide px-1.5">
                        <div onclick="applyPromoFilter('under-15', 'all')"
                             class="shimmer-bg group cursor-pointer rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-md transition-all">
                            <img src="{{ asset('images/banners/promo-rantau.webp') }}"
                                 alt="Promo Anak Rantau & Kuliah"
                                 width="800" height="320"
                                 decoding="sync"
                                 onload="this.parentElement.classList.remove('shimmer-bg')"
                                 class="w-full aspect-[5/2] object-cover block group-hover:scale-[1.02] transition-transform duration-300">
                        </div>
                    </div>

                    {{-- Slide 2 --}}
                    <div class="banner-slide px-1.5">
                        <div onclick="applyPromoFilter('15-25', 'all')"
                             class="shimmer-bg group cursor-pointer rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-md transition-all">
                            <img src="{{ asset('images/banners/promo-pekerja.webp') }}"
                                 alt="Paket Kerja & WFH Nyaman"
                                 width="800" height="320"
                                 decoding="sync"
                                 onload="this.parentElement.classList.remove('shimmer-bg')"
                                 class="w-full aspect-[5/2] object-cover block group-hover:scale-[1.02] transition-transform duration-300">
                        </div>
                    </div>

                    {{-- Slide 3 --}}
                    <div class="banner-slide px-1.5">
                        <div onclick="applyPromoFilter('all', 'campur')"
                             class="shimmer-bg group cursor-pointer rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-md transition-all">
                            <img src="{{ asset('images/banners/promo-tahunan.webp') }}"
                                 alt="Hemat Sewa & Keluarga Muda"
                                 width="800" height="320"
                                 decoding="sync"
                                 onload="this.parentElement.classList.remove('shimmer-bg')"
                                 class="w-full aspect-[5/2] object-cover block group-hover:scale-[1.02] transition-transform duration-300">
                        </div>
                    </div>

                    {{-- Slide 4 --}}
                    <div class="banner-slide px-1.5">
                        <div onclick="applyPromoFilter('under-15', 'putri')"
                             class="shimmer-bg group cursor-pointer rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-md transition-all">
                            <img src="{{ asset('images/banners/promo-kampus.webp') }}"
                                 alt="Kos Dekat Kampus Pilihan"
                                 width="800" height="320"
                                 decoding="sync"
                                 onload="this.parentElement.classList.remove('shimmer-bg')"
                                 class="w-full aspect-[5/2] object-cover block group-hover:scale-[1.02] transition-transform duration-300">
                        </div>
                    </div>
                </div>

                {{-- Indicator Dots --}}
                <div class="flex items-center justify-center gap-1.5 mt-3" id="bannerDots">
                    <button type="button" onclick="goToBannerSlide(0)" class="banner-dot active h-2 w-2 rounded-full bg-slate-300 transition-all"></button>
                    <button type="button" onclick="goToBannerSlide(1)" class="banner-dot h-2 w-2 rounded-full bg-slate-300 transition-all"></button>
                    <button type="button" onclick="goToBannerSlide(2)" class="banner-dot h-2 w-2 rounded-full bg-slate-300 transition-all"></button>
                    <button type="button" onclick="goToBannerSlide(3)" class="banner-dot md:hidden h-2 w-2 rounded-full bg-slate-300 transition-all"></button>
                </div>
            </div>
        </div>
    </section>

</div>

{{-- ══════════════════════════════════════════════════════════════════
     SECTION 2: KOTA POPULER (UKURAN BESAR & PROPORSIONAL)
══════════════════════════════════════════════════════════════════ --}}
<section id="kota" class="bg-white py-12 sm:py-16 border-b border-slate-200/80">
    <div class="max-w-6xl w-full mx-auto px-4 sm:px-6">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-6 gap-2">
            <div>
                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900">Kota Populer Pilihan Perantau</h2>
                <p class="text-slate-500 text-xs sm:text-sm mt-0.5">Pilih kota tujuan untuk melihat kos & co-living yang tersedia.</p>
            </div>
            <a href="{{ route('rooms.search') }}"
               class="text-xs sm:text-sm font-bold text-blue-600 hover:text-blue-700 self-start sm:self-auto shrink-0">
                Eksplorasi Semua Kota →
            </a>
        </div>

        @php
            $cityCards = $popularCities ?? collect([
                ['name' => 'Bandung', 'rooms_count' => 12, 'available_count' => 10, 'min_price' => 1400000, 'image' => asset('images/cities/bandung.webp')],
                ['name' => 'Jakarta', 'rooms_count' => 2, 'available_count' => 2, 'min_price' => 2700000, 'image' => asset('images/cities/jakarta.webp')],
                ['name' => 'Yogyakarta', 'rooms_count' => 2, 'available_count' => 2, 'min_price' => 1300000, 'image' => asset('images/cities/yogyakarta.webp')],
                ['name' => 'Surabaya', 'rooms_count' => 2, 'available_count' => 2, 'min_price' => 1850000, 'image' => asset('images/cities/surabaya.webp')],
            ]);
        @endphp

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-5">
            @foreach($cityCards as $c)
            <div onclick="selectCityFilter('{{ strtolower($c['name']) }}')"
                 class="shimmer-dark group relative h-52 sm:h-64 lg:h-72 rounded-2xl overflow-hidden cursor-pointer border border-slate-200 bg-slate-800 shadow-sm hover:shadow-lg transition-all">
                <img src="{{ $c['image'] }}"
                     alt="Kos {{ $c['name'] }}"
                     decoding="sync"
                     onload="this.parentElement.classList.remove('shimmer-dark')"
                     onerror="this.onerror=null;this.src='{{ asset('images/default-room.webp') }}';this.parentElement.classList.remove('shimmer-dark');"
                     class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/25 to-transparent"></div>
                <div class="absolute inset-0 p-4 sm:p-5 flex flex-col justify-end text-white">
                    <h3 class="text-base sm:text-xl font-extrabold text-white leading-tight">{{ $c['name'] }}</h3>
                    <p class="text-xs sm:text-sm text-slate-200 mt-1">{{ $c['available_count'] }} kamar tersedia</p>
                    <p class="text-[11px] sm:text-xs text-sky-300 font-semibold mt-0.5">Mulai Rp {{ number_format($c['min_price'] / 1000000, 1) }}jt/bln</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════════
     SECTION 3: DAFTAR KAMAR KOS (COMPACT CARDS: 4 KOLOM DESKTOP, 2 KOLOM MOBILE)
══════════════════════════════════════════════════════════════════ --}}
<section id="kamar" class="full-screen-section flex flex-col justify-center bg-slate-50 py-10 sm:py-12 border-b border-slate-200/80">
    <div class="max-w-6xl w-full mx-auto px-3.5 sm:px-6">

        {{-- Header & Controls Row --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-5 gap-3">
            <div>
                <h2 class="text-lg sm:text-2xl font-extrabold text-slate-900">Rekomendasi Kamar Kos</h2>
                <p class="text-slate-500 text-xs sm:text-sm mt-0.5">
                    Menampilkan <span id="displayCount" class="font-bold text-slate-800">{{ $rooms->count() }}</span> pilihan kamar siap huni
                </p>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                {{-- Search Input --}}
                <div class="relative w-full sm:w-60">
                    <input id="keywordInput" type="text" placeholder="Cari kosan..."
                           oninput="applyFilters(true)"
                           class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-lg text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                    <button type="button" onclick="clearKeyword()" id="clearKeywordBtn"
                            class="hidden absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs font-semibold">
                        Hapus
                    </button>
                </div>

                {{-- Filter Chips --}}
                <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar">
                    <button type="button" onclick="setChipFilter('all')" data-chip="all"
                            class="filter-chip active px-3 py-2 rounded-lg text-xs font-bold border border-slate-200 bg-white text-slate-700 hover:border-slate-300 shrink-0">
                        Semua
                    </button>
                    <button type="button" onclick="setChipFilter('available')" data-chip="available"
                            class="filter-chip px-3 py-2 rounded-lg text-xs font-bold border border-slate-200 bg-white text-slate-700 hover:border-slate-300 shrink-0">
                        Tersedia
                    </button>
                    <button type="button" onclick="toggleSort('price-asc')" id="sortPriceBtn"
                            class="filter-chip px-3 py-2 rounded-lg text-xs font-bold border border-slate-200 bg-white text-slate-700 hover:border-slate-300 shrink-0">
                        Harga Terendah
                    </button>
                </div>
            </div>
        </div>

        {{-- Lightweight Shimmer Skeleton Grid (Shown briefly on load & filter transitions) --}}
        <div id="roomsShimmerGrid" class="hidden grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
            @for($s = 0; $s < 8; $s++)
                <div class="bg-white rounded-xl border border-slate-200/80 overflow-hidden flex flex-col">
                    <div class="h-28 sm:h-32 shimmer-bg"></div>
                    <div class="p-2.5 sm:p-3 space-y-2">
                        <div class="flex justify-between gap-2">
                            <div class="h-3 w-24 rounded shimmer-bg"></div>
                            <div class="h-3 w-8 rounded shimmer-bg"></div>
                        </div>
                        <div class="h-3.5 w-3/4 rounded shimmer-bg"></div>
                        <div class="h-2.5 w-1/2 rounded shimmer-bg"></div>
                        <div class="pt-2 border-t border-slate-100 flex justify-between items-center">
                            <div class="h-4 w-24 rounded shimmer-bg"></div>
                            <div class="h-3 w-10 rounded shimmer-bg"></div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>

        {{-- Compact Grid: 2 Columns on Mobile, 3 on Tablet, 4 on Desktop (8 per page) --}}
        <div id="roomsContainer" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
            @forelse($rooms as $room)
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
                @endphp

                <a href="{{ route('rooms.detail', $room) }}"
                   class="room-card bg-white rounded-xl border border-slate-200/90 hover:border-blue-300 hover:shadow-md transition-all overflow-hidden flex flex-col group"
                   data-name="{{ strtolower($room->name) }}"
                   data-city="{{ strtolower($cityName) }}"
                   data-type="{{ strtolower($propType) }}"
                   data-status="{{ $room->status }}"
                   data-price="{{ $room->price }}"
                   data-rating="{{ $avgRating }}">

                    {{-- Compact Image with Shimmer Loader (Clean, No Badges) --}}
                    <div class="relative h-28 sm:h-32 overflow-hidden bg-slate-100 shimmer-bg">
                        @php
                            $roomImgUrl = (!empty($room->image) && file_exists(public_path('storage/' . $room->image)))
                                ? asset('storage/' . $room->image)
                                : asset('images/default-room.webp');
                        @endphp
                        <img src="{{ $roomImgUrl }}"
                             alt="{{ $room->name }}"
                             decoding="sync"
                             onload="this.parentElement.classList.remove('shimmer-bg')"
                             onerror="this.onerror=null;this.src='{{ asset('images/default-room.webp') }}';this.parentElement.classList.remove('shimmer-bg');"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>

                    {{-- Compact Card Body --}}
                    <div class="p-2.5 sm:p-3 flex-1 flex flex-col justify-between gap-2">
                        <div>
                            <div class="flex items-center justify-between gap-1 text-[10px] sm:text-[11px] text-slate-500 mb-0.5">
                                <span class="font-semibold text-blue-600 truncate">{{ $cityName }} • Kos {{ ucfirst($propType) }}</span>
                                <span class="font-bold text-slate-700 shrink-0">★ {{ number_format($avgRating, 1) }}</span>
                            </div>
                            <h3 class="font-bold text-slate-900 text-xs sm:text-[13px] group-hover:text-blue-600 transition-colors line-clamp-1">
                                {{ $room->name }}
                            </h3>
                            <p class="text-[10px] sm:text-[11px] text-slate-400 mt-0.5 line-clamp-1">
                                {{ $room->size ? $room->size . ' • ' : '' }}AC • WiFi • K. Mandi Dalam
                            </p>
                        </div>

                        <div class="pt-2 border-t border-slate-100 flex items-baseline justify-between">
                            <div>
                                <span class="text-blue-600 font-extrabold text-xs sm:text-sm">
                                    Rp {{ number_format($room->price, 0, ',', '.') }}
                                </span>
                                <span class="text-[10px] text-slate-400">/{{ $cycle }}</span>
                            </div>
                            <span class="hidden sm:inline text-[11px] font-bold text-slate-600 group-hover:text-blue-600">Lihat →</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-slate-500 text-sm">Belum ada kamar yang terdaftar.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination Controls & Link ke Halaman Cari & Daftar Kos --}}
        <div id="paginationContainer" class="mt-5 flex items-center justify-center gap-1.5"></div>

        <div class="mt-6 flex items-center justify-center">
            <a href="{{ route('rooms.search') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-bold shadow-2xs transition-all">
                <span>Lihat &amp; Filter Seluruh Daftar Kos</span>
                <span>→</span>
            </a>
        </div>

        <div id="noResultsState" class="hidden text-center py-10">
            <p class="text-slate-600 text-xs sm:text-sm font-medium mb-3">Tidak ada kamar kos yang sesuai filter.</p>
            <button type="button" onclick="resetAllFilters()"
                    class="bg-blue-600 text-white font-semibold text-xs px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Reset Filter
            </button>
        </div>

    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════════
     SECTION 4: FASILITAS UMUM + KONTAK + FOOTER
══════════════════════════════════════════════════════════════════ --}}
<div class="full-screen-section flex flex-col justify-between bg-white">

    {{-- Bagian Atas: Fasilitas Umum --}}
    <section id="fasilitas" class="flex-1 flex flex-col justify-center py-8 sm:py-10">
        <div class="max-w-6xl w-full mx-auto px-4 sm:px-6">
            <div class="mb-5 text-center sm:text-left">
                <h2 class="text-lg sm:text-2xl font-extrabold text-slate-900">Fasilitas Standar RumahKos</h2>
                <p class="text-slate-500 text-xs sm:text-sm mt-0.5">Fasilitas penunjang kuliah, kerja, dan istirahat di setiap unit.</p>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                @php
                    $mainFacilities = [
                        ['name' => 'WiFi Cepat', 'desc' => 'Internet stabil 24 jam untuk kuliah & WFH.', 'icon' => asset('images/icons/fac-wifi.webp')],
                        ['name' => 'AC Kamar', 'desc' => 'Pendingin ruangan dingin & rutin diservis.', 'icon' => asset('images/icons/fac-ac.webp')],
                        ['name' => 'K. Mandi Dalam', 'desc' => 'Kamar mandi pribadi bersih + shower.', 'icon' => asset('images/icons/fac-bath.webp')],
                        ['name' => 'Full Furnished', 'desc' => 'Kasur empuk, lemari, & meja belajar/kerja.', 'icon' => asset('images/icons/fac-bed.webp')],
                        ['name' => 'Area Parkir', 'desc' => 'Parkir motor & mobil aman di dalam area.', 'icon' => asset('images/icons/fac-parking.webp')],
                        ['name' => 'CCTV 24 Jam', 'desc' => 'Keamanan terpantau kamera 24 jam penuh.', 'icon' => asset('images/icons/fac-cctv.webp')],
                        ['name' => 'Dapur Bersama', 'desc' => 'Kompor, kulkas, & dispenser air minum.', 'icon' => asset('images/icons/fac-kitchen.webp')],
                        ['name' => 'Cuci & Jemur', 'desc' => 'Tersedia area cuci dan ruang jemur pakaian.', 'icon' => asset('images/icons/fac-laundry.webp')],
                    ];
                @endphp

                @foreach($mainFacilities as $fac)
                <div class="p-3.5 sm:p-4 rounded-xl border border-slate-200/90 bg-slate-50/70 hover:bg-white hover:border-slate-300 transition-all flex items-start gap-3">
                    <img src="{{ $fac['icon'] }}" alt="{{ $fac['name'] }}" width="40" height="40" loading="lazy" decoding="async" class="w-9 h-9 sm:w-10 sm:h-10 object-contain shrink-0">
                    <div class="min-w-0">
                        <h4 class="font-bold text-slate-900 text-xs sm:text-sm">{{ $fac['name'] }}</h4>
                        <p class="text-[11px] sm:text-xs text-slate-500 mt-0.5 leading-snug">{{ $fac['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Bagian Tengah & Bawah: Kontak Bar + Footer --}}
    <div id="kontak" class="bg-slate-900 text-white">
        <div class="border-b border-slate-800 py-6 px-4 sm:px-6">
            <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-white">
                        Mau tanya ketersediaan kamar atau jadwal survei lokasi?
                    </h2>
                    <p class="text-slate-400 text-xs sm:text-sm mt-0.5">
                        Tim admin kami siap bantu rekomendasikan kos sesuai kampus, kantor, atau budgetmu.
                    </p>
                </div>
                <div class="flex flex-wrap items-center justify-center gap-2.5 shrink-0">
                    <a href="https://wa.me/{{ !empty($globalProperty?->whatsapp) ? preg_replace('/[^0-9]/', '', $globalProperty->whatsapp) : '6283841806357' }}?text=Halo%20RumahKos,%20saya%20butuh%20info%20kamar%20kos"
                       target="_blank"
                       class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-lg transition-colors text-xs sm:text-sm">
                        <span>Chat WhatsApp</span>
                    </a>
                    <a href="#kamar"
                       class="inline-flex items-center justify-center gap-2 border border-slate-700 hover:bg-slate-800 text-white font-semibold px-5 py-2.5 rounded-lg transition-colors text-xs sm:text-sm">
                        <span>Lihat Kamar</span>
                    </a>
                </div>
            </div>
        </div>

        @include('landing.footer')
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════════════
     KHUSUS MOBILE (< md): STICKY BOTTOM APP NAVIGATION BAR
══════════════════════════════════════════════════════════════════ --}}
<nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 px-2 py-1.5 grid grid-cols-5 text-center shadow-[0_-2px_10px_rgba(0,0,0,0.05)]">
    <a href="{{ route('landing') }}" class="flex flex-col items-center justify-center gap-0.5 text-blue-600">
        <img src="{{ asset('images/icons/logo-rumahkos.webp') }}" alt="" class="w-5 h-5 object-contain">
        <span class="text-[10px] font-bold">Beranda</span>
    </a>
    <a href="{{ route('rooms.search') }}" class="flex flex-col items-center justify-center gap-0.5 text-slate-500 hover:text-blue-600">
        <img src="{{ asset('images/icons/ic-search-building.webp') }}" alt="" class="w-5 h-5 object-contain">
        <span class="text-[10px] font-semibold">Cari Kos</span>
    </a>
    <a href="#promo" class="flex flex-col items-center justify-center gap-0.5 text-slate-500 hover:text-blue-600">
        <img src="{{ asset('images/icons/ic-price.webp') }}" alt="" class="w-5 h-5 object-contain">
        <span class="text-[10px] font-semibold">Promo</span>
    </a>
    <a href="#kota" class="flex flex-col items-center justify-center gap-0.5 text-slate-500 hover:text-blue-600">
        <img src="{{ asset('images/icons/ic-location.webp') }}" alt="" class="w-5 h-5 object-contain">
        <span class="text-[10px] font-semibold">Kota</span>
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

<button id="backToTop" onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Kembali ke atas"
        class="w-9 h-9 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-blue-600 hover:text-white shadow-md flex items-center justify-center transition-all">
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
    </svg>
</button>

<script>
    let activeType = 'all';
    let activeChip = 'all';
    let currentSort = null;
    let currentPage = 1;
    const perPage = 8;

    // ═══ AUTO-SLIDING BANNER CAROUSEL LOGIC ═══
    let bannerIndex = 0;
    let bannerTimer = null;
    const totalBanners = 4;

    function getMaxBannerIndex() {
        return window.innerWidth >= 768 ? totalBanners - 2 : totalBanners - 1;
    }

    function updateBannerCarousel() {
        const track = document.getElementById('bannerTrack');
        if (!track || !track.children.length) return;
        const maxIdx = getMaxBannerIndex();
        if (bannerIndex > maxIdx) bannerIndex = 0;
        if (bannerIndex < 0) bannerIndex = maxIdx;

        const slideWidth = track.children[0].getBoundingClientRect().width;
        track.style.transform = `translate3d(-${Math.round(bannerIndex * slideWidth)}px, 0, 0)`;

        document.querySelectorAll('#bannerDots .banner-dot').forEach((dot, idx) => {
            dot.classList.toggle('active', idx === bannerIndex);
        });
    }

    function moveBannerSlide(delta) {
        bannerIndex += delta;
        updateBannerCarousel();
    }

    function goToBannerSlide(idx) {
        bannerIndex = idx;
        updateBannerCarousel();
    }

    function startBannerAutoPlay() {
        stopBannerAutoPlay();
        bannerTimer = setInterval(() => {
            bannerIndex++;
            updateBannerCarousel();
        }, 3200);
    }

    function stopBannerAutoPlay() {
        if (bannerTimer) {
            clearInterval(bannerTimer);
            bannerTimer = null;
        }
    }

    function pauseBannerAutoPlay() {
        stopBannerAutoPlay();
    }

    window.addEventListener('resize', updateBannerCarousel, { passive: true });

    let shimmerTimer = null;

    function triggerRoomsShimmer(matchingCount) {
        const shimmerEl = document.getElementById('roomsShimmerGrid');
        const containerEl = document.getElementById('roomsContainer');
        const noResultsEl = document.getElementById('noResultsState');
        if (!shimmerEl || !containerEl) return;

        if (shimmerTimer) clearTimeout(shimmerTimer);

        if (matchingCount === 0) {
            shimmerEl.classList.add('hidden');
            containerEl.classList.add('hidden');
            noResultsEl?.classList.remove('hidden');
            return;
        }

        noResultsEl?.classList.add('hidden');

        // Cek apakah gambar di kartu yang tampil sudah ada di cache browser (complete).
        // Kalau sudah ke-load di cache, tampilkan langsung TANPA blink shimmer!
        const visibleImgs = Array.from(containerEl.querySelectorAll('.room-card:not(.hidden-room) img'));
        const pendingImgs = visibleImgs.filter(img => !img.complete);

        if (pendingImgs.length === 0) {
            shimmerEl.classList.add('hidden');
            containerEl.classList.remove('hidden');
            return;
        }

        containerEl.classList.add('hidden');
        shimmerEl.classList.remove('hidden');
        shimmerEl.classList.add('grid');

        const finishShimmer = () => {
            if (shimmerTimer) clearTimeout(shimmerTimer);
            shimmerEl.classList.add('hidden');
            shimmerEl.classList.remove('grid');
            containerEl.classList.remove('hidden');
        };

        let remaining = pendingImgs.length;
        pendingImgs.forEach(img => {
            const onDone = () => {
                remaining--;
                if (remaining <= 0) finishShimmer();
            };
            img.addEventListener('load', onDone, { once: true });
            img.addEventListener('error', onDone, { once: true });
        });

        shimmerTimer = setTimeout(finishShimmer, 280);
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Strip shimmer background dari semua gambar yang sudah ada di cache browser
        document.querySelectorAll('img').forEach(img => {
            if (img.complete && img.parentElement) {
                img.parentElement.classList.remove('shimmer-bg', 'shimmer-dark');
            }
        });

        applyFilters(false, true);
        updateBannerCarousel();
        startBannerAutoPlay();
    });

    window.addEventListener('scroll', () => {
        const btn = document.getElementById('backToTop');
        if (btn) {
            btn.classList.toggle('show', window.scrollY > 350);
        }
    }, { passive: true });

    function scrollToRooms() {
        const cityVal = (document.getElementById('filterCity')?.value || 'all').toLowerCase();
        const budgetVal = document.getElementById('filterBudget')?.value || 'all';
        const kwVal = (document.getElementById('heroKeywordInput')?.value || document.getElementById('keywordInput')?.value || '').trim();

        const params = new URLSearchParams();
        if (cityVal !== 'all') params.set('city', cityVal);
        if (activeType !== 'all') params.set('type', activeType);
        if (budgetVal !== 'all') params.set('budget', budgetVal);
        if (kwVal) params.set('q', kwVal);

        const qs = params.toString();
        window.location.href = '{{ route("rooms.search") }}' + (qs ? '?' + qs : '');
    }

    function setSearchTypeTab(type) {
        activeType = type;
        currentPage = 1;
        ['all', 'campur', 'putri', 'putra'].forEach(t => {
            const cap = t.charAt(0).toUpperCase() + t.slice(1);
            document.getElementById('tab' + cap)?.classList.toggle('active', t === type);
            document.getElementById('mobTab' + cap)?.classList.toggle('active', t === type);
        });
        applyFilters();
    }

    function syncMobileCity(val) {
        const select = document.getElementById('filterCity');
        if (select) select.value = val;
        applyFilters(true);
    }

    function syncMobileBudget(val) {
        const select = document.getElementById('filterBudget');
        if (select) select.value = val;
        applyFilters(true);
    }

    function applyPromoFilter(budgetVal, typeVal) {
        const budgetSelect = document.getElementById('filterBudget');
        const mobBudgetSelect = document.getElementById('mobileFilterBudget');
        if (budgetSelect) budgetSelect.value = budgetVal;
        if (mobBudgetSelect) mobBudgetSelect.value = budgetVal;
        setSearchTypeTab(typeVal);
        document.getElementById('kamar')?.scrollIntoView({ behavior: 'smooth' });
    }

    function selectCityFilter(cityName) {
        const val = cityName.toLowerCase();
        const select = document.getElementById('filterCity');
        const mobSelect = document.getElementById('mobileFilterCity');
        if (select) select.value = val;
        if (mobSelect) mobSelect.value = val;
        currentPage = 1;
        applyFilters();
        document.getElementById('kamar')?.scrollIntoView({ behavior: 'smooth' });
    }

    function resetCityFilter() {
        const select = document.getElementById('filterCity');
        const mobSelect = document.getElementById('mobileFilterCity');
        if (select) select.value = 'all';
        if (mobSelect) mobSelect.value = 'all';
        currentPage = 1;
        applyFilters();
        document.getElementById('kamar')?.scrollIntoView({ behavior: 'smooth' });
    }

    function setChipFilter(chip) {
        activeChip = chip;
        currentPage = 1;
        document.querySelectorAll('.filter-chip[data-chip]').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.chip === chip);
        });
        applyFilters();
    }

    function toggleSort(type) {
        const btn = document.getElementById('sortPriceBtn');
        if (currentSort === type) {
            currentSort = null;
            btn?.classList.remove('active');
        } else {
            currentSort = type;
            btn?.classList.add('active');
        }
        currentPage = 1;
        applyFilters();
    }

    function syncHeroKeyword(val) {
        const input = document.getElementById('keywordInput');
        if (input) input.value = val;
        currentPage = 1;
        applyFilters();
    }

    function clearKeyword() {
        const input = document.getElementById('keywordInput');
        const heroInput = document.getElementById('heroKeywordInput');
        if (input) input.value = '';
        if (heroInput) heroInput.value = '';
        document.getElementById('clearKeywordBtn')?.classList.add('hidden');
        currentPage = 1;
        applyFilters();
    }

    function resetAllFilters() {
        ['filterCity', 'mobileFilterCity', 'filterBudget', 'mobileFilterBudget'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = 'all';
        });
        const keywordInput = document.getElementById('keywordInput');
        const heroKeywordInput = document.getElementById('heroKeywordInput');
        if (keywordInput) keywordInput.value = '';
        if (heroKeywordInput) heroKeywordInput.value = '';

        setSearchTypeTab('all');
        activeChip = 'all';
        currentSort = null;
        currentPage = 1;

        document.querySelectorAll('.filter-chip[data-chip]').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.chip === 'all');
        });
        document.getElementById('sortPriceBtn')?.classList.remove('active');
        document.getElementById('clearKeywordBtn')?.classList.add('hidden');

        applyFilters();
    }

    function goToPage(page) {
        currentPage = page;
        applyFilters(false);
        document.getElementById('kamar')?.scrollIntoView({ behavior: 'smooth' });
    }

    function renderPagination(totalMatching) {
        const pagEl = document.getElementById('paginationContainer');
        if (!pagEl) return;

        const totalPages = Math.ceil(totalMatching / perPage);
        if (totalPages <= 1) {
            pagEl.innerHTML = '';
            return;
        }

        let html = '';
        for (let i = 1; i <= totalPages; i++) {
            const activeClass = (i === currentPage) ? 'active' : 'bg-white text-slate-700 hover:bg-slate-100';
            html += `<button type="button" onclick="goToPage(${i})" class="page-btn ${activeClass} w-7 h-7 sm:w-8 sm:h-8 rounded-lg text-xs font-bold border border-slate-200 transition-colors">${i}</button>`;
        }
        pagEl.innerHTML = html;
    }

    function applyFilters(resetPage = false, withShimmer = true) {
        if (resetPage) currentPage = 1;

        const cityVal = (document.getElementById('filterCity')?.value || 'all').toLowerCase();
        const budgetVal = document.getElementById('filterBudget')?.value || 'all';
        const keywordVal = (document.getElementById('keywordInput')?.value || '').toLowerCase().trim();

        const mobCity = document.getElementById('mobileFilterCity');
        const mobBudget = document.getElementById('mobileFilterBudget');
        if (mobCity && mobCity.value !== cityVal) mobCity.value = cityVal;
        if (mobBudget && mobBudget.value !== budgetVal) mobBudget.value = budgetVal;

        document.getElementById('clearKeywordBtn')?.classList.toggle('hidden', keywordVal.length === 0);

        const cards = Array.from(document.querySelectorAll('.room-card'));

        if (currentSort === 'price-asc') {
            const container = document.getElementById('roomsContainer');
            if (container) {
                cards.sort((a, b) => (parseFloat(a.dataset.price) || 0) - (parseFloat(b.dataset.price) || 0))
                     .forEach(card => container.appendChild(card));
            }
        }

        const matchingCards = cards.filter(card => {
            const cardName = card.dataset.name || '';
            const cardCity = (card.dataset.city || '').toLowerCase();
            const cardType = (card.dataset.type || '').toLowerCase();
            const cardStatus = card.dataset.status || '';
            const cardPrice = parseFloat(card.dataset.price) || 0;

            const matchCity = (cityVal === 'all') || (cardCity === cityVal);
            const matchTypeTab = (activeType === 'all') || (cardType === activeType);

            let matchBudget = true;
            if (budgetVal === 'under-15') matchBudget = cardPrice < 1500000;
            else if (budgetVal === '15-25') matchBudget = cardPrice >= 1500000 && cardPrice <= 2500000;
            else if (budgetVal === 'above-25') matchBudget = cardPrice > 2500000;

            const matchChip = (activeChip !== 'available') || (cardStatus === 'available');
            const matchKeyword = !keywordVal || cardName.includes(keywordVal) || cardCity.includes(keywordVal) || cardType.includes(keywordVal);

            return matchCity && matchTypeTab && matchBudget && matchChip && matchKeyword;
        });

        const totalPages = Math.max(1, Math.ceil(matchingCards.length / perPage));
        if (currentPage > totalPages) currentPage = 1;

        const startIdx = (currentPage - 1) * perPage;
        const endIdx = startIdx + perPage;
        const visibleSet = new Set(matchingCards.slice(startIdx, endIdx));

        cards.forEach(card => {
            card.classList.toggle('hidden-room', !visibleSet.has(card));
        });

        const kamarSection = document.getElementById('kamar');
        if (kamarSection) {
            kamarSection.classList.toggle('full-screen-section', visibleSet.size > 4);
        }

        renderPagination(matchingCards.length);

        const displayCountEl = document.getElementById('displayCount');
        const noResultsEl = document.getElementById('noResultsState');
        const containerEl = document.getElementById('roomsContainer');

        if (displayCountEl) displayCountEl.textContent = matchingCards.length;

        if (withShimmer) {
            triggerRoomsShimmer(matchingCards.length);
        } else if (noResultsEl && containerEl) {
            noResultsEl.classList.toggle('hidden', matchingCards.length > 0);
            containerEl.classList.toggle('hidden', matchingCards.length === 0);
        }
    }
</script>

</body>
</html>
