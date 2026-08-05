<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Reservasi - {{ $room->name }} | Cemara Residence</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #FAFAFC; color: #0F172A; }
        .font-heading { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="antialiased selection:bg-amber-500 selection:text-slate-950">

    @include('landing.navbar')

    @php
        $tenantUser = Auth::user() ?? Auth::guard('tenant')->user();
        $roomImg = $room->image ? (str_starts_with($room->image, 'http') ? $room->image : asset('storage/' . $room->image)) : asset('images/room-default.webp');
    @endphp

    <!-- Breadcrumb -->
    <div class="bg-white border-b border-slate-100 py-3.5 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto flex items-center gap-2 text-xs font-semibold text-slate-500">
            <a href="{{ route('landing') }}" class="hover:text-amber-600 transition-colors">Beranda</a>
            <i class="fas fa-chevron-right text-[10px] text-slate-300"></i>
            <a href="{{ route('rooms.show', $room->id) }}" class="hover:text-amber-600 transition-colors">{{ $room->name }}</a>
            <i class="fas fa-chevron-right text-[10px] text-slate-300"></i>
            <span class="text-slate-900 font-bold">Reservasi & Checkout</span>
        </div>
    </div>

    <main class="py-8 sm:py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-[11px] font-black uppercase tracking-wider">
                Instant Reservation
            </span>
            <h1 class="text-2xl sm:text-4xl font-extrabold font-heading text-slate-900 tracking-tight mt-2">
                Konfirmasi Reservasi Kamar
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Lengkapi data sewa Anda di bawah untuk mengamankan kamar impian Anda secara instan.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- LEFT FORM (8 COLS) -->
            <div class="lg:col-span-7 space-y-6">

                @if(session('error') || $errors->any())
                    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold space-y-1">
                        @if(session('error'))
                            <div>{{ session('error') }}</div>
                        @endif
                        @foreach($errors->all() as $error)
                            <div>• {{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('tenant.booking.store', $room->id) }}" method="POST" id="bookingForm" class="space-y-6">
                    @csrf

                    <!-- STEP 1: GUEST INFO -->
                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                        <div class="flex items-center gap-3 border-b border-slate-100 pb-3">
                            <div class="w-8 h-8 rounded-xl bg-slate-900 text-amber-400 font-black text-xs flex items-center justify-center">1</div>
                            <h2 class="text-base font-bold font-heading text-slate-900">Data Penyewa Terverifikasi</h2>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100">
                                <div class="text-[10px] uppercase font-bold text-slate-400">Nama Lengkap</div>
                                <div class="text-xs font-bold text-slate-900 truncate mt-0.5">{{ $tenantUser->name ?? '-' }}</div>
                            </div>
                            <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100">
                                <div class="text-[10px] uppercase font-bold text-slate-400">Email Akun</div>
                                <div class="text-xs font-bold text-slate-900 truncate mt-0.5">{{ $tenantUser->email ?? '-' }}</div>
                            </div>
                            <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100">
                                <div class="text-[10px] uppercase font-bold text-slate-400">No. WhatsApp</div>
                                <div class="text-xs font-bold text-slate-900 mt-0.5">{{ $tenantUser->profile->phone ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2: LEASE PERIOD -->
                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                        <div class="flex items-center gap-3 border-b border-slate-100 pb-3">
                            <div class="w-8 h-8 rounded-xl bg-slate-900 text-amber-400 font-black text-xs flex items-center justify-center">2</div>
                            <h2 class="text-base font-bold font-heading text-slate-900">Pilih Periode Sewa</h2>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Tanggal Mulai Check-in <span class="text-rose-500">*</span>
                                </label>
                                <input type="date" name="start_date" id="start_date" 
                                       min="{{ date('Y-m-d') }}" value="{{ old('start_date', date('Y-m-d')) }}" 
                                       required 
                                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-900 focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Durasi Kontrak <span class="text-rose-500">*</span>
                                </label>
                                <select name="duration_months" id="duration_months" required 
                                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-900 focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all outline-none">
                                    <option value="">-- Pilih Durasi --</option>
                                    <option value="1" {{ old('duration_months') == 1 ? 'selected' : '' }}>1 Bulan (Standar)</option>
                                    <option value="3" {{ old('duration_months') == 3 ? 'selected' : '' }}>3 Bulan (Diskon 5%)</option>
                                    <option value="6" {{ old('duration_months') == 6 ? 'selected' : '' }}>6 Bulan (Diskon 10%)</option>
                                    <option value="12" {{ old('duration_months') == 12 ? 'selected' : '' }}>12 Bulan (Best Value 15%)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Live Dates Breakdown -->
                        <div id="contract_info" class="hidden grid grid-cols-2 sm:grid-cols-4 gap-2 pt-2">
                            <div class="p-3 bg-slate-50 rounded-xl text-center border border-slate-100">
                                <div class="text-[10px] text-slate-400 uppercase font-bold">Check-in</div>
                                <div class="text-xs font-bold text-slate-900 mt-0.5" id="info_start">-</div>
                            </div>
                            <div class="p-3 bg-slate-50 rounded-xl text-center border border-slate-100">
                                <div class="text-[10px] text-slate-400 uppercase font-bold">Berakhir</div>
                                <div class="text-xs font-bold text-slate-900 mt-0.5" id="info_end">-</div>
                            </div>
                            <div class="p-3 bg-slate-50 rounded-xl text-center border border-slate-100">
                                <div class="text-[10px] text-slate-400 uppercase font-bold">Total Durasi</div>
                                <div class="text-xs font-bold text-slate-900 mt-0.5" id="info_duration">-</div>
                            </div>
                            <div class="p-3 bg-slate-50 rounded-xl text-center border border-slate-100">
                                <div class="text-[10px] text-slate-400 uppercase font-bold">Jatuh Tempo</div>
                                <div class="text-xs font-bold text-amber-600 mt-0.5">Tgl <span id="info_due">-</span></div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3: PAYMENT GATEWAY SELECTION -->
                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                        <div class="flex items-center gap-3 border-b border-slate-100 pb-3">
                            <div class="w-8 h-8 rounded-xl bg-slate-900 text-amber-400 font-black text-xs flex items-center justify-center">3</div>
                            <h2 class="text-base font-bold font-heading text-slate-900">Metode Pembayaran Resmi</h2>
                        </div>

                        <div class="p-4 rounded-2xl bg-gradient-to-r from-slate-900 to-indigo-950 text-white flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-amber-500 text-slate-950 flex items-center justify-center text-lg font-black shrink-0 shadow-md">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="space-y-1">
                                <div class="font-bold text-sm">Midtrans Automated Gateway</div>
                                <p class="text-xs text-slate-300 leading-relaxed">
                                    Mendukung QRIS, BCA, Mandiri, BRI, BNI Virtual Account, GoPay, dan ShopeePay. Verifikasi otomatis dalam hitungan detik.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 4: TERMS & CONDITIONS -->
                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-3">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="agree_terms" required 
                                   class="w-4 h-4 rounded text-amber-600 focus:ring-amber-500 border-slate-300 mt-0.5">
                            <span class="text-xs text-slate-600 leading-relaxed">
                                Saya menyetujui seluruh tata tertib dan ketentuan sewa hunian di <strong>Cemara Living & Residence</strong>, serta bersedia mematuhi jadwal pembayaran tepat waktu.
                            </span>
                        </label>
                    </div>

                    <!-- SUBMIT ACTIONS -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <a href="{{ route('rooms.show', $room->id) }}" 
                           class="px-6 py-4 rounded-2xl border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 text-center transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        <button type="submit" 
                                class="flex-1 py-4 rounded-2xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 hover:from-amber-500 hover:to-amber-600 hover:text-slate-950 text-white font-black text-sm shadow-xl hover:shadow-2xl transition-all duration-300 flex items-center justify-center gap-2">
                            <i class="fas fa-lock text-amber-400"></i>
                            <span>Konfirmasi & Lanjut ke Pembayaran Midtrans</span>
                        </button>
                    </div>

                </form>

            </div>

            <!-- RIGHT SUMMARY (5 COLS) -->
            <div class="lg:col-span-5">
                <div class="sticky top-28 bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
                    <div class="relative h-48 bg-slate-900">
                        <img src="{{ $roomImg }}" alt="{{ $room->name }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 right-4 text-white">
                            <span class="px-2.5 py-0.5 rounded-lg bg-amber-500 text-slate-950 text-[10px] font-black uppercase">
                                Lantai {{ $room->floor }} • {{ $room->size ?? 24 }} m²
                            </span>
                            <h3 class="text-lg font-black font-heading text-white mt-1">{{ $room->name }}</h3>
                            <p class="text-xs text-slate-300 truncate">{{ $globalProperty->address ?? 'Setiabudi, Bandung' }}</p>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between text-slate-500">
                                <span>Harga Sewa / bulan</span>
                                <span class="font-bold text-slate-900">Rp {{ number_format($room->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-slate-500">
                                <span>Fasilitas Air & WiFi 100Mbps</span>
                                <span class="font-bold text-emerald-600">GRATIS</span>
                            </div>
                            <div class="flex justify-between text-slate-500">
                                <span>Durasi Kontrak</span>
                                <span class="font-bold text-slate-900" id="summary_duration">—</span>
                            </div>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-900 text-white">
                            <div class="text-[10px] text-amber-400 font-bold uppercase tracking-wider">Bayar Sekarang (Bulan 1)</div>
                            <div class="text-2xl font-black font-heading mt-0.5" id="summary_pay_now">
                                Rp {{ number_format($room->price, 0, ',', '.') }}
                            </div>
                            <div class="text-[10px] text-slate-400 mt-1">Pembayaran bulan berikutnya jatuh tempo tiap tanggal sewa.</div>
                        </div>

                        <div class="pt-2 border-t border-slate-100 space-y-2 text-[11px] text-slate-500">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-shield-alt text-emerald-500"></i>
                                <span>Garansi Transaksi Aman Midtrans SSL</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-file-invoice text-blue-500"></i>
                                <span>Kwitansi & Invoice Otomatis Terbit</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </main>

    @include('landing.footer')

    <script>
        const startDateInput = document.getElementById('start_date');
        const durationInput  = document.getElementById('duration_months');
        const pricePerMonth  = {{ $room->price }};

        function formatDate(date) {
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
        }

        function updateBookingCalc() {
            const startDateVal = startDateInput.value;
            const durationVal  = parseInt(durationInput.value);

            if (startDateVal && durationVal) {
                const start = new Date(startDateVal);
                const end = new Date(start);
                end.setMonth(end.getMonth() + durationVal);

                document.getElementById('info_start').textContent = formatDate(start);
                document.getElementById('info_end').textContent = formatDate(end);
                document.getElementById('info_duration').textContent = durationVal + ' Bulan';
                document.getElementById('info_due').textContent = start.getDate();
                document.getElementById('contract_info').classList.remove('hidden');

                document.getElementById('summary_duration').textContent = durationVal + ' Bulan';
            } else {
                document.getElementById('contract_info').classList.add('hidden');
                document.getElementById('summary_duration').textContent = '—';
            }
        }

        startDateInput.addEventListener('change', updateBookingCalc);
        durationInput.addEventListener('change', updateBookingCalc);

        if (startDateInput.value && durationInput.value) {
            updateBookingCalc();
        }
    </script>
</body>
</html>