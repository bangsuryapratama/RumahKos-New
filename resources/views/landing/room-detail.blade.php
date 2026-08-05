<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    @php
        $roomImg = $room->image ? (str_starts_with($room->image, 'http') ? $room->image : asset('storage/' . $room->image)) : asset('images/room-default.webp');
        $schemaJson = \App\Services\SeoService::getRoomSchema($room, $globalProperty);
        $whatsappNum = preg_replace('/[^0-9]/', '', $contact->whatsapp ?? '6281234567890');
        if (str_starts_with($whatsappNum, '0')) $whatsappNum = '62' . substr($whatsappNum, 1);
        $isAvailable = $room->status === 'available';
    @endphp

    <!-- SEO & Metadata Component -->
    @include('components.seo-head', [
        'title' => $room->name . ' - Sewa Kost Eksklusif',
        'description' => $room->description ?: "Sewa kamar premium {$room->name} di Cemara Residence Bandung. Dilengkapi AC, WiFi 100Mbps, Water Heater, Smart TV, dan kamar mandi dalam.",
        'image' => $roomImg,
        'schemaJson' => $schemaJson
    ])

    <!-- Meta Pixel ViewContent Event -->
    @include('components.meta-pixel', [
        'eventName' => 'ViewContent',
        'eventData' => [
            'content_name' => $room->name,
            'content_ids' => [(string) $room->id],
            'content_type' => 'product',
            'value' => (float) $room->price,
            'currency' => 'IDR'
        ]
    ])

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Stylesheets via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #FAFAFC; color: #0F172A; }
        .font-heading { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="antialiased selection:bg-amber-500 selection:text-slate-950">

    <!-- Header Navigation -->
    @include('landing.navbar')

    <!-- BREADCRUMBS -->
    <div class="bg-white border-b border-slate-100 py-3.5 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto flex items-center gap-2 text-xs font-semibold text-slate-500">
            <a href="{{ route('landing') }}" class="hover:text-amber-600 transition-colors">Beranda</a>
            <i class="fas fa-chevron-right text-[10px] text-slate-300"></i>
            <a href="{{ route('landing') }}#kamar" class="hover:text-amber-600 transition-colors">Kamar & Suite</a>
            <i class="fas fa-chevron-right text-[10px] text-slate-300"></i>
            <span class="text-slate-900 font-bold truncate">{{ $room->name }}</span>
        </div>
    </div>

    <!-- MAIN ROOM CONTENT -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        
        <!-- ROOM TITLE & ACTION BAR -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-black uppercase tracking-wider">
                        Lantai {{ $room->floor }} • {{ $room->size ?? 24 }} m²
                    </span>
                    @if($isAvailable)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Tersedia Siap Huni
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-100 text-rose-800 text-xs font-bold">
                            <i class="fas fa-lock text-[10px]"></i> Terisi
                        </span>
                    @endif
                </div>
                <h1 class="text-2xl sm:text-4xl font-extrabold font-heading text-slate-900 tracking-tight">
                    {{ $room->name }}
                </h1>
                <p class="text-sm text-slate-500 mt-1 flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-amber-500"></i>
                    <span>{{ $globalProperty->address ?? 'Jl. Hegarmanah Kulon No. 42, Setiabudi, Bandung' }}</span>
                </p>
            </div>

            <!-- Share & Wishlist -->
            <div class="flex items-center gap-3">
                <button onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan kamar berhasil disalin ke clipboard!');" 
                        class="px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-50 shadow-sm flex items-center gap-2">
                    <i class="fas fa-share-alt text-slate-400"></i> Bagikan
                </button>
            </div>
        </div>

        <!-- HOTEL MOSAIC GALLERY GRID -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 rounded-3xl overflow-hidden shadow-xl mb-12 h-[340px] sm:h-[480px]">
            <!-- Main Featured Image (2 cols on desktop) -->
            <div class="md:col-span-2 relative h-full group overflow-hidden bg-slate-900 cursor-pointer">
                <img src="{{ $roomImg }}" alt="{{ $room->name }}" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                <div class="absolute bottom-4 left-4 text-white text-xs font-bold px-3 py-1.5 rounded-xl bg-black/60 backdrop-blur-md">
                    <i class="fas fa-camera mr-1"></i> Foto Utama Kamar
                </div>
            </div>

            <!-- Secondary Detail 1 -->
            <div class="hidden md:block relative h-full group overflow-hidden bg-slate-800">
                <img src="https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=600&q=80" alt="Kamar Mandi Dalam" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                <div class="absolute bottom-4 left-4 text-white text-xs font-bold px-3 py-1.5 rounded-xl bg-black/60 backdrop-blur-md">
                    <i class="fas fa-bath mr-1"></i> Kamar Mandi Dalam
                </div>
            </div>

            <!-- Secondary Detail 2 -->
            <div class="hidden md:block relative h-full group overflow-hidden bg-slate-800">
                <img src="https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=600&q=80" alt="Area Kerja & Santai" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                <div class="absolute bottom-4 left-4 text-white text-xs font-bold px-3 py-1.5 rounded-xl bg-black/60 backdrop-blur-md">
                    <i class="fas fa-laptop mr-1"></i> Area Kerja & Meja
                </div>
            </div>
        </div>

        <!-- TWO COLUMN LAYOUT: ROOM SPECS & STICKY BOOKING CALCULATOR -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <!-- LEFT COLUMN (7 COLS): SPECS, AMENITIES, REVIEWS -->
            <div class="lg:col-span-7 space-y-10">
                
                <!-- KEY SPECS ROW -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 p-5 rounded-3xl bg-white border border-slate-200/80 shadow-sm text-center">
                    <div class="p-2 border-r border-slate-100 last:border-0">
                        <i class="fas fa-vector-square text-amber-500 text-lg mb-1"></i>
                        <div class="text-xs text-slate-400 font-semibold">Ukuran Ruang</div>
                        <div class="text-sm font-black text-slate-900 font-heading">{{ $room->size ?? 24 }} m²</div>
                    </div>
                    <div class="p-2 border-r border-slate-100 last:border-0">
                        <i class="fas fa-layer-group text-amber-500 text-lg mb-1"></i>
                        <div class="text-xs text-slate-400 font-semibold">Posisi Lantai</div>
                        <div class="text-sm font-black text-slate-900 font-heading">Lantai {{ $room->floor }}</div>
                    </div>
                    <div class="p-2 border-r border-slate-100 last:border-0">
                        <i class="fas fa-user-friends text-amber-500 text-lg mb-1"></i>
                        <div class="text-xs text-slate-400 font-semibold">Kapasitas</div>
                        <div class="text-sm font-black text-slate-900 font-heading">1 - 2 Tamu</div>
                    </div>
                    <div class="p-2">
                        <i class="fas fa-bed text-amber-500 text-lg mb-1"></i>
                        <div class="text-xs text-slate-400 font-semibold">Tipe Kasur</div>
                        <div class="text-sm font-black text-slate-900 font-heading">Queen Size</div>
                    </div>
                </div>

                <!-- DESCRIPTION -->
                <div class="space-y-4">
                    <h2 class="text-xl font-bold font-heading text-slate-900">Deskripsi Kamar</h2>
                    <div class="text-sm text-slate-600 leading-relaxed space-y-3">
                        <p>
                            {{ $room->description ?: 'Kamar eksklusif tipe ' . $room->name . ' dirancang untuk memberikan kenyamanan istirahat maksimal dan privasi tinggi. Dilengkapi dengan perabotan lengkap berkualitas premium, pencahayaan alami yang lapang, serta sirkulasi udara yang menyegarkan.' }}
                        </p>
                        <p>
                            Sangat cocok bagi profesional muda, ekspatriat, maupun mahasiswa yang menginginkan lingkungan tinggal yang tenang, higienis, dan terkelola secara profesional.
                        </p>
                    </div>
                </div>

                <!-- 5-STAR ROOM FACILITIES -->
                <div class="space-y-4 pt-4 border-t border-slate-200/80">
                    <h2 class="text-xl font-bold font-heading text-slate-900">Fasilitas Dalam Kamar</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @forelse($room->facilities as $facility)
                            <div class="flex items-center gap-3 p-3.5 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
                                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm font-bold shrink-0">
                                    <i class="{{ $facility->icon ?? 'fas fa-check' }}"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-800">{{ $facility->name }}</span>
                            </div>
                        @empty
                            <div class="col-span-3 text-xs text-slate-400">AC, Water Heater, Smart TV, WiFi, Meja & Kursi Kerja, Lemari Pakaian.</div>
                        @endforelse
                    </div>
                </div>

                <!-- HOUSE RULES & CHECK-IN POLICY -->
                <div class="space-y-4 pt-4 border-t border-slate-200/80">
                    <h2 class="text-xl font-bold font-heading text-slate-900">Ketentuan & Tata Tertib</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs text-slate-600">
                        <div class="flex items-start gap-2.5 p-3 rounded-2xl bg-slate-50 border border-slate-200/60">
                            <i class="fas fa-clock text-amber-500 mt-0.5 shrink-0"></i>
                            <div><strong>Check-in / Check-out:</strong> Masuk mulai pukul 14:00 WIB, keluar maksimal pukul 12:00 WIB.</div>
                        </div>
                        <div class="flex items-start gap-2.5 p-3 rounded-2xl bg-slate-50 border border-slate-200/60">
                            <i class="fas fa-smoking-ban text-rose-500 mt-0.5 shrink-0"></i>
                            <div><strong>Bebas Asap Rokok:</strong> Dilarang merokok di dalam kamar tidur (tersedia area khusus).</div>
                        </div>
                        <div class="flex items-start gap-2.5 p-3 rounded-2xl bg-slate-50 border border-slate-200/60">
                            <i class="fas fa-user-friends text-blue-500 mt-0.5 shrink-0"></i>
                            <div><strong>Penerimaan Tamu:</strong> Tamu luar dipersilakan bertemu di area Lobby hingga 22:00 WIB.</div>
                        </div>
                        <div class="flex items-start gap-2.5 p-3 rounded-2xl bg-slate-50 border border-slate-200/60">
                            <i class="fas fa-paw text-amber-500 mt-0.5 shrink-0"></i>
                            <div><strong>Hewan Peliharaan:</strong> Tidak diperkenankan membawa hewan peliharaan demi kenyamanan bersama.</div>
                        </div>
                    </div>
                </div>

                <!-- RATING & REVIEWS SECTION -->
                <div class="space-y-6 pt-4 border-t border-slate-200/80">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold font-heading text-slate-900">Ulasan & Reputasi Tamu</h2>
                            <p class="text-xs text-slate-500">Berdasarkan penilaian penghuni terverifikasi</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="text-2xl font-black text-slate-900 font-heading">{{ $averageRating }}</div>
                            <div class="flex text-amber-400 text-sm">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Category Breakdown -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-600">Kebersihan</span>
                            <span class="font-bold text-slate-900">{{ $categoryRatings['cleanliness'] ?? '4.9' }}/5</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-600">Fasilitas</span>
                            <span class="font-bold text-slate-900">{{ $categoryRatings['facilities'] ?? '4.8' }}/5</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-600">Pelayanan</span>
                            <span class="font-bold text-slate-900">{{ $categoryRatings['service'] ?? '4.9' }}/5</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-600">Lokasi</span>
                            <span class="font-bold text-slate-900">{{ $categoryRatings['location'] ?? '5.0' }}/5</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-600">Kesesuaian Harga</span>
                            <span class="font-bold text-slate-900">{{ $categoryRatings['price'] ?? '4.8' }}/5</span>
                        </div>
                    </div>

                    <!-- Individual Reviews List -->
                    <div class="space-y-4">
                        @forelse($filteredReviews as $rev)
                            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-slate-900 text-amber-400 font-bold text-xs flex items-center justify-center">
                                            {{ strtoupper(substr($rev->user->name ?? 'T', 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-slate-900">{{ $rev->user->name ?? 'Tamu Terverifikasi' }}</div>
                                            <div class="text-[10px] text-slate-400">{{ $rev->created_at?->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                    <div class="flex text-amber-400 text-xs">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $rev->rating ? '' : 'text-slate-200' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-xs sm:text-sm text-slate-700 italic">"{{ $rev->comment }}"</p>
                            </div>
                        @empty
                            <div class="p-6 rounded-2xl bg-white border border-slate-200 text-center text-xs text-slate-400">
                                Belum ada ulasan untuk kamar ini. Jadilah yang pertama memberikan ulasan!
                            </div>
                        @endforelse
                    </div>

                    <!-- Post Review Form (if permitted) -->
                    @if($canReview)
                        <div class="p-6 rounded-3xl bg-slate-900 text-white space-y-4">
                            <h3 class="text-base font-bold">Beri Ulasan untuk {{ $room->name }}</h3>
                            <form method="POST" action="{{ route('room.review.store', $room->id) }}" class="space-y-3">
                                @csrf
                                <div>
                                    <label class="block text-xs text-slate-300 mb-1">Rating Bintang (1 - 5)</label>
                                    <select name="rating" class="w-full bg-slate-800 border border-slate-700 rounded-xl p-2.5 text-xs text-white">
                                        <option value="5">⭐⭐⭐⭐⭐ 5 Bintang (Luar Biasa)</option>
                                        <option value="4">⭐⭐⭐⭐ 4 Bintang (Sangat Bagus)</option>
                                        <option value="3">⭐⭐⭐ 3 Bintang (Cukup)</option>
                                        <option value="2">⭐⭐ 2 Bintang (Kurang)</option>
                                        <option value="1">⭐ 1 Bintang (Buruk)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs text-slate-300 mb-1">Komentar / Pengalaman Anda</label>
                                    <textarea name="comment" rows="3" required placeholder="Tuliskan ulasan jujur Anda mengenai fasilitas dan kebersihan..." 
                                              class="w-full bg-slate-800 border border-slate-700 rounded-xl p-3 text-xs text-white placeholder-slate-500"></textarea>
                                </div>
                                <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs shadow-md">
                                    Kirim Ulasan
                                </button>
                            </form>
                        </div>
                    @endif

                </div>

            </div>

            <!-- RIGHT COLUMN (5 COLS): STICKY BOOKING CALCULATOR WIDGET -->
            <div class="lg:col-span-5">
                <div class="sticky top-28 bg-white rounded-3xl border border-slate-200 shadow-2xl p-6 sm:p-8 space-y-6">
                    
                    <!-- Price Header -->
                    <div class="flex items-baseline justify-between border-b border-slate-100 pb-5">
                        <div>
                            <div class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Harga Sewa</div>
                            <div class="text-2xl sm:text-3xl font-black text-slate-900 font-heading">
                                Rp {{ number_format($room->price, 0, ',', '.') }}
                                <span class="text-xs font-normal text-slate-500">/bulan</span>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">
                            All-Inclusive Air & WiFi
                        </span>
                    </div>

                    <!-- LEASE TERM SELECTOR -->
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Pilih Durasi Sewa
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" class="duration-btn active p-3 rounded-2xl border-2 border-amber-500 bg-amber-50/50 text-left transition-all" data-months="1" data-discount="0">
                                <div class="text-xs font-bold text-slate-900">1 Bulan</div>
                                <div class="text-[10px] text-slate-500">Standar Fleksibel</div>
                            </button>
                            <button type="button" class="duration-btn p-3 rounded-2xl border border-slate-200 hover:border-slate-300 bg-white text-left transition-all" data-months="3" data-discount="5">
                                <div class="text-xs font-bold text-slate-900">3 Bulan</div>
                                <div class="text-[10px] text-emerald-600 font-bold">Diskon 5%</div>
                            </button>
                            <button type="button" class="duration-btn p-3 rounded-2xl border border-slate-200 hover:border-slate-300 bg-white text-left transition-all" data-months="6" data-discount="10">
                                <div class="text-xs font-bold text-slate-900">6 Bulan</div>
                                <div class="text-[10px] text-emerald-600 font-bold">Diskon 10%</div>
                            </button>
                            <button type="button" class="duration-btn p-3 rounded-2xl border border-slate-200 hover:border-slate-300 bg-white text-left transition-all" data-months="12" data-discount="15">
                                <div class="text-xs font-bold text-slate-900">1 Tahun</div>
                                <div class="text-[10px] text-amber-600 font-bold">⭐ Best Value 15%</div>
                            </button>
                        </div>
                    </div>

                    <!-- PRICE BREAKDOWN BOX -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2 text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>Sewa Bulanan</span>
                            <span id="baseMonthlyPrice">Rp {{ number_format($room->price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>WiFi Fiber & Air Bersih</span>
                            <span class="text-emerald-600 font-bold">GRATIS</span>
                        </div>
                        <div class="flex justify-between text-slate-600" id="discountRow" style="display: none;">
                            <span>Potongan Diskon</span>
                            <span class="text-emerald-600 font-bold" id="discountAmount">- Rp 0</span>
                        </div>
                        <div class="pt-2 border-t border-slate-200 flex justify-between font-black text-sm text-slate-900">
                            <span>Total Estimasi</span>
                            <span id="totalCalcPrice">Rp {{ number_format($room->price, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- DUAL ACTION BUTTONS -->
                    <div class="space-y-3">
                        @if($isAvailable)
                            <a href="{{ route('tenant.booking.create', $room->id) }}" 
                               class="w-full py-4 rounded-2xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 hover:from-amber-500 hover:to-amber-600 hover:text-slate-950 text-white font-black text-sm shadow-xl hover:shadow-2xl transition-all duration-300 flex items-center justify-center gap-2">
                                <i class="fas fa-bolt text-amber-400"></i>
                                <span>Pesan Instan (Midtrans Pay)</span>
                            </a>
                        @else
                            <button disabled class="w-full py-4 rounded-2xl bg-slate-200 text-slate-400 font-bold text-sm cursor-not-allowed">
                                <i class="fas fa-lock mr-2"></i> Kamar Sedang Terisi
                            </button>
                        @endif

                        <a href="https://wa.me/{{ $whatsappNum }}?text=Halo%20Concierge%20Cemara%20Residence,%20saya%20tertarik%20dengan%20kamar%20{{ urlencode($room->name) }}%20dan%20ingin%20jadwalkan%20survey" 
                           target="_blank" rel="noopener noreferrer" 
                           class="w-full py-3.5 rounded-2xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs border border-emerald-200/80 transition-all flex items-center justify-center gap-2">
                            <i class="fab fa-whatsapp text-emerald-600 text-base"></i>
                            <span>Jadwalkan Survey via WhatsApp</span>
                        </a>
                    </div>

                    <!-- TRUST BADGES -->
                    <div class="pt-2 border-t border-slate-100 grid grid-cols-2 gap-2 text-[11px] text-slate-500">
                        <div class="flex items-center gap-1.5">
                            <i class="fas fa-shield-alt text-amber-500"></i> Tanpa Biaya Tersembunyi
                        </div>
                        <div class="flex items-center gap-1.5">
                            <i class="fas fa-check-circle text-emerald-500"></i> Kamar Terverifikasi
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </main>

    <!-- Footer -->
    @include('landing.footer')

    <!-- Interactive Rent Calculator Script -->
    <script>
        const basePrice = {{ $room->price }};
        const durationBtns = document.querySelectorAll('.duration-btn');
        const totalCalcPrice = document.getElementById('totalCalcPrice');
        const discountRow = document.getElementById('discountRow');
        const discountAmount = document.getElementById('discountAmount');

        function formatIDR(num) {
            return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        durationBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                durationBtns.forEach(b => {
                    b.classList.remove('active', 'border-2', 'border-amber-500', 'bg-amber-50/50');
                    b.classList.add('border', 'border-slate-200', 'bg-white');
                });

                btn.classList.add('active', 'border-2', 'border-amber-500', 'bg-amber-50/50');
                btn.classList.remove('border-slate-200', 'bg-white');

                const months = parseInt(btn.getAttribute('data-months'));
                const discount = parseInt(btn.getAttribute('data-discount'));

                const subtotal = basePrice * months;
                const discVal = Math.round(subtotal * (discount / 100));
                const finalTotal = subtotal - discVal;

                if (discount > 0) {
                    discountRow.style.display = 'flex';
                    discountAmount.innerText = '- ' + formatIDR(discVal);
                } else {
                    discountRow.style.display = 'none';
                }

                totalCalcPrice.innerText = formatIDR(finalTotal);
            });
        });
    </script>
</body>
</html>
