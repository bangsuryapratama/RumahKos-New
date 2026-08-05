<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    @php
        $schemaJson = \App\Services\SeoService::getLodgingBusinessSchema($globalProperty, $rooms, 4.9, 120);
    @endphp

    <!-- SEO & Metadata Component -->
    @include('components.seo-head', [
        'title' => 'Sewa Kost Eksklusif & Hotel-Grade Living Bandung',
        'description' => 'Cemara Living & Residence: Hunian kost mewah dan berfasilitas lengkap berstandar hotel bintang 5 di Setiabudi Bandung. AC, WiFi 100Mbps, Water Heater, Smart TV & 24/7 Security.',
        'schemaJson' => $schemaJson
    ])

    <!-- Meta Pixel Tracking -->
    @include('components.meta-pixel')

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Stylesheets via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #0F172A;
            background-color: #FAFAFC;
        }
        .font-heading {
            font-family: 'Outfit', sans-serif;
        }
        .gold-gradient-text {
            background: linear-gradient(135deg, #D97706 0%, #F59E0B 50%, #B45309 100%);
            -webkit-background-clip: text;
            -webkit-fill-color: transparent;
            background-clip: text;
        }
        .luxury-gradient-dark {
            background: linear-gradient(135deg, #0B132B 0%, #1C2541 60%, #0F172A 100%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }
        .room-card:hover .room-img {
            transform: scale(1.06);
        }
    </style>
</head>
<body class="antialiased selection:bg-amber-500 selection:text-slate-950">

    <!-- Header Navigation -->
    @include('landing.navbar')

    <!-- HERO SECTION (Luxury Hotel Atmosphere) -->
    <section class="relative min-h-[640px] lg:min-h-[720px] luxury-gradient-dark text-white flex items-center justify-center overflow-hidden pt-12 pb-24 px-4 sm:px-6 lg:px-8">
        
        <!-- Decorative Ambient Light Gradients -->
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -right-32 w-96 h-96 bg-indigo-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-6xl mx-auto text-center relative z-10 space-y-8">
            
            <!-- Top Badge -->
            <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-slate-800/80 border border-amber-400/30 text-amber-400 text-xs sm:text-sm font-bold tracking-wide uppercase shadow-lg animate-pulse">
                <i class="fas fa-crown text-xs"></i>
                <span>The Premier Living Experience in Bandung</span>
            </div>

            <!-- Headline -->
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold font-heading tracking-tight leading-[1.15] max-w-4xl mx-auto">
                Kenyamanan Hunian Eksklusif Berstandar 
                <span class="gold-gradient-text">Hotel Bintang 5</span>
            </h1>

            <!-- Subtitle -->
            <p class="text-base sm:text-lg lg:text-xl text-slate-300 max-w-2xl mx-auto font-normal leading-relaxed">
                Nikmati privasi maksimal, kamar fully-furnished dengan AC & WiFi 100 Mbps, keamanan 24 jam, dan lokasi super strategis di kawasan prestisius Hegarmanah Setiabudi.
            </p>

            <!-- Trust Stats Row -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-3xl mx-auto pt-2 pb-4">
                <div class="p-3 rounded-2xl bg-white/5 backdrop-blur-md border border-white/10">
                    <div class="text-2xl sm:text-3xl font-black text-amber-400 font-heading">{{ $totalRooms ?? 12 }}</div>
                    <div class="text-xs text-slate-300 font-medium mt-0.5">Kamar & Suite Mewah</div>
                </div>
                <div class="p-3 rounded-2xl bg-white/5 backdrop-blur-md border border-white/10">
                    <div class="text-2xl sm:text-3xl font-black text-amber-400 font-heading">4.9 / 5.0</div>
                    <div class="text-xs text-slate-300 font-medium mt-0.5">⭐ 120+ Ulasan Tamu</div>
                </div>
                <div class="p-3 rounded-2xl bg-white/5 backdrop-blur-md border border-white/10">
                    <div class="text-2xl sm:text-3xl font-black text-amber-400 font-heading">100%</div>
                    <div class="text-xs text-slate-300 font-medium mt-0.5">Foto Asli & Terverifikasi</div>
                </div>
                <div class="p-3 rounded-2xl bg-white/5 backdrop-blur-md border border-white/10">
                    <div class="text-2xl sm:text-3xl font-black text-emerald-400 font-heading">Midtrans</div>
                    <div class="text-xs text-slate-300 font-medium mt-0.5">Bayar Instan QRIS & VA</div>
                </div>
            </div>

            <!-- INTERACTIVE QUICK SEARCH & BOOKING BAR -->
            <div class="max-w-4xl mx-auto glass-card rounded-3xl p-4 sm:p-5 shadow-2xl border border-white/40 text-slate-800 text-left">
                <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-3 items-center">
                    
                    <!-- Check-in Date -->
                    <div class="bg-slate-50/90 rounded-2xl p-3 border border-slate-200/80">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                            <i class="fas fa-calendar-alt text-amber-500 mr-1"></i> Rencana Masuk
                        </label>
                        <input type="date" id="searchCheckIn" value="{{ date('Y-m-d') }}" 
                               class="w-full bg-transparent border-0 p-0 text-sm font-bold text-slate-900 focus:ring-0 cursor-pointer">
                    </div>

                    <!-- Lease Duration -->
                    <div class="bg-slate-50/90 rounded-2xl p-3 border border-slate-200/80">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                            <i class="fas fa-clock text-amber-500 mr-1"></i> Durasi Sewa
                        </label>
                        <select id="searchDuration" class="w-full bg-transparent border-0 p-0 text-sm font-bold text-slate-900 focus:ring-0 cursor-pointer">
                            <option value="1">1 Bulan (Fleksibel)</option>
                            <option value="3">3 Bulan (Hemat 5%)</option>
                            <option value="6">6 Bulan (Hemat 10%)</option>
                            <option value="12">1 Tahun (Best Value 15%)</option>
                        </select>
                    </div>

                    <!-- Room Type Filter -->
                    <div class="bg-slate-50/90 rounded-2xl p-3 border border-slate-200/80">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                            <i class="fas fa-bed text-amber-500 mr-1"></i> Tipe Kamar
                        </label>
                        <select id="searchType" class="w-full bg-transparent border-0 p-0 text-sm font-bold text-slate-900 focus:ring-0 cursor-pointer">
                            <option value="all">Semua Tipe Kamar</option>
                            <option value="deluxe">Deluxe Room</option>
                            <option value="executive">Executive Suite</option>
                            <option value="vip">VIP Penthouse</option>
                            <option value="standard">Standard Cozy</option>
                        </select>
                    </div>

                    <!-- Action Search CTA -->
                    <div class="sm:col-span-3 lg:col-span-1">
                        <button type="button" id="executeSearchBtn" 
                                class="w-full h-full min-h-[52px] rounded-2xl bg-gradient-to-r from-amber-500 via-amber-600 to-amber-500 text-slate-950 font-black text-sm shadow-lg hover:shadow-xl hover:scale-[1.02] active:scale-95 transition-all duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-search"></i>
                            <span>Cari Kamar</span>
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <!-- 4 PILLARS OF EXCELLENCE (Hotel Standards) -->
    <section class="py-16 sm:py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="text-center max-w-3xl mx-auto space-y-3 mb-12">
            <span class="text-xs font-bold uppercase tracking-widest text-amber-600">Standard & Keunggulan</span>
            <h2 class="text-2xl sm:text-4xl font-extrabold font-heading text-slate-900 tracking-tight">
                Mengapa Memilih Cemara Residence?
            </h2>
            <p class="text-sm sm:text-base text-slate-600">
                Kami merancang setiap detail ruang untuk memastikan produktivitas, ketenangan, dan kenyamanan istirahat Anda.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card 1 -->
            <div class="p-6 rounded-3xl bg-white border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl mb-5">
                    <i class="fas fa-bed"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Hotel-Grade Comfort</h3>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                    Kasur springbed orthopedic tebal, sprei bersih, AC dingin, dan kamar mandi dalam dengan water heater.
                </p>
            </div>

            <!-- Card 2 -->
            <div class="p-6 rounded-3xl bg-white border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl mb-5">
                    <i class="fas fa-wifi"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">100 Mbps Dedicated WiFi</h3>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                    Koneksi internet serat optik berkecepatan tinggi tanpa hambatan untuk WFH, kuliah online, dan streaming.
                </p>
            </div>

            <!-- Card 3 -->
            <div class="p-6 rounded-3xl bg-white border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mb-5">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">24/7 Security & CCTV</h3>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                    Pengawasan CCTV 24 jam di seluruh area publik, security on-duty, dan sistem akses pintu aman.
                </p>
            </div>

            <!-- Card 4 -->
            <div class="p-6 rounded-3xl bg-white border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl mb-5">
                    <i class="fas fa-receipt"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Transparan & Midtrans Pay</h3>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                    Tanpa biaya tersembunyi. Pembayaran sewa bulanan praktis dalam 1-klik menggunakan QRIS & Virtual Account.
                </p>
            </div>
        </div>
    </section>

    <!-- ROOM SHOWCASE & INTERACTIVE FILTER (Section #kamar) -->
    <section id="kamar" class="py-16 sm:py-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
            <div class="space-y-2">
                <span class="text-xs font-bold uppercase tracking-widest text-amber-600">Pilihan Hunian</span>
                <h2 class="text-2xl sm:text-4xl font-extrabold font-heading text-slate-900 tracking-tight">
                    Koleksi Kamar & Suite Eksklusif
                </h2>
                <p class="text-sm sm:text-base text-slate-600 max-w-xl">
                    Setiap kamar dirancang dengan standar hotel berbintang, siap huni (*ready to move in*) dengan perabotan lengkap.
                </p>
            </div>

            <!-- Floor & Category Filters -->
            <div class="flex flex-wrap items-center gap-2">
                <button type="button" class="filter-category-btn active px-4 py-2 rounded-xl text-xs font-bold bg-slate-900 text-white transition-all shadow-sm" data-category="all">
                    Semua ({{ $rooms->count() }})
                </button>
                <button type="button" class="filter-category-btn px-4 py-2 rounded-xl text-xs font-bold bg-white text-slate-700 hover:bg-slate-100 border border-slate-200 transition-all" data-category="deluxe">
                    Deluxe
                </button>
                <button type="button" class="filter-category-btn px-4 py-2 rounded-xl text-xs font-bold bg-white text-slate-700 hover:bg-slate-100 border border-slate-200 transition-all" data-category="executive">
                    Executive
                </button>
                <button type="button" class="filter-category-btn px-4 py-2 rounded-xl text-xs font-bold bg-white text-slate-700 hover:bg-slate-100 border border-slate-200 transition-all" data-category="vip">
                    VIP Penthouse
                </button>
                <button type="button" class="filter-category-btn px-4 py-2 rounded-xl text-xs font-bold bg-white text-slate-700 hover:bg-slate-100 border border-slate-200 transition-all" data-category="standard">
                    Standard
                </button>
            </div>
        </div>

        <!-- Room Cards Grid -->
        <div id="roomCardsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($rooms as $room)
                @php
                    $isAvailable = $room->status === 'available';
                    $roomImg = $room->image ? (str_starts_with($room->image, 'http') ? $room->image : asset('storage/' . $room->image)) : asset('images/room-default.webp');
                    $categoryTag = 'standard';
                    $lowerName = strtolower($room->name);
                    if (str_contains($lowerName, 'deluxe')) $categoryTag = 'deluxe';
                    elseif (str_contains($lowerName, 'executive')) $categoryTag = 'executive';
                    elseif (str_contains($lowerName, 'vip') || str_contains($lowerName, 'penthouse')) $categoryTag = 'vip';

                    $avgScore = round($room->reviews->avg('rating') ?: 4.9, 1);
                    $reviewCount = $room->reviews->count() ?: 8;
                @endphp

                <div class="room-card group bg-white rounded-3xl overflow-hidden border border-slate-200/80 shadow-sm hover:shadow-2xl transition-all duration-500 flex flex-col justify-between" 
                     data-category="{{ $categoryTag }}" 
                     data-floor="{{ $room->floor }}" 
                     data-price="{{ $room->price }}" 
                     data-status="{{ $room->status }}">
                    
                    <!-- Image Box with Badges -->
                    <div class="relative h-60 w-full overflow-hidden bg-slate-900">
                        <img loading="lazy" decoding="async" src="{{ $roomImg }}" alt="{{ $room->name }}" 
                             class="room-img w-full h-full object-cover transition-transform duration-700 ease-out"
                             onerror="this.src='https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&w=800&q=80'">

                        <!-- Status Badge -->
                        <div class="absolute top-4 left-4">
                            @if($isAvailable)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-500/90 backdrop-blur-md text-white text-xs font-black shadow-lg">
                                    <span class="w-2 h-2 rounded-full bg-white animate-ping"></span>
                                    <span>Tersedia • Siap Huni</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-900/80 backdrop-blur-md text-amber-400 text-xs font-bold shadow-md">
                                    <i class="fas fa-lock text-[10px]"></i>
                                    <span>Terisi Penuh</span>
                                </span>
                            @endif
                        </div>

                        <!-- Floor & Size Badge -->
                        <div class="absolute bottom-4 left-4 right-4 flex items-center justify-between text-white text-xs font-semibold">
                            <span class="px-2.5 py-1 rounded-lg bg-slate-900/80 backdrop-blur-md border border-white/10">
                                <i class="fas fa-layer-group text-amber-400 mr-1"></i> Lantai {{ $room->floor }}
                            </span>
                            <span class="px-2.5 py-1 rounded-lg bg-slate-900/80 backdrop-blur-md border border-white/10">
                                <i class="fas fa-vector-square text-amber-400 mr-1"></i> {{ $room->size ?? 24 }} m²
                            </span>
                        </div>
                    </div>

                    <!-- Room Content -->
                    <div class="p-6 space-y-4 flex-1 flex flex-col justify-between">
                        <div>
                            <!-- Rating and Type -->
                            <div class="flex items-center justify-between gap-2 text-xs mb-2">
                                <span class="font-bold text-amber-600 uppercase tracking-wider">
                                    {{ strtoupper($categoryTag) }} SUITE
                                </span>
                                <div class="flex items-center gap-1 text-slate-700 font-bold">
                                    <i class="fas fa-star text-amber-400 text-xs"></i>
                                    <span>{{ $avgScore }}</span>
                                    <span class="text-slate-400 font-normal">({{ $reviewCount }})</span>
                                </div>
                            </div>

                            <!-- Room Title -->
                            <h3 class="text-xl font-bold font-heading text-slate-900 group-hover:text-amber-600 transition-colors">
                                <a href="{{ route('rooms.show', $room->id) }}">
                                    {{ $room->name }}
                                </a>
                            </h3>

                            <!-- Amenities Pills -->
                            <div class="flex flex-wrap gap-1.5 pt-3">
                                @foreach($room->facilities->take(4) as $fac)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-[11px] font-medium">
                                        <i class="{{ $fac->icon ?? 'fas fa-check' }} text-amber-500 text-[10px]"></i>
                                        <span>{{ $fac->name }}</span>
                                    </span>
                                @endforeach
                                @if($room->facilities->count() > 4)
                                    <span class="px-2 py-1 rounded-lg bg-slate-100 text-slate-500 text-[11px] font-bold">
                                        +{{ $room->facilities->count() - 4 }} lainnya
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Price and Action CTA -->
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-4">
                            <div>
                                <div class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider">Mulai Dari</div>
                                <div class="text-lg sm:text-xl font-black text-slate-900 font-heading">
                                    Rp {{ number_format($room->price, 0, ',', '.') }}
                                    <span class="text-xs font-normal text-slate-500">/bln</span>
                                </div>
                            </div>

                            <a href="{{ route('rooms.show', $room->id) }}" 
                               class="px-4 py-2.5 rounded-xl bg-slate-900 text-white hover:bg-amber-500 hover:text-slate-950 font-bold text-xs shadow-md transition-all duration-200 flex items-center gap-2 shrink-0">
                                <span>Detail</span>
                                <i class="fas fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>

                    </div>

                </div>
            @empty
                <div class="col-span-3 text-center py-16 bg-white rounded-3xl border border-slate-200 p-8 space-y-4">
                    <i class="fas fa-bed text-4xl text-slate-300"></i>
                    <h3 class="text-lg font-bold text-slate-800">Saat ini belum ada kamar yang terdaftar.</h3>
                    <p class="text-sm text-slate-500">Silakan hubungi customer service kami untuk informasi ketersediaan.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- 5-STAR AMENITIES SECTION (Section #fasilitas) -->
    <section id="fasilitas" class="py-16 sm:py-24 bg-slate-900 text-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <div class="text-center max-w-3xl mx-auto space-y-3 mb-16">
                <span class="text-xs font-bold uppercase tracking-widest text-amber-400">All-Inclusive Living</span>
                <h2 class="text-2xl sm:text-4xl font-extrabold font-heading tracking-tight">
                    Fasilitas Lengkap Berstandar Hotel
                </h2>
                <p class="text-sm sm:text-base text-slate-300">
                    Semua fasilitas dirawat secara berkala untuk menjamin standar kebersihan, keamanan, dan fungsionalitas optimal.
                </p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-6">
                @php
                    $standardFacilities = [
                        ['icon' => 'fas fa-snowflake', 'name' => 'AC Dingin & Terawat', 'desc' => 'Daikin/Panasonic hemat energi'],
                        ['icon' => 'fas fa-wifi', 'name' => 'WiFi Fiber 100 Mbps', 'desc' => 'Unlimited kuota & stabil'],
                        ['icon' => 'fas fa-shower', 'name' => 'Water Heater Otomatis', 'desc' => 'Air panas 24 jam nonstop'],
                        ['icon' => 'fas fa-bath', 'name' => 'Kamar Mandi Dalam', 'desc' => 'Shower, kloset duduk & wastafel'],
                        ['icon' => 'fas fa-tv', 'name' => 'Smart TV LED', 'desc' => 'Support Netflix & YouTube'],
                        ['icon' => 'fas fa-bed', 'name' => 'Kasur Springbed Tebal', 'desc' => 'Bantal guling & sprei premium'],
                        ['icon' => 'fas fa-shield-alt', 'name' => 'CCTV & Security 24 Jam', 'desc' => 'Keamanan terjamin setiap sudut'],
                        ['icon' => 'fas fa-parking', 'name' => 'Parkir Mobil & Motor', 'desc' => 'Area parkir luas & beratap'],
                        ['icon' => 'fas fa-utensils', 'name' => 'Dapur Bersama Modern', 'desc' => 'Kompor, kulkas & microwave'],
                        ['icon' => 'fas fa-tshirt', 'name' => 'Mesin Cuci & Jemuran', 'desc' => 'Area laundry bersih & lapang'],
                        ['icon' => 'fas fa-tint', 'name' => 'Dispenser Air Bersih', 'desc' => 'Air minum galon higienis gratis'],
                        ['icon' => 'fas fa-broom', 'name' => 'Housekeeping Area Publik', 'desc' => 'Pembersihan rutin setiap hari'],
                        ['icon' => 'fas fa-plug', 'name' => 'Token Listrik Mandiri', 'desc' => 'Bebas atur pemakaian sendiri'],
                        ['icon' => 'fas fa-couch', 'name' => 'Lobby Tamu Eksklusif', 'desc' => 'Ruang santai nyaman ber-AC'],
                        ['icon' => 'fas fa-key', 'name' => 'Digital Smart Lock', 'desc' => 'Akses pintu aman kartu/PIN'],
                    ];
                @endphp

                @foreach($standardFacilities as $fac)
                    <div class="p-5 rounded-2xl bg-slate-800/80 border border-slate-700/80 hover:border-amber-400/50 transition-all duration-300 group">
                        <div class="w-10 h-10 rounded-xl bg-amber-400/10 text-amber-400 flex items-center justify-center text-lg mb-3 group-hover:scale-110 transition-transform">
                            <i class="{{ $fac['icon'] }}"></i>
                        </div>
                        <h4 class="text-sm font-bold text-white mb-1">{{ $fac['name'] }}</h4>
                        <p class="text-[11px] text-slate-400 leading-snug">{{ $fac['desc'] }}</p>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- PRIME LOCATION & NEIGHBORHOOD (Section #lokasi) -->
    <section id="lokasi" class="py-16 sm:py-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            
            <!-- Left Location Context -->
            <div class="lg:col-span-5 space-y-6">
                <span class="text-xs font-bold uppercase tracking-widest text-amber-600">Lokasi Prestisius</span>
                <h2 class="text-2xl sm:text-4xl font-extrabold font-heading text-slate-900 tracking-tight">
                    Akses Cepat ke Pusat Pendidikan & Bisnis Bandung
                </h2>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Terletak di kawasan elite Setiabudi Hegarmanah, bebas bising namun sangat dekat dengan berbagai kampus ternama, pusat perbelanjaan, dan fasilitas kesehatan.
                </p>

                <!-- Points of Interest List -->
                <div class="space-y-3 pt-2">
                    <div class="flex items-center gap-3 p-3 rounded-2xl bg-white border border-slate-200/80">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm font-bold shrink-0">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="flex-1 text-xs">
                            <div class="font-bold text-slate-900">ITB & UNPAR</div>
                            <div class="text-slate-500">Hanya 5 - 8 Menit Berkendara</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 rounded-2xl bg-white border border-slate-200/80">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold shrink-0">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <div class="flex-1 text-xs">
                            <div class="font-bold text-slate-900">Paris Van Java (PVJ) & Ciwalk</div>
                            <div class="text-slate-500">Hanya 7 Menit Berkendara</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 rounded-2xl bg-white border border-slate-200/80">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-bold shrink-0">
                            <i class="fas fa-hospital"></i>
                        </div>
                        <div class="flex-1 text-xs">
                            <div class="font-bold text-slate-900">RS Advent & RS Hasan Sadikin</div>
                            <div class="text-slate-500">Hanya 8 Menit Berkendara</div>
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <a href="https://maps.google.com/?q=Hegarmanah+Setiabudi+Bandung" target="_blank" 
                       class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-slate-900 text-white font-bold text-xs hover:bg-amber-600 transition-colors shadow-md">
                        <i class="fas fa-map-marked-alt text-amber-400"></i>
                        <span>Buka di Google Maps</span>
                    </a>
                </div>
            </div>

            <!-- Right Interactive Map Embed -->
            <div class="lg:col-span-7 h-[380px] sm:h-[450px] rounded-3xl overflow-hidden shadow-2xl border border-slate-200 relative bg-slate-100">
                {!! $mapsEmbed->maps_embed ?? '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.0504172457814!2d107.5956041!3d-6.8845579!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6931557088b%3A0x6b8f1d8f58b761!2sHegarmanah%2C%20Cidadap%2C%20Bandung%20City%2C%20West%20Java!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>' !!}
            </div>

        </div>
    </section>

    <!-- VERIFIED GUEST REVIEWS (Section #ulasan) -->
    <section id="ulasan" class="py-16 sm:py-24 bg-slate-50/80 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-t border-slate-200/60">
        <div class="text-center max-w-3xl mx-auto space-y-3 mb-16">
            <span class="text-xs font-bold uppercase tracking-widest text-amber-600">Ulasan & Reputasi</span>
            <h2 class="text-2xl sm:text-4xl font-extrabold font-heading text-slate-900 tracking-tight">
                Pengalaman Nyata Para Penghuni
            </h2>
            <div class="inline-flex items-center gap-3 px-4 py-2 rounded-2xl bg-white border border-slate-200 shadow-sm mt-2">
                <div class="flex text-amber-400 text-sm">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <span class="text-slate-900 font-extrabold text-sm">4.9 / 5.0</span>
                <span class="text-slate-400 text-xs">• Berdasarkan 120+ Ulasan Terverifikasi</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Review Card 1 -->
            <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-sm space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex text-amber-400 text-xs">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="text-[11px] text-slate-400">Tamu Eksekutif</span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-700 italic leading-relaxed">
                        "Kamar sangat bersih, water heater stabil dan WiFi super cepat buat kerja remote. Fasilitas parkir mobilnya luas dan security-nya ramah banget."
                    </p>
                </div>
                <div class="pt-4 border-t border-slate-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-amber-500 text-slate-950 flex items-center justify-center font-bold text-xs">
                        BS
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-900">Budi Santoso</div>
                        <div class="text-xs text-slate-400">Penghuni Deluxe Room A02</div>
                    </div>
                </div>
            </div>

            <!-- Review Card 2 -->
            <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-sm space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex text-amber-400 text-xs">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="text-[11px] text-slate-400">Mahasiswi ITB</span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-700 italic leading-relaxed">
                        "Lokasi strategis banget ke kampus, lingkungannya tenang dan aman. Dapur bersamanya bersih dan lengkap, jadi hemat banget."
                    </p>
                </div>
                <div class="pt-4 border-t border-slate-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-xs">
                        SR
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-900">Siti Rahmawati</div>
                        <div class="text-xs text-slate-400">Penghuni Executive Suite B01</div>
                    </div>
                </div>
            </div>

            <!-- Review Card 3 -->
            <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-sm space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex text-amber-400 text-xs">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="text-[11px] text-slate-400">Profesional</span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-700 italic leading-relaxed">
                        "Sistem bayar sewa pakai Midtrans QRIS sangat praktis, invoice langsung keluar. Kamarnya kedap suara dan pemandangan kotanya bagus."
                    </p>
                </div>
                <div class="pt-4 border-t border-slate-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-xs">
                        DP
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-900">Dimas Pratama</div>
                        <div class="text-xs text-slate-400">Penghuni VIP Penthouse D01</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ ACCORDION (Section #faq) -->
    <section id="faq" class="py-16 sm:py-24 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
        <div class="text-center space-y-3 mb-12">
            <span class="text-xs font-bold uppercase tracking-widest text-amber-600">Informasi Penting</span>
            <h2 class="text-2xl sm:text-4xl font-extrabold font-heading text-slate-900 tracking-tight">
                Pertanyaan yang Sering Diajukan (FAQ)
            </h2>
            <p class="text-sm text-slate-500">
                Temukan jawaban cepat seputar tata cara pemesanan, fasilitas, dan ketentuan sewa.
            </p>
        </div>

        <div class="space-y-3" id="faqAccordion">
            
            <!-- Item 1 -->
            <div class="faq-item rounded-2xl bg-white border border-slate-200 overflow-hidden transition-all duration-200">
                <button type="button" class="faq-toggle w-full px-6 py-4 text-left font-bold text-sm sm:text-base text-slate-900 flex items-center justify-between gap-4">
                    <span>Bagaimana cara booking kamar di Cemara Residence?</span>
                    <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200"></i>
                </button>
                <div class="faq-content hidden px-6 pb-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                    Anda dapat memilih kamar yang berstatus "Tersedia", menentukan durasi sewa, lalu melakukan pembayaran bulan pertama via Midtrans (QRIS/VA) atau langsung menghubungi Concierge kami melalui WhatsApp untuk survey lokasi.
                </div>
            </div>

            <!-- Item 2 -->
            <div class="faq-item rounded-2xl bg-white border border-slate-200 overflow-hidden transition-all duration-200">
                <button type="button" class="faq-toggle w-full px-6 py-4 text-left font-bold text-sm sm:text-base text-slate-900 flex items-center justify-between gap-4">
                    <span>Apakah biaya sewa sudah termasuk fasilitas listrik dan air?</span>
                    <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200"></i>
                </button>
                <div class="faq-content hidden px-6 pb-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                    Harga sewa sudah termasuk air bersih, pemakaian WiFi 100 Mbps, fasilitas dapur bersama, dan iuran sampah/keamanan. Untuk listrik kamar menggunakan meteran token prabayar mandiri agar pemakaian Anda transparan dan fleksibel.
                </div>
            </div>

            <!-- Item 3 -->
            <div class="faq-item rounded-2xl bg-white border border-slate-200 overflow-hidden transition-all duration-200">
                <button type="button" class="faq-toggle w-full px-6 py-4 text-left font-bold text-sm sm:text-base text-slate-900 flex items-center justify-between gap-4">
                    <span>Apakah diperbolehkan membawa kendaraan roda empat (mobil)?</span>
                    <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200"></i>
                </button>
                <div class="faq-content hidden px-6 pb-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                    Ya, kami memiliki area parkir mobil dan motor yang memadai, beratap, dan dipantau CCTV serta security 24 jam. Slot parkir mobil dapat dikonfirmasi saat proses reservasi.
                </div>
            </div>

            <!-- Item 4 -->
            <div class="faq-item rounded-2xl bg-white border border-slate-200 overflow-hidden transition-all duration-200">
                <button type="button" class="faq-toggle w-full px-6 py-4 text-left font-bold text-sm sm:text-base text-slate-900 flex items-center justify-between gap-4">
                    <span>Bagaimana jam malam dan aturan penerimaan tamu?</span>
                    <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200"></i>
                </button>
                <div class="faq-content hidden px-6 pb-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                    Penghuni memiliki akses gerbang 24 jam dengan kartu akses. Tamu luar dapat diterima di area Lobby Tamu Eksklusif hingga pukul 22.00 WIB untuk menjaga privasi dan ketenangan seluruh penghuni.
                </div>
            </div>

        </div>
    </section>

    <!-- VIP CALL TO ACTION BANNER -->
    <section class="py-16 sm:py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="rounded-3xl luxury-gradient-dark text-white p-8 sm:p-12 lg:p-16 text-center relative overflow-hidden shadow-2xl border border-slate-700/80">
            <div class="max-w-2xl mx-auto space-y-6 relative z-10">
                <span class="px-4 py-1.5 rounded-full bg-amber-500/20 text-amber-400 font-bold text-xs tracking-wider uppercase border border-amber-400/30">
                    Kamar Terbatas • Siap Huni
                </span>
                <h2 class="text-2xl sm:text-4xl font-extrabold font-heading tracking-tight leading-tight">
                    Temukan Hunian Impian Anda di Cemara Residence Hari Ini
                </h2>
                <p class="text-sm sm:text-base text-slate-300 font-normal">
                    Dapatkan penawaran harga terbaik dan potongan spesial untuk sewa 6 bulan & 1 tahun.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                    <a href="#kamar" 
                       class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-sm shadow-xl hover:scale-105 transition-all">
                        <i class="fas fa-calendar-check mr-2"></i> Pilih & Pesan Kamar
                    </a>
                    @php
                        $whatsappNum = preg_replace('/[^0-9]/', '', $contact->whatsapp ?? '6281234567890');
                        if (str_starts_with($whatsappNum, '0')) $whatsappNum = '62' . substr($whatsappNum, 1);
                    @endphp
                    <a href="https://wa.me/{{ $whatsappNum }}?text=Halo%20Concierge,%20saya%20tertarik%20untuk%20survey%20kamar%20di%20Cemara%20Residence" 
                       target="_blank" rel="noopener noreferrer" 
                       class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-bold text-sm border border-white/20 transition-all flex items-center justify-center gap-2">
                        <i class="fab fa-whatsapp text-emerald-400 text-base"></i> Hubungi Concierge
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    @include('landing.footer')

    <!-- Interactive Client Scripts -->
    <script>
        // Category Filter Logic
        const filterBtns = document.querySelectorAll('.filter-category-btn');
        const roomCards = document.querySelectorAll('.room-card');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => {
                    b.classList.remove('active', 'bg-slate-900', 'text-white');
                    b.classList.add('bg-white', 'text-slate-700');
                });
                btn.classList.add('active', 'bg-slate-900', 'text-white');
                btn.classList.remove('bg-white', 'text-slate-700');

                const selectedCategory = btn.getAttribute('data-category');

                roomCards.forEach(card => {
                    if (selectedCategory === 'all' || card.getAttribute('data-category') === selectedCategory) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Quick Search Bar Execute
        const executeSearchBtn = document.getElementById('executeSearchBtn');
        if (executeSearchBtn) {
            executeSearchBtn.addEventListener('click', () => {
                const searchType = document.getElementById('searchType').value;
                const kamarSection = document.getElementById('kamar');

                if (kamarSection) {
                    kamarSection.scrollIntoView({ behavior: 'smooth' });
                }

                // Trigger filter button match
                const matchBtn = document.querySelector(`.filter-category-btn[data-category="${searchType}"]`);
                if (matchBtn) {
                    matchBtn.click();
                }
            });
        }

        // FAQ Accordion Toggle
        const faqToggles = document.querySelectorAll('.faq-toggle');
        faqToggles.forEach(toggle => {
            toggle.addEventListener('click', () => {
                const content = toggle.nextElementSibling;
                const icon = toggle.querySelector('i');
                const isOpen = !content.classList.contains('hidden');

                document.querySelectorAll('.faq-content').forEach(c => c.classList.add('hidden'));
                document.querySelectorAll('.faq-toggle i').forEach(i => i.style.transform = 'rotate(0deg)');

                if (!isOpen) {
                    content.classList.remove('hidden');
                    icon.style.transform = 'rotate(180deg)';
                }
            });
        });
    </script>
</body>
</html>