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
        .star-rating { display: inline-flex; gap: 0.25rem; }
        .star { cursor: pointer; color: #d1d5db; transition: color 0.2s; }
        .star.active, .star:hover { color: #fbbf24; }
        .image-gallery img { cursor: pointer; transition: transform 0.3s; }
        .image-gallery img:hover { transform: scale(1.05); }
    </style>
</head>
<body class="bg-gray-50">
    
    {{-- Navbar --}}
    @include('landing.navbar')

    {{-- BREADCRUMB --}}
    <section class="bg-white border-b pt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <a href="{{ route('landing') }}" class="hover:text-blue-600 transition">
                    <i class="fas fa-home"></i> Beranda
                </a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('landing') }}#kamar" class="hover:text-blue-600 transition">Kamar</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-gray-900 font-medium">{{ $room->name }}</span>
            </div>
        </div>
    </section>

    {{-- ROOM DETAIL HERO --}}
    <section class="py-8 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Room Title & Status --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2">{{ $room->name }}</h1>
                    <div class="flex items-center gap-4 text-gray-600">
                        <span><i class="fas fa-building mr-1"></i>{{ $room->property->name }}</span>
                        <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $room->property->address ?? 'Bandung' }}</span>
                    </div>
                </div>
                <div>
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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                @if($room->image)
                    <div class="md:col-span-2 md:row-span-2">
                        <img src="{{ asset('storage/' . $room->image) }}" 
                             alt="{{ $room->name }}" 
                             class="w-full h-96 md:h-[500px] object-cover rounded-2xl shadow-lg">
                    </div>
                @else
                    <div class="md:col-span-2 md:row-span-2">
                        <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=1200&h=800&fit=crop" 
                             alt="{{ $room->name }}" 
                             class="w-full h-96 md:h-[500px] object-cover rounded-2xl shadow-lg">
                    </div>
                @endif
                
                {{-- Additional Images (if you have gallery) --}}
                {{-- @if($room->gallery && count($room->gallery) > 0)
                    @foreach($room->gallery->take(4) as $image)
                        <img src="{{ asset('storage/' . $image) }}" 
                             alt="Gallery" 
                             class="w-full h-48 object-cover rounded-xl shadow-md">
                    @endforeach
                @endif --}}
            </div>

            {{-- Main Content Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Left Column: Details --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    {{-- Room Info --}}
                    <div class="bg-white rounded-2xl shadow-md p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Informasi Kamar</h2>
                        <div class="grid grid-cols-2 gap-4">
                            @if($room->size)
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-ruler-combined text-blue-600 text-xl"></i>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-600">Ukuran</div>
                                    <div class="font-semibold text-gray-900">{{ $room->size }}</div>
                                </div>
                            </div>
                            @endif
                            
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-layer-group text-blue-600 text-xl"></i>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-600">Lantai</div>
                                    <div class="font-semibold text-gray-900">Lantai {{ $room->floor }}</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-600">Periode Sewa</div>
                                    <div class="font-semibold text-gray-900">
                                        @if($room->billing_cycle == 'daily') Harian
                                        @elseif($room->billing_cycle == 'weekly') Mingguan
                                        @elseif($room->billing_cycle == 'monthly') Bulanan
                                        @else Tahunan
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-star text-blue-600 text-xl"></i>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-600">Rating</div>
                                    <div class="font-semibold text-gray-900">
                                        {{ number_format($averageRating ?? 0, 1) }} <span class="text-sm text-gray-500">({{ $totalReviews ?? 0 }} ulasan)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    @if($room->description)
                    <div class="bg-white rounded-2xl shadow-md p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Deskripsi</h2>
                        <p class="text-gray-700 leading-relaxed">{{ $room->description }}</p>
                    </div>
                    @endif

                    {{-- Facilities --}}
                    <div class="bg-white rounded-2xl shadow-md p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Fasilitas Kamar</h2>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            @if($room->facilities->count() > 0)
                                @foreach($room->facilities as $facility)
                                <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg">
                                    <i class="{{ $facility->icon }} text-blue-600 text-xl"></i>
                                    <span class="font-medium text-gray-900">{{ $facility->name }}</span>
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

                    {{-- Reviews Section --}}
                    <div class="bg-white rounded-2xl shadow-md p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Ulasan Penyewa</h2>
                            <button onclick="document.getElementById('reviewModal').classList.remove('hidden')" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-plus mr-2"></i>Tulis Ulasan
                            </button>
                        </div>

                        {{-- Average Rating Display --}}
                        <div class="flex items-center gap-6 p-6 bg-gradient-to-br from-blue-50 to-purple-50 rounded-xl mb-6">
                            <div class="text-center">
                                <div class="text-5xl font-bold text-blue-600">{{ number_format($averageRating ?? 0, 1) }}</div>
                                <div class="star-rating justify-center mt-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= round($averageRating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                                <div class="text-sm text-gray-600 mt-1">{{ $totalReviews ?? 0 }} ulasan</div>
                            </div>
                            <div class="flex-1">
                                @for($i = 5; $i >= 1; $i--)
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-sm w-8">{{ $i }}<i class="fas fa-star text-xs text-yellow-400 ml-1"></i></span>
                                    <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-yellow-400 rounded-full" style="width: {{ ($ratingDistribution[$i] ?? 0) }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 w-12 text-right">{{ $ratingDistribution[$i] ?? 0 }}%</span>
                                </div>
                                @endfor
                            </div>
                        </div>

                        {{-- Reviews List --}}
                        <div class="space-y-4">
                            @forelse($reviews ?? [] as $review)
                            <div class="border-b border-gray-200 pb-4 last:border-0">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-user text-blue-600 text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <div>
                                                <div class="font-semibold text-gray-900">{{ $review->user->name ?? 'Anonim' }}</div>
                                                <div class="star-rating">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star text-sm {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-700">{{ $review->comment }}</p>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8">
                                <i class="fas fa-comments text-4xl text-gray-300 mb-2"></i>
                                <p class="text-gray-500">Belum ada ulasan. Jadilah yang pertama!</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                </div>

                {{-- Right Column: Booking Card --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                        
                        {{-- Price --}}
                        <div class="mb-6 p-6 bg-gradient-to-br from-blue-50 to-purple-50 rounded-xl border border-blue-100">
                            <div class="text-3xl font-bold text-blue-600">
                                Rp {{ number_format($room->price, 0, ',', '.') }}
                            </div>
                            <div class="text-gray-600 mt-1">
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
                                       class="block w-full text-center bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4 rounded-xl font-bold hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg hover:shadow-xl">
                                        <i class="fas fa-credit-card mr-2"></i>Pesan Sekarang
                                    </a>
                                @else
                                    <a href="{{ route('tenant.login') }}" 
                                       class="block w-full text-center bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4 rounded-xl font-bold hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg hover:shadow-xl">
                                        <i class="fas fa-sign-in-alt mr-2"></i>Login untuk Pesan
                                    </a>
                                @endauth
                                
                                <a href="https://wa.me/6283841806357?text=Halo,%20saya%20tertarik%20dengan%20{{ urlencode($room->name) }}%20di%20{{ urlencode($room->property->name) }}" 
                                   target="_blank"
                                   class="block w-full text-center bg-green-500 text-white px-6 py-4 rounded-xl font-bold hover:bg-green-600 transition-all shadow-md hover:shadow-lg">
                                    <i class="fab fa-whatsapp mr-2"></i>Tanya via WhatsApp
                                </a>
                            @else
                                <button disabled 
                                        class="block w-full text-center bg-gray-200 text-gray-400 px-6 py-4 rounded-xl font-bold cursor-not-allowed">
                                    <i class="fas fa-ban mr-2"></i>{{ $room->status == 'occupied' ? 'Tidak Tersedia' : 'Sedang Perbaikan' }}
                                </button>
                            @endif
                        </div>

                        {{-- Additional Info --}}
                        <div class="mt-6 pt-6 border-t border-gray-200 space-y-3 text-sm text-gray-600">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-shield-alt text-blue-600"></i>
                                <span>Pembayaran aman & terpercaya</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-undo text-blue-600"></i>
                                <span>Bisa refund sesuai ketentuan</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-headset text-blue-600"></i>
                                <span>Customer service 24/7</span>
                            </div>
                        </div>

                        {{-- Contact Info --}}
                        <div class="mt-6 pt-6 border-t border-gray-200">
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
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Kamar Lainnya</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($similarRooms ?? [] as $similarRoom)
                    <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all overflow-hidden">
                        <div class="relative h-48">
                            @if($similarRoom->image)
                                <img src="{{ asset('storage/' . $similarRoom->image) }}" 
                                     alt="{{ $similarRoom->name }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=600&h=400&fit=crop" 
                                     alt="{{ $similarRoom->name }}" 
                                     class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="p-5">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $similarRoom->name }}</h3>
                            <div class="text-xl font-bold text-blue-600 mb-3">
                                Rp {{ number_format($similarRoom->price, 0, ',', '.') }}
                            </div>
                            <a href="{{ route('rooms.detail', $similarRoom->id) }}" 
                               class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Review Modal --}}
    <div id="reviewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Tulis Ulasan</h3>
                <button onclick="document.getElementById('reviewModal').classList.add('hidden')" 
                        class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route('room.review.store', $room->id) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Rating</label>
                    <div class="star-rating text-3xl" id="ratingInput">
                        <i class="star fas fa-star" data-rating="1"></i>
                        <i class="star fas fa-star" data-rating="2"></i>
                        <i class="star fas fa-star" data-rating="3"></i>
                        <i class="star fas fa-star" data-rating="4"></i>
                        <i class="star fas fa-star" data-rating="5"></i>
                    </div>
                    <input type="hidden" name="rating" id="ratingValue" required>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Ulasan Anda</label>
                    <textarea name="comment" 
                              rows="4" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                              placeholder="Ceritakan pengalaman Anda..."
                              required></textarea>
                </div>

                <button type="submit" 
                        class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition">
                    <i class="fas fa-paper-plane mr-2"></i>Kirim Ulasan
                </button>
            </form>
        </div>
    </div>

    {{-- Footer --}}
    @include('landing.footer')

    <script>
        // Star Rating Interactive
        const stars = document.querySelectorAll('#ratingInput .star');
        const ratingValue = document.getElementById('ratingValue');
        
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.dataset.rating;
                ratingValue.value = rating;
                
                stars.forEach(s => {
                    if (s.dataset.rating <= rating) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
            });
            
            star.addEventListener('mouseenter', function() {
                const rating = this.dataset.rating;
                stars.forEach(s => {
                    if (s.dataset.rating <= rating) {
                        s.style.color = '#fbbf24';
                    } else {
                        s.style.color = '#d1d5db';
                    }
                });
            });
        });
        
        document.getElementById('ratingInput').addEventListener('mouseleave', function() {
            const currentRating = ratingValue.value;
            stars.forEach(s => {
                if (currentRating && s.dataset.rating <= currentRating) {
                    s.style.color = '#fbbf24';
                } else {
                    s.style.color = '#d1d5db';
                }
            });
        });
    </script>

</body>
</html>