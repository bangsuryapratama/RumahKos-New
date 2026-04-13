<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RumahKos - KosNyaman</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --blue: #2563eb;
            --blue-dark: #1d4ed8;
            --blue-light: #eff6ff;
            --text: #111827;
            --muted: #6b7280;
            --border: #e5e7eb;
        }

        * { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text); overflow-x: hidden; }
        html { scroll-behavior: smooth; }

        /* ── Gradient text ── */
        .gradient-text {
            background: linear-gradient(135deg, #1d4ed8 0%, #06b6d4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ── Room card ── */
        .room-card {
            transition: transform .3s cubic-bezier(.22,.61,.36,1), box-shadow .3s ease;
            will-change: transform;
        }
        .room-card:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(37,99,235,.12); }
        .room-card:hover .card-img { transform: scale(1.07); }
        .card-img { transition: transform .5s ease; }

        /* ── Filter active ── */
        .filter-btn.active { background: var(--blue); color: #fff; border-color: var(--blue); }
        .hidden-filter { display: none !important; }

        /* ── Horizontal scroll (mobile) ── */
        .h-scroll {
            display: flex; overflow-x: auto; gap: .75rem;
            scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch;
            scrollbar-width: none; -ms-overflow-style: none; padding-bottom: .5rem;
        }
        .h-scroll::-webkit-scrollbar { display: none; }
        .h-scroll > * { scroll-snap-align: start; flex: 0 0 auto; }

        /* ── Back to top ── */
        #backToTop {
            position: fixed; bottom: 90px; right: 20px; z-index: 50;
            opacity: 0; transform: translateY(8px);
            transition: opacity .25s ease, transform .25s ease;
            pointer-events: none;
        }
        #backToTop.show { opacity: 1; transform: translateY(0); pointer-events: auto; }

        /* ── Floating dev button ── */
        #floatingDev {
            position: fixed; bottom: 24px; right: 20px; z-index: 100;
            animation: floatIn .5s .3s cubic-bezier(.22,.61,.36,1) both;
        }
        @keyframes floatIn {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Pulse badge ── */
        @keyframes pulse-soft { 0%,100% { opacity:1; } 50% { opacity:.65; } }
        .pulse { animation: pulse-soft 2.5s ease-in-out infinite; }

        /* ── Fade up ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fu   { animation: fadeUp .55s ease both; }
        .fu-1 { animation: fadeUp .55s .1s ease both; }
        .fu-2 { animation: fadeUp .55s .2s ease both; }
        .fu-3 { animation: fadeUp .55s .35s ease both; }

        /* ── Modal ── */
        #devModal {
            display: none; position: fixed; inset: 0; z-index: 9999;
            background: rgba(0,0,0,.45); backdrop-filter: blur(4px);
            align-items: center; justify-content: center;
        }
        #devModal.open { display: flex; }
        #devModal .modal-box {
            animation: fadeUp .3s ease both;
        }

        /* ── Stat card shimmer ── */
        .stat-card {
            position: relative; overflow: hidden;
        }
        .stat-card::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(120deg, transparent 30%, rgba(255,255,255,.08) 50%, transparent 70%);
            transform: translateX(-100%);
            transition: transform .6s ease;
        }
        .stat-card:hover::after { transform: translateX(100%); }

        /* ── Section fade-in on scroll ── */
        .reveal { opacity: 0; transform: translateY(28px); transition: opacity .6s ease, transform .6s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }

        /* ── Responsive image object ── */
        img { max-width: 100%; }

        /* ── Lucide icon sizing in buttons ── */
        [data-lucide] { display: inline-block; }
        #floatingDev [data-lucide] { width: 14px; height: 14px; }
        .dev-close-btn [data-lucide] { width: 14px; height: 14px; }
        .dev-modal-icon-ring [data-lucide] { width: 28px; height: 28px; color: #2563eb; }
        .dev-status-pill [data-lucide] { width: 11px; height: 11px; }
    </style>
</head>
<body class="bg-white">

{{-- Navbar --}}
@include('landing.navbar')

{{-- ════════════════════════════════════
     HERO
════════════════════════════════════ --}}
<section class="relative bg-gradient-to-br from-blue-50 via-white to-cyan-50 pt-24 sm:pt-28 pb-14 sm:pb-20 overflow-hidden">
    {{-- Decorative blobs --}}
    <div class="absolute -top-16 -right-16 w-72 sm:w-96 h-72 sm:h-96 bg-blue-100 rounded-full opacity-40 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 -left-10 w-52 sm:w-72 h-52 sm:h-72 bg-cyan-100 rounded-full opacity-30 blur-3xl pointer-events-none"></div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">

        <span class="inline-flex items-center gap-2 bg-blue-100 text-blue-700 text-xs sm:text-sm font-semibold px-4 py-2 rounded-full mb-5 pulse fu">
            <i class="fas fa-map-marker-alt text-blue-500 text-xs"></i>
            Lokasi di {{ $propertyLocation }}
        </span>

        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black leading-[1.12] tracking-tight mb-5 fu-1">
            Kos Nyaman untuk<br>
            <span class="gradient-text">Mahasiswa & Pekerja</span>
        </h1>

        <p class="text-gray-500 text-sm sm:text-base lg:text-lg max-w-xl mx-auto mb-8 leading-relaxed fu-2">
            Fasilitas lengkap, WiFi cepat, kamar mandi dalam & keamanan 24 jam.
            Mulai dari <strong class="text-blue-600 font-bold">Rp {{ number_format($minPrice / 1000000, 1) }}jt</strong>/bulan.
        </p>

        <div class="flex flex-col xs:flex-row gap-3 justify-center mb-10 fu-3">
            <a href="#kamar"
               class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-bold px-6 py-3.5 rounded-2xl shadow-lg hover:shadow-blue-200 transition-all text-sm sm:text-base">
                <i class="fas fa-bed"></i> Lihat Kamar Tersedia
            </a>
            <a href="#kontak"
               class="inline-flex items-center justify-center gap-2 border-2 border-blue-600 text-blue-600 hover:bg-blue-50 active:scale-95 font-bold px-6 py-3.5 rounded-2xl transition-all text-sm sm:text-base">
                <i class="fab fa-whatsapp text-green-500"></i> Hubungi Kami
            </a>
        </div>

        {{-- Hero image --}}
        <div class="rounded-3xl overflow-hidden shadow-2xl fu-3 ring-1 ring-black/5">
            <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=1200&h=560&fit=crop"
                 alt="Interior Kosan"
                 class="w-full h-48 sm:h-72 lg:h-96 object-cover">
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-3 gap-3 sm:gap-4 mt-8 max-w-lg mx-auto">
            @php $stats_items = [
                ['val' => $availableRooms, 'label' => 'Kamar Tersedia', 'icon' => 'fa-bed'],
                ['val' => $minPrice > 0 ? number_format($minPrice/1000000,1).'jt' : 'N/A', 'label' => 'Mulai /bulan', 'icon' => 'fa-tag'],
                ['val' => '24/7', 'label' => 'Keamanan', 'icon' => 'fa-shield-halved'],
            ]; @endphp
            @foreach($stats_items as $s)
            <div class="stat-card bg-white border border-gray-100 rounded-2xl p-3 sm:p-5 shadow-sm text-center hover:border-blue-200 transition-colors">
                <i class="fas {{ $s['icon'] }} text-blue-500 text-sm mb-1 block"></i>
                <div class="text-xl sm:text-3xl font-black text-blue-600">{{ $s['val'] }}</div>
                <div class="text-gray-400 text-[10px] sm:text-xs mt-1 font-medium">{{ $s['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ════════════════════════════════════
     FASILITAS
════════════════════════════════════ --}}
<section id="fasilitas" class="py-14 sm:py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-10 reveal">
            <span class="text-xs font-bold text-blue-500 uppercase tracking-widest">Fasilitas</span>
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mt-1 mb-2">Semua Sudah Tersedia</h2>
            <p class="text-gray-400 text-sm sm:text-base">Tinggal bawa diri, semua sudah siap</p>
        </div>

        @php
            $facList = $FacilityAll->count() > 0
                ? $FacilityAll->take(8)->map(fn($f) => ['icon'=>$f->icon,'name'=>$f->name])->toArray()
                : [
                    ['icon'=>'fas fa-bed',            'name'=>'Kasur & Lemari'],
                    ['icon'=>'fas fa-wifi',            'name'=>'WiFi 100Mbps'],
                    ['icon'=>'fas fa-shower',          'name'=>'K. Mandi Dalam'],
                    ['icon'=>'fas fa-square-parking',  'name'=>'Parkir Motor'],
                    ['icon'=>'fas fa-shield-halved',   'name'=>'Keamanan 24/7'],
                    ['icon'=>'fas fa-kitchen-set',     'name'=>'Dapur Bersama'],
                    ['icon'=>'fas fa-jug-detergent',   'name'=>'Laundry'],
                    ['icon'=>'fas fa-snowflake',       'name'=>'AC (Opsional)'],
                  ];
        @endphp

        {{-- Mobile horizontal scroll --}}
        <div class="sm:hidden h-scroll -mx-4 px-4">
            @foreach($facList as $f)
            <div class="flex flex-col items-center justify-center text-center bg-gradient-to-b from-blue-50 to-white border border-blue-100 rounded-2xl w-28 p-4 gap-2 shadow-sm">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm">
                    <i class="{{ $f['icon'] }} text-blue-600 text-sm"></i>
                </div>
                <span class="text-xs font-semibold text-gray-700 leading-tight">{{ $f['name'] }}</span>
            </div>
            @endforeach
        </div>

        {{-- Desktop grid --}}
        <div class="hidden sm:grid grid-cols-4 gap-4 reveal">
            @foreach($facList as $f)
            <div class="group flex flex-col items-center text-center bg-gradient-to-b from-blue-50 to-white border border-blue-100 rounded-2xl p-5 sm:p-6 gap-3 hover:shadow-lg hover:border-blue-300 hover:-translate-y-1 transition-all duration-300">
                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm group-hover:shadow-md group-hover:scale-110 transition-all duration-300">
                    <i class="{{ $f['icon'] }} text-blue-600 text-lg"></i>
                </div>
                <span class="text-sm font-semibold text-gray-700">{{ $f['name'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ════════════════════════════════════
     KAMAR
════════════════════════════════════ --}}
<section id="kamar" class="py-14 sm:py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-8 reveal">
            <span class="text-xs font-bold text-blue-500 uppercase tracking-widest">Kamar</span>
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mt-1 mb-2">Pilih Kamarmu</h2>
            <p class="text-gray-400 text-sm sm:text-base">Temukan yang paling cocok untukmu</p>
        </div>

        {{-- Search & Filter --}}
        <div class="max-w-2xl mx-auto mb-6 space-y-3 reveal">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                <input id="searchInput" type="text" placeholder="Cari nama kamar..."
                       oninput="searchRooms()"
                       class="w-full pl-11 pr-4 py-3 sm:py-3.5 border border-gray-200 bg-white rounded-2xl text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>
            <div class="flex flex-wrap gap-2 justify-center">
                <button id="btnAvailable" onclick="filterAvailable()"
                        class="filter-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold border border-gray-200 bg-white text-gray-600 hover:border-blue-400 hover:bg-blue-50 transition-all active:scale-95">
                    <i class="fas fa-check-circle mr-1.5 text-green-500"></i>Hanya Tersedia
                </button>
                <button onclick="sortRooms('name-asc')"
                        class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold border border-gray-200 bg-white text-gray-600 hover:border-blue-400 hover:bg-blue-50 transition-all active:scale-95">
                    <i class="fas fa-arrow-down-a-z mr-1.5 text-blue-400"></i>A–Z
                </button>
                <button onclick="sortRooms('price-asc')"
                        class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold border border-gray-200 bg-white text-gray-600 hover:border-blue-400 hover:bg-blue-50 transition-all active:scale-95">
                    <i class="fas fa-tag mr-1.5 text-blue-400"></i>Harga Terendah
                </button>
            </div>
        </div>

        {{-- Count --}}
        <p class="text-center text-xs sm:text-sm text-gray-400 mb-6">
            Menampilkan <strong id="displayCount" class="text-gray-700">0</strong> dari
            <strong id="totalCount" class="text-gray-700">0</strong> kamar
        </p>

        {{-- Grid --}}
        <div id="roomsContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
            @forelse($rooms as $room)
                @php
                    $avg   = $room->reviews->avg('rating') ?? 0;
                    $rcnt  = $room->reviews->count();
                    $cycle = match($room->billing_cycle ?? '') {
                        'daily'   => 'hari',
                        'weekly'  => 'minggu',
                        'monthly' => 'bulan',
                        default   => 'tahun'
                    };
                @endphp

                <a href="{{ route('rooms.detail', $room->id) }}"
                   class="room-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden block group"
                   data-status="{{ $room->status }}"
                   data-name="{{ strtolower($room->name) }}"
                   data-price="{{ $room->price }}">

                    {{-- Image --}}
                    <div class="relative h-44 sm:h-48 overflow-hidden bg-gray-100">
                        @if($room->image)
                            <img src="{{ asset('storage/'.$room->image) }}" alt="{{ $room->name }}"
                                 class="card-img w-full h-full object-cover">
                        @else
                            <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=600&h=400&fit=crop"
                                 alt="{{ $room->name }}" class="card-img w-full h-full object-cover">
                        @endif

                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent pointer-events-none"></div>

                        {{-- Status badge --}}
                        <div class="absolute top-3 right-3">
                            @if($room->status == 'available')
                                <span class="inline-flex items-center gap-1.5 bg-green-500 text-white text-[11px] font-bold px-2.5 py-1 rounded-full shadow">
                                    <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>Tersedia
                                </span>
                            @elseif($room->status == 'occupied')
                                <span class="inline-flex items-center gap-1.5 bg-red-500 text-white text-[11px] font-bold px-2.5 py-1 rounded-full shadow">
                                    <span class="w-1.5 h-1.5 bg-white rounded-full"></span>Terisi
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-orange-500 text-white text-[11px] font-bold px-2.5 py-1 rounded-full shadow">
                                    <i class="fas fa-tools text-[9px]"></i>Perbaikan
                                </span>
                            @endif
                        </div>

                        {{-- Property name --}}
                        <div class="absolute bottom-3 left-3">
                            <span class="text-[11px] text-white bg-black/55 px-2.5 py-1 rounded-full backdrop-blur-sm font-medium">
                                <i class="fas fa-building mr-1 opacity-70 text-[9px]"></i>{{ $room->property->name }}
                            </span>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="p-4 space-y-2.5">

                        {{-- Name + rating --}}
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="font-bold text-gray-900 text-sm sm:text-base leading-snug line-clamp-1 flex-1">{{ $room->name }}</h3>
                            @if($rcnt > 0)
                            <div class="flex items-center gap-1 bg-yellow-50 border border-yellow-100 px-2 py-0.5 rounded-lg shrink-0">
                                <i class="fas fa-star text-yellow-400 text-[10px]"></i>
                                <span class="text-xs font-bold text-gray-800">{{ number_format($avg,1) }}</span>
                                <span class="text-[10px] text-gray-400">({{ $rcnt }})</span>
                            </div>
                            @endif
                        </div>

                        {{-- Meta --}}
                        <div class="flex flex-wrap gap-x-3 gap-y-1 text-[11px] text-gray-400">
                            @if($room->size)
                                <span><i class="fas fa-ruler-combined mr-1"></i>{{ $room->size }}</span>
                            @endif
                            <span><i class="fas fa-layer-group mr-1"></i>Lantai {{ $room->floor }}</span>
                        </div>

                        {{-- Facility tags --}}
                        <div class="flex flex-wrap gap-1.5">
                            @if($room->facilities->count() > 0)
                                @foreach($room->facilities->take(3) as $fac)
                                <span class="text-[11px] bg-blue-50 text-blue-600 border border-blue-100 px-2 py-0.5 rounded-lg font-semibold">
                                    <i class="{{ $fac->icon }} mr-1 text-[9px]"></i>{{ $fac->name }}
                                </span>
                                @endforeach
                                @if($room->facilities->count() > 3)
                                <span class="text-[11px] bg-gray-100 text-gray-500 px-2 py-0.5 rounded-lg font-semibold">
                                    +{{ $room->facilities->count() - 3 }}
                                </span>
                                @endif
                            @else
                                <span class="text-[11px] bg-blue-50 text-blue-600 border border-blue-100 px-2 py-0.5 rounded-lg font-semibold"><i class="fas fa-bed mr-1 text-[9px]"></i>Kasur</span>
                                <span class="text-[11px] bg-blue-50 text-blue-600 border border-blue-100 px-2 py-0.5 rounded-lg font-semibold"><i class="fas fa-bath mr-1 text-[9px]"></i>K. Mandi</span>
                            @endif
                        </div>

                        {{-- Price bar --}}
                        <div class="flex items-center justify-between bg-gradient-to-r from-blue-600 to-blue-500 rounded-xl px-4 py-3 mt-1">
                            <div>
                                <div class="text-white font-black text-sm sm:text-base leading-none">
                                    Rp {{ number_format($room->price, 0, ',', '.') }}
                                </div>
                                <div class="text-blue-200 text-[11px] mt-0.5">per {{ $cycle }}</div>
                            </div>
                            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center group-hover:bg-white/30 group-hover:translate-x-1 transition-all">
                                <i class="fas fa-arrow-right text-white text-sm"></i>
                            </div>
                        </div>

                        @if($room->status != 'available')
                        <p class="text-center text-[11px] text-gray-400 italic">
                            <i class="fas fa-info-circle mr-1"></i>Klik untuk info lebih lanjut
                        </p>
                        @endif
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-16">
                    <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-bed text-2xl text-blue-300"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-1">Belum Ada Kamar</h3>
                    <p class="text-gray-500 text-sm mb-5">Silakan hubungi kami untuk info lebih lanjut.</p>
                    <a href="https://wa.me/6283841806357"
                       class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-xl hover:bg-blue-700 transition text-sm font-bold">
                        <i class="fab fa-whatsapp"></i> Hubungi Kami
                    </a>
                </div>
            @endforelse
        </div>

        {{-- No results --}}
        <div id="noResults" class="hidden text-center py-14">
            <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-search text-xl text-gray-300"></i>
            </div>
            <h3 class="font-bold text-gray-700 mb-1">Tidak ada hasil</h3>
            <p class="text-sm text-gray-400">Coba kata kunci lain</p>
        </div>
    </div>
</section>

{{-- ════════════════════════════════════
     LOKASI
════════════════════════════════════ --}}
<section id="lokasi" class="py-14 sm:py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-10 reveal">
            <span class="text-xs font-bold text-blue-500 uppercase tracking-widest">Lokasi</span>
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mt-1 mb-2">Lokasi Strategis</h2>
            <p class="text-gray-400 text-sm sm:text-base max-w-lg mx-auto">Dekat kampus, minimarket, transportasi, dan area kuliner.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 items-stretch reveal">

            {{-- Location list --}}
            <div class="space-y-3">
                @php $locs = [
                    ['icon'=>'fas fa-graduation-cap','bg'=>'bg-blue-50','ic'=>'text-blue-600','title'=>'Kampus ITB','sub'=>'5 menit berkendara'],
                    ['icon'=>'fas fa-shop','bg'=>'bg-green-50','ic'=>'text-green-600','title'=>'Minimarket','sub'=>'2 menit jalan kaki'],
                    ['icon'=>'fas fa-bus','bg'=>'bg-purple-50','ic'=>'text-purple-600','title'=>'Transportasi Umum','sub'=>'Mudah diakses'],
                    ['icon'=>'fas fa-utensils','bg'=>'bg-orange-50','ic'=>'text-orange-600','title'=>'Area Kuliner','sub'=>'Banyak tempat makan'],
                ]; @endphp
                @foreach($locs as $loc)
                <div class="flex items-center gap-4 p-4 sm:p-5 rounded-2xl bg-gray-50 border border-gray-100 hover:bg-white hover:shadow-md hover:border-gray-200 transition-all group">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 {{ $loc['bg'] }} rounded-2xl flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                        <i class="{{ $loc['icon'] }} {{ $loc['ic'] }} text-lg sm:text-xl"></i>
                    </div>
                    <div>
                        <div class="font-bold text-gray-900 text-sm sm:text-base">{{ $loc['title'] }}</div>
                        <div class="text-gray-400 text-xs sm:text-sm mt-0.5">{{ $loc['sub'] }}</div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-300 ml-auto text-xs group-hover:text-blue-400 transition-colors"></i>
                </div>
                @endforeach
            </div>

            {{-- Map --}}
            <div class="rounded-2xl overflow-hidden shadow-lg border border-gray-100 h-60 sm:h-80 lg:h-full min-h-[280px]">
                {!! $mapsEmbed->maps_embed !!}
            </div>
        </div>
    </div>
</section>

{{-- ════════════════════════════════════
     CTA
════════════════════════════════════ --}}
<section id="kontak" class="py-14 sm:py-20 relative overflow-hidden bg-blue-600">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 20% 50%, white 1px, transparent 1px), radial-gradient(circle at 80% 20%, white 1px, transparent 1px); background-size: 40px 40px;"></div>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center relative">
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white mb-3">
            Tertarik untuk Ngekos?
        </h2>
        <p class="text-blue-100 text-sm sm:text-base lg:text-lg mb-8 max-w-lg mx-auto">
            Hubungi kami sekarang atau jadwalkan kunjungan langsung ke lokasi.
        </p>
        <div class="flex flex-col xs:flex-row gap-3 justify-center">
             <a href="https://wa.me/{{ $contact && $contact->whatsapp ? preg_replace('/[^0-9]/', '', $contact->whatsapp) : '6283841806357' }}?text=Halo,%20saya%20ingin%20info%20lengkap%20tentang%20kos"
               class="inline-flex items-center justify-center gap-2 bg-white text-blue-700 font-bold px-7 py-3.5 rounded-2xl hover:bg-blue-50 active:scale-95 transition-all shadow-md text-sm sm:text-base">
                <i class="fab fa-whatsapp text-green-500 text-lg"></i> Chat WhatsApp
            </a>
            <a href="tel:+6283841806357"
               class="inline-flex items-center justify-center gap-2 border-2 border-white/50 text-white font-bold px-7 py-3.5 rounded-2xl hover:bg-white/10 active:scale-95 transition-all text-sm sm:text-base">
                <i class="fas fa-phone"></i> Telepon
            </a>
        </div>
    </div>
</section>

{{-- Footer --}}
@include('landing.footer')

@include('landing.floating-message')

{{-- ════════════════════════════════════
     BACK TO TOP
════════════════════════════════════ --}}
<button id="backToTop" onclick="window.scrollTo({top:0,behavior:'smooth'})"
        class="w-11 h-11 rounded-full bg-white border border-gray-200 hover:bg-blue-600 hover:text-white hover:border-blue-600 text-gray-600 shadow-lg flex items-center justify-center transition-all active:scale-95">
    <i class="fas fa-arrow-up text-sm"></i>
</button>

{{-- ════════════════════════════════════
     SCRIPTS
════════════════════════════════════ --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();

    let isAvailableFilter = false;

    document.addEventListener('DOMContentLoaded', () => {
        updateCount();
        initReveal();
    });

    window.addEventListener('scroll', () => {
        document.getElementById('backToTop').classList.toggle('show', window.scrollY > 350);
    }, { passive: true });

    function searchRooms() {
        const term = document.getElementById('searchInput').value.toLowerCase().trim();
        let visible = 0;
        document.querySelectorAll('.room-card').forEach(card => {
            const match = card.dataset.name.includes(term) &&
                          (!isAvailableFilter || card.dataset.status === 'available');
            card.classList.toggle('hidden-filter', !match);
            if (match) visible++;
        });
        updateCount();
        document.getElementById('noResults').classList.toggle('hidden', visible > 0);
        document.getElementById('roomsContainer').classList.toggle('hidden', visible === 0);
    }

    function filterAvailable() {
        isAvailableFilter = !isAvailableFilter;
        document.getElementById('btnAvailable').classList.toggle('active', isAvailableFilter);
        searchRooms();
    }

    function sortRooms(by) {
        const c = document.getElementById('roomsContainer');
        [...document.querySelectorAll('.room-card')]
            .sort((a, b) => by === 'name-asc'
                ? a.dataset.name.localeCompare(b.dataset.name)
                : +a.dataset.price - +b.dataset.price)
            .forEach(el => c.appendChild(el));
    }

    function updateCount() {
        document.getElementById('displayCount').textContent =
            document.querySelectorAll('.room-card:not(.hidden-filter)').length;
        document.getElementById('totalCount').textContent =
            document.querySelectorAll('.room-card').length;
    }

    function initReveal() {
        const obs = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); }
            });
        }, { threshold: 0.12 });
        document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
    }
</script>

</body>
</html>