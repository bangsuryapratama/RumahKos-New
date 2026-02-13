<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RumahKos - KosNyaman</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        html { scroll-behavior: smooth; }
        .gradient-text {
            background: linear-gradient(to right, #2563eb, #1d4ed8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes pulse-soft {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        .animate-pulse-soft {
            animation: pulse-soft 2s ease-in-out infinite;
        }

        /* Mobile horizontal scroll */
        .horizontal-scroll {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .horizontal-scroll::-webkit-scrollbar {
            display: none;
        }
        .horizontal-scroll > * {
            scroll-snap-align: start;
            flex: 0 0 auto;
        }

        /* Filter styles */
        .filter-btn.active {
            background: #2563eb;
            color: white;
            border-color: #2563eb;
        }

        /* Card hover effect */
        .room-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .room-card:hover {
            transform: translateY(-4px);
        }

        /* Hidden class for filtering */
        .hidden-filter {
            display: none !important;
        }

        /* Back to top button */
        #backToTop {
            position: fixed;
            bottom: 80px;
            right: 20px;
            z-index: 50;
            transition: all 0.3s ease;
            transform: scale(0);
        }
        #backToTop.show {
            transform: scale(1);
        }
    </style>
</head>
<body class="bg-white">

    {{-- Navbar --}}
    @include('landing.navbar')

    {{-- HERO SECTION --}}
    <section class="bg-gradient-to-br from-blue-50 via-white to-gray-50 pt-24 sm:pt-28 pb-12 sm:pb-16 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-12">
                <span class="inline-block bg-blue-100 text-blue-600 px-4 py-2 rounded-full text-xs sm:text-sm font-semibold mb-4 sm:mb-6 animate-pulse-soft">
                    <i class="fas fa-map-marker-alt mr-1"></i> Lokasi di {{ $propertyLocation }}
                </span>

                <h1 class="text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-bold text-gray-900 mb-4 sm:mb-6 leading-tight px-4">
                    Kos Nyaman untuk<br>
                    <span class="gradient-text">Mahasiswa & Pekerja</span>
                </h1>

                <p class="text-base sm:text-lg lg:text-xl text-gray-600 max-w-3xl mx-auto mb-6 sm:mb-8 px-4">
                    Fasilitas lengkap, nyaman, dan strategis. WiFi cepat, kamar mandi dalam, keamanan 24 jam.
                    <br class="hidden sm:block">
                    Mulai dari <strong class="text-blue-600">Rp {{ number_format($minPrice / 1000000, 1) }}jt</strong>/bulan
                </p>

                <div class="flex flex-col sm:flex-row gap-3 justify-center px-4">
                    <a href="#kamar" class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-all shadow-lg hover:shadow-xl text-sm sm:text-base">
                        <i class="fas fa-bed mr-2"></i> Lihat Kamar Tersedia
                    </a>
                    <a href="#kontak" class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 border-2 border-blue-600 text-blue-600 font-semibold rounded-xl hover:bg-blue-50 transition-all text-sm sm:text-base">
                        <i class="fab fa-whatsapp mr-2"></i> Hubungi Kami
                    </a>
                </div>
            </div>

            {{-- Hero Image --}}
            <div class="mt-8 sm:mt-12 px-4 sm:px-0">
                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=1200&h=600&fit=crop"
                     alt="Kosan"
                     class="w-full h-48 sm:h-64 lg:h-80 xl:h-96 object-cover rounded-xl sm:rounded-2xl shadow-2xl ">
            </div>

            {{-- Quick Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mt-8 sm:mt-12 max-w-4xl mx-auto px-4 sm:px-0">
                <div class="text-center bg-white p-5 sm:p-6 rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:scale-105 border border-gray-100">
                    <div class="text-3xl sm:text-4xl font-bold text-blue-600">{{ $availableRooms }}</div>
                    <div class="text-gray-600 text-xs sm:text-sm mt-1">Kamar Tersedia</div>
                </div>
                <div class="text-center bg-white p-5 sm:p-6 rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:scale-105 border border-gray-100">
                    <div class="text-3xl sm:text-4xl font-bold text-blue-600">
                        {{ $minPrice > 0 ? number_format($minPrice / 1000000, 1) . 'jt' : 'N/A' }}
                    </div>
                    <div class="text-gray-600 text-xs sm:text-sm mt-1">Mulai dari /bulan</div>
                </div>
                <div class="text-center bg-white p-5 sm:p-6 rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:scale-105 border border-gray-100">
                    <div class="text-3xl sm:text-4xl font-bold text-blue-600">24/7</div>
                    <div class="text-gray-600 text-xs sm:text-sm mt-1">Keamanan</div>
                </div>
            </div>
        </div>
    </section>

    {{-- FASILITAS SECTION (WITH HORIZONTAL SCROLL ON MOBILE) --}}
    <section class="py-12 sm:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-12">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">Fasilitas Lengkap</h2>
                <p class="text-gray-600 text-sm sm:text-base">Semua yang kamu butuhkan untuk kenyamanan maksimal</p>
            </div>

            {{-- Mobile: Horizontal Scroll | Desktop: Grid --}}
            <div class="sm:hidden horizontal-scroll gap-3 px-4 -mx-4">
                @if($FacilityAll->count() > 0)
                    @foreach($FacilityAll->take(8) as $facility)
                    <div class="text-center p-4 bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl w-32 flex-shrink-0 border border-gray-100">
                        <div class="text-3xl mb-2">
                            <i class="{{ $facility->icon }} text-blue-600"></i>
                        </div>
                        <div class="font-semibold text-gray-800 text-xs">{{ $facility->name }}</div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center p-4 bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl w-32 flex-shrink-0 border border-gray-100">
                        <div class="text-3xl mb-2"><i class="fas fa-bed text-blue-600"></i></div>
                        <div class="font-semibold text-gray-800 text-xs">Kasur & Lemari</div>
                    </div>
                    <div class="text-center p-4 bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl w-32 flex-shrink-0 border border-gray-100">
                        <div class="text-3xl mb-2"><i class="fas fa-wifi text-blue-600"></i></div>
                        <div class="font-semibold text-gray-800 text-xs">WiFi 100Mbps</div>
                    </div>
                    <div class="text-center p-4 bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl w-32 flex-shrink-0 border border-gray-100">
                        <div class="text-3xl mb-2"><i class="fas fa-shower text-blue-600"></i></div>
                        <div class="font-semibold text-gray-800 text-xs">K. Mandi Dalam</div>
                    </div>
                    <div class="text-center p-4 bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl w-32 flex-shrink-0 border border-gray-100">
                        <div class="text-3xl mb-2"><i class="fas fa-square-parking text-blue-600"></i></div>
                        <div class="font-semibold text-gray-800 text-xs">Parkir Motor</div>
                    </div>
                    <div class="text-center p-4 bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl w-32 flex-shrink-0 border border-gray-100">
                        <div class="text-3xl mb-2"><i class="fas fa-shield-halved text-blue-600"></i></div>
                        <div class="font-semibold text-gray-800 text-xs">Keamanan 24/7</div>
                    </div>
                    <div class="text-center p-4 bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl w-32 flex-shrink-0 border border-gray-100">
                        <div class="text-3xl mb-2"><i class="fas fa-kitchen-set text-blue-600"></i></div>
                        <div class="font-semibold text-gray-800 text-xs">Dapur Bersama</div>
                    </div>
                    <div class="text-center p-4 bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl w-32 flex-shrink-0 border border-gray-100">
                        <div class="text-3xl mb-2"><i class="fas fa-jug-detergent text-blue-600"></i></div>
                        <div class="font-semibold text-gray-800 text-xs">Laundry</div>
                    </div>
                    <div class="text-center p-4 bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl w-32 flex-shrink-0 border border-gray-100">
                        <div class="text-3xl mb-2"><i class="fas fa-snowflake text-blue-600"></i></div>
                        <div class="font-semibold text-gray-800 text-xs">AC (Opsional)</div>
                    </div>
                @endif
            </div>

            {{-- Desktop: Grid --}}
            <div class="hidden sm:grid grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-6">
                @if($FacilityAll->count() > 0)
                    @foreach($FacilityAll->take(8) as $facility)
                    <div class="text-center p-6 bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl hover:shadow-lg transition-all transform hover:scale-105 duration-300 border border-gray-100">
                        <div class="text-4xl mb-3">
                            <i class="{{ $facility->icon }} text-blue-600"></i>
                        </div>
                        <div class="font-semibold text-gray-800 text-sm lg:text-base">{{ $facility->name }}</div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center p-6 bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl hover:shadow-lg transition-all transform hover:scale-105 border border-gray-100">
                        <div class="text-4xl mb-3"><i class="fas fa-bed text-blue-600"></i></div>
                        <div class="font-semibold text-gray-800 text-sm">Kasur & Lemari</div>
                    </div>
                    <div class="text-center p-6 bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl hover:shadow-lg transition-all transform hover:scale-105 border border-gray-100">
                        <div class="text-4xl mb-3"><i class="fas fa-wifi text-blue-600"></i></div>
                        <div class="font-semibold text-gray-800 text-sm">WiFi 100Mbps</div>
                    </div>
                    <div class="text-center p-6 bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl hover:shadow-lg transition-all transform hover:scale-105 border border-gray-100">
                        <div class="text-4xl mb-3"><i class="fas fa-shower text-blue-600"></i></div>
                        <div class="font-semibold text-gray-800 text-sm">K. Mandi Dalam</div>
                    </div>
                    <div class="text-center p-6 bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl hover:shadow-lg transition-all transform hover:scale-105 border border-gray-100">
                        <div class="text-4xl mb-3"><i class="fas fa-square-parking text-blue-600"></i></div>
                        <div class="font-semibold text-gray-800 text-sm">Parkir Motor</div>
                    </div>
                    <div class="text-center p-6 bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl hover:shadow-lg transition-all transform hover:scale-105 border border-gray-100">
                        <div class="text-4xl mb-3"><i class="fas fa-shield-halved text-blue-600"></i></div>
                        <div class="font-semibold text-gray-800 text-sm">Keamanan 24/7</div>
                    </div>
                    <div class="text-center p-6 bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl hover:shadow-lg transition-all transform hover:scale-105 border border-gray-100">
                        <div class="text-4xl mb-3"><i class="fas fa-kitchen-set text-blue-600"></i></div>
                        <div class="font-semibold text-gray-800 text-sm">Dapur Bersama</div>
                    </div>
                    <div class="text-center p-6 bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl hover:shadow-lg transition-all transform hover:scale-105 border border-gray-100">
                        <div class="text-4xl mb-3"><i class="fas fa-jug-detergent text-blue-600"></i></div>
                        <div class="font-semibold text-gray-800 text-sm">Laundry</div>
                    </div>
                    <div class="text-center p-6 bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl hover:shadow-lg transition-all transform hover:scale-105 border border-gray-100">
                        <div class="text-4xl mb-3"><i class="fas fa-snowflake text-blue-600"></i></div>
                        <div class="font-semibold text-gray-800 text-sm">AC (Opsional)</div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- DAFTAR KAMAR SECTION WITH SIMPLE SEARCH & FILTER --}}
    <section id="kamar" class="py-12 sm:py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-6 sm:mb-8">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">Daftar Kamar</h2>
                <p class="text-gray-600 text-sm sm:text-base">Pilih kamar yang sesuai dengan kebutuhanmu</p>
            </div>

            {{-- Search & Simple Filter --}}
            <div class="mb-6">
                <div class="max-w-3xl mx-auto space-y-3">
                    {{-- Search Bar --}}
                    <div class="relative">
                        <input type="text"
                               id="searchInput"
                               placeholder="Cari nama kamar..."
                               class="w-full px-4 py-3 pl-12 pr-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-sm"
                               oninput="searchRooms()">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>

                    {{-- Simple Filters --}}
                    <div class="flex flex-wrap gap-2 justify-center">
                        <button onclick="filterAvailable()" id="btnAvailable" class="filter-btn px-4 py-2 rounded-lg font-semibold text-sm border border-gray-300 bg-white text-gray-700 hover:bg-blue-50 hover:border-blue-600">
                            <i class="fas fa-check-circle mr-1"></i> Hanya Tersedia
                        </button>
                        <button onclick="sortRooms('name-asc')" class="px-4 py-2 rounded-lg font-semibold text-sm border border-gray-300 bg-white text-gray-700 hover:bg-blue-50 hover:border-blue-600">
                            <i class="fas fa-arrow-down-a-z mr-1"></i> A-Z
                        </button>
                        <button onclick="sortRooms('price-asc')" class="px-4 py-2 rounded-lg font-semibold text-sm border border-gray-300 bg-white text-gray-700 hover:bg-blue-50 hover:border-blue-600">
                            <i class="fas fa-tag mr-1"></i> Harga Terendah
                        </button>
                    </div>
                </div>
            </div>

            {{-- Results Count --}}
            <div class="text-center text-sm text-gray-600 mb-4">
                <span id="resultCount">Menampilkan <strong id="displayCount">0</strong> dari <strong id="totalCount">0</strong> kamar</span>
            </div>

            {{-- Rooms Grid --}}
            <div id="roomsContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @forelse($rooms as $room)
                    @php
                        $roomRating = $room->reviews->avg('rating') ?? 0;
                        $roomReviewCount = $room->reviews->count();
                    @endphp

                    <a href="{{ route('rooms.detail', $room->id) }}"
                       class="room-card bg-white rounded-xl sm:rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden group block border border-gray-100"
                       data-status="{{ $room->status }}"
                       data-name="{{ strtolower($room->name) }}"
                       data-price="{{ $room->price }}">

                        {{-- Image + Overlay --}}
                        <div class="relative h-44 sm:h-52 overflow-hidden">
                            @if($room->image)
                                <img src="{{ asset('storage/' . $room->image) }}"
                                     alt="{{ $room->name }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=600&h=400&fit=crop"
                                     alt="{{ $room->name }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @endif

                            {{-- Status Badge --}}
                            <div class="absolute top-2 sm:top-3 right-2 sm:right-3">
                                @if($room->status == 'available')
                                    <span class="bg-green-600 text-white text-xs font-bold px-2 sm:px-3 py-1 rounded-full shadow-lg">
                                        <i class="fas fa-check-circle mr-1"></i>Tersedia
                                    </span>
                                @elseif($room->status == 'occupied')
                                    <span class="bg-red-600 text-white text-xs font-bold px-2 sm:px-3 py-1 rounded-full shadow-lg">
                                        <i class="fas fa-times-circle mr-1"></i>Terisi
                                    </span>
                                @else
                                    <span class="bg-orange-600 text-white text-xs font-bold px-2 sm:px-3 py-1 rounded-full shadow-lg">
                                        <i class="fas fa-tools mr-1"></i>Perbaikan
                                    </span>
                                @endif
                            </div>

                            {{-- Property Badge --}}
                            <div class="absolute bottom-2 sm:bottom-3 left-2 sm:left-3">
                                <span class="bg-black bg-opacity-70 text-white text-xs px-2 sm:px-3 py-1 rounded-full backdrop-blur-sm">
                                    <i class="fas fa-building mr-1"></i>{{ $room->property->name }}
                                </span>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-4 sm:p-5 flex flex-col gap-2 sm:gap-3">
                            <div class="flex items-start justify-between gap-2">
                                <h3 class="text-base sm:text-lg lg:text-xl font-bold text-gray-900 flex-1 line-clamp-1">{{ $room->name }}</h3>

                                {{-- Rating Display --}}
                                @if($roomReviewCount > 0)
                                <div class="flex items-center gap-1 bg-yellow-50 px-2 py-1 rounded-lg flex-shrink-0">
                                    <i class="fas fa-star text-yellow-500 text-xs"></i>
                                    <span class="text-xs font-bold text-gray-900">{{ number_format($roomRating, 1) }}</span>
                                    <span class="text-xs text-gray-500">({{ $roomReviewCount }})</span>
                                </div>
                                @endif
                            </div>

                            <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-gray-600 text-xs sm:text-sm">
                                @if($room->size)
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-ruler-combined"></i>
                                        <span>{{ $room->size }}</span>
                                    </span>
                                    <span class="text-gray-300">•</span>
                                @endif
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-layer-group"></i>
                                    <span>Lantai {{ $room->floor }}</span>
                                </span>
                            </div>

                            {{-- Facilities --}}
                            @if($room->facilities->count() > 0)
                            <div class="flex flex-wrap gap-1.5 sm:gap-2 text-xs">
                                @foreach($room->facilities->take(3) as $facility)
                                <span class="px-2 py-1 rounded-lg font-medium bg-blue-50 text-blue-600 hover:bg-blue-100">
                                    <i class="{{ $facility->icon }} mr-1"></i>{{ $facility->name }}
                                </span>
                                @endforeach
                                @if($room->facilities->count() > 3)
                                <span class="px-2 py-1 rounded-lg font-medium bg-blue-50 text-blue-600">
                                    +{{ $room->facilities->count() - 3 }}
                                </span>
                                @endif
                            </div>
                            @else
                            <div class="flex flex-wrap gap-1.5 sm:gap-2 text-xs">
                                <span class="px-2 py-1 rounded-lg font-medium bg-blue-50 text-blue-600">
                                    <i class="fas fa-bed mr-1"></i>Kasur
                                </span>
                                <span class="px-2 py-1 rounded-lg font-medium bg-blue-50 text-blue-600">
                                    <i class="fas fa-door-open mr-1"></i>Lemari
                                </span>
                                <span class="px-2 py-1 rounded-lg font-medium bg-blue-50 text-blue-600">
                                    <i class="fas fa-bath mr-1"></i>K. Mandi
                                </span>
                            </div>
                            @endif

                            {{-- Harga --}}
                            <div class="px-3 sm:px-4 py-2.5 sm:py-3 bg-gradient-to-r from-blue-50 to-gray-50 rounded-xl mt-2 sm:mt-3 border border-blue-100">
                                <div class="text-lg sm:text-xl font-bold text-blue-600">
                                    Rp {{ number_format($room->price, 0, ',', '.') }}
                                </div>
                                <div class="text-xs text-gray-600">
                                    @if($room->billing_cycle == 'daily')
                                        per hari
                                    @elseif($room->billing_cycle == 'weekly')
                                        per minggu
                                    @elseif($room->billing_cycle == 'monthly')
                                        per bulan
                                    @else
                                        per tahun
                                    @endif
                                </div>
                            </div>

                            {{-- Status Info for Non-Available Rooms --}}
                            @if($room->status != 'available')
                            <div class="text-center text-xs text-gray-500 italic mt-1">
                                <i class="fas fa-info-circle mr-1"></i>Klik untuk info lebih lanjut
                            </div>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-12 sm:py-16">
                        <div class="bg-gray-100 w-20 h-20 sm:w-24 sm:h-24 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-bed text-3xl sm:text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Belum Ada Kamar</h3>
                        <p class="text-gray-600 text-sm sm:text-base mb-4 sm:mb-6 px-4">Belum ada kamar yang tersedia saat ini. Silakan hubungi kami untuk info lebih lanjut.</p>
                        <a href="https://wa.me/6283841806357" class="inline-block px-5 sm:px-6 py-2.5 sm:py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm sm:text-base">
                            <i class="fab fa-whatsapp mr-2"></i>Hubungi Kami
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- No Results Message --}}
            <div id="noResults" class="hidden text-center py-12">
                <div class="bg-gray-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-search text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak Ada Hasil</h3>
                <p class="text-gray-600 text-sm">Tidak ada kamar yang sesuai dengan pencarian Anda</p>
            </div>

            {{-- Load More Button --}}
            <div id="loadMoreContainer" class="hidden text-center mt-8">
                <button onclick="loadMore()" id="btnLoadMore" class="inline-flex items-center gap-2 px-6 py-3 bg-white border-2 border-blue-600 text-blue-600 font-semibold rounded-xl hover:bg-blue-600 hover:text-white transition-all">
                    <span>Muat Lebih Banyak</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <p class="text-sm text-gray-500 mt-3">
                    <span id="loadMoreText">Menampilkan 9 dari 20 kamar</span>
                </p>
            </div>
        </div>
    </section>

    {{-- LOKASI SECTION --}}
    <section id="lokasi" class="py-12 sm:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="text-center mb-8 sm:mb-12">
                <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-blue-50 text-blue-600 shadow-sm mb-4">
                    <i class="fa-solid fa-map-location-dot text-xl sm:text-2xl"></i>
                </div>

                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-2 sm:mb-3">
                    Lokasi Strategis
                </h2>
                <p class="text-gray-600 max-w-xl mx-auto text-sm sm:text-base px-4">
                    Kos kami dekat dengan kampus, minimarket, transportasi, dan area kuliner. Lokasi mudah diakses dan aman.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 lg:gap-12">

                {{-- Fasilitas Sekitar --}}
                <div class="space-y-3 sm:space-y-4">
                    <div class="flex items-center gap-3 sm:gap-4 bg-gray-50 p-4 sm:p-5 rounded-xl hover:shadow-lg transition-all transform hover:scale-105 border border-gray-100">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-blue-100 rounded-lg flex items-center justify-center text-xl sm:text-2xl flex-shrink-0">
                            <i class="fa-solid fa-graduation-cap text-blue-600"></i>
                        </div>
                        <div>
                            <div class="font-bold text-gray-900 text-sm sm:text-base">Kampus ITB</div>
                            <div class="text-xs sm:text-sm text-gray-600">5 menit berkendara</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 sm:gap-4 bg-gray-50 p-4 sm:p-5 rounded-xl hover:shadow-lg transition-all transform hover:scale-105 border border-gray-100">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-blue-100 rounded-lg flex items-center justify-center text-xl sm:text-2xl flex-shrink-0">
                            <i class="fa-solid fa-shop text-blue-600"></i>
                        </div>
                        <div>
                            <div class="font-bold text-gray-900 text-sm sm:text-base">Minimarket</div>
                            <div class="text-xs sm:text-sm text-gray-600">2 menit jalan kaki</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 sm:gap-4 bg-gray-50 p-4 sm:p-5 rounded-xl hover:shadow-lg transition-all transform hover:scale-105 border border-gray-100">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-blue-100 rounded-lg flex items-center justify-center text-xl sm:text-2xl flex-shrink-0">
                            <i class="fa-solid fa-bus text-blue-600"></i>
                        </div>
                        <div>
                            <div class="font-bold text-gray-900 text-sm sm:text-base">Transportasi Umum</div>
                            <div class="text-xs sm:text-sm text-gray-600">Mudah diakses</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 sm:gap-4 bg-gray-50 p-4 sm:p-5 rounded-xl hover:shadow-lg transition-all transform hover:scale-105 border border-gray-100">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-blue-100 rounded-lg flex items-center justify-center text-xl sm:text-2xl flex-shrink-0">
                            <i class="fa-solid fa-utensils text-blue-600"></i>
                        </div>
                        <div>
                            <div class="font-bold text-gray-900 text-sm sm:text-base">Area Kuliner</div>
                            <div class="text-xs sm:text-sm text-gray-600">Banyak tempat makan</div>
                        </div>
                    </div>
                </div>

                {{-- Google Maps --}}
                <div class="relative bg-gray-100 rounded-xl overflow-hidden shadow-lg h-64 sm:h-80 lg:h-full min-h-[320px]">
                    <div class="relative bg-gray-100 rounded-xl overflow-hidden shadow-lg h-64 sm:h-80 lg:h-full min-h-[320px]">
                        {!! $mapsEmbed->maps_embed !!}
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- CTA SECTION --}}
    <section class="py-12 sm:py-16 bg-gradient-to-r from-blue-600 to-blue-700 text-white" id="kontak">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-3 sm:mb-4">Tertarik untuk Ngekos di Sini?</h2>
            <p class="text-base sm:text-lg lg:text-xl mb-6 sm:mb-8 opacity-90">Hubungi kami sekarang untuk info lebih lanjut atau jadwalkan kunjungan</p>
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
                <a href="https://wa.me/6283841806357?text=Halo,%20saya%20ingin%20info%20lengkap%20tentang%20kos"
                   class="inline-flex items-center justify-center bg-white text-blue-600 px-6 sm:px-8 py-3 sm:py-4 rounded-xl font-bold hover:bg-gray-100 transition-all shadow-lg text-sm sm:text-base">
                    <i class="fab fa-whatsapp mr-2 text-lg sm:text-xl text-green-600"></i> Chat WhatsApp
                </a>
                <a href="tel:+6283841806357"
                   class="inline-flex items-center justify-center border-2 border-white text-white px-6 sm:px-8 py-3 sm:py-4 rounded-xl font-bold hover:bg-white hover:text-blue-600 transition-all text-sm sm:text-base">
                    <i class="fas fa-phone mr-2"></i> Telepon
                </a>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    @include('landing.footer')

    {{-- Back to Top Button --}}
    <button id="backToTop" onclick="scrollToTop()" class="w-12 h-12 rounded-full bg-blue-600 text-white shadow-xl hover:bg-blue-700 transition-all flex items-center justify-center">
        <i class="fas fa-arrow-up text-lg"></i>
    </button>

    {{-- JavaScript --}}
    <script>
        let isAvailableFilter = false;
        const itemsPerPage = 9;
        let currentPage = 1;

        // Initialize
        window.addEventListener('DOMContentLoaded', function() {
            updateCount();
            checkScroll();
        });

        // Search function
        function searchRooms() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const cards = document.querySelectorAll('.room-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const name = card.getAttribute('data-name');
                const status = card.getAttribute('data-status');

                const matchSearch = name.includes(searchTerm);
                const matchFilter = !isAvailableFilter || status === 'available';

                if (matchSearch && matchFilter) {
                    card.classList.remove('hidden-filter');
                    visibleCount++;
                } else {
                    card.classList.add('hidden-filter');
                }
            });

            updateCount();
            showNoResults(visibleCount === 0);
        }

        // Filter available only
        function filterAvailable() {
            isAvailableFilter = !isAvailableFilter;
            const btn = document.getElementById('btnAvailable');

            if (isAvailableFilter) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }

            searchRooms(); // Reapply search with new filter
        }

        // Sort rooms
        function sortRooms(sortBy) {
            const container = document.getElementById('roomsContainer');
            const cards = Array.from(document.querySelectorAll('.room-card'));

            cards.sort((a, b) => {
                if (sortBy === 'name-asc') {
                    return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
                } else if (sortBy === 'price-asc') {
                    return parseInt(a.getAttribute('data-price')) - parseInt(b.getAttribute('data-price'));
                }
            });

            cards.forEach(card => container.appendChild(card));
        }

        // Update count display
        function updateCount() {
            const cards = document.querySelectorAll('.room-card');
            const visibleCards = document.querySelectorAll('.room-card:not(.hidden-filter)');

            document.getElementById('displayCount').textContent = visibleCards.length;
            document.getElementById('totalCount').textContent = cards.length;
        }

        // Show/hide no results
        function showNoResults(show) {
            const noResults = document.getElementById('noResults');
            const container = document.getElementById('roomsContainer');

            if (show) {
                noResults.classList.remove('hidden');
                container.classList.add('hidden');
            } else {
                noResults.classList.add('hidden');
                container.classList.remove('hidden');
            }
        }

        // Load more (if needed for pagination)
        function loadMore() {
            currentPage++;
            // Implementation depends on backend pagination
            alert('Load more functionality - integrate with your backend');
        }

        // Back to top functionality
        window.addEventListener('scroll', checkScroll);

        function checkScroll() {
            const backToTop = document.getElementById('backToTop');
            if (window.pageYOffset > 300) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        }

        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    </script>
</body>
</html>
