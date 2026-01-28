<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $room->name }} - RumahKos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        html { scroll-behavior: smooth; }
        .gradient-text {
            background: linear-gradient(to right, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        @keyframes slide-in {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        .animate-slide-in {
            animation: slide-in 0.3s ease-out;
        }
        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="bg-gray-50">

    {{-- Navbar --}}
    @include('landing.navbar')

    {{-- Alert Messages --}}
    @if(session('success'))
    <div class="fixed top-20 right-4 z-50 max-w-md animate-slide-in">
        <div class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3">
            <i class="fas fa-check-circle text-xl"></i>
            <span class="flex-1">{{ session('success') }}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="fixed top-20 right-4 z-50 max-w-md animate-slide-in">
        <div class="bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-xl"></i>
            <span class="flex-1">{{ session('error') }}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    {{-- BREADCRUMB --}}
    <section class="bg-white border-b pt-20 sm:pt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4">
            <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-600 overflow-x-auto">
                <a href="{{ route('landing') }}" class="hover:text-blue-600 transition whitespace-nowrap">
                    <i class="fas fa-home"></i> Beranda
                </a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('landing') }}#kamar" class="hover:text-blue-600 transition whitespace-nowrap">Kamar</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-gray-900 font-medium truncate">{{ $room->name }}</span>
            </div>
        </div>
    </section>

    {{-- ROOM DETAIL HERO --}}
    <section class="py-6 sm:py-8 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Room Title & Status --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div class="flex-1">
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-2">{{ $room->name }}</h1>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 text-gray-600 text-sm">
                        <span class="flex items-center gap-1">
                            <i class="fas fa-building"></i>
                            <span>{{ $room->property->name }}</span>
                        </span>
                        <span class="hidden sm:inline text-gray-300">|</span>
                        <span class="flex items-center gap-1">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $room->property->address ?? 'Bandung' }}</span>
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-3 flex-wrap">
                    <div class="flex items-center gap-2 bg-yellow-50 px-3 py-2 rounded-lg border border-yellow-200">
                        <i class="fas fa-star text-yellow-400"></i>
                        <span class="font-bold text-gray-900">{{ number_format($averageRating, 1) }}</span>
                        <span class="text-sm text-gray-600">({{ $totalReviews }})</span>
                    </div>
                    @if($room->status == 'available')
                        <span class="inline-block bg-green-500 text-white text-sm font-bold px-4 py-2 rounded-full shadow-lg">
                            <i class="fas fa-check-circle mr-1"></i>Tersedia
                        </span>
                    @elseif($room->status == 'occupied')
                        <span class="inline-block bg-red-500 text-white text-sm font-bold px-4 py-2 rounded-full shadow-lg">
                            <i class="fas fa-times-circle mr-1"></i>Terisi
                        </span>
                    @else
                        <span class="inline-block bg-orange-500 text-white text-sm font-bold px-4 py-2 rounded-full shadow-lg">
                            <i class="fas fa-tools mr-1"></i>Sedang Perbaikan
                        </span>
                    @endif
                </div>
            </div>

            {{-- Image Gallery --}}
            <div class="mb-6 sm:mb-8">
                @if($room->image)
                    <img src="{{ asset('storage/' . $room->image) }}"
                         alt="{{ $room->name }}"
                         class="w-full h-64 sm:h-80 lg:h-[500px] object-cover rounded-xl sm:rounded-2xl shadow-lg">
                @else
                    <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=1200&h=800&fit=crop"
                         alt="{{ $room->name }}"
                         class="w-full h-64 sm:h-80 lg:h-[500px] object-cover rounded-xl sm:rounded-2xl shadow-lg">
                @endif
            </div>

            {{-- Main Content Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

                {{-- Left Column: Details --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Room Info --}}
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-md p-4 sm:p-6">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">Informasi Kamar</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            @if($room->size)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-ruler-combined text-blue-600 text-lg sm:text-xl"></i>
                                </div>
                                <div>
                                    <div class="text-xs sm:text-sm text-gray-600">Ukuran</div>
                                    <div class="font-semibold text-gray-900 text-sm sm:text-base">{{ $room->size }}</div>
                                </div>
                            </div>
                            @endif

                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-layer-group text-blue-600 text-lg sm:text-xl"></i>
                                </div>
                                <div>
                                    <div class="text-xs sm:text-sm text-gray-600">Lantai</div>
                                    <div class="font-semibold text-gray-900 text-sm sm:text-base">Lantai {{ $room->floor }}</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-calendar-alt text-blue-600 text-lg sm:text-xl"></i>
                                </div>
                                <div>
                                    <div class="text-xs sm:text-sm text-gray-600">Periode Sewa</div>
                                    <div class="font-semibold text-gray-900 text-sm sm:text-base">
                                        @if($room->billing_cycle == 'daily') Harian
                                        @elseif($room->billing_cycle == 'weekly') Mingguan
                                        @elseif($room->billing_cycle == 'monthly') Bulanan
                                        @else Tahunan
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-star text-blue-600 text-lg sm:text-xl"></i>
                                </div>
                                <div>
                                    <div class="text-xs sm:text-sm text-gray-600">Rating</div>
                                    <div class="font-semibold text-gray-900 text-sm sm:text-base">
                                        {{ number_format($averageRating, 1) }}
                                        <span class="text-xs sm:text-sm text-gray-500">({{ $totalReviews }} ulasan)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    @if($room->description)
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-md p-4 sm:p-6">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4">Deskripsi</h2>
                        <p class="text-gray-700 leading-relaxed text-sm sm:text-base">{{ $room->description }}</p>
                    </div>
                    @endif

                    {{-- Facilities --}}
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-md p-4 sm:p-6">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">Fasilitas Kamar</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                            @if($room->facilities->count() > 0)
                                @foreach($room->facilities as $facility)
                                <div class="flex items-center gap-2 sm:gap-3 p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                    <i class="{{ $facility->icon }} text-blue-600 text-lg sm:text-xl flex-shrink-0"></i>
                                    <span class="font-medium text-gray-900 text-sm sm:text-base">{{ $facility->name }}</span>
                                </div>
                                @endforeach
                            @else
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                    <i class="fas fa-bed text-gray-600 text-xl"></i>
                                    <span class="font-medium text-gray-700">Kasur</span>
                                </div>
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                    <i class="fas fa-door-open text-gray-600 text-xl"></i>
                                    <span class="font-medium text-gray-700">Lemari</span>
                                </div>
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                    <i class="fas fa-bath text-gray-600 text-xl"></i>
                                    <span class="font-medium text-gray-700">Kamar Mandi</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Reviews Section with PHP-based Filter --}}
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-md p-4 sm:p-6 lg:p-8">
                        {{-- Header --}}
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                            <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">
                                Ulasan Penyewa
                            </h2>

                            @if($canReview)
                                <button
                                    onclick="document.getElementById('reviewModal').classList.remove('hidden')"
                                    class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all shadow-sm hover:shadow-md text-sm font-semibold">
                                    <i class="fas fa-plus mr-2"></i>
                                    <span>Tulis Ulasan</span>
                                </button>
                            @else
                                <div class="text-sm text-gray-500 max-w-xs sm:text-right">
                                    {{ $reviewMessage }}
                                </div>
                            @endif
                        </div>

                        {{-- Enhanced Rating Card with Category Breakdown --}}
                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 p-4 sm:p-6 bg-gradient-to-br from-blue-50 via-white to-purple-50 rounded-xl mb-8 border border-blue-100">

                            {{-- Left: Overall Score --}}
                            <div class="text-center py-4">
                                <div class="text-5xl sm:text-6xl font-bold text-blue-600 mb-2">
                                    {{ number_format($averageRating, 1) }}
                                </div>

                                <div class="flex justify-center gap-1 mb-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star text-lg {{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>

                                <div class="text-sm text-gray-600 mb-4">
                                    Berdasarkan <span class="font-semibold text-gray-900">{{ $totalReviews }}</span> ulasan
                                </div>

                                {{-- Star Distribution --}}
                                <div class="space-y-2 max-w-sm mx-auto">
                                    @for($i = 5; $i >= 1; $i--)
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-1 w-12 text-xs font-medium text-gray-700">
                                            <span>{{ $i }}</span>
                                            <i class="fas fa-star text-xs text-yellow-400"></i>
                                        </div>

                                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-full transition-all duration-500"
                                                 style="width: {{ $ratingDistribution[$i] }}%">
                                            </div>
                                        </div>

                                        <div class="text-xs text-gray-600 w-10 text-right font-medium">
                                            {{ $ratingDistribution[$i] }}%
                                        </div>
                                    </div>
                                    @endfor
                                </div>
                            </div>

                            {{-- Right: Category Ratings --}}
                            <div class="py-4 border-t xl:border-t-0 xl:border-l border-gray-200 xl:pl-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 text-center xl:text-left">Rating Berdasarkan Kategori</h3>
                                <div class="space-y-3">
                                    {{-- Kebersihan --}}
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-broom text-blue-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-sm font-semibold text-gray-700">Kebersihan</span>
                                                <span class="text-sm font-bold text-gray-900">{{ number_format($categoryRatings['cleanliness'] ?? 0, 1) }}</span>
                                            </div>
                                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-gradient-to-r from-blue-400 to-blue-600 rounded-full transition-all duration-500"
                                                     style="width: {{ ($categoryRatings['cleanliness'] ?? 0) * 20 }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Fasilitas --}}
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-couch text-purple-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-sm font-semibold text-gray-700">Fasilitas</span>
                                                <span class="text-sm font-bold text-gray-900">{{ number_format($categoryRatings['facilities'] ?? 0, 1) }}</span>
                                            </div>
                                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-gradient-to-r from-purple-400 to-purple-600 rounded-full transition-all duration-500"
                                                     style="width: {{ ($categoryRatings['facilities'] ?? 0) * 20 }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Pelayanan --}}
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-user-tie text-green-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-sm font-semibold text-gray-700">Pelayanan</span>
                                                <span class="text-sm font-bold text-gray-900">{{ number_format($categoryRatings['service'] ?? 0, 1) }}</span>
                                            </div>
                                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-gradient-to-r from-green-400 to-green-600 rounded-full transition-all duration-500"
                                                     style="width: {{ ($categoryRatings['service'] ?? 0) * 20 }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Lokasi --}}
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-map-marker-alt text-orange-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-sm font-semibold text-gray-700">Lokasi</span>
                                                <span class="text-sm font-bold text-gray-900">{{ number_format($categoryRatings['location'] ?? 0, 1) }}</span>
                                            </div>
                                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-gradient-to-r from-orange-400 to-orange-600 rounded-full transition-all duration-500"
                                                     style="width: {{ ($categoryRatings['location'] ?? 0) * 20 }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Harga --}}
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-tag text-red-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-sm font-semibold text-gray-700">Harga</span>
                                                <span class="text-sm font-bold text-gray-900">{{ number_format($categoryRatings['price'] ?? 0, 1) }}</span>
                                            </div>
                                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-gradient-to-r from-red-400 to-red-600 rounded-full transition-all duration-500"
                                                     style="width: {{ ($categoryRatings['price'] ?? 0) * 20 }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- PHP-based Filter & Sort Form --}}
                        <form method="GET" action="{{ route('rooms.detail', $room->id) }}" class="flex flex-col sm:flex-row gap-3 mb-6 pb-6 border-b border-gray-200">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Filter Rating</label>
                                <select name="filter_rating"
                                        onchange="this.form.submit()"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option value="all" {{ request('filter_rating') == 'all' || !request('filter_rating') ? 'selected' : '' }}>Semua Rating</option>
                                    <option value="5" {{ request('filter_rating') == '5' ? 'selected' : '' }}>5 Bintang</option>
                                    <option value="4" {{ request('filter_rating') == '4' ? 'selected' : '' }}>4 Bintang</option>
                                    <option value="3" {{ request('filter_rating') == '3' ? 'selected' : '' }}>3 Bintang</option>
                                    <option value="2" {{ request('filter_rating') == '2' ? 'selected' : '' }}>2 Bintang</option>
                                    <option value="1" {{ request('filter_rating') == '1' ? 'selected' : '' }}>1 Bintang</option>
                                </select>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                                <select name="sort_by"
                                        onchange="this.form.submit()"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option value="newest" {{ request('sort_by') == 'newest' || !request('sort_by') ? 'selected' : '' }}>Terbaru</option>
                                    <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                                    <option value="highest" {{ request('sort_by') == 'highest' ? 'selected' : '' }}>Rating Tertinggi</option>
                                    <option value="lowest" {{ request('sort_by') == 'lowest' ? 'selected' : '' }}>Rating Terendah</option>
                                </select>
                            </div>
                        </form>

                        {{-- Reviews List --}}
                        <div class="space-y-6">
                            @forelse($filteredReviews as $review)
                            <div class="border-b border-gray-100 pb-6 last:border-0 last:pb-0">
                                <div class="flex items-start gap-3 sm:gap-4">
                                    {{-- Avatar --}}
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center flex-shrink-0 shadow-md">
                                        <span class="text-white font-bold text-sm sm:text-base">
                                            {{ strtoupper(substr($review->user->name ?? 'A', 0, 1)) }}
                                        </span>
                                    </div>

                                    {{-- Content --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-2">
                                            <div>
                                                <div class="font-semibold text-gray-900 text-sm sm:text-base">
                                                    {{ $review->user->name ?? 'Anonim' }}
                                                </div>
                                                <div class="flex items-center gap-1 mt-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star text-xs {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                                    @endfor
                                                </div>
                                            </div>

                                            <span class="text-xs sm:text-sm text-gray-500">
                                                {{ $review->created_at->diffForHumans() }}
                                            </span>
                                        </div>

                                        <p class="text-gray-700 text-sm sm:text-base leading-relaxed mb-3">
                                            {{ $review->comment }}
                                        </p>

                                        {{-- Category Ratings (if available) --}}
                                        @if($review->category_ratings)
                                        @php
                                            $catRatings = is_string($review->category_ratings)
                                                ? json_decode($review->category_ratings, true)
                                                : $review->category_ratings;
                                        @endphp
                                        <div class="flex flex-wrap gap-2 mt-3">
                                            @if(isset($catRatings['cleanliness']) && $catRatings['cleanliness'] > 0)
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs">
                                                <i class="fas fa-broom"></i> {{ number_format($catRatings['cleanliness'], 1) }}
                                            </span>
                                            @endif
                                            @if(isset($catRatings['facilities']) && $catRatings['facilities'] > 0)
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-purple-50 text-purple-700 rounded text-xs">
                                                <i class="fas fa-couch"></i> {{ number_format($catRatings['facilities'], 1) }}
                                            </span>
                                            @endif
                                            @if(isset($catRatings['service']) && $catRatings['service'] > 0)
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-50 text-green-700 rounded text-xs">
                                                <i class="fas fa-user-tie"></i> {{ number_format($catRatings['service'], 1) }}
                                            </span>
                                            @endif
                                            @if(isset($catRatings['location']) && $catRatings['location'] > 0)
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-orange-50 text-orange-700 rounded text-xs">
                                                <i class="fas fa-map-marker-alt"></i> {{ number_format($catRatings['location'], 1) }}
                                            </span>
                                            @endif
                                            @if(isset($catRatings['price']) && $catRatings['price'] > 0)
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-50 text-red-700 rounded text-xs">
                                                <i class="fas fa-tag"></i> {{ number_format($catRatings['price'], 1) }}
                                            </span>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-12">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-{{ request('filter_rating') && request('filter_rating') != 'all' ? 'filter' : 'comments' }} text-3xl text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                    {{ request('filter_rating') && request('filter_rating') != 'all' ? 'Tidak Ada Ulasan' : 'Belum Ada Ulasan' }}
                                </h3>
                                <p class="text-gray-500 text-sm">
                                    {{ request('filter_rating') && request('filter_rating') != 'all'
                                        ? 'Tidak ada ulasan yang sesuai dengan filter Anda'
                                        : 'Jadilah yang pertama memberikan ulasan untuk kamar ini!' }}
                                </p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                </div>

                {{-- Right Column: Booking Card (STICKY) --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 lg:sticky lg:top-24">

                        {{-- Price --}}
                        <div class="mb-4 sm:mb-6 p-4 sm:p-6 bg-gradient-to-br from-blue-50 to-purple-50 rounded-xl border border-blue-100">
                            <div class="text-2xl sm:text-3xl font-bold text-blue-600">
                                Rp {{ number_format($room->price, 0, ',', '.') }}
                            </div>
                            <div class="text-gray-600 mt-1 text-sm sm:text-base">
                                per
                                @if($room->billing_cycle == 'daily') hari
                                @elseif($room->billing_cycle == 'weekly') minggu
                                @elseif($room->billing_cycle == 'monthly') bulan
                                @else tahun
                                @endif
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="space-y-3">
                            @if($room->status == 'available')
                                @auth('tenant')
                                    <a href="{{ route('tenant.booking.create', $room->id) }}"
                                    class="block w-full text-center bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 sm:py-4 rounded-xl font-bold hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg hover:shadow-xl text-sm sm:text-base">
                                        <i class="fas fa-credit-card mr-2"></i>Pesan Sekarang
                                    </a>
                                @else
                                    <a href="{{ route('tenant.login') }}"
                                    class="block w-full text-center bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 sm:py-4 rounded-xl font-bold hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg hover:shadow-xl text-sm sm:text-base">
                                        <i class="fas fa-sign-in-alt mr-2"></i>Login untuk Pesan
                                    </a>
                                @endauth

                                <a href="https://wa.me/6283841806357?text=Halo,%20saya%20tertarik%20dengan%20{{ urlencode($room->name) }}%20di%20{{ urlencode($room->property->name) }}"
                                target="_blank"
                                class="block w-full text-center bg-green-500 text-white px-6 py-3 sm:py-4 rounded-xl font-bold hover:bg-green-600 transition-all shadow-md hover:shadow-lg text-sm sm:text-base">
                                    <i class="fab fa-whatsapp mr-2"></i>Tanya via WhatsApp
                                </a>
                            @else
                                <button disabled
                                        class="block w-full text-center bg-gray-200 text-gray-400 px-6 py-3 sm:py-4 rounded-xl font-bold cursor-not-allowed text-sm sm:text-base">
                                    <i class="fas fa-ban mr-2"></i>{{ $room->status == 'occupied' ? 'Tidak Tersedia' : 'Sedang Perbaikan' }}
                                </button>
                            @endif
                        </div>

                        {{-- Additional Info --}}
                        <div class="mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-gray-200 space-y-2 sm:space-y-3 text-xs sm:text-sm text-gray-600">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-shield-alt text-blue-600 flex-shrink-0"></i>
                                <span>Pembayaran aman & terpercaya</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-undo text-blue-600 flex-shrink-0"></i>
                                <span>Bisa refund sesuai ketentuan</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-headset text-blue-600 flex-shrink-0"></i>
                                <span>Customer service 24/7</span>
                            </div>
                        </div>

                        {{-- Contact Info --}}
                        <div class="mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-gray-200">
                            <div class="text-sm font-semibold text-gray-900 mb-2">Butuh bantuan?</div>
                            <a href="tel:+6283841806357" class="text-blue-600 hover:text-blue-700 text-sm">
                                <i class="fas fa-phone mr-1"></i>+62 838-4180-6357
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    {{-- Similar Rooms --}}
    @if($similarRooms->count() > 0)
    <section class="py-12 sm:py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6 sm:mb-8">Kamar Lainnya</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach($similarRooms as $similarRoom)
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-md hover:shadow-xl transition-all overflow-hidden group">
                        <div class="relative h-48">
                            @if($similarRoom->image)
                                <img src="{{ asset('storage/' . $similarRoom->image) }}"
                                     alt="{{ $similarRoom->name }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @else
                                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=600&h=400&fit=crop"
                                     alt="{{ $similarRoom->name }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @endif
                        </div>
                        <div class="p-4 sm:p-5">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-2 line-clamp-1">{{ $similarRoom->name }}</h3>
                            <div class="text-lg sm:text-xl font-bold text-blue-600 mb-3">
                                Rp {{ number_format($similarRoom->price, 0, ',', '.') }}
                            </div>
                            <a href="{{ route('rooms.detail', $similarRoom->id) }}"
                               class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm sm:text-base">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Review Modal (Simple JS for open/close only) --}}
    <div id="reviewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 overflow-y-auto"
         onclick="if(event.target === this) this.classList.add('hidden')">

        <div class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl transform transition-all animate-fade-in my-8 max-h-[90vh] overflow-y-auto custom-scrollbar"
             onclick="event.stopPropagation()">

            {{-- Header --}}
            <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white z-10">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900">Tulis Ulasan</h3>
                    <p class="text-sm text-gray-500 mt-1">Bagikan pengalaman Anda menginap di sini</p>
                </div>
                <button
                    onclick="document.getElementById('reviewModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            {{-- Form --}}
            <form action="{{ route('room.review.store', $room->id) }}" method="POST" class="p-6">
                @csrf

                {{-- Overall Rating --}}
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <label class="block text-base font-bold text-gray-900 mb-3">
                        Rating Keseluruhan <span class="text-red-500">*</span>
                    </label>

                    <div class="flex gap-2">
                        @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer">
                            <input type="radio" name="rating" value="{{ $i }}" required class="sr-only peer">
                            <i class="fas fa-star text-4xl sm:text-5xl text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-300 transition-colors"></i>
                        </label>
                        @endfor
                    </div>

                    @error('rating')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Category Ratings --}}
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <label class="block text-base font-bold text-gray-900 mb-4">
                        Rating Per Kategori <span class="text-gray-500 text-sm font-normal">(Opsional)</span>
                    </label>

                    <div class="space-y-4">
                        {{-- Kebersihan --}}
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-broom text-blue-600"></i>
                                    <span class="font-semibold text-gray-900 text-sm">Kebersihan</span>
                                </div>
                                <span class="text-sm font-bold text-blue-600" id="cleanliness-value">0</span>
                            </div>
                            <input type="range" name="category_ratings[cleanliness]" min="0" max="5" step="0.5" value="0"
                                   class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600"
                                   oninput="document.getElementById('cleanliness-value').textContent = this.value">
                        </div>

                        {{-- Fasilitas --}}
                        <div class="p-4 bg-purple-50 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-couch text-purple-600"></i>
                                    <span class="font-semibold text-gray-900 text-sm">Fasilitas</span>
                                </div>
                                <span class="text-sm font-bold text-purple-600" id="facilities-value">0</span>
                            </div>
                            <input type="range" name="category_ratings[facilities]" min="0" max="5" step="0.5" value="0"
                                   class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-purple-600"
                                   oninput="document.getElementById('facilities-value').textContent = this.value">
                        </div>

                        {{-- Pelayanan --}}
                        <div class="p-4 bg-green-50 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-user-tie text-green-600"></i>
                                    <span class="font-semibold text-gray-900 text-sm">Pelayanan</span>
                                </div>
                                <span class="text-sm font-bold text-green-600" id="service-value">0</span>
                            </div>
                            <input type="range" name="category_ratings[service]" min="0" max="5" step="0.5" value="0"
                                   class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-green-600"
                                   oninput="document.getElementById('service-value').textContent = this.value">
                        </div>

                        {{-- Lokasi --}}
                        <div class="p-4 bg-orange-50 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-orange-600"></i>
                                    <span class="font-semibold text-gray-900 text-sm">Lokasi</span>
                                </div>
                                <span class="text-sm font-bold text-orange-600" id="location-value">0</span>
                            </div>
                            <input type="range" name="category_ratings[location]" min="0" max="5" step="0.5" value="0"
                                   class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-orange-600"
                                   oninput="document.getElementById('location-value').textContent = this.value">
                        </div>

                        {{-- Harga --}}
                        <div class="p-4 bg-red-50 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-tag text-red-600"></i>
                                    <span class="font-semibold text-gray-900 text-sm">Harga</span>
                                </div>
                                <span class="text-sm font-bold text-red-600" id="price-value">0</span>
                            </div>
                            <input type="range" name="category_ratings[price]" min="0" max="5" step="0.5" value="0"
                                   class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-red-600"
                                   oninput="document.getElementById('price-value').textContent = this.value">
                        </div>
                    </div>
                </div>

                {{-- Comment Input --}}
                <div class="mb-6">
                    <label class="block text-base font-bold text-gray-900 mb-2">
                        Ulasan Anda <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        name="comment"
                        id="commentTextarea"
                        rows="5"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none text-sm sm:text-base"
                        placeholder="Ceritakan pengalaman Anda tentang kamar ini... (minimal 10 karakter)"
                        required
                        minlength="10"
                        maxlength="1000"
                        oninput="document.getElementById('charCount').textContent = this.value.length + '/1000'"></textarea>

                    <div class="flex items-center justify-between mt-2">
                        <p class="text-xs text-gray-500">Minimal 10 karakter, maksimal 1000 karakter</p>
                        <span id="charCount" class="text-xs text-gray-500">0/1000</span>
                    </div>

                    @error('comment')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col-reverse sm:flex-row gap-3">
                    <button
                        type="button"
                        onclick="document.getElementById('reviewModal').classList.add('hidden')"
                        class="w-full sm:w-auto px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-all">
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="w-full sm:flex-1 bg-blue-600 text-white py-3 px-6 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg">
                        <i class="fas fa-paper-plane mr-2"></i>Kirim Ulasan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Footer --}}
    @include('landing.footer')

    {{-- Minimal JS for auto-hide alerts only --}}
    <script>
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.animate-slide-in');
        alerts.forEach(alert => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        });
    }, 5000);
    </script>

</body>
</html>
