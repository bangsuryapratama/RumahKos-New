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
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .dropdown-menu {
            animation: slideDown 0.2s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .modal-backdrop {
            animation: fadeIn 0.2s ease-out;
        }
    </style>
</head>
<body class="bg-gray-50">

    @include('landing.navbar')

    {{-- Alert Messages --}}
    @if(session('success'))
    <div class="fixed top-20 right-4 z-50 max-w-sm mx-4 sm:mx-0">
        <div class="bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-3">
            <i class="fas fa-check-circle"></i>
            <span class="flex-1 text-sm">{{ session('success') }}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-gray-200">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="fixed top-20 right-4 z-50 max-w-sm mx-4 sm:mx-0">
        <div class="bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-3">
            <i class="fas fa-exclamation-circle"></i>
            <span class="flex-1 text-sm">{{ session('error') }}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-gray-200">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
    </div>
    @endif

    {{-- BREADCRUMB --}}
    <section class="bg-white border-b pt-16 sm:pt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-600 overflow-x-auto whitespace-nowrap">
                <a href="{{ route('landing') }}" class="hover:text-blue-600 transition">
                    <i class="fas fa-home"></i> Beranda
                </a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('landing') }}#kamar" class="hover:text-blue-600 transition">Kamar</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-gray-900 font-medium truncate">{{ $room->name }}</span>
            </div>
        </div>
    </section>

    {{-- ROOM DETAIL --}}
    <section class="py-4 sm:py-8 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-4">
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-2">{{ $room->name }}</h1>
                <div class="flex flex-wrap items-center gap-3 text-xs sm:text-sm text-gray-600">
                    <span class="flex items-center gap-1">
                        <i class="fas fa-building"></i>
                        {{ $room->property->name }}
                    </span>
                    <span class="hidden sm:inline">•</span>
                    <span class="flex items-center gap-1">
                        <i class="fas fa-map-marker-alt"></i>
                        {{ $room->property->address ?? 'Bandung' }}
                    </span>
                    <span class="hidden sm:inline">•</span>
                    <div class="flex items-center gap-1.5 bg-blue-50 px-2 py-1 rounded">
                        <i class="fas fa-star text-blue-600 text-sm"></i>
                        <span class="font-bold text-gray-900">{{ number_format($averageRating, 1) }}</span>
                        <span class="text-gray-500">({{ $totalReviews }})</span>
                    </div>
                </div>
            </div>

            {{-- Image --}}
            <div class="mb-6">
                @if($room->image)
                    <img src="{{ asset('storage/' . $room->image) }}" alt="{{ $room->name }}"
                         class="w-full h-48 sm:h-64 lg:h-96 object-cover rounded-xl shadow-md">
                @else
                    <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=1200&h=800&fit=crop"
                         alt="{{ $room->name }}"
                         class="w-full h-48 sm:h-64 lg:h-96 object-cover rounded-xl shadow-md">
                @endif
            </div>

            {{-- Content Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">

                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-4">

                    {{-- Info Kamar --}}
                    <div class="bg-white rounded-lg border border-gray-200 p-4">
                        <h2 class="text-lg font-bold text-gray-900 mb-3">Informasi Kamar</h2>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @if($room->size)
                            <div class="text-center">
                                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center mx-auto mb-1">
                                    <i class="fas fa-ruler-combined text-blue-600"></i>
                                </div>
                                <div class="text-xs text-gray-500">Ukuran</div>
                                <div class="text-sm font-semibold text-gray-900">{{ $room->size }}</div>
                            </div>
                            @endif
                            <div class="text-center">
                                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center mx-auto mb-1">
                                    <i class="fas fa-layer-group text-blue-600"></i>
                                </div>
                                <div class="text-xs text-gray-500">Lantai</div>
                                <div class="text-sm font-semibold text-gray-900">{{ $room->floor }}</div>
                            </div>
                            <div class="text-center">
                                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center mx-auto mb-1">
                                    <i class="fas fa-calendar-alt text-blue-600"></i>
                                </div>
                                <div class="text-xs text-gray-500">Periode</div>
                                <div class="text-sm font-semibold text-gray-900">
                                    @if($room->billing_cycle == 'daily') Harian
                                    @elseif($room->billing_cycle == 'weekly') Mingguan
                                    @elseif($room->billing_cycle == 'monthly') Bulanan
                                    @else Tahunan
                                    @endif
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center mx-auto mb-1">
                                    <i class="fas fa-star text-blue-600"></i>
                                </div>
                                <div class="text-xs text-gray-500">Rating</div>
                                <div class="text-sm font-semibold text-gray-900">{{ number_format($averageRating, 1) }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    @if($room->description)
                    <div class="bg-white rounded-lg border border-gray-200 p-4">
                        <h2 class="text-lg font-bold text-gray-900 mb-2">Deskripsi</h2>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $room->description }}</p>
                    </div>
                    @endif

                    {{-- Fasilitas --}}
                    <div class="bg-white rounded-lg border border-gray-200 p-4">
                        <h2 class="text-lg font-bold text-gray-900 mb-3">Fasilitas</h2>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            @if($room->facilities->count() > 0)
                                @foreach($room->facilities as $facility)
                                <div class="flex items-center gap-2 p-2 bg-blue-50 rounded-lg text-sm">
                                    <i class="{{ $facility->icon }} text-blue-600"></i>
                                    <span class="text-gray-900">{{ $facility->name }}</span>
                                </div>
                                @endforeach
                            @else
                                <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg text-sm">
                                    <i class="fas fa-bed text-gray-600"></i>
                                    <span class="text-gray-700">Kasur</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg border border-gray-200 p-4 lg:sticky lg:top-20">
                        <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-lg p-4 mb-4">
                            <div class="text-2xl font-bold text-blue-600">
                                Rp {{ number_format($room->price, 0, ',', '.') }}
                            </div>
                            <div class="text-sm text-gray-600">
                                per
                                @if($room->billing_cycle == 'daily') hari
                                @elseif($room->billing_cycle == 'weekly') minggu
                                @elseif($room->billing_cycle == 'monthly') bulan
                                @else tahun
                                @endif
                            </div>
                        </div>

                        <div class="space-y-2">
                            @if($room->status == 'available')
                                @auth('tenant')
                                    <a href="{{ route('tenant.booking.create', $room->id) }}"
                                    class="block w-full text-center bg-blue-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-blue-700 transition text-sm">
                                        <i class="fas fa-credit-card mr-1"></i>Pesan Sekarang
                                    </a>
                                @else
                                    <a href="{{ route('tenant.login') }}"
                                    class="block w-full text-center bg-blue-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-blue-700 transition text-sm">
                                        <i class="fas fa-sign-in-alt mr-1"></i>Login untuk Pesan
                                    </a>
                                @endauth

                                <a href="https://wa.me/6283841806357?text=Halo,%20saya%20tertarik%20dengan%20{{ urlencode($room->name) }}"
                                target="_blank"
                                class="block w-full text-center bg-green-500 text-white px-4 py-3 rounded-lg font-semibold hover:bg-green-600 transition text-sm">
                                    <i class="fab fa-whatsapp mr-1"></i>Tanya via WhatsApp
                                </a>
                            @else
                                <button disabled
                                        class="block w-full text-center bg-gray-200 text-gray-400 px-4 py-3 rounded-lg font-semibold cursor-not-allowed text-sm">
                                    <i class="fas fa-ban mr-1"></i>{{ $room->status == 'occupied' ? 'Tidak Tersedia' : 'Perbaikan' }}
                                </button>
                            @endif
                        </div>

                        <div class="mt-4 pt-4 border-t space-y-2 text-xs text-gray-600">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-shield-alt text-blue-600"></i>
                                <span>Pembayaran aman</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-undo text-blue-600"></i>
                                <span>Bisa refund</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-headset text-blue-600"></i>
                                <span>CS 24/7</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- REVIEWS SECTION --}}
    <section class="py-6 sm:py-8 bg-white border-t">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <div class="flex items-center gap-2">
                    <i class="fas fa-star text-blue-600 text-xl"></i>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900">
                        {{ number_format($averageRating, 1) }}
                        <span class="text-base text-gray-500 font-normal">({{ $totalReviews }} review)</span>
                    </h2>
                </div>

                @auth('tenant')
                    @php
                        $canReview = \App\Models\Resident::where('user_id', auth('tenant')->id())
                            ->where('room_id', $room->id)
                            ->whereIn('status', ['active', 'completed', 'moved_out'])
                            ->exists();
                        $hasReviewed = \App\Models\Review::where('room_id', $room->id)
                            ->where('user_id', auth('tenant')->id())
                            ->exists();
                    @endphp
                    @if($canReview && !$hasReviewed)
                        <button onclick="openReviewModal()"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition text-sm">
                            Tulis Ulasan
                        </button>
                    @endif
                @endauth
            </div>

            {{-- Category Ratings --}}
            @if(!empty($categoryAverages))
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
                @foreach($categoryAverages as $key => $category)
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <div class="text-xs text-gray-600 mb-1">{{ $category['name'] }}</div>
                    <div class="flex items-center justify-center gap-0.5 mb-1">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($category['score']))
                                <i class="fas fa-star text-gray-900 text-xs"></i>
                            @elseif($i == ceil($category['score']) && $category['score'] % 1 != 0)
                                <i class="fas fa-star-half-alt text-gray-900 text-xs"></i>
                            @else
                                <i class="far fa-star text-gray-300 text-xs"></i>
                            @endif
                        @endfor
                    </div>
                    <div class="text-sm font-bold text-gray-900">{{ number_format($category['score'], 1) }}</div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Reviews List --}}
            @if($reviews && $reviews->count() > 0)
                <div class="space-y-4">
                    @foreach($reviews as $review)
                    <div class="border-b border-gray-200 pb-4 last:border-0">

                        {{-- Header --}}
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div class="flex items-center gap-2 flex-1 min-w-0">
                                <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-user text-gray-500 text-sm"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="font-medium text-sm text-gray-900 truncate">{{ $review->user->name ?? 'Anonim' }}</div>
                                    <div class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 flex-shrink-0">
                                <div class="flex items-center gap-1 border border-gray-200 px-2 py-1 rounded">
                                    <i class="fas fa-star text-gray-900 text-xs"></i>
                                    <span class="font-bold text-sm">{{ number_format($review->rating, 1) }}</span>
                                </div>

                                @auth('tenant')
                                    @if($review->user_id == auth('tenant')->id())
                                    <div class="relative">
                                        <button onclick="toggleDropdown({{ $review->id }})" class="p-1.5 hover:bg-gray-100 rounded-full">
                                            <i class="fas fa-ellipsis-v text-gray-600 text-sm"></i>
                                        </button>
                                        <div id="dropdown-{{ $review->id }}" class="hidden absolute right-0 mt-1 w-32 bg-white rounded-lg shadow-lg border z-20 dropdown-menu">
                                            <button onclick="openEditModal({{ $review->id }}, '{{ addslashes($review->comment) }}', {{ $review->rating }})"
                                                    class="w-full text-left px-3 py-2 hover:bg-gray-50 text-xs text-gray-700 rounded-t-lg flex items-center gap-2">
                                                <i class="fas fa-edit text-blue-600"></i>Edit
                                            </button>
                                            <form action="{{ route('review.destroy', $review->id) }}" method="POST"
                                                  onsubmit="return confirm('Hapus ulasan?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full text-left px-3 py-2 hover:bg-gray-50 text-xs text-red-600 rounded-b-lg flex items-center gap-2">
                                                    <i class="fas fa-trash"></i>Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @endif
                                @endauth
                            </div>
                        </div>

                        {{-- Comment --}}
                        <p class="text-sm text-gray-700 mb-3 leading-relaxed">{{ $review->comment }}</p>

                        {{-- Category Insights (Auto-detected) --}}
                        @if($review->category_ratings && count($review->category_ratings) > 0)
                        <div class="flex flex-wrap gap-2 mb-3">
                            @foreach($review->category_ratings as $catKey => $score)
                                @php
                                    $catInfo = \App\Services\ReviewAutoScoring::getCategories()[$catKey] ?? null;
                                @endphp
                                @if($catInfo && $score > 0)
                                <div class="inline-flex items-center gap-1.5 bg-gray-50 border border-gray-200 px-2 py-1 rounded text-xs">
                                    <i class="{{ $catInfo['icon'] }} text-gray-600"></i>
                                    <span class="text-gray-700">{{ $catInfo['name'] }}</span>
                                    <span class="font-bold text-gray-900">{{ number_format($score, 1) }}</span>
                                </div>
                                @endif
                            @endforeach
                        </div>
                        @endif

                        {{-- Admin Reply --}}
                        @if(method_exists($review, 'hasReply') && $review->hasReply())
                            <div class="ml-0 sm:ml-12 bg-gray-50 border-l-4 border-gray-300 rounded-lg p-3">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="text-xs font-bold text-gray-900">Balasan dari Pemilik kos</div>
                                    <div class="text-xs text-gray-500">• {{ $review->latestReply->created_at->diffForHumans() }}</div>
                                </div>
                                <p class="text-sm text-gray-700">{{ $review->latestReply->reply }}</p>
                                @auth('web')
                                <button onclick="openReplyModal({{ $review->id }}, '{{ addslashes($review->latestReply->reply) }}')"
                                        class="mt-2 text-xs text-blue-600 hover:text-blue-700">
                                    Edit balasan
                                </button>
                                @endauth
                            </div>
                        @else
                            @auth('web')
                            <button onclick="openReplyModal({{ $review->id }})"
                                    class="ml-0 sm:ml-12 text-xs text-gray-600 hover:text-blue-600">
                                Balas
                            </button>
                            @endauth
                        @endif
                    </div>
                    @endforeach
                </div>

                @if(method_exists($reviews, 'hasPages') && $reviews->hasPages())
                <div class="mt-6">
                    {{ $reviews->links() }}
                </div>
                @endif
            @else
                <div class="text-center py-12">
                    <i class="fas fa-comments text-gray-300 text-5xl mb-3"></i>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Ulasan</h3>
                    <p class="text-sm text-gray-600">Jadilah yang pertama memberikan ulasan!</p>
                </div>
            @endif

        </div>
    </section>

    {{-- Similar Rooms --}}
    @if(isset($similarRooms) && $similarRooms->count() > 0)
    <section class="py-6 sm:py-8 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Kamar Lainnya</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($similarRooms as $similarRoom)
                    <div class="bg-white rounded-lg shadow hover:shadow-md transition overflow-hidden">
                        <div class="h-40">
                            @if($similarRoom->image)
                                <img src="{{ asset('storage/' . $similarRoom->image) }}" alt="{{ $similarRoom->name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=600&h=400&fit=crop"
                                     alt="{{ $similarRoom->name }}"
                                     class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-gray-900 mb-1 text-sm">{{ $similarRoom->name }}</h3>
                            <div class="text-lg font-bold text-blue-600 mb-2">
                                Rp {{ number_format($similarRoom->price, 0, ',', '.') }}
                            </div>
                            <a href="{{ route('rooms.detail', $similarRoom->id) }}"
                               class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- CREATE REVIEW MODAL --}}
    <div id="reviewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 modal-backdrop"
         onclick="if(event.target === this) closeReviewModal()">
        <div class="bg-white rounded-lg max-w-md w-full shadow-xl" onclick="event.stopPropagation()">

            <div class="p-4 border-b">
                <h3 class="text-lg font-bold text-gray-900">Tulis Ulasan</h3>
                <p class="text-xs text-gray-500 mt-0.5">Bagikan pengalaman Anda</p>
            </div>

            <form action="{{ route('room.review.store', $room->id) }}" method="POST" class="p-4">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                    <input type="hidden" name="rating" id="ratingValue" required>
                    <div class="flex gap-1 justify-center" id="starContainer">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" class="star-btn text-3xl text-gray-300 hover:text-yellow-400 transition" data-rating="{{ $i }}">
                                <i class="far fa-star"></i>
                            </button>
                        @endfor
                    </div>
                    <p class="text-center text-xs text-gray-500 mt-1" id="ratingText">Pilih rating</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ulasan</label>
                    <textarea name="comment" id="commentInput" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none text-sm"
                        placeholder="Ceritakan pengalaman Anda tentang kebersihan, keamanan, kenyamanan, fasilitas, dll..."
                        required minlength="10" maxlength="1000"></textarea>
                    <div class="flex justify-between mt-1">
                        <span class="text-xs text-gray-500">Min. 10 karakter</span>
                        <span class="text-xs text-gray-500" id="charCount">0/1000</span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="button" onclick="closeReviewModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 text-sm">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-blue-700 text-sm">
                        Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- EDIT REVIEW MODAL --}}
    <div id="editReviewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 modal-backdrop"
         onclick="if(event.target === this) closeEditModal()">
        <div class="bg-white rounded-lg max-w-md w-full shadow-xl" onclick="event.stopPropagation()">

            <div class="p-4 border-b">
                <h3 class="text-lg font-bold text-gray-900">Edit Ulasan</h3>
                <p class="text-xs text-gray-500 mt-0.5">Perbarui ulasan Anda</p>
            </div>

            <form id="editReviewForm" method="POST" class="p-4">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                    <input type="hidden" name="rating" id="editRatingValue" required>
                    <div class="flex gap-1 justify-center" id="editStarContainer">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" class="edit-star-btn text-3xl text-gray-300 hover:text-yellow-400 transition" data-rating="{{ $i }}">
                                <i class="far fa-star"></i>
                            </button>
                        @endfor
                    </div>
                    <p class="text-center text-xs text-gray-500 mt-1" id="editRatingText">Pilih rating</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ulasan</label>
                    <textarea name="comment" id="editCommentInput" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none text-sm"
                        required minlength="10" maxlength="1000"></textarea>
                    <div class="flex justify-between mt-1">
                        <span class="text-xs text-gray-500">Min. 10 karakter</span>
                        <span class="text-xs text-gray-500" id="editCharCount">0/1000</span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="button" onclick="closeEditModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 text-sm">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-blue-700 text-sm">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ADMIN REPLY MODAL --}}
    <div id="replyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 modal-backdrop"
         onclick="if(event.target === this) closeReplyModal()">
        <div class="bg-white rounded-lg max-w-md w-full shadow-xl" onclick="event.stopPropagation()">

            <div class="p-4 border-b">
                <h3 class="text-lg font-bold text-gray-900">Balas Ulasan</h3>
                <p class="text-xs text-gray-500 mt-0.5">Sebagai pemilik kos</p>
            </div>

            <form id="replyForm" method="POST" class="p-4">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Balasan</label>
                    <textarea name="reply" id="replyInput" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none text-sm"
                        placeholder="Terima kasih atas ulasannya..."
                        required minlength="10" maxlength="500"></textarea>
                    <div class="flex justify-between mt-1">
                        <span class="text-xs text-gray-500">Min. 10 karakter</span>
                        <span class="text-xs text-gray-500" id="replyCharCount">0/500</span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="button" onclick="closeReplyModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 text-sm">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-blue-700 text-sm">
                        Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>

    @include('landing.footer')

<script>
// Dropdown
function toggleDropdown(reviewId) {
    const dropdown = document.getElementById('dropdown-' + reviewId);
    document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
        if (d.id !== 'dropdown-' + reviewId) d.classList.add('hidden');
    });
    dropdown.classList.toggle('hidden');
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('button')) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(d => d.classList.add('hidden'));
    }
});

// Create Review
function openReviewModal() {
    document.getElementById('reviewModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    setupStars();
}

function closeReviewModal() {
    document.getElementById('reviewModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    resetStars();
}

function setupStars() {
    document.querySelectorAll('.star-btn').forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.dataset.rating;
            document.getElementById('ratingValue').value = rating;
            updateStars(rating);
            updateRatingText(rating);
        });
    });
}

function updateStars(rating) {
    document.querySelectorAll('.star-btn').forEach((star, i) => {
        const icon = star.querySelector('i');
        if (i < rating) {
            icon.classList.remove('far');
            icon.classList.add('fas', 'text-yellow-400');
            star.classList.remove('text-gray-300');
        } else {
            icon.classList.remove('fas', 'text-yellow-400');
            icon.classList.add('far');
            star.classList.add('text-gray-300');
        }
    });
}

function resetStars() {
    document.querySelectorAll('.star-btn').forEach(star => {
        const icon = star.querySelector('i');
        icon.classList.remove('fas', 'text-yellow-400');
        icon.classList.add('far');
        star.classList.add('text-gray-300');
    });
    document.getElementById('ratingValue').value = '';
    document.getElementById('commentInput').value = '';
    document.getElementById('ratingText').textContent = 'Pilih rating';
    document.getElementById('charCount').textContent = '0/1000';
}

function updateRatingText(rating) {
    const texts = ['', 'Buruk', 'Kurang', 'Cukup', 'Bagus', 'Sangat Bagus'];
    document.getElementById('ratingText').textContent = texts[rating] || 'Pilih rating';
}

document.getElementById('commentInput')?.addEventListener('input', function() {
    document.getElementById('charCount').textContent = this.value.length + '/1000';
});

// Edit Review
function openEditModal(reviewId, comment, rating) {
    document.getElementById('editReviewModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    document.getElementById('editReviewForm').action = '/review/' + reviewId;
    document.getElementById('editCommentInput').value = comment;
    document.getElementById('editRatingValue').value = rating;
    updateEditStars(rating);
    updateEditRatingText(rating);
    document.getElementById('editCharCount').textContent = comment.length + '/1000';
    setupEditStars();
}

function closeEditModal() {
    document.getElementById('editReviewModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function setupEditStars() {
    document.querySelectorAll('.edit-star-btn').forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.dataset.rating;
            document.getElementById('editRatingValue').value = rating;
            updateEditStars(rating);
            updateEditRatingText(rating);
        });
    });
}

function updateEditStars(rating) {
    document.querySelectorAll('.edit-star-btn').forEach((star, i) => {
        const icon = star.querySelector('i');
        if (i < rating) {
            icon.classList.remove('far');
            icon.classList.add('fas', 'text-yellow-400');
            star.classList.remove('text-gray-300');
        } else {
            icon.classList.remove('fas', 'text-yellow-400');
            icon.classList.add('far');
            star.classList.add('text-gray-300');
        }
    });
}

function updateEditRatingText(rating) {
    const texts = ['', 'Buruk', 'Kurang', 'Cukup', 'Bagus', 'Sangat Bagus'];
    document.getElementById('editRatingText').textContent = texts[rating] || 'Pilih rating';
}

document.getElementById('editCommentInput')?.addEventListener('input', function() {
    document.getElementById('editCharCount').textContent = this.value.length + '/1000';
});

// Reply
function openReplyModal(reviewId, existingReply = '') {
    document.getElementById('replyModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    document.getElementById('replyForm').action = '/admin/review/' + reviewId + '/reply';
    document.getElementById('replyInput').value = existingReply;
    document.getElementById('replyCharCount').textContent = existingReply.length + '/500';
}

function closeReplyModal() {
    document.getElementById('replyModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

document.getElementById('replyInput')?.addEventListener('input', function() {
    document.getElementById('replyCharCount').textContent = this.value.length + '/500';
});

// ESC close
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeReviewModal();
        closeEditModal();
        closeReplyModal();
    }
});

// Auto dismiss alerts
setTimeout(() => {
    document.querySelectorAll('.fixed.top-20').forEach(alert => {
        alert.style.opacity = '0';
        alert.style.transition = 'opacity 0.3s';
        setTimeout(() => alert.remove(), 300);
    });
}, 5000);
</script>

</body>
</html>
