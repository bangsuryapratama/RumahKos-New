<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RumahKos - Kos Nyaman</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        html { scroll-behavior: smooth; }
        .gradient-text { 
            background: linear-gradient(to right, #3b82f6, #8b5cf6); 
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent; 
        }
        @media (max-width: 640px) {
            .hero-title { font-size: 2rem; }
        }
    </style>
</head>
<body class="bg-white">
    
    {{-- Navbar --}}
    @include('landing.navbar')

    {{-- HERO SECTION --}}
    <section class="bg-gradient-to-br from-blue-50 via-white to-purple-50 pt-20 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="inline-block bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-semibold mb-6 animate-pulse">
                    <i class="fas fa-map-marker-alt mr-1"></i> Lokasi Strategis di {{ $propertyLocation }}
                </span>
                <h1 class="hero-title text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                    Kos Nyaman untuk<br>
                    <span class="gradient-text">Mahasiswa & Pekerja</span>
                </h1>
                 {{-- <p class="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                    Fasilitas lengkap, nyaman, dan strategis. WiFi cepat, kamar mandi dalam, keamanan 24 jam. 
                    Mulai dari <strong class="text-blue-600">Rp. 800.000</strong>/bulan
                </p> --}}
                <p class="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                    Fasilitas lengkap, nyaman, dan strategis. WiFi cepat, kamar mandi dalam, keamanan 24 jam. 
                    Mulai dari <strong class="text-blue-600">Rp {{ number_format($minPrice / 1000000, 1) }}jt</strong>/bulan
                </p>
                <div class="flex flex-col sm:flex-row gap-2 justify-center">
                    <a href="#kamar" class="inline-flex items-center justify-center px-8 py-4 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-all shadow-lg hover:shadow-xl">
                        <i class="fas fa-bed mr-2"></i> Lihat Kamar Tersedia
                    </a>
                </div>
            </div>

            {{-- Hero Image --}}
            <div class="mt-12">
                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=1200&h=600&fit=crop" 
                     alt="Kosan" 
                     class="w-full h-64 sm:h-80 lg:h-96 object-cover rounded-2xl shadow-2xl">
            </div>

            {{-- Quick Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mt-12 max-w-4xl mx-auto">
                <div class="text-center bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                    <div class="text-3xl sm:text-4xl font-bold text-blue-600">{{ $availableRooms }}</div>
                    <div class="text-gray-600 text-sm mt-1">Kamar Tersedia</div>
                </div>
                <div class="text-center bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                    <div class="text-3xl sm:text-4xl font-bold text-blue-600">
                        {{ $minPrice > 0 ? number_format($minPrice / 1000000, 1) . 'jt' : 'N/A' }}
                    </div>
                    <div class="text-gray-600 text-sm mt-1">Mulai dari /bulan</div>
                </div>
                <div class="text-center bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                    <div class="text-3xl sm:text-4xl font-bold text-blue-600">24/7</div>
                    <div class="text-gray-600 text-sm mt-1">Keamanan</div>
                </div>
            </div>
        </div>
    </section>

    {{-- FASILITAS SECTION --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Fasilitas Lengkap</h2>
                <p class="text-gray-600">Semua yang kamu butuhkan untuk kenyamanan maksimal</p>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @if($FacilityAll->count() > 0)
                    @foreach($FacilityAll->take(8) as $facility)
                    <div class="text-center p-6 bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl hover:shadow-lg transition-all">
                        <div class="text-4xl mb-3">
                            <i class="{{ $facility->icon }} text-blue-600"></i>
                        </div>
                        <div class="font-semibold text-gray-800">{{ $facility->name }}</div>
                    </div>
                    @endforeach
                @else
                    {{-- Default facilities if no data --}}
                    <div class="text-center p-6 bg-gray-50 rounded-xl"><div class="text-4xl mb-3">🛏️</div><div class="font-semibold text-gray-800">Kasur & Lemari</div></div>
                    <div class="text-center p-6 bg-gray-50 rounded-xl"><div class="text-4xl mb-3">📶</div><div class="font-semibold text-gray-800">WiFi 100Mbps</div></div>
                    <div class="text-center p-6 bg-gray-50 rounded-xl"><div class="text-4xl mb-3">🚿</div><div class="font-semibold text-gray-800">K. Mandi Dalam</div></div>
                    <div class="text-center p-6 bg-gray-50 rounded-xl"><div class="text-4xl mb-3">🅿️</div><div class="font-semibold text-gray-800">Parkir Motor</div></div>
                    <div class="text-center p-6 bg-gray-50 rounded-xl"><div class="text-4xl mb-3">🔐</div><div class="font-semibold text-gray-800">Keamanan 24/7</div></div>
                    <div class="text-center p-6 bg-gray-50 rounded-xl"><div class="text-4xl mb-3">🍳</div><div class="font-semibold text-gray-800">Dapur Bersama</div></div>
                    <div class="text-center p-6 bg-gray-50 rounded-xl"><div class="text-4xl mb-3">🧺</div><div class="font-semibold text-gray-800">Laundry</div></div>
                    <div class="text-center p-6 bg-gray-50 rounded-xl"><div class="text-4xl mb-3">❄️</div><div class="font-semibold text-gray-800">AC (Opsional)</div></div>
                @endif
            </div>
        </div>
    </section>
 
    {{-- DAFTAR KAMAR SECTION --}}
    <section id="kamar" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Daftar Kamar</h2>
                <p class="text-gray-600">Pilih kamar yang sesuai dengan kebutuhanmu</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($rooms as $room)
                    <div class="bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden group {{ $room->status != 'available' ? 'opacity-75' : '' }}">
                        
                        {{-- Image + Overlay --}}
                        <div class="relative h-52 overflow-hidden">
                            @if($room->image)
                                <img src="{{ asset('storage/' . $room->image) }}" 
                                     alt="{{ $room->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 {{ $room->status != 'available' ? 'grayscale' : '' }}">
                            @else
                                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=600&h=400&fit=crop" 
                                     alt="{{ $room->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 {{ $room->status != 'available' ? 'grayscale' : '' }}">
                            @endif
                            
                            {{-- Status Badge --}}
                            <div class="absolute top-3 right-3">
                                @if($room->status == 'available')
                                    <span class="bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                        <i class="fas fa-check-circle mr-1"></i>Tersedia
                                    </span>
                                @elseif($room->status == 'occupied')
                                    <span class="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                        <i class="fas fa-times-circle mr-1"></i>Terisi
                                    </span>
                                @else
                                    <span class="bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                        <i class="fas fa-tools mr-1"></i>Perbaikan
                                    </span>
                                @endif
                            </div>

                            {{-- Property Badge --}}
                            <div class="absolute bottom-3 left-3">
                                <span class="bg-black bg-opacity-60 text-white text-xs px-3 py-1 rounded-full">
                                    <i class="fas fa-building mr-1"></i>{{ $room->property->name }}
                                </span>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-5 flex flex-col gap-3">
                            <h3 class="text-xl font-bold text-gray-900">{{ $room->name }}</h3>
                            
                            <div class="flex items-center gap-3 text-gray-600 text-sm">
                                @if($room->size)
                                    <span><i class="fas fa-ruler-combined mr-1"></i>{{ $room->size }}</span>
                                @endif
                                <span><i class="fas fa-layer-group mr-1"></i>Lantai {{ $room->floor }}</span>
                            </div>

                            {{-- Facilities --}}
                            @if($room->facilities->count() > 0)
                            <div class="flex flex-wrap gap-2 text-xs">
                                @foreach($room->facilities->take(3) as $facility)
                                <span class="px-2 py-1 rounded-lg font-medium {{ $room->status == 'available' ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-500' }}">
                                    <i class="{{ $facility->icon }} mr-1"></i>{{ $facility->name }}
                                </span>
                                @endforeach
                                @if($room->facilities->count() > 3)
                                <span class="px-2 py-1 rounded-lg font-medium {{ $room->status == 'available' ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-500' }}">
                                    +{{ $room->facilities->count() - 3 }}
                                </span>
                                @endif
                            </div>
                            @else
                            <div class="flex flex-wrap gap-2 text-xs">
                                <span class="px-2 py-1 rounded-lg font-medium {{ $room->status == 'available' ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-500' }}">
                                    <i class="fas fa-bed mr-1"></i>Kasur
                                </span>
                                <span class="px-2 py-1 rounded-lg font-medium {{ $room->status == 'available' ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-500' }}">
                                    <i class="fas fa-door-open mr-1"></i>Lemari
                                </span>
                                <span class="px-2 py-1 rounded-lg font-medium {{ $room->status == 'available' ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-500' }}">
                                    <i class="fas fa-bath mr-1"></i>K. Mandi
                                </span>
                            </div>
                            @endif

                            {{-- Harga --}}
                            <div class="px-4 py-3 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl mt-3 border border-blue-100">
                                <div class="text-xl font-bold text-blue-600">
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

                            {{-- Action Button --}}
                           @if($room->status == 'available')
                                    <a href="{{ route('rooms.detail', $room->id) }}" 
                                    class="block w-full text-center bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-blue-600 hover:to-blue-700 transition-all shadow-md hover:shadow-lg">
                                        <i class="fas fa-info-circle mr-2"></i>Lihat Detail
                                    </a>
                                @else
                                    <button disabled class="block w-full text-center bg-gray-200 text-gray-400 px-6 py-3 rounded-xl font-semibold cursor-not-allowed">
                                        <i class="fas fa-ban mr-2"></i>{{ $room->status == 'occupied' ? 'Tidak Tersedia' : 'Sedang Perbaikan' }}
                                    </button>
                             @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16">
                        <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-bed text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Kamar</h3>
                        <p class="text-gray-600">Belum ada kamar yang tersedia saat ini. Silakan hubungi kami untuk info lebih lanjut.</p>
                        <a href="https://wa.me/6283841806357" class="inline-block mt-6 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="fab fa-whatsapp mr-2"></i>Hubungi Kami
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- LOKASI SECTION --}}
  <section id="lokasi" class="py-16 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Header -->
    <div class="text-center mb-12">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 text-blue-600 shadow-sm">
        <i class="fa-solid fa-map-location-dot text-2xl"></i>
      </div>

      <h2 class="mt-5 text-3xl sm:text-4xl font-bold text-gray-900 mb-3">
        Lokasi Strategis
      </h2>
      <p class="text-gray-600 max-w-xl mx-auto">
        Kos kami dekat dengan kampus, minimarket, transportasi, dan area kuliner. Lokasi mudah diakses dan aman.
      </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
      
      <!-- Fasilitas Sekitar -->
      <div class="space-y-4">
        <div class="flex items-center gap-4 bg-gray-50 p-5 rounded-xl hover:shadow-lg transition-shadow">
          <div class="w-14 h-14 bg-blue-100 rounded-lg flex items-center justify-center text-2xl flex-shrink-0">
            <i class="fa-solid fa-graduation-cap text-blue-600"></i>
          </div>
          <div>
            <div class="font-bold text-gray-900">Kampus ITB</div>
            <div class="text-sm text-gray-600">5 menit berkendara</div>
          </div>
        </div>

        <div class="flex items-center gap-4 bg-gray-50 p-5 rounded-xl hover:shadow-lg transition-shadow">
          <div class="w-14 h-14 bg-blue-100 rounded-lg flex items-center justify-center text-2xl flex-shrink-0">
            <i class="fa-solid fa-shop text-blue-600"></i>
          </div>
          <div>
            <div class="font-bold text-gray-900">Minimarket</div>
            <div class="text-sm text-gray-600">2 menit jalan kaki</div>
          </div>
        </div>

        <div class="flex items-center gap-4 bg-gray-50 p-5 rounded-xl hover:shadow-lg transition-shadow">
          <div class="w-14 h-14 bg-blue-100 rounded-lg flex items-center justify-center text-2xl flex-shrink-0">
            <i class="fa-solid fa-bus text-blue-600"></i>
          </div>
          <div>
            <div class="font-bold text-gray-900">Transportasi Umum</div>
            <div class="text-sm text-gray-600">Mudah diakses</div>
          </div>
        </div>

        <div class="flex items-center gap-4 bg-gray-50 p-5 rounded-xl hover:shadow-lg transition-shadow">
          <div class="w-14 h-14 bg-blue-100 rounded-lg flex items-center justify-center text-2xl flex-shrink-0">
            <i class="fa-solid fa-utensils text-blue-600"></i>
          </div>
          <div>
            <div class="font-bold text-gray-900">Area Kuliner</div>
            <div class="text-sm text-gray-600">Banyak tempat makan</div>
          </div>
        </div>
      </div>

      <!-- Google Maps -->
      <div class="relative bg-gray-100 rounded-xl overflow-hidden shadow-lg h-80 lg:h-full">
        
        <iframe 
          src="{{ $mapsEmbed->maps_embed ?? '' }}"
          class="w-full h-full border-0"
          allowfullscreen="" 
          loading="lazy" 
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>

      </div>

    </div>
  </div>
</section>


    {{-- CTA SECTION --}}
    <section class="py-16 bg-gradient-to-r from-blue-600 to-purple-600 text-white" id="kontak">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold mb-4">Tertarik untuk Ngekos di Sini?</h2>
            <p class="text-lg sm:text-xl mb-8 opacity-90">Hubungi kami sekarang untuk info lebih lanjut atau jadwalkan kunjungan</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="https://wa.me/6283841806357?text=Halo,%20saya%20ingin%20info%20lengkap%20tentang%20kos" 
                   class="inline-flex items-center justify-center bg-white text-blue-600 px-8 py-4 rounded-xl font-bold hover:bg-gray-100 transition-all shadow-lg">
                    <i class="fab fa-whatsapp mr-2 text-xl"></i> Chat WhatsApp
                </a>
                <a href="tel:+6283841806357" 
                   class="inline-flex items-center justify-center border-2 border-white text-white px-8 py-4 rounded-xl font-bold hover:bg-white hover:text-blue-600 transition-all">
                    <i class="fas fa-phone mr-2"></i> Telepon
                </a>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    @include('landing.footer')
</body>
</html>