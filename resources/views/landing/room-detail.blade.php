<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @php
        $propName = $room->property?->name ?? 'RumahKos';
        $cityName = $room->property?->city ?? 'Bandung';
        $propType = ucfirst($room->property?->type ?? 'campur');
        $propAddr = $room->property?->address ?? ('Lokasi Strategis ' . $cityName);
        $mainPhoto = $galleryPhotos[0] ?? asset('images/default-room.webp');
        $seoTitle = "{$room->name} - {$propName} ({$cityName})";
        $seoDesc = $room->description
            ?: "Sewa {$room->name} di {$propName}, {$cityName} (Kos {$propType}). Harga Rp " . number_format($room->price, 0, ',', '.') . " dilengkapi WiFi cepat, AC, dan kamar mandi dalam.";
        $schemaJson = \App\Services\SeoService::getRoomSchema($room, $room->property ?? $globalProperty ?? null);
    @endphp

    @include('components.seo-head', [
        'title' => $seoTitle,
        'description' => $seoDesc,
        'image' => $mainPhoto,
        'type' => 'product',
        'schemaJson' => $schemaJson
    ])
    <link rel="preload" as="image" href="{{ $mainPhoto }}" type="image/webp" fetchpriority="high">
    @include('components.meta-pixel')

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
        .thumb-active {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.35);
        }
    </style>
</head>
<body class="bg-slate-50 antialiased pb-24 lg:pb-0">

@include('landing.navbar')

{{-- Alert Toast Messages --}}
@if(session('success'))
<div class="fixed top-20 right-4 z-50 max-w-sm mx-4 sm:mx-0 rk-toast">
    <div class="bg-emerald-600 text-white px-4 py-3 rounded-xl shadow-xl flex items-center gap-3">
        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        <span class="flex-1 text-xs sm:text-sm font-semibold">{{ session('success') }}</span>
        <button type="button" onclick="this.closest('.rk-toast').remove()" class="text-white/80 hover:text-white font-bold">✕</button>
    </div>
</div>
@endif

@if(session('error'))
<div class="fixed top-20 right-4 z-50 max-w-sm mx-4 sm:mx-0 rk-toast">
    <div class="bg-rose-600 text-white px-4 py-3 rounded-xl shadow-xl flex items-center gap-3">
        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
        </svg>
        <span class="flex-1 text-xs sm:text-sm font-semibold">{{ session('error') }}</span>
        <button type="button" onclick="this.closest('.rk-toast').remove()" class="text-white/80 hover:text-white font-bold">✕</button>
    </div>
</div>
@endif

@php
    $cycleShort = match($room->billing_cycle ?? 'monthly') {
        'daily'   => 'hari',
        'weekly'  => 'minggu',
        'monthly' => 'bulan',
        default   => 'tahun'
    };
    $waNumber = !empty($contact?->whatsapp)
        ? preg_replace('/[^0-9]/', '', $contact->whatsapp)
        : (!empty($globalProperty?->whatsapp) ? preg_replace('/[^0-9]/', '', $globalProperty->whatsapp) : '6283841806357');
    $waText = urlencode("Halo Admin RumahKos, saya tertarik menyewa kamar {$room->name} di {$propName} ({$cityName}). Apakah masih tersedia?");
@endphp

{{-- ══════════════════════════════════════════════════════════════════
     DARK HERO HEADER BANNER (AGAR NAVBAR TRANSPARAN TETAP KONTRAS)
══════════════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-slate-900 pt-24 sm:pt-28 pb-7 sm:pb-9">
    <img src="{{ $cityBannerUrl ?? asset('images/hero-kos.webp') }}"
         alt="{{ $propName }}"
         decoding="sync"
         class="absolute inset-0 w-full h-full object-cover opacity-35 pointer-events-none">
    <div class="absolute inset-0 bg-gradient-to-b from-slate-950/90 via-slate-900/80 to-slate-950/95 pointer-events-none"></div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 relative z-10">
        {{-- Breadcrumb SEO --}}
        <nav aria-label="Breadcrumb" class="flex flex-wrap items-center justify-between gap-2 mb-3">
            <ol class="flex items-center gap-1.5 text-xs font-semibold text-white/75 overflow-x-auto no-scrollbar whitespace-nowrap">
                <li><a href="{{ route('landing') }}" class="hover:text-white transition-colors">Beranda</a></li>
                <li>/</li>
                <li><a href="{{ route('rooms.search') }}" class="hover:text-white transition-colors">Cari &amp; Daftar Kos</a></li>
                <li>/</li>
                <li><a href="{{ route('rooms.search', ['city' => strtolower($cityName)]) }}" class="hover:text-white transition-colors">{{ $cityName }}</a></li>
                <li>/</li>
                <li class="text-sky-300 font-bold truncate max-w-[180px] sm:max-w-xs">{{ $room->name }}</li>
            </ol>

            <button type="button" onclick="shareRoomUrl()"
                    class="inline-flex items-center gap-1.5 text-xs font-bold text-white bg-white/10 hover:bg-white/20 border border-white/15 px-3 py-1.5 rounded-xl transition-all shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                </svg>
                <span id="shareBtnLabel">Bagikan Link</span>
            </button>
        </nav>

        {{-- Room Title & Quick Metadata --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-3">
            <div>
                <div class="flex flex-wrap items-center gap-2 text-xs font-bold text-sky-300 mb-1">
                    <span>{{ $cityName }}</span>
                    <span>•</span>
                    <span>Kos {{ $propType }}</span>
                    <span>•</span>
                    <span>Lantai {{ $room->floor ?? 1 }}</span>
                    <span>•</span>
                    <span class="{{ $room->status === 'available' ? 'text-emerald-400' : 'text-rose-400' }}">
                        {{ $room->status === 'available' ? 'Kamar Tersedia' : 'Sedang Terisi' }}
                    </span>
                </div>

                <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-white tracking-tight leading-snug">
                    {{ $room->name }} — {{ $propName }}
                </h1>

                <p class="text-xs sm:text-sm text-white/80 mt-1 flex items-center gap-1.5">
                    <img src="{{ asset('images/icons/ic-location.webp') }}" alt="" class="w-4 h-4 object-contain shrink-0">
                    <span>{{ $propAddr }}</span>
                </p>
            </div>

            <div class="flex items-center gap-3 self-start md:self-end bg-white/10 backdrop-blur-md border border-white/15 rounded-xl px-3.5 py-2">
                <div class="text-amber-400 text-base font-extrabold">★ {{ number_format($averageRating, 1) }}</div>
                <div class="h-4 w-px bg-white/20"></div>
                <a href="#ulasan-penghuni" class="text-xs font-semibold text-white/90 hover:text-white underline-offset-2 hover:underline">
                    {{ $totalReviews > 0 ? $totalReviews . ' Ulasan Penghuni' : 'Ulasan Terverifikasi' }}
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════════
     MAIN CONTENT: BENTO PHOTO GALLERY + 2-COLUMN LAYOUT
══════════════════════════════════════════════════════════════════ --}}
<main class="max-w-6xl mx-auto px-4 sm:px-6 pt-5 pb-12">

    {{-- 1. INTERACTIVE PHOTO SHOWCASE (BENTO DESKTOP + THUMBNAIL STRIP MOBILE) --}}
    <div class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-2.5 sm:gap-3">
            {{-- Foto Utama Besar --}}
            <div class="md:col-span-8 relative h-60 sm:h-80 md:h-[380px] rounded-2xl overflow-hidden bg-slate-200 shimmer-bg group cursor-pointer"
                 onclick="openLightbox(0)">
                <img id="mainHeroPhoto"
                     src="{{ $mainPhoto }}"
                     alt="{{ $room->name }}"
                     fetchpriority="high"
                     decoding="sync"
                     onload="this.parentElement.classList.remove('shimmer-bg')"
                     onerror="this.onerror=null;this.src='{{ asset('images/default-room.webp') }}';this.parentElement.classList.remove('shimmer-bg');"
                     class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-300">

                <button type="button" onclick="event.stopPropagation(); openLightbox(0)"
                        class="absolute bottom-3 right-3 bg-slate-950/75 hover:bg-slate-950/90 text-white text-xs font-bold px-3.5 py-2 rounded-xl backdrop-blur-md flex items-center gap-1.5 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Lihat Semua Foto ({{ count($galleryPhotos) }})</span>
                </button>
            </div>

            {{-- Kolom Foto Pendamping (Desktop Bento Grid) --}}
            <div class="hidden md:grid md:col-span-4 grid-rows-2 gap-3 h-[380px]">
                @php
                    $sidePhoto1 = $galleryPhotos[1] ?? $cityBannerUrl ?? $mainPhoto;
                    $sidePhoto2 = $galleryPhotos[2] ?? asset('images/hero-kos.webp');
                @endphp
                <div class="relative rounded-2xl overflow-hidden bg-slate-200 shimmer-bg group cursor-pointer"
                     onclick="switchMainPhoto('{{ $sidePhoto1 }}', 1)">
                    <img src="{{ $sidePhoto1 }}"
                         alt="{{ $room->name }} Interior"
                         decoding="sync"
                         onload="this.parentElement.classList.remove('shimmer-bg')"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                <div class="relative rounded-2xl overflow-hidden bg-slate-200 shimmer-bg group cursor-pointer"
                     onclick="openLightbox(2)">
                    <img src="{{ $sidePhoto2 }}"
                         alt="{{ $propName }} Exterior"
                         decoding="sync"
                         onload="this.parentElement.classList.remove('shimmer-bg')"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-slate-950/35 group-hover:bg-slate-950/20 transition-colors flex items-center justify-center">
                        <span class="text-white text-xs font-extrabold bg-slate-950/60 px-3 py-1.5 rounded-lg backdrop-blur-xs">
                            Perbesar Galeri Foto
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Thumbnail Selector Strip (Enak di-tap di HP & Desktop) --}}
        @if(count($galleryPhotos) > 1)
        <div class="flex items-center gap-2 mt-2.5 overflow-x-auto no-scrollbar pb-1">
            @foreach($galleryPhotos as $idx => $gUrl)
                <button type="button"
                        onclick="switchMainPhoto('{{ $gUrl }}', {{ $idx }})"
                        data-thumb-idx="{{ $idx }}"
                        class="gallery-thumb {{ $idx === 0 ? 'thumb-active' : '' }} relative w-16 h-12 sm:w-20 sm:h-14 rounded-xl overflow-hidden border-2 border-transparent shrink-0 bg-slate-200 transition-all">
                    <img src="{{ $gUrl }}" alt="" decoding="sync" class="w-full h-full object-cover">
                </button>
            @endforeach
        </div>
        @endif
    </div>

    {{-- 2. GRID 2 KOLOM: INFORMASI LENGKAP (KIRI) & KARTU SEWA STICKY (KANAN) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        {{-- ═══ KOLOM KIRI (8 KOLOM): SPESIFIKASI, FASILITAS, ATURAN, LOKASI, ULASAN ═══ --}}
        <div class="lg:col-span-8 space-y-5">

            {{-- A. Ringkasan Spesifikasi Utama --}}
            <div class="bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5 shadow-2xs">
                <h2 class="text-sm sm:text-base font-extrabold text-slate-900 mb-3.5">Spesifikasi Kamar</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center gap-3">
                        <img src="{{ asset('images/icons/fac-bed.webp') }}" alt="" class="w-7 h-7 object-contain shrink-0">
                        <div>
                            <div class="text-[11px] text-slate-400 font-semibold">Ukuran Kamar</div>
                            <div class="text-xs sm:text-sm font-extrabold text-slate-800">{{ $room->size ?: '3 x 4 m' }}</div>
                        </div>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center gap-3">
                        <img src="{{ asset('images/icons/ic-search-building.webp') }}" alt="" class="w-7 h-7 object-contain shrink-0">
                        <div>
                            <div class="text-[11px] text-slate-400 font-semibold">Posisi Lantai</div>
                            <div class="text-xs sm:text-sm font-extrabold text-slate-800">Lantai {{ $room->floor ?? 1 }}</div>
                        </div>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center gap-3">
                        <img src="{{ asset('images/icons/cat-' . strtolower($room->property?->type ?? 'campur') . '.webp') }}" alt="" class="w-7 h-7 object-contain shrink-0">
                        <div>
                            <div class="text-[11px] text-slate-400 font-semibold">Tipe Hunian</div>
                            <div class="text-xs sm:text-sm font-extrabold text-slate-800">Kos {{ $propType }}</div>
                        </div>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center gap-3">
                        <img src="{{ asset('images/icons/ic-price.webp') }}" alt="" class="w-7 h-7 object-contain shrink-0">
                        <div>
                            <div class="text-[11px] text-slate-400 font-semibold">Periode Sewa</div>
                            <div class="text-xs sm:text-sm font-extrabold text-slate-800">Per {{ ucfirst($cycleShort) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- B. Tentang Kamar & Gedung Properti --}}
            <div class="bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5 shadow-2xs">
                <h2 class="text-sm sm:text-base font-extrabold text-slate-900 mb-2">Tentang {{ $room->name }}</h2>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    {{ $room->description ?: "Kamar {$room->name} di {$propName} ({$cityName}) dirancang dengan tata ruang modern, sirkulasi udara sehat, dan pencahayaan alami yang nyaman untuk mahasiswa maupun pekerja profesional. Setiap unit sudah siap huni (fully furnished) dengan koneksi internet cepat dan keamanan 24 jam." }}
                </p>

                <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                    <div>
                        <span class="text-slate-400 font-medium block">Nama Properti</span>
                        <span class="font-extrabold text-slate-800">{{ $propName }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium block">Kota &amp; Area</span>
                        <span class="font-extrabold text-slate-800">{{ $cityName }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium block">Kapasitas Maksimal</span>
                        <span class="font-extrabold text-slate-800">1 - 2 Penghuni / Kamar</span>
                    </div>
                </div>
            </div>

            {{-- C. Fasilitas Kamar & Bersama (100% WebP Icons) --}}
            <div class="bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5 shadow-2xs">
                <h2 class="text-sm sm:text-base font-extrabold text-slate-900 mb-3.5">Fasilitas Kamar &amp; Properti</h2>

                @php
                    $defaultFacilities = [
                        ['name' => 'WiFi Cepat 100 Mbps', 'desc' => 'Internet stabil untuk WFH & kuliah', 'icon' => 'fac-wifi.webp'],
                        ['name' => 'AC Dingin Hemat Daya', 'desc' => 'Suhu ruangan sejuk & rutin diservis', 'icon' => 'fac-ac.webp'],
                        ['name' => 'Kamar Mandi Dalam', 'desc' => 'Shower, kloset duduk & air bersih', 'icon' => 'fac-bath.webp'],
                        ['name' => 'Kasur Springbed & Meja', 'desc' => 'Full furnished dengan lemari pakaian', 'icon' => 'fac-bed.webp'],
                        ['name' => 'Area Parkir Motor & Mobil', 'desc' => 'Parkir teduh khusus penghuni kos', 'icon' => 'fac-parking.webp'],
                        ['name' => 'Keamanan CCTV 24 Jam', 'desc' => 'Akses keluar-masuk penghuni aman', 'icon' => 'fac-cctv.webp'],
                    ];
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($defaultFacilities as $df)
                        <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50/80 border border-slate-100">
                            <img src="{{ asset('images/icons/' . $df['icon']) }}" alt="{{ $df['name'] }}" class="w-6 h-6 object-contain shrink-0 mt-0.5">
                            <div>
                                <div class="text-xs sm:text-sm font-bold text-slate-800">{{ $df['name'] }}</div>
                                <div class="text-[11px] text-slate-500">{{ $df['desc'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($room->facilities && $room->facilities->count() > 0)
                    <div class="mt-4 pt-3.5 border-t border-slate-100">
                        <div class="text-xs font-bold text-slate-500 mb-2">Fasilitas Tambahan Unit Ini:</div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($room->facilities as $facility)
                                <span class="text-xs font-semibold text-slate-700 bg-slate-100 px-3 py-1 rounded-lg">
                                    {{ $facility->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- D. Peraturan Kos & Ketentuan Check-in --}}
            <div class="bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5 shadow-2xs">
                <h2 class="text-sm sm:text-base font-extrabold text-slate-900 mb-3">Ketentuan Hunian &amp; Tata Tertib</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs text-slate-600">
                    <div class="flex items-start gap-2.5">
                        <span class="text-blue-600 font-extrabold">•</span>
                        <span>Wajib melampirkan identitas e-KTP aktif saat pengajuan sewa kamar.</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="text-blue-600 font-extrabold">•</span>
                        <span>Akses kunci mandiri 24 jam bagi penghuni terdaftar dengan pengawasan CCTV.</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="text-blue-600 font-extrabold">•</span>
                        <span>Menjaga ketenangan lingkungan kos terutama di atas pukul 22.00 WIB.</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="text-blue-600 font-extrabold">•</span>
                        <span>Dilarang membawa barang terlarang atau merusak inventaris fasilitas kamar.</span>
                    </div>
                </div>
            </div>

            {{-- E. Ulasan & Rating Penghuni --}}
            <div id="ulasan-penghuni" class="bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5 shadow-2xs">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-sm sm:text-base font-extrabold text-slate-900">
                            Ulasan Penghuni ({{ $totalReviews }})
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Penilaian jujur dari penghuni yang pernah atau sedang menyewa kamar ini
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1.5 bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-xl">
                            <span class="text-amber-500 font-extrabold text-sm">★</span>
                            <span class="text-sm font-extrabold text-slate-900">{{ number_format($averageRating, 1) }}</span>
                            <span class="text-xs text-slate-400">/ 5.0</span>
                        </div>

                        @auth('tenant')
                            @php
                                $canTenantReview = \App\Models\Resident::where('user_id', auth('tenant')->id())
                                    ->where('room_id', $room->id)
                                    ->whereIn('status', ['active', 'completed', 'moved_out'])
                                    ->exists();
                                $hasTenantReviewed = \App\Models\Review::where('room_id', $room->id)
                                    ->where('user_id', auth('tenant')->id())
                                    ->exists();
                            @endphp
                            @if($canTenantReview && !$hasTenantReviewed)
                                <button type="button" onclick="openReviewModal()"
                                        class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-3.5 py-2 rounded-xl transition-colors">
                                    Tulis Ulasan
                                </button>
                            @endif
                        @endauth
                    </div>
                </div>

                {{-- Skor Kategori --}}
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5 py-4 border-b border-slate-100">
                    @php
                        $catLabels = [
                            'cleanliness' => 'Kebersihan',
                            'facilities'  => 'Fasilitas',
                            'service'     => 'Pelayanan',
                            'location'    => 'Lokasi',
                            'price'       => 'Harga',
                        ];
                    @endphp
                    @foreach($catLabels as $cKey => $cName)
                        @php $cScore = $categoryRatings[$cKey] ?? 4.9; @endphp
                        <div class="bg-slate-50 rounded-xl p-2.5 text-center border border-slate-100">
                            <div class="text-[11px] text-slate-500 font-medium">{{ $cName }}</div>
                            <div class="text-sm font-extrabold text-slate-900 mt-0.5">★ {{ number_format($cScore, 1) }}</div>
                        </div>
                    @endforeach
                </div>

                {{-- Daftar Ulasan --}}
                @if($reviews && $reviews->count() > 0)
                    <div class="divide-y divide-slate-100 mt-2">
                        @foreach($reviews as $review)
                            <div class="py-4 first:pt-2 last:pb-0">
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 font-extrabold text-xs flex items-center justify-center shrink-0">
                                            {{ strtoupper(substr($review->user->name ?? 'P', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs sm:text-sm font-bold text-slate-900 truncate">{{ $review->user->name ?? 'Penghuni RumahKos' }}</div>
                                            <div class="text-[11px] text-slate-400">{{ $review->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 shrink-0">
                                        <span class="text-xs font-extrabold text-slate-800 bg-slate-100 px-2.5 py-1 rounded-lg">
                                            ★ {{ number_format($review->rating, 1) }}
                                        </span>

                                        @auth('tenant')
                                            @if($review->user_id == auth('tenant')->id())
                                                <div class="relative">
                                                    <button type="button" onclick="toggleDropdown({{ $review->id }})" class="p-1.5 hover:bg-slate-100 rounded-lg text-slate-500 font-bold text-xs">
                                                        •••
                                                    </button>
                                                    <div id="dropdown-{{ $review->id }}" class="hidden absolute right-0 mt-1 w-32 bg-white rounded-xl shadow-lg border border-slate-200 z-20 overflow-hidden">
                                                        <button type="button" onclick="openEditModal({{ $review->id }}, '{{ addslashes($review->comment) }}', {{ $review->rating }})"
                                                                class="w-full text-left px-3 py-2 hover:bg-slate-50 text-xs font-semibold text-slate-700">
                                                            Edit Ulasan
                                                        </button>
                                                        <form action="{{ route('review.destroy', $review->id) }}" method="POST"
                                                              onsubmit="return confirm('Hapus ulasan ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="w-full text-left px-3 py-2 hover:bg-rose-50 text-xs font-semibold text-rose-600">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endif
                                        @endauth
                                    </div>
                                </div>

                                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">{{ $review->comment }}</p>

                                @if(method_exists($review, 'hasReply') && $review->hasReply())
                                    <div class="mt-3 ml-0 sm:ml-8 bg-slate-50 border-l-3 border-blue-600 rounded-r-xl p-3">
                                        <div class="text-[11px] font-bold text-slate-800 mb-0.5">
                                            Balasan Pengelola Kos • <span class="font-normal text-slate-400">{{ $review->latestReply->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-xs text-slate-600">{{ $review->latestReply->reply }}</p>
                                        @auth('web')
                                            <button type="button" onclick="openReplyModal({{ $review->id }}, '{{ addslashes($review->latestReply->reply) }}')"
                                                    class="mt-1.5 text-[11px] font-bold text-blue-600 hover:underline">
                                                Edit Balasan
                                            </button>
                                        @endauth
                                    </div>
                                @else
                                    @auth('web')
                                        <button type="button" onclick="openReplyModal({{ $review->id }})"
                                                class="mt-2 text-xs font-bold text-blue-600 hover:underline">
                                            Balas Ulasan
                                        </button>
                                    @endauth
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-8 text-center">
                        <p class="text-xs sm:text-sm font-bold text-slate-700">Belum ada ulasan tertulis untuk kamar ini</p>
                        <p class="text-xs text-slate-400 mt-0.5">Penghuni terdaftar dapat memberikan ulasan setelah masa sewa dimulai.</p>
                    </div>
                @endif
            </div>

        </div>

        {{-- ═══ KOLOM KANAN (4 KOLOM): STICKY BOOKING & SIMULASI HARGA ═══ --}}
        <aside class="lg:col-span-4 lg:sticky lg:top-24">
            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-lg">
                {{-- Harga Utama --}}
                <div class="pb-4 border-b border-slate-100">
                    <div class="text-xs text-slate-400 font-semibold">Harga sewa kamar</div>
                    <div class="flex items-baseline gap-1 mt-0.5">
                        <span class="text-2xl sm:text-3xl font-extrabold text-blue-600">
                            Rp {{ number_format($room->price, 0, ',', '.') }}
                        </span>
                        <span class="text-xs font-semibold text-slate-400">/ {{ $cycleShort }}</span>
                    </div>
                    <div class="text-[11px] text-emerald-600 font-bold mt-1">
                        Gratis biaya admin • Konfirmasi instan
                    </div>
                </div>

                {{-- Rincian Termasuk --}}
                <div class="py-3.5 border-b border-slate-100 space-y-2 text-xs">
                    <div class="flex items-center justify-between text-slate-600">
                        <span>Properti</span>
                        <span class="font-bold text-slate-800 text-right truncate max-w-[180px]">{{ $propName }}</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-600">
                        <span>Lokasi Kota</span>
                        <span class="font-bold text-slate-800">{{ $cityName }}</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-600">
                        <span>WiFi &amp; Air Bersih</span>
                        <span class="font-bold text-emerald-600">Termasuk</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-600">
                        <span>Status Kamar</span>
                        <span class="font-bold {{ $room->status === 'available' ? 'text-emerald-600' : 'text-rose-500' }}">
                            {{ $room->status === 'available' ? 'Siap Huni' : 'Sedang Penuh' }}
                        </span>
                    </div>
                </div>

                {{-- Tombol Aksi Pemesanan --}}
                <div class="pt-4 space-y-2.5">
                    @if($room->status === 'available')
                        @auth('tenant')
                            @php
                                $profile = auth('tenant')->user()->profile;
                                $isProfileComplete = $profile && $profile->phone && $profile->identity_number && $profile->ktp_photo;
                            @endphp

                            @if($isProfileComplete)
                                <a href="{{ route('tenant.booking.create', $room->id) }}"
                                   class="w-full py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs sm:text-sm flex items-center justify-center gap-2 shadow-md shadow-blue-600/20 transition-all">
                                    <span>Ajukan Sewa Kamar Ini</span>
                                    <span>→</span>
                                </a>
                            @else
                                <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900 mb-2">
                                    <div class="font-extrabold mb-1">Lengkapi Profil Penghuni</div>
                                    <p class="text-[11px] text-amber-800 leading-relaxed">
                                        Mohon lengkapi nomor telepon &amp; foto KTP di dashboard sebelum memesan kamar.
                                    </p>
                                </div>
                                <a href="{{ route('tenant.dashboard') }}"
                                   class="w-full py-3 px-4 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-extrabold text-xs sm:text-sm flex items-center justify-center gap-2 transition-all">
                                    <span>Lengkapi Profil Sekarang</span>
                                    <span>→</span>
                                </a>
                            @endif
                        @else
                            <a href="{{ route('tenant.login') }}"
                               class="w-full py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs sm:text-sm flex items-center justify-center gap-2 shadow-md shadow-blue-600/20 transition-all">
                                <span>Masuk untuk Sewa Kamar</span>
                                <span>→</span>
                            </a>
                        @endauth

                        <a href="https://wa.me/{{ $waNumber }}?text={{ $waText }}"
                           target="_blank" rel="noopener"
                           class="w-full py-2.5 px-4 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 font-bold text-xs sm:text-sm flex items-center justify-center gap-2 transition-all">
                            <span>Tanya Pengelola via WhatsApp</span>
                        </a>
                    @else
                        <button type="button" disabled
                                class="w-full py-3 px-4 rounded-xl bg-slate-100 text-slate-400 font-bold text-xs sm:text-sm cursor-not-allowed">
                            Kamar Sedang Terisi
                        </button>
                        <a href="{{ route('rooms.search', ['city' => strtolower($cityName)]) }}"
                           class="w-full py-2.5 px-4 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-600 font-bold text-xs sm:text-sm flex items-center justify-center gap-1.5 transition-all">
                            <span>Cari Kamar Kosong di {{ $cityName }}</span>
                            <span>→</span>
                        </a>
                    @endif
                </div>

                {{-- Metode Pembayaran Resmi (.webp) --}}
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <div class="text-[11px] font-semibold text-slate-400 mb-2">Didukung pembayaran resmi:</div>
                    <div class="flex flex-wrap items-center gap-1.5">
                        @foreach(['qris', 'bca', 'mandiri', 'bni', 'bri', 'gopay'] as $pm)
                            <div class="h-6 px-2 rounded-md border border-slate-200/80 bg-slate-50 flex items-center justify-center">
                                <img src="{{ asset('images/payments/' . $pm . '.webp') }}" alt="{{ strtoupper($pm) }}" class="h-3.5 w-auto object-contain">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </aside>

    </div>

    {{-- 3. REKOMENDASI KAMAR SERUPA --}}
    @if(isset($similarRooms) && $similarRooms->count() > 0)
    <section class="mt-10 pt-8 border-t border-slate-200/80">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-base sm:text-lg font-extrabold text-slate-900">Pilihan Kamar Kos Lainnya</h2>
                <p class="text-xs text-slate-500">Kamar siap huni yang mungkin cocok dengan preferensimu</p>
            </div>
            <a href="{{ route('rooms.search') }}" class="text-xs font-bold text-blue-600 hover:underline">
                Lihat Semua Kamar →
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3.5 sm:gap-4">
            @foreach($similarRooms as $simRoom)
                @php
                    $simImg = (!empty($simRoom->image) && file_exists(public_path('storage/' . $simRoom->image)))
                        ? asset('storage/' . $simRoom->image)
                        : asset('images/default-room.webp');
                    $simCity = $simRoom->property?->city ?? 'Bandung';
                    $simType = ucfirst($simRoom->property?->type ?? 'campur');
                    $simRating = round($simRoom->reviews->avg('rating') ?? 4.9, 1);
                @endphp
                <a href="{{ route('rooms.detail', $simRoom) }}"
                   class="bg-white rounded-xl border border-slate-200/90 hover:border-blue-300 hover:shadow-md transition-all overflow-hidden flex flex-col group">
                    <div class="h-32 sm:h-36 bg-slate-100 shimmer-bg overflow-hidden relative">
                        <img src="{{ $simImg }}"
                             alt="{{ $simRoom->name }}"
                             decoding="sync"
                             onload="this.parentElement.classList.remove('shimmer-bg')"
                             onerror="this.onerror=null;this.src='{{ asset('images/default-room.webp') }}';this.parentElement.classList.remove('shimmer-bg');"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="p-3 flex-1 flex flex-col justify-between gap-2">
                        <div>
                            <div class="flex items-center justify-between text-[10px] sm:text-[11px] text-slate-500 mb-0.5">
                                <span class="font-bold text-blue-600 truncate">{{ $simCity }} • Kos {{ $simType }}</span>
                                <span class="font-bold text-slate-700 shrink-0">★ {{ number_format($simRating, 1) }}</span>
                            </div>
                            <h3 class="text-xs sm:text-sm font-extrabold text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-1">
                                {{ $simRoom->name }}
                            </h3>
                            <p class="text-[11px] text-slate-500 line-clamp-1">
                                {{ $simRoom->property?->name ?? 'RumahKos' }}
                            </p>
                        </div>
                        <div class="pt-2 border-t border-slate-100 flex items-baseline justify-between">
                            <span class="text-xs sm:text-sm font-extrabold text-blue-600">
                                Rp {{ number_format($simRoom->price, 0, ',', '.') }}<span class="text-[10px] font-normal text-slate-400">/bln</span>
                            </span>
                            <span class="text-[11px] font-bold text-emerald-600">Tersedia</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
    @endif

</main>

{{-- ══════════════════════════════════════════════════════════════════
     STICKY BOTTOM BOOKING BAR KHUSUS MOBILE & TABLET (< lg)
══════════════════════════════════════════════════════════════════ --}}
<div class="lg:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 px-4 py-2.5 shadow-[0_-4px_12px_rgba(15,23,42,0.06)]">
    <div class="max-w-xl mx-auto flex items-center justify-between gap-3">
        <div>
            <div class="text-[10px] font-semibold text-slate-400">Harga Sewa</div>
            <div class="text-base font-extrabold text-blue-600 leading-tight">
                Rp {{ number_format($room->price, 0, ',', '.') }}
                <span class="text-[11px] font-normal text-slate-400">/{{ $cycleShort }}</span>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="https://wa.me/{{ $waNumber }}?text={{ $waText }}"
               target="_blank" rel="noopener"
               class="px-3 py-2.5 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 font-bold text-xs">
                Chat WA
            </a>

            @if($room->status === 'available')
                @auth('tenant')
                    <a href="{{ route('tenant.booking.create', $room->id) }}"
                       class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-sm">
                        Sewa Sekarang
                    </a>
                @else
                    <a href="{{ route('tenant.login') }}"
                       class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-sm">
                        Masuk &amp; Sewa
                    </a>
                @endauth
            @else
                <span class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-400 font-bold text-xs">
                    Penuh
                </span>
            @endif
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════
     MODAL LIGHTBOX GALERI FOTO
══════════════════════════════════════════════════════════════════ --}}
<div id="photoLightboxModal" class="hidden fixed inset-0 z-50 bg-slate-950/90 backdrop-blur-xs flex items-center justify-center p-4"
     onclick="if(event.target === this) closeLightbox()">
    <div class="max-w-4xl w-full relative">
        <div class="flex items-center justify-between text-white mb-3">
            <div class="text-xs sm:text-sm font-bold">{{ $room->name }} — {{ $propName }}</div>
            <button type="button" onclick="closeLightbox()" class="text-white/80 hover:text-white text-sm font-bold px-2.5 py-1 rounded-lg bg-white/10">
                Tutup ✕
            </button>
        </div>
        <div class="rounded-2xl overflow-hidden bg-slate-900 max-h-[75vh] flex items-center justify-center">
            <img id="lightboxImg" src="{{ $mainPhoto }}" alt="{{ $room->name }}" class="max-h-[75vh] w-auto object-contain">
        </div>
        <div class="flex items-center justify-center gap-2 mt-3 overflow-x-auto no-scrollbar">
            @foreach($galleryPhotos as $idx => $gUrl)
                <button type="button" onclick="openLightbox({{ $idx }})"
                        class="w-16 h-11 rounded-lg overflow-hidden border border-white/25 hover:border-white shrink-0">
                    <img src="{{ $gUrl }}" alt="" class="w-full h-full object-cover">
                </button>
            @endforeach
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════
     MODALS ULASAN (CREATE, EDIT, ADMIN REPLY)
══════════════════════════════════════════════════════════════════ --}}
<div id="reviewModal" class="hidden fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-50 flex items-center justify-center p-4"
     onclick="if(event.target === this) closeReviewModal()">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden" onclick="event.stopPropagation()">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Tulis Ulasan Kamar</h3>
                <p class="text-xs text-slate-500">Bagikan pengalamanmu menghuni {{ $room->name }}</p>
            </div>
            <button type="button" onclick="closeReviewModal()" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
        </div>

        <form action="{{ route('room.review.store', $room->id) }}" method="POST" class="p-4 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2 text-center">Pilih Rating Bintang</label>
                <input type="hidden" name="rating" id="ratingValue" required>
                <div class="flex gap-1.5 justify-center" id="starContainer">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" class="star-btn text-3xl text-slate-300 hover:text-amber-400 transition" data-rating="{{ $i }}">★</button>
                    @endfor
                </div>
                <p class="text-center text-xs font-semibold text-slate-500 mt-1" id="ratingText">Klik bintang untuk menilai</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Ceritakan Pengalamanmu</label>
                <textarea name="comment" id="commentInput" rows="4"
                          class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none text-xs sm:text-sm"
                          placeholder="Bagaimana kebersihan kamar, kecepatan WiFi, dan kenyamanan lingkungannya?"
                          required minlength="10" maxlength="1000"></textarea>
                <div class="flex justify-between mt-1 text-[11px] text-slate-400">
                    <span>Minimal 10 karakter</span>
                    <span id="charCount">0/1000</span>
                </div>
            </div>

            <div class="flex gap-2">
                <button type="button" onclick="closeReviewModal()"
                        class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-700 rounded-xl font-bold hover:bg-slate-50 text-xs">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 bg-blue-600 text-white py-2.5 px-4 rounded-xl font-bold hover:bg-blue-700 text-xs">
                    Publikasikan
                </button>
            </div>
        </form>
    </div>
</div>

<div id="editReviewModal" class="hidden fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-50 flex items-center justify-center p-4"
     onclick="if(event.target === this) closeEditModal()">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden" onclick="event.stopPropagation()">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-base font-extrabold text-slate-900">Perbarui Ulasan</h3>
            <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
        </div>

        <form id="editReviewForm" method="POST" class="p-4 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <input type="hidden" name="rating" id="editRatingValue" required>
                <div class="flex gap-1.5 justify-center" id="editStarContainer">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" class="edit-star-btn text-3xl text-slate-300 hover:text-amber-400 transition" data-rating="{{ $i }}">★</button>
                    @endfor
                </div>
                <p class="text-center text-xs font-semibold text-slate-500 mt-1" id="editRatingText">Pilih rating</p>
            </div>

            <div>
                <textarea name="comment" id="editCommentInput" rows="4"
                          class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none text-xs sm:text-sm"
                          required minlength="10" maxlength="1000"></textarea>
                <div class="flex justify-between mt-1 text-[11px] text-slate-400">
                    <span>Minimal 10 karakter</span>
                    <span id="editCharCount">0/1000</span>
                </div>
            </div>

            <div class="flex gap-2">
                <button type="button" onclick="closeEditModal()"
                        class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-700 rounded-xl font-bold hover:bg-slate-50 text-xs">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 bg-blue-600 text-white py-2.5 px-4 rounded-xl font-bold hover:bg-blue-700 text-xs">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<div id="replyModal" class="hidden fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-50 flex items-center justify-center p-4"
     onclick="if(event.target === this) closeReplyModal()">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden" onclick="event.stopPropagation()">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-base font-extrabold text-slate-900">Balas Ulasan Penghuni</h3>
            <button type="button" onclick="closeReplyModal()" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
        </div>

        <form id="replyForm" method="POST" class="p-4 space-y-4">
            @csrf
            <div>
                <textarea name="reply" id="replyInput" rows="4"
                          class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none text-xs sm:text-sm"
                          placeholder="Tulis tanggapan pengelola kos..."
                          required minlength="10" maxlength="500"></textarea>
                <div class="flex justify-between mt-1 text-[11px] text-slate-400">
                    <span>Minimal 10 karakter</span>
                    <span id="replyCharCount">0/500</span>
                </div>
            </div>

            <div class="flex gap-2">
                <button type="button" onclick="closeReplyModal()"
                        class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-700 rounded-xl font-bold hover:bg-slate-50 text-xs">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 bg-blue-600 text-white py-2.5 px-4 rounded-xl font-bold hover:bg-blue-700 text-xs">
                    Kirim Balasan
                </button>
            </div>
        </form>
    </div>
</div>

@include('landing.footer')

<script>
    const galleryPhotos = @json($galleryPhotos);

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('img').forEach(img => {
            if (img.complete && img.parentElement) {
                img.parentElement.classList.remove('shimmer-bg', 'shimmer-dark');
            }
        });
    });

    function switchMainPhoto(url, idx) {
        const mainImg = document.getElementById('mainHeroPhoto');
        if (mainImg) mainImg.src = url;
        document.querySelectorAll('.gallery-thumb').forEach(btn => {
            btn.classList.toggle('thumb-active', parseInt(btn.dataset.thumbIdx) === idx);
        });
    }

    function openLightbox(idx = 0) {
        const modal = document.getElementById('photoLightboxModal');
        const img = document.getElementById('lightboxImg');
        if (img && galleryPhotos[idx]) img.src = galleryPhotos[idx];
        modal?.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        document.getElementById('photoLightboxModal')?.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function shareRoomUrl() {
        const url = window.location.href;
        const label = document.getElementById('shareBtnLabel');
        if (navigator.share) {
            navigator.share({ title: document.title, url }).catch(() => {});
        } else if (navigator.clipboard) {
            navigator.clipboard.writeText(url).then(() => {
                if (label) {
                    label.textContent = 'Link Disalin!';
                    setTimeout(() => { label.textContent = 'Bagikan Link'; }, 2000);
                }
            });
        }
    }

    function toggleDropdown(reviewId) {
        const dropdown = document.getElementById('dropdown-' + reviewId);
        document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
            if (d.id !== 'dropdown-' + reviewId) d.classList.add('hidden');
        });
        dropdown?.classList.toggle('hidden');
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('button')) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(d => d.classList.add('hidden'));
        }
    });

    function openReviewModal() {
        document.getElementById('reviewModal')?.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setupStars();
    }

    function closeReviewModal() {
        document.getElementById('reviewModal')?.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function setupStars() {
        document.querySelectorAll('.star-btn').forEach(star => {
            star.onclick = function() {
                const rating = parseInt(this.dataset.rating);
                document.getElementById('ratingValue').value = rating;
                document.querySelectorAll('.star-btn').forEach((s, i) => {
                    s.classList.toggle('text-amber-400', i < rating);
                    s.classList.toggle('text-slate-300', i >= rating);
                });
                const texts = ['', 'Kurang Memuaskan', 'Cukup', 'Nyaman', 'Sangat Nyaman', 'Luar Biasa!'];
                document.getElementById('ratingText').textContent = texts[rating] || '';
            };
        });
    }

    document.getElementById('commentInput')?.addEventListener('input', function() {
        document.getElementById('charCount').textContent = this.value.length + '/1000';
    });

    function openEditModal(reviewId, comment, rating) {
        document.getElementById('editReviewModal')?.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        document.getElementById('editReviewForm').action = '/review/' + reviewId;
        document.getElementById('editCommentInput').value = comment;
        document.getElementById('editRatingValue').value = rating;
        document.getElementById('editCharCount').textContent = comment.length + '/1000';
        document.querySelectorAll('.edit-star-btn').forEach((s, i) => {
            s.classList.toggle('text-amber-400', i < rating);
            s.classList.toggle('text-slate-300', i >= rating);
            s.onclick = function() {
                const r = parseInt(this.dataset.rating);
                document.getElementById('editRatingValue').value = r;
                document.querySelectorAll('.edit-star-btn').forEach((btn, idx) => {
                    btn.classList.toggle('text-amber-400', idx < r);
                    btn.classList.toggle('text-slate-300', idx >= r);
                });
            };
        });
    }

    function closeEditModal() {
        document.getElementById('editReviewModal')?.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    document.getElementById('editCommentInput')?.addEventListener('input', function() {
        document.getElementById('editCharCount').textContent = this.value.length + '/1000';
    });

    function openReplyModal(reviewId, existingReply = '') {
        document.getElementById('replyModal')?.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        document.getElementById('replyForm').action = '/admin/review/' + reviewId + '/reply';
        document.getElementById('replyInput').value = existingReply;
        document.getElementById('replyCharCount').textContent = existingReply.length + '/500';
    }

    function closeReplyModal() {
        document.getElementById('replyModal')?.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    document.getElementById('replyInput')?.addEventListener('input', function() {
        document.getElementById('replyCharCount').textContent = this.value.length + '/500';
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLightbox();
            closeReviewModal();
            closeEditModal();
            closeReplyModal();
        }
    });

    setTimeout(() => {
        document.querySelectorAll('.rk-toast').forEach(el => el.remove());
    }, 5000);
</script>

</body>
</html>
