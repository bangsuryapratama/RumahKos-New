<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi & Tagihan Saya - Cemara Residence</title>
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

    <!-- Breadcrumbs -->
    <div class="bg-white border-b border-slate-100 py-3.5 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto flex items-center gap-2 text-xs font-semibold text-slate-500">
            <a href="{{ route('landing') }}" class="hover:text-amber-600 transition-colors">Beranda</a>
            <i class="fas fa-chevron-right text-[10px] text-slate-300"></i>
            <a href="{{ route('tenant.dashboard') }}" class="hover:text-amber-600 transition-colors">Portal Penghuni</a>
            <i class="fas fa-chevron-right text-[10px] text-slate-300"></i>
            <span class="text-slate-900 font-bold">Reservasi & Tagihan</span>
        </div>
    </div>

    <main class="py-8 sm:py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-[11px] font-black uppercase tracking-wider">
                Resident Portal
            </span>
            <h1 class="text-2xl sm:text-4xl font-extrabold font-heading text-slate-900 tracking-tight mt-2">
                Daftar Reservasi & Pembayaran
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Pantau masa sewa hunian, unduh faktur resmi, dan lunasi tagihan berjalan.
            </p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-semibold flex items-center gap-2">
                <i class="fas fa-check-circle text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 text-rose-800 border border-rose-200 text-xs font-semibold flex items-center gap-2">
                <i class="fas fa-exclamation-circle text-base"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($residents->isEmpty())
            <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center shadow-sm space-y-4 max-w-md mx-auto">
                <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl mx-auto">
                    <i class="fas fa-bed"></i>
                </div>
                <h3 class="text-lg font-bold font-heading text-slate-900">Belum Ada Riwayat Sewa</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Anda belum memesan kamar apapun. Temukan kamar eksklusif dengan fasilitas lengkap sekarang.
                </p>
                <a href="{{ route('landing') }}#kamar" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-slate-900 text-amber-400 hover:bg-slate-800 font-bold text-xs shadow-lg transition-all">
                    <span>Jelajahi Kamar Tersedia</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        @else
            <div class="space-y-8">
                @foreach($residents as $resident)
                    @php
                        $firstUnpaid = $resident->payments->whereNotIn('status', ['paid', 'cancelled'])->sortBy('billing_month')->first();
                    @endphp

                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-md overflow-hidden">
                        
                        <!-- CARD HEADER -->
                        <div class="p-6 bg-gradient-to-r from-slate-950 via-slate-900 to-indigo-950 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <span class="px-2.5 py-0.5 rounded-lg bg-amber-500 text-slate-950 text-[10px] font-black uppercase">
                                    Lantai {{ $resident->room->floor }} • {{ $resident->room->size ?? 24 }} m²
                                </span>
                                <h3 class="text-xl font-black font-heading text-white mt-1">{{ $resident->room->name }}</h3>
                                <p class="text-xs text-slate-300 flex items-center gap-1.5 mt-0.5">
                                    <i class="fas fa-map-marker-alt text-amber-400"></i>
                                    <span>{{ $resident->room->property->name ?? 'Cemara Residence Bandung' }}</span>
                                </p>
                            </div>

                            <div>
                                @if($resident->status === 'active')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-xs font-bold">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                        Sewa Aktif
                                    </span>
                                @elseif($resident->status === 'inactive')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-500/20 text-amber-300 border border-amber-400/30 text-xs font-bold">
                                        <i class="fas fa-clock"></i>
                                        Menunggu Pembayaran
                                    </span>
                                @elseif($resident->status === 'cancelled')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-rose-500/20 text-rose-300 border border-rose-400/30 text-xs font-bold">
                                        <i class="fas fa-ban"></i>
                                        Dibatalkan
                                    </span>
                                @else
                                    <span class="px-3 py-1.5 rounded-full bg-slate-800 text-slate-300 text-xs font-bold">
                                        {{ ucfirst($resident->status) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- CONTRACT DETAILS -->
                        <div class="p-6 border-b border-slate-100 grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                                <div class="text-[10px] text-slate-400 font-bold uppercase">Tanggal Masuk</div>
                                <div class="text-xs font-bold text-slate-900 mt-0.5">{{ $resident->start_date->format('d M Y') }}</div>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                                <div class="text-[10px] text-slate-400 font-bold uppercase">Tanggal Berakhir</div>
                                <div class="text-xs font-bold text-slate-900 mt-0.5">{{ $resident->end_date->format('d M Y') }}</div>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                                <div class="text-[10px] text-slate-400 font-bold uppercase">Total Durasi</div>
                                <div class="text-xs font-bold text-slate-900 mt-0.5">{{ $resident->getDurationInMonths() }} Bulan</div>
                            </div>
                        </div>

                        <!-- BILLING INVOICES TABLE -->
                        <div class="p-6 space-y-4">
                            <h4 class="text-sm font-bold font-heading text-slate-900 uppercase tracking-wider">Jadwal Tagihan & Faktur</h4>
                            <div class="overflow-x-auto">
                                <table class="w-full text-xs">
                                    <thead>
                                        <tr class="text-slate-400 border-b border-slate-100 text-[10px] uppercase font-bold text-left">
                                            <th class="pb-3">Periode</th>
                                            <th class="pb-3">Jatuh Tempo</th>
                                            <th class="pb-3">Nominal</th>
                                            <th class="pb-3">Status</th>
                                            <th class="pb-3 text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 font-medium">
                                        @forelse($resident->payments as $payment)
                                            <tr>
                                                <td class="py-3 font-bold text-slate-900">{{ $payment->billing_month ? $payment->billing_month->format('F Y') : '-' }}</td>
                                                <td class="py-3 text-slate-600">{{ $payment->due_date ? $payment->due_date->format('d M Y') : '-' }}</td>
                                                <td class="py-3 font-black text-slate-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                                <td class="py-3">
                                                    @if($payment->status === 'paid')
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">
                                                            <i class="fas fa-check"></i> Lunas
                                                        </span>
                                                    @elseif($payment->status === 'pending')
                                                        @if($firstUnpaid && $payment->id === $firstUnpaid->id)
                                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold">
                                                                <i class="fas fa-clock"></i> Belum Bayar
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 text-[10px] font-bold">
                                                                <i class="fas fa-lock"></i> Terkunci
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold">{{ ucfirst($payment->status) }}</span>
                                                    @endif
                                                </td>
                                                <td class="py-3 text-right">
                                                    @if($payment->status === 'pending')
                                                        @if($firstUnpaid && $payment->id === $firstUnpaid->id)
                                                            <a href="{{ route('tenant.payment.midtrans', $payment->id) }}" 
                                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-900 text-amber-400 hover:bg-slate-800 text-xs font-bold shadow-sm transition-all">
                                                                <i class="fas fa-credit-card"></i> Bayar
                                                            </a>
                                                        @else
                                                            <span class="text-[10px] text-slate-400 italic">Bulan sebelumnya belum lunas</span>
                                                        @endif
                                                    @elseif($payment->status === 'paid')
                                                        <a href="{{ route('tenant.payment.invoice', $payment->id) }}" target="_blank"
                                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 text-xs font-bold transition-all">
                                                            <i class="fas fa-file-invoice"></i> Faktur Resmi
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="py-4 text-center text-slate-400">Belum ada tagihan terbit.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </main>

    @include('landing.footer')

</body>
</html>