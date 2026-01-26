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
    </style>
</head>
<body class="bg-gray-50">

    {{-- Navbar --}}
    @include('landing.navbar')

    {{-- Breadcrumb --}}
    <section class="bg-white border-b pt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <a href="{{ route('tenant.dashboard') }}" class="hover:text-blue-600 transition">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('rooms.detail', $room->id) }}" class="hover:text-blue-600 transition">{{ $room->name }}</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-gray-900 font-medium">Booking</span>
            </div>
        </div>
    </section>

    {{-- Main Content --}}
    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Left: Booking Form --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
                        
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Form Booking Kamar</h1>
                        <p class="text-gray-600 mb-8">Isi data booking Anda dengan lengkap</p>

                        @if(session('error'))
                            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>{{ session('error') }}</span>
                                </div>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                                <div class="font-semibold text-red-700 mb-2">
                                    <i class="fas fa-exclamation-circle mr-2"></i>Terdapat kesalahan:
                                </div>
                                <ul class="list-disc list-inside text-red-700 text-sm">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('tenant.booking.store', $room->id) }}" method="POST" id="bookingForm">
                            @csrf

                            {{-- Informasi Penyewa --}}
                            <div class="mb-8">
                                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <i class="fas fa-user text-blue-600"></i>
                                    Informasi Penyewa
                                </h2>
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <div class="text-sm text-gray-600">Nama Lengkap</div>
                                            <div class="font-semibold text-gray-900">{{ Auth::guard('tenant')->user()->name }}</div>
                                        </div>
                                        <div>
                                            <div class="text-sm text-gray-600">Email</div>
                                            <div class="font-semibold text-gray-900">{{ Auth::guard('tenant')->user()->email }}</div>
                                        </div>
                                        <div>
                                            <div class="text-sm text-gray-600">No. Telepon</div>
                                            <div class="font-semibold text-gray-900">{{ Auth::guard('tenant')->user()->phone ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Periode Sewa --}}
                            <div class="mb-8">
                                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <i class="fas fa-calendar-alt text-blue-600"></i>
                                    Periode Sewa
                                </h2>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {{-- Tanggal Mulai --}}
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Tanggal Mulai Sewa <span class="text-red-500">*</span>
                                        </label>
                                        <input type="date" 
                                               name="start_date" 
                                               id="start_date"
                                               min="{{ date('Y-m-d') }}"
                                               value="{{ old('start_date', date('Y-m-d')) }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               required>
                                        @error('start_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Durasi Sewa --}}
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Durasi Kontrak <span class="text-red-500">*</span>
                                        </label>
                                        <select name="duration_months" 
                                                id="duration_months"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                required>
                                            <option value="">Pilih Durasi</option>
                                            <option value="1" {{ old('duration_months') == 1 ? 'selected' : '' }}>1 Bulan</option>
                                            <option value="3" {{ old('duration_months') == 3 ? 'selected' : '' }}>3 Bulan</option>
                                            <option value="6" {{ old('duration_months') == 6 ? 'selected' : '' }}>6 Bulan</option>
                                            <option value="12" {{ old('duration_months') == 12 ? 'selected' : '' }}>12 Bulan (1 Tahun)</option>
                                        </select>
                                        @error('duration_months')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Info Kontrak --}}
                                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <div class="text-sm space-y-1">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-info-circle text-blue-600"></i>
                                            <span class="text-gray-700">
                                                Kontrak berakhir: <strong id="end_date_display">-</strong>
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-calendar-check text-blue-600"></i>
                                            <span class="text-gray-700">
                                                Total durasi: <strong id="total_duration">-</strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Metode Pembayaran --}}
                            <div class="mb-8">
                                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <i class="fas fa-credit-card text-blue-600"></i>
                                    Metode Pembayaran
                                </h2>

                                <div class="p-6 border-2 border-blue-500 rounded-lg bg-blue-50">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="font-bold text-lg text-gray-900 mb-2">
                                                <i class="fas fa-shield-alt text-blue-600 mr-2"></i>
                                                Payment Gateway (Midtrans)
                                            </div>
                                            <p class="text-sm text-gray-600 mb-3">
                                                Pembayaran aman dengan berbagai metode: Kartu Kredit, Transfer Bank, E-Wallet
                                            </p>
                                            <div class="flex gap-3 items-center flex-wrap">
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/200px-Visa_Inc._logo.svg.png" alt="Visa" class="h-6">
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/200px-Mastercard-logo.svg.png" alt="Mastercard" class="h-6">
                                                <span class="text-xs text-gray-500">+ Bank Transfer, GoPay, OVO, Dana</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <i class="fas fa-check-circle text-4xl text-blue-600"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Info Pembayaran --}}
                            <div class="mb-8">
                                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
                                    <div class="flex gap-3">
                                        <i class="fas fa-exclamation-triangle text-yellow-600 mt-1"></i>
                                        <div class="text-sm text-gray-700">
                                            <p class="font-semibold mb-2">Informasi Pembayaran:</p>
                                            <ul class="list-disc list-inside space-y-1">
                                                <li>Pembayaran dilakukan <strong>per bulan</strong></li>
                                                <li>Bayar <strong>bulan pertama</strong> saat booking untuk konfirmasi</li>
                                                <li>Pembayaran bulan berikutnya jatuh tempo setiap tanggal <strong id="payment_date">-</strong></li>
                                                <li>Notifikasi pembayaran akan dikirim 3 hari sebelum jatuh tempo</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Terms & Conditions --}}
                            <div class="mb-8">
                                <label class="flex items-start gap-3 cursor-pointer">
                                    <input type="checkbox" 
                                           name="agree_terms" 
                                           class="w-5 h-5 text-blue-600 rounded mt-1"
                                           required>
                                    <span class="text-sm text-gray-700">
                                        Saya menyetujui <a href="#" class="text-blue-600 hover:underline">syarat dan ketentuan</a> yang berlaku, 
                                        serta bersedia mematuhi peraturan kosan dan membayar setiap bulan tepat waktu.
                                    </span>
                                </label>
                            </div>

                            {{-- Submit Button --}}
                            <div class="flex flex-col sm:flex-row gap-4">
                                <a href="{{ route('rooms.detail', $room->id) }}" 
                                   class="flex-1 px-6 py-4 border-2 border-gray-300 text-gray-700 rounded-xl font-bold text-center hover:bg-gray-50 transition">
                                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                                </a>
                                <button type="submit" 
                                        class="flex-1 px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl font-bold hover:from-blue-700 hover:to-blue-800 transition shadow-lg hover:shadow-xl">
                                    <i class="fas fa-check-circle mr-2"></i>Konfirmasi Booking
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

                {{-- Right: Room Summary --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                        
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Ringkasan Booking</h3>

                        {{-- Room Image --}}
                        <div class="mb-4">
                            @if($room->image)
                                <img src="{{ asset('storage/' . $room->image) }}" 
                                     alt="{{ $room->name }}" 
                                     class="w-full h-48 object-cover rounded-lg">
                            @else
                                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=600&h=400&fit=crop" 
                                     alt="{{ $room->name }}" 
                                     class="w-full h-48 object-cover rounded-lg">
                            @endif
                        </div>

                        {{-- Room Info --}}
                        <div class="mb-4 pb-4 border-b">
                            <h4 class="font-bold text-lg text-gray-900">{{ $room->name }}</h4>
                            <p class="text-sm text-gray-600">{{ $room->property->name }}</p>
                            <div class="flex items-center gap-2 mt-2 text-sm text-gray-600">
                                @if($room->size)
                                    <span><i class="fas fa-ruler-combined mr-1"></i>{{ $room->size }}</span>
                                @endif
                                <span><i class="fas fa-layer-group mr-1"></i>Lt. {{ $room->floor }}</span>
                            </div>
                        </div>

                        {{-- Price Breakdown --}}
                        <div class="space-y-3 mb-4 pb-4 border-b">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Harga per bulan</span>
                                <span class="font-semibold text-gray-900">Rp {{ number_format($room->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Durasi kontrak</span>
                                <span class="font-semibold text-gray-900" id="duration_display">- bulan</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Sistem pembayaran</span>
                                <span class="font-semibold text-blue-600">Per Bulan</span>
                            </div>
                        </div>

                        {{-- Total to Pay Now --}}
                        <div class="bg-gradient-to-br from-blue-50 to-purple-50 p-4 rounded-lg border border-blue-100 mb-4">
                            <div class="text-xs text-gray-600 mb-1">Bayar Sekarang (Bulan Pertama)</div>
                            <div class="text-2xl font-bold text-blue-600">Rp {{ number_format($room->price, 0, ',', '.') }}</div>
                        </div>

                        {{-- Next Payment Info --}}
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="text-xs text-gray-600 mb-2">Pembayaran Selanjutnya</div>
                            <div class="space-y-1 text-sm text-gray-700">
                                <div class="flex justify-between">
                                    <span>Jumlah</span>
                                    <span class="font-semibold">Rp {{ number_format($room->price, 0, ',', '.') }}/bulan</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Sisa cicilan</span>
                                    <span class="font-semibold" id="remaining_months">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Total kontrak</span>
                                    <span class="font-semibold text-blue-600" id="total_contract">-</span>
                                </div>
                            </div>
                        </div>

                        {{-- Additional Info --}}
                        <div class="mt-6 pt-4 border-t space-y-2 text-xs text-gray-600">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-shield-alt text-green-600"></i>
                                <span>Pembayaran aman & terverifikasi</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-bell text-green-600"></i>
                                <span>Reminder otomatis sebelum jatuh tempo</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-headset text-green-600"></i>
                                <span>Customer service siap membantu</span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </section>

    {{-- Footer --}}
    @include('landing.footer')

    <script>
        const startDateInput = document.getElementById('start_date');
        const durationInput = document.getElementById('duration_months');
        const pricePerMonth = {{ $room->price }};

        function calculateBooking() {
            const startDate = startDateInput.value;
            const duration = parseInt(durationInput.value);

            if (startDate && duration) {
                const start = new Date(startDate);
                const end = new Date(start);
                end.setMonth(end.getMonth() + duration);
                
                // Format tanggal berakhir
                const endDateFormatted = end.toLocaleDateString('id-ID', { 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });
                
                // Format tanggal pembayaran (sama dengan tanggal mulai setiap bulan)
                const paymentDate = start.getDate();
                
                document.getElementById('end_date_display').textContent = endDateFormatted;
                document.getElementById('total_duration').textContent = duration + ' bulan';
                document.getElementById('duration_display').textContent = duration + ' bulan';
                document.getElementById('payment_date').textContent = paymentDate;
                
                // Sisa pembayaran (total - 1 karena bulan pertama sudah dibayar)
                const remainingMonths = duration - 1;
                document.getElementById('remaining_months').textContent = remainingMonths + ' bulan';
                
                // Total kontrak
                const totalContract = pricePerMonth * duration;
                document.getElementById('total_contract').textContent = 'Rp ' + totalContract.toLocaleString('id-ID');
                
            } else {
                document.getElementById('end_date_display').textContent = '-';
                document.getElementById('total_duration').textContent = '-';
                document.getElementById('duration_display').textContent = '- bulan';
                document.getElementById('payment_date').textContent = '-';
                document.getElementById('remaining_months').textContent = '-';
                document.getElementById('total_contract').textContent = '-';
            }
        }

        startDateInput.addEventListener('change', calculateBooking);
        durationInput.addEventListener('change', calculateBooking);

        // Initial calculation
        if (startDateInput.value && durationInput.value) {
            calculateBooking();
        }
    </script>

</body>
</html>