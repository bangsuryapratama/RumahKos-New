<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Kamar - {{ $room->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        html { scroll-behavior: smooth; }
        .payment-method-card {
            transition: all 0.2s ease;
        }
        .step-line::after {
            content: '';
            position: absolute;
            left: 50%;
            top: 100%;
            width: 2px;
            height: 100%;
            background: #e5e7eb;
            transform: translateX(-50%);
        }
    </style>
</head>
<body class="bg-gray-50">

    @include('landing.navbar')

    {{-- Breadcrumb --}}
    <section class="bg-white border-b pt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4">
            <div class="flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-gray-500 flex-wrap">
                <a href="{{ route('tenant.dashboard') }}" class="hover:text-blue-600 transition flex items-center gap-1">
                    <i class="fas fa-home"></i>
                    <span class="hidden sm:inline">Dashboard</span>
                </a>
                <i class="fas fa-chevron-right text-xs text-gray-300"></i>
                <a href="{{ route('rooms.detail', $room->id) }}" class="hover:text-blue-600 transition truncate max-w-[120px] sm:max-w-none">{{ $room->name }}</a>
                <i class="fas fa-chevron-right text-xs text-gray-300"></i>
                <span class="text-gray-900 font-medium">Booking</span>
            </div>
        </div>
    </section>

    <section class="py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Page Header --}}
            <div class="mb-6 sm:mb-8">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Booking Kamar</h1>
                <p class="text-sm text-gray-500 mt-1">Lengkapi data di bawah untuk menyelesaikan booking</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

                {{-- LEFT: Form --}}
                <div class="lg:col-span-2 space-y-5">

                    {{-- Alert Error --}}
                    @if(session('error') || $errors->any())
                        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm flex gap-3">
                            <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
                            <div>
                                @if(session('error'))
                                    {{ session('error') }}
                                @endif
                                @if($errors->any())
                                    <ul class="list-disc list-inside space-y-0.5">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('tenant.booking.store', $room->id) }}" method="POST" id="bookingForm">
                        @csrf

                        {{-- Step 1: Info Penyewa --}}
                        <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                            <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs sm:text-sm font-bold flex-shrink-0">1</div>
                                <h2 class="font-semibold text-gray-900 text-sm sm:text-base">Informasi Penyewa</h2>
                            </div>
                            <div class="p-5 sm:p-6">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                                    <div class="p-3 sm:p-4 bg-gray-50 rounded-lg sm:rounded-xl">
                                        <div class="text-xs text-gray-500 mb-1">Nama Lengkap</div>
                                        <div class="font-semibold text-gray-900 text-sm truncate">{{ Auth::guard('tenant')->user()->name }}</div>
                                    </div>
                                    <div class="p-3 sm:p-4 bg-gray-50 rounded-lg sm:rounded-xl">
                                        <div class="text-xs text-gray-500 mb-1">Email</div>
                                        <div class="font-semibold text-gray-900 text-sm truncate">{{ Auth::guard('tenant')->user()->email }}</div>
                                    </div>
                                    <div class="p-3 sm:p-4 bg-gray-50 rounded-lg sm:rounded-xl">
                                        <div class="text-xs text-gray-500 mb-1">No. Telepon</div>
                                        <div class="font-semibold text-gray-900 text-sm">{{ Auth::guard('tenant')->user()->profile->phone ?? '-' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Step 2: Periode Sewa --}}
                        <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                            <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs sm:text-sm font-bold flex-shrink-0">2</div>
                                <h2 class="font-semibold text-gray-900 text-sm sm:text-base">Periode Sewa</h2>
                            </div>
                            <div class="p-5 sm:p-6 space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">
                                            Tanggal Mulai <span class="text-red-500">*</span>
                                        </label>
                                        <input type="date"
                                               name="start_date"
                                               id="start_date"
                                               min="{{ date('Y-m-d') }}"
                                               value="{{ old('start_date', date('Y-m-d')) }}"
                                               class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-200 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition"
                                               required>
                                        @error('start_date')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">
                                            Durasi Kontrak <span class="text-red-500">*</span>
                                        </label>
                                        <select name="duration_months"
                                                id="duration_months"
                                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-200 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition"
                                                required>
                                            <option value="">Pilih Durasi</option>
                                            <option value="1"  {{ old('duration_months') == 1  ? 'selected' : '' }}>1 Bulan</option>
                                            <option value="3"  {{ old('duration_months') == 3  ? 'selected' : '' }}>3 Bulan</option>
                                            <option value="6"  {{ old('duration_months') == 6  ? 'selected' : '' }}>6 Bulan</option>
                                            <option value="12" {{ old('duration_months') == 12 ? 'selected' : '' }}>12 Bulan (1 Tahun)</option>
                                        </select>
                                        @error('duration_months')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Contract Info --}}
                                <div id="contract_info" class="hidden grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    <div class="p-3 bg-blue-50 rounded-lg text-center">
                                        <div class="text-xs text-gray-500 mb-1">Mulai</div>
                                        <div class="text-xs sm:text-sm font-semibold text-gray-900" id="info_start">-</div>
                                    </div>
                                    <div class="p-3 bg-blue-50 rounded-lg text-center">
                                        <div class="text-xs text-gray-500 mb-1">Berakhir</div>
                                        <div class="text-xs sm:text-sm font-semibold text-gray-900" id="info_end">-</div>
                                    </div>
                                    <div class="p-3 bg-blue-50 rounded-lg text-center">
                                        <div class="text-xs text-gray-500 mb-1">Durasi</div>
                                        <div class="text-xs sm:text-sm font-semibold text-gray-900" id="info_duration">-</div>
                                    </div>
                                    <div class="p-3 bg-blue-50 rounded-lg text-center">
                                        <div class="text-xs text-gray-500 mb-1">Jatuh Tempo</div>
                                        <div class="text-xs sm:text-sm font-semibold text-gray-900">Tgl <span id="info_due">-</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Step 3: Metode Pembayaran --}}
                     
                        <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                            <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs sm:text-sm font-bold flex-shrink-0">3</div>
                                <h2 class="font-semibold text-gray-900 text-sm sm:text-base">Metode Pembayaran</h2>
                            </div>
                            <div class="p-5 sm:p-6">
                                <div class="flex items-center gap-4 p-4 sm:p-5 border-2 border-blue-500 bg-blue-50 rounded-xl">
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-shield-alt text-white text-base sm:text-lg"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-900 text-sm sm:text-base">Midtrans Payment Gateway</div>
                                        <div class="text-xs text-gray-500 mt-0.5 mb-3">Berbagai metode pembayaran tersedia</div>
                                    <div class="flex-1 min-w-0">
                                  
                                {{-- E-Wallet --}}
                                <div class="mb-3">
                                    <p class="text-xs text-gray-400 mb-2 font-medium uppercase tracking-wide">E-Wallet & QRIS</p>
                                    <div class="flex items-center gap-3 flex-wrap">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/86/Gopay_logo.svg/120px-Gopay_logo.svg.png"
                                            alt="GoPay" class="h-5 object-contain"
                                            onerror="this.style.display='none'">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/eb/Logo_ovo_purple.svg/120px-Logo_ovo_purple.svg.png"
                                            alt="OVO" class="h-5 object-contain"
                                            onerror="this.style.display='none'">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/72/Logo_dana_blue.svg/120px-Logo_dana_blue.svg.png"
                                            alt="DANA" class="h-5 object-contain"
                                            onerror="this.style.display='none'">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f1/ShopeePay.svg/120px-ShopeePay.svg.png"
                                            alt="ShopeePay" class="h-5 object-contain"
                                            onerror="this.style.display='none'">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Logo_QRIS.svg/120px-Logo_QRIS.svg.png"
                                            alt="QRIS" class="h-5 object-contain"
                                            onerror="this.style.display='none'">
                                    </div>
                                </div>

                                {{-- Transfer Bank --}}
                                <div>
                                    <p class="text-xs text-gray-400 mb-2 font-medium uppercase tracking-wide">Transfer Bank</p>
                                    <div class="flex items-center gap-3 flex-wrap">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/120px-Bank_Central_Asia.svg.png"
                                            alt="BCA" class="h-4 object-contain"
                                            onerror="this.style.display='none'">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/120px-Bank_Mandiri_logo_2016.svg.png"
                                            alt="Mandiri" class="h-4 object-contain"
                                            onerror="this.style.display='none'">
                                        <img src="https://upload.wikimedia.org/wikipedia/id/thumb/5/55/BNI_logo.svg/120px-BNI_logo.svg.png"
                                            alt="BNI" class="h-4 object-contain"
                                            onerror="this.style.display='none'">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/68/BANK_BRI_logo.svg/120px-BANK_BRI_logo.svg.png"
                                            alt="BRI" class="h-4 object-contain"
                                            onerror="this.style.display='none'">
                                    </div>
                                </div>
                            </div>
                                    </div>
                                    <i class="fas fa-check-circle text-blue-600 text-xl sm:text-2xl flex-shrink-0 self-start"></i>
                                </div>

                                {{-- Info Box --}}
                                <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                                    <div class="flex gap-3">
                                        <i class="fas fa-info-circle text-amber-500 mt-0.5 flex-shrink-0 text-sm"></i>
                                        <div class="text-xs sm:text-sm text-gray-700 space-y-1">
                                            <p class="font-medium text-gray-900">Informasi Pembayaran</p>
                                            <p>• Pembayaran dilakukan <strong>per bulan</strong></p>
                                            <p>• Bayar <strong>bulan pertama</strong> sekarang untuk konfirmasi booking</p>
                                            <p>• Pembayaran bulan berikutnya jatuh tempo tiap tanggal <strong id="payment_date">-</strong></p>
                                            <p>• Notifikasi dikirim 3 hari sebelum jatuh tempo</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Step 4: Syarat & Ketentuan --}}
                        <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                            <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs sm:text-sm font-bold flex-shrink-0">4</div>
                                <h2 class="font-semibold text-gray-900 text-sm sm:text-base">Syarat & Ketentuan</h2>
                            </div>
                            <div class="p-5 sm:p-6">
                                <label class="flex items-start gap-3 cursor-pointer group">
                                    <input type="checkbox"
                                           name="agree_terms"
                                           class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 rounded mt-0.5 flex-shrink-0"
                                           required>
                                    <span class="text-xs sm:text-sm text-gray-600 group-hover:text-gray-900 transition">
                                        Saya menyetujui <a href="#" onclick="openTermsModal(event)" class="text-blue-600 hover:underline font-medium">
                                            syarat dan ketentuan
                                        </a> yang berlaku,
                                        serta bersedia mematuhi peraturan kosan dan membayar setiap bulan tepat waktu.
                                    </span>
                                </label>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('rooms.detail', $room->id) }}"
                               class="flex-1 px-6 py-3 sm:py-3.5 border-2 border-gray-200 text-gray-700 rounded-xl font-semibold text-center hover:bg-gray-50 hover:border-gray-300 transition text-sm active:scale-[0.98]">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                            <button type="submit"
                                    class="flex-1 px-6 py-3 sm:py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 transition shadow-md hover:shadow-lg text-sm active:scale-[0.98]">
                                <i class="fas fa-check-circle mr-2"></i>Konfirmasi & Bayar
                            </button>
                        </div>

                    </form>
                </div>

                {{-- RIGHT: Summary --}}
                <div class="lg:col-span-1 order-first lg:order-last">
                    <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 shadow-sm lg:sticky lg:top-24 overflow-hidden">

                        {{-- Room Image --}}
                        <div class="relative">
                            @if($room->image)
                                <img src="{{ asset('storage/' . $room->image) }}"
                                     alt="{{ $room->name }}"
                                     class="w-full h-40 sm:h-48 object-cover">
                            @else
                                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=600&h=400&fit=crop"
                                     alt="{{ $room->name }}"
                                     class="w-full h-40 sm:h-48 object-cover">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <h3 class="font-bold text-white text-base sm:text-lg leading-tight">{{ $room->name }}</h3>
                                <p class="text-white/80 text-xs sm:text-sm">{{ $room->property->name }}</p>
                            </div>
                        </div>

                        <div class="p-4 sm:p-5 space-y-4">

                            {{-- Room Meta --}}
                            <div class="flex items-center gap-3 text-xs sm:text-sm text-gray-500">
                                @if($room->size)
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-ruler-combined text-gray-400"></i>{{ $room->size }}
                                    </span>
                                @endif
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-layer-group text-gray-400"></i>Lantai {{ $room->floor }}
                                </span>
                            </div>

                            {{-- Price Breakdown --}}
                            <div class="space-y-2.5 pt-2 border-t border-gray-100">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Harga / bulan</span>
                                    <span class="font-semibold text-gray-900">Rp {{ number_format($room->price, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Durasi</span>
                                    <span class="font-semibold text-gray-900" id="duration_display">— bulan</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Sistem bayar</span>
                                    <span class="font-semibold text-blue-600">Per Bulan</span>
                                </div>
                            </div>

                            {{-- Pay Now --}}
                            <div class="bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl p-4 text-white">
                                <div class="text-xs opacity-80 mb-1">Bayar Sekarang (Bulan 1)</div>
                                <div class="text-2xl sm:text-3xl font-bold">Rp {{ number_format($room->price, 0, ',', '.') }}</div>
                                <div class="text-xs opacity-70 mt-1">Bulan berikutnya dibayar terpisah</div>
                            </div>

                            {{-- Contract Summary --}}
                            <div id="contract_summary" class="hidden space-y-2 pt-2 border-t border-gray-100">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Ringkasan Kontrak</p>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Sisa cicilan</span>
                                    <span class="font-semibold text-gray-900" id="remaining_months">-</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Total kontrak</span>
                                    <span class="font-bold text-blue-600" id="total_contract">-</span>
                                </div>
                            </div>

                            {{-- Trust Badges --}}
                            <div class="pt-3 border-t border-gray-100 space-y-2">
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <i class="fas fa-shield-alt text-green-500 w-4"></i>
                                    <span>Pembayaran aman & terverifikasi</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <i class="fas fa-bell text-green-500 w-4"></i>
                                    <span>Reminder otomatis jatuh tempo</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <i class="fas fa-headset text-green-500 w-4"></i>
                                    <span>Customer service siap membantu</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    @include('landing.footer')

    <script>
        const startDateInput   = document.getElementById('start_date');
        const durationInput    = document.getElementById('duration_months');
        const pricePerMonth    = {{ $room->price }};

        function formatDate(date) {
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
        }

        function calculateBooking() {
            const startDate = startDateInput.value;
            const duration  = parseInt(durationInput.value);

            if (startDate && duration) {
                const start = new Date(startDate);
                const end   = new Date(start);
                end.setMonth(end.getMonth() + duration);

                const remaining     = duration - 1;
                const totalContract = pricePerMonth * duration;

                // Contract info bar
                document.getElementById('info_start').textContent    = formatDate(start);
                document.getElementById('info_end').textContent      = formatDate(end);
                document.getElementById('info_duration').textContent = duration + ' bulan';
                document.getElementById('info_due').textContent      = start.getDate();
                document.getElementById('contract_info').classList.remove('hidden');
                document.getElementById('contract_info').classList.add('grid');

                // Sidebar
                document.getElementById('duration_display').textContent  = duration + ' bulan';
                document.getElementById('remaining_months').textContent  = remaining > 0 ? remaining + ' bulan lagi' : 'Selesai setelah bulan ini';
                document.getElementById('total_contract').textContent    = 'Rp ' + totalContract.toLocaleString('id-ID');
                document.getElementById('contract_summary').classList.remove('hidden');

                // Payment date info
                document.getElementById('payment_date').textContent = start.getDate();

            } else {
                document.getElementById('contract_info').classList.add('hidden');
                document.getElementById('contract_info').classList.remove('grid');
                document.getElementById('contract_summary').classList.add('hidden');
                document.getElementById('duration_display').textContent = '— bulan';
                document.getElementById('payment_date').textContent     = '-';
            }
        }

        startDateInput.addEventListener('change', calculateBooking);
        durationInput.addEventListener('change', calculateBooking);

        if (startDateInput.value && durationInput.value) {
            calculateBooking();
        }
    </script>
    <script>
document.addEventListener("DOMContentLoaded", function () {

    function openTermsModal(e) {
        e.preventDefault();
        const modal = document.getElementById('termsModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeTermsModal() {
        const modal = document.getElementById('termsModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function acceptTerms() {
        closeTermsModal();
        document.querySelector('input[name="agree_terms"]').checked = true;
    }

    window.openTermsModal = openTermsModal;
    window.closeTermsModal = closeTermsModal;
    window.acceptTerms = acceptTerms;

    const termsContent = document.getElementById('termsContent');
    const agreeBtn = document.getElementById('agreeBtn');

    if (termsContent && agreeBtn) {
        termsContent.addEventListener('scroll', function () {
            if (termsContent.scrollTop + termsContent.clientHeight >= termsContent.scrollHeight - 5) {
                agreeBtn.disabled = false;
                agreeBtn.classList.remove('bg-gray-300', 'cursor-not-allowed');
                agreeBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
            }
        });
    }

});
</script>
    <div id="termsModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl p-6 relative animate-[fadeIn_.3s_ease]">
        
        <!-- close -->
        <button onclick="closeTermsModal()" 
            class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-xl">
            &times;
        </button>

        <h2 class="text-lg font-bold text-gray-900 mb-3">
            Syarat & Ketentuan Kos
        </h2>

        <div id="termsContent" class="text-sm text-gray-600 max-h-64 overflow-y-auto space-y-2 pr-2">
            <p>Dengan melakukan booking kamar, penyewa menyetujui ketentuan berikut:</p>
            
            <ul class="list-disc pl-5 space-y-1">
                <li>Pembayaran bulan pertama wajib dilakukan untuk konfirmasi booking.</li>
                <li>Pembayaran selanjutnya dilakukan setiap bulan sebelum tanggal jatuh tempo.</li>
                <li>Keterlambatan pembayaran dapat dikenakan denda sesuai kebijakan kos.</li>
                <li>Dilarang melakukan aktivitas yang melanggar hukum di area kos.</li>
                <li>Menjaga kebersihan dan ketertiban lingkungan kos.</li>
                <li>Pemilik kos berhak mengakhiri sewa jika terjadi pelanggaran.</li>
            </ul>

            <p class="text-xs text-gray-400 pt-2">Scroll sampai bawah untuk menyetujui</p>
        </div>

        <button id="agreeBtn" onclick="acceptTerms()"
            class="mt-4 w-full bg-gray-300 text-white py-2 rounded-lg text-sm font-medium cursor-not-allowed"
            disabled>
            Saya Setuju
        </button>
    </div>
</div>

</body>
</html>