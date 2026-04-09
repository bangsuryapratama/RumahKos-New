<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RumahKos - KosNyaman</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-text {
            background: linear-gradient(135deg, #1d4ed8, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .card-hover { transition: transform .3s ease, box-shadow .3s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(37,99,235,.1); }
        .card-hover:hover img { transform: scale(1.05); }
        img { transition: transform .4s ease; }
    </style>
</head>
<body class="bg-white text-gray-900 overflow-x-hidden">

{{-- NAVBAR --}}
@include('landing.navbar')

{{-- HERO --}}
<section class="bg-gradient-to-br from-blue-50 via-white to-cyan-50 pt-24 pb-16 relative overflow-hidden">
    <div class="absolute -top-20 -right-20 w-80 h-80 bg-blue-100 rounded-full opacity-40 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-10 -left-10 w-64 h-64 bg-cyan-100 rounded-full opacity-30 blur-3xl pointer-events-none"></div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 text-center relative">
        <span class="inline-flex items-center gap-2 bg-blue-100 text-blue-700 text-xs font-bold px-4 py-2 rounded-full mb-5">
            <i class="fas fa-map-marker-alt text-xs"></i>
            {{ $propertyLocation }}
        </span>

        <h1 class="text-3xl sm:text-5xl font-black leading-tight mb-4">
            Kos Nyaman untuk<br>
            <span class="gradient-text">Mahasiswa & Pekerja</span>
        </h1>

        <p class="text-gray-500 text-sm sm:text-base max-w-lg mx-auto mb-8">
            Fasilitas lengkap, WiFi cepat, kamar mandi dalam & keamanan 24 jam.
            Mulai dari <strong class="text-blue-600">Rp {{ number_format($minPrice / 1000000, 1) }}jt</strong>/bulan.
        </p>

        <div class="flex flex-col sm:flex-row gap-3 justify-center mb-10">
            <a href="#kamar" class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-xl shadow-md transition text-sm">
                <i class="fas fa-bed"></i> Lihat Kamar
            </a>
            <a href="#kontak" class="inline-flex items-center justify-center gap-2 border-2 border-blue-600 text-blue-600 hover:bg-blue-50 font-bold px-6 py-3 rounded-xl transition text-sm">
                <i class="fab fa-whatsapp text-green-500"></i> Hubungi Kami
            </a>
        </div>

        <div class="rounded-2xl overflow-hidden shadow-xl ring-1 ring-black/5">
            <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=1200&h=500&fit=crop"
                 alt="Interior Kosan" class="w-full h-48 sm:h-72 object-cover">
        </div>

        <div class="grid grid-cols-3 gap-3 mt-6 max-w-md mx-auto">
            @php $stats = [
                ['val' => $availableRooms, 'label' => 'Kamar Tersedia', 'icon' => 'fa-bed'],
                ['val' => number_format($minPrice/1000000,1).'jt', 'label' => 'Mulai /bulan', 'icon' => 'fa-tag'],
                ['val' => '24/7', 'label' => 'Keamanan', 'icon' => 'fa-shield-halved'],
            ]; @endphp
            @foreach($stats as $s)
            <div class="bg-white border border-gray-100 rounded-xl p-3 text-center shadow-sm">
                <i class="fas {{ $s['icon'] }} text-blue-500 text-sm mb-1 block"></i>
                <div class="text-xl sm:text-2xl font-black text-blue-600">{{ $s['val'] }}</div>
                <div class="text-gray-400 text-[10px] font-medium">{{ $s['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- FASILITAS --}}
<section id="fasilitas" class="py-16 bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-10">
            <span class="text-xs font-bold text-blue-500 uppercase tracking-widest">Fasilitas</span>
            <h2 class="text-2xl sm:text-3xl font-black mt-1 mb-1">Semua Sudah Tersedia</h2>
            <p class="text-gray-400 text-sm">Tinggal bawa diri, semua sudah siap</p>
        </div>

        @php
        $facList = $FacilityAll->count() > 0
            ? $FacilityAll->take(8)->map(fn($f) => ['icon'=>$f->icon,'name'=>$f->name])->toArray()
            : [
                ['icon'=>'fas fa-bed','name'=>'Kasur & Lemari'],
                ['icon'=>'fas fa-wifi','name'=>'WiFi 100Mbps'],
                ['icon'=>'fas fa-shower','name'=>'K. Mandi Dalam'],
                ['icon'=>'fas fa-square-parking','name'=>'Parkir Motor'],
                ['icon'=>'fas fa-shield-halved','name'=>'Keamanan 24/7'],
                ['icon'=>'fas fa-kitchen-set','name'=>'Dapur Bersama'],
                ['icon'=>'fas fa-jug-detergent','name'=>'Laundry'],
                ['icon'=>'fas fa-snowflake','name'=>'AC (Opsional)'],
              ];
        @endphp

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @foreach($facList as $f)
            <div class="flex flex-col items-center text-center bg-blue-50 border border-blue-100 rounded-xl p-4 gap-3 hover:shadow-md hover:-translate-y-1 transition-all duration-200">
                <div class="w-11 h-11 bg-white rounded-xl flex items-center justify-center shadow-sm">
                    <i class="{{ $f['icon'] }} text-blue-600"></i>
                </div>
                <span class="text-xs font-semibold text-gray-700">{{ $f['name'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- KAMAR --}}
<section id="kamar" class="py-16 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-8">
            <span class="text-xs font-bold text-blue-500 uppercase tracking-widest">Kamar</span>
            <h2 class="text-2xl sm:text-3xl font-black mt-1 mb-1">Pilih Kamarmu</h2>
            <p class="text-gray-400 text-sm">Temukan yang paling cocok untukmu</p>
        </div>

        {{-- Filter --}}
        <div class="max-w-xl mx-auto mb-6 space-y-3">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                <input id="searchInput" type="text" placeholder="Cari nama kamar..."
                       oninput="filterRooms()"
                       class="w-full pl-10 pr-4 py-3 border border-gray-200 bg-white rounded-xl text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>
            <div class="flex flex-wrap gap-2 justify-center">
                <button id="btnAvailable" onclick="toggleAvailable()"
                        class="filter-btn px-4 py-2 rounded-xl text-xs font-semibold border border-gray-200 bg-white text-gray-600 hover:border-blue-400 transition">
                    <i class="fas fa-check-circle mr-1 text-green-500"></i>Hanya Tersedia
                </button>
                <button onclick="sortRooms('name')"
                        class="px-4 py-2 rounded-xl text-xs font-semibold border border-gray-200 bg-white text-gray-600 hover:border-blue-400 transition">
                    <i class="fas fa-arrow-down-a-z mr-1 text-blue-400"></i>A–Z
                </button>
                <button onclick="sortRooms('price')"
                        class="px-4 py-2 rounded-xl text-xs font-semibold border border-gray-200 bg-white text-gray-600 hover:border-blue-400 transition">
                    <i class="fas fa-tag mr-1 text-blue-400"></i>Harga Terendah
                </button>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mb-5">
            Menampilkan <strong id="displayCount" class="text-gray-700">0</strong> dari
            <strong id="totalCount" class="text-gray-700">0</strong> kamar
        </p>

        <div id="roomsContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($rooms as $room)
            @php
                $avg  = $room->reviews->avg('rating') ?? 0;
                $rcnt = $room->reviews->count();
                $cycle = match($room->billing_cycle ?? '') {
                    'daily'   => 'hari',
                    'weekly'  => 'minggu',
                    'monthly' => 'bulan',
                    default   => 'tahun'
                };
            @endphp
            <a href="{{ route('rooms.detail', $room->id) }}"
               class="room-card card-hover bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden block"
               data-status="{{ $room->status }}"
               data-name="{{ strtolower($room->name) }}"
               data-price="{{ $room->price }}">

                <div class="relative h-44 overflow-hidden bg-gray-100">
                    @if($room->image)
                        <img src="{{ asset('storage/'.$room->image) }}" alt="{{ $room->name }}" class="w-full h-full object-cover">
                    @else
                        <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=600&h=400&fit=crop" alt="{{ $room->name }}" class="w-full h-full object-cover">
                    @endif

                    <div class="absolute top-3 right-3">
                        @if($room->status == 'available')
                            <span class="inline-flex items-center gap-1 bg-green-500 text-white text-[11px] font-bold px-2.5 py-1 rounded-full">
                                <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>Tersedia
                            </span>
                        @elseif($room->status == 'occupied')
                            <span class="inline-flex items-center gap-1 bg-red-500 text-white text-[11px] font-bold px-2.5 py-1 rounded-full">
                                <span class="w-1.5 h-1.5 bg-white rounded-full"></span>Terisi
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-orange-500 text-white text-[11px] font-bold px-2.5 py-1 rounded-full">
                                <i class="fas fa-tools text-[9px]"></i>Perbaikan
                            </span>
                        @endif
                    </div>
                </div>

                <div class="p-4 space-y-2">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-bold text-sm leading-snug line-clamp-1 flex-1">{{ $room->name }}</h3>
                        @if($rcnt > 0)
                        <div class="flex items-center gap-1 bg-yellow-50 border border-yellow-100 px-2 py-0.5 rounded-lg shrink-0">
                            <i class="fas fa-star text-yellow-400 text-[10px]"></i>
                            <span class="text-xs font-bold">{{ number_format($avg,1) }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="flex flex-wrap gap-x-3 text-[11px] text-gray-400">
                        @if($room->size)<span><i class="fas fa-ruler-combined mr-1"></i>{{ $room->size }}</span>@endif
                        <span><i class="fas fa-layer-group mr-1"></i>Lantai {{ $room->floor }}</span>
                    </div>

                    <div class="flex flex-wrap gap-1.5">
                        @foreach($room->facilities->take(3) as $fac)
                        <span class="text-[11px] bg-blue-50 text-blue-600 border border-blue-100 px-2 py-0.5 rounded-lg font-semibold">
                            <i class="{{ $fac->icon }} mr-1 text-[9px]"></i>{{ $fac->name }}
                        </span>
                        @endforeach
                        @if($room->facilities->count() > 3)
                        <span class="text-[11px] bg-gray-100 text-gray-500 px-2 py-0.5 rounded-lg font-semibold">+{{ $room->facilities->count() - 3 }}</span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between bg-blue-600 rounded-xl px-4 py-3 mt-1">
                        <div>
                            <div class="text-white font-black text-sm">Rp {{ number_format($room->price, 0, ',', '.') }}</div>
                            <div class="text-blue-200 text-[11px]">per {{ $cycle }}</div>
                        </div>
                        <i class="fas fa-arrow-right text-white text-sm"></i>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-full text-center py-16">
                <i class="fas fa-bed text-4xl text-blue-200 mb-4 block"></i>
                <h3 class="font-bold text-gray-800 mb-1">Belum Ada Kamar</h3>
                <p class="text-gray-400 text-sm mb-5">Hubungi kami untuk informasi lebih lanjut.</p>
                <a href="https://wa.me/6287824660303" class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-xl hover:bg-blue-700 transition text-sm font-bold">
                    <i class="fab fa-whatsapp"></i> Hubungi Kami
                </a>
            </div>
            @endforelse
        </div>

        <div id="noResults" class="hidden text-center py-14">
            <i class="fas fa-search text-3xl text-gray-200 mb-3 block"></i>
            <h3 class="font-bold text-gray-600">Tidak ada hasil</h3>
            <p class="text-sm text-gray-400">Coba kata kunci lain</p>
        </div>
    </div>
</section>

{{-- LOKASI --}}
<section id="lokasi" class="py-16 bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-10">
            <span class="text-xs font-bold text-blue-500 uppercase tracking-widest">Lokasi</span>
            <h2 class="text-2xl sm:text-3xl font-black mt-1 mb-1">Lokasi Strategis</h2>
            <p class="text-gray-400 text-sm">Dekat kampus, minimarket, transportasi, dan area kuliner.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
            <div class="space-y-3">
                @php $locs = [
                    ['icon'=>'fas fa-graduation-cap','bg'=>'bg-blue-50','ic'=>'text-blue-600','title'=>'Kampus ITB','sub'=>'5 menit berkendara'],
                    ['icon'=>'fas fa-shop','bg'=>'bg-green-50','ic'=>'text-green-600','title'=>'Minimarket','sub'=>'2 menit jalan kaki'],
                    ['icon'=>'fas fa-bus','bg'=>'bg-purple-50','ic'=>'text-purple-600','title'=>'Transportasi Umum','sub'=>'Mudah diakses'],
                    ['icon'=>'fas fa-utensils','bg'=>'bg-orange-50','ic'=>'text-orange-600','title'=>'Area Kuliner','sub'=>'Banyak pilihan makan'],
                ]; @endphp
                @foreach($locs as $loc)
                <div class="flex items-center gap-4 p-4 rounded-xl bg-gray-50 border border-gray-100 hover:bg-white hover:shadow-sm transition-all group">
                    <div class="w-12 h-12 {{ $loc['bg'] }} rounded-xl flex items-center justify-center shrink-0">
                        <i class="{{ $loc['icon'] }} {{ $loc['ic'] }} text-lg"></i>
                    </div>
                    <div>
                        <div class="font-bold text-sm">{{ $loc['title'] }}</div>
                        <div class="text-gray-400 text-xs">{{ $loc['sub'] }}</div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-300 ml-auto text-xs"></i>
                </div>
                @endforeach
            </div>

            <div class="rounded-xl overflow-hidden shadow-md border border-gray-100 h-64 lg:h-full min-h-[260px]">
                {!! $mapsEmbed->maps_embed !!}
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section id="kontak" class="py-16 bg-blue-600">
    <div class="max-w-2xl mx-auto px-4 text-center">
        <h2 class="text-2xl sm:text-3xl font-black text-white mb-3">Tertarik untuk Ngekos?</h2>
        <p class="text-blue-100 text-sm sm:text-base mb-8">Hubungi kami sekarang atau jadwalkan kunjungan langsung.</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="https://wa.me/6287824660303?text=Halo,%20saya%20ingin%20info%20kos"
               class="inline-flex items-center justify-center gap-2 bg-white text-blue-700 font-bold px-7 py-3 rounded-xl hover:bg-blue-50 transition shadow-md text-sm">
                <i class="fab fa-whatsapp text-green-500 text-lg"></i> Chat WhatsApp
            </a>
            <a href="tel:+6287824660303"
               class="inline-flex items-center justify-center gap-2 border-2 border-white/50 text-white font-bold px-7 py-3 rounded-xl hover:bg-white/10 transition text-sm">
                <i class="fas fa-phone"></i> Telepon
            </a>
        </div>
    </div>
</section>

@include('landing.footer')
@include('landing.floating-message')

{{-- BACK TO TOP --}}
<button id="backToTop" onclick="window.scrollTo({top:0,behavior:'smooth'})"
        class="fixed bottom-20 right-5 z-50 w-10 h-10 rounded-full bg-white border border-gray-200 hover:bg-blue-600 hover:text-white text-gray-500 shadow-lg items-center justify-center transition-all hidden"
        style="display:none">
    <i class="fas fa-arrow-up text-sm"></i>
</button>

<script>
    let onlyAvailable = false;

    document.addEventListener('DOMContentLoaded', updateCount);

    window.addEventListener('scroll', () => {
        const btn = document.getElementById('backToTop');
        btn.style.display = window.scrollY > 300 ? 'flex' : 'none';
    }, { passive: true });

    function filterRooms() {
        const q = document.getElementById('searchInput').value.toLowerCase();
        let shown = 0;
        document.querySelectorAll('.room-card').forEach(c => {
            const match = c.dataset.name.includes(q) && (!onlyAvailable || c.dataset.status === 'available');
            c.style.display = match ? '' : 'none';
            if (match) shown++;
        });
        document.getElementById('noResults').style.display = shown === 0 ? 'block' : 'none';
        document.getElementById('roomsContainer').style.display = shown === 0 ? 'none' : '';
        updateCount();
    }

    function toggleAvailable() {
        onlyAvailable = !onlyAvailable;
        const btn = document.getElementById('btnAvailable');
        btn.classList.toggle('bg-blue-600', onlyAvailable);
        btn.classList.toggle('text-white', onlyAvailable);
        btn.classList.toggle('border-blue-600', onlyAvailable);
        filterRooms();
    }

    function sortRooms(by) {
        const container = document.getElementById('roomsContainer');
        const cards = [...container.querySelectorAll('.room-card')];
        cards.sort((a, b) => by === 'name'
            ? a.dataset.name.localeCompare(b.dataset.name)
            : +a.dataset.price - +b.dataset.price
        );
        cards.forEach(c => container.appendChild(c));
    }

    function updateCount() {
        const all   = document.querySelectorAll('.room-card').length;
        const shown = [...document.querySelectorAll('.room-card')].filter(c => c.style.display !== 'none').length;
        document.getElementById('displayCount').textContent = shown;
        document.getElementById('totalCount').textContent   = all;
    }
</script>
</body>
</html>