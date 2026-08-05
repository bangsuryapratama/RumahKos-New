<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerbang Pembayaran - {{ $room->name }} | Cemara Residence</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Midtrans Snap JS -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #FAFAFC; color: #0F172A; }
        .font-heading { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="antialiased selection:bg-amber-500 selection:text-slate-950">

    @include('landing.navbar')

    @php
        $roomImg = $room->image ? (str_starts_with($room->image, 'http') ? $room->image : asset('storage/' . $room->image)) : asset('images/room-default.webp');
        $whatsappNum = preg_replace('/[^0-9]/', '', $contact->whatsapp ?? '6281234567890');
    @endphp

    <main class="py-10 sm:py-16 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-8">
            <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-[11px] font-black uppercase tracking-wider">
                Langkah Terakhir Reservasi
            </span>
            <h1 class="text-2xl sm:text-4xl font-extrabold font-heading text-slate-900 tracking-tight mt-2">
                Checkout & Pembayaran Aman
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Selesaikan transaksi melalui Midtrans untuk aktivasi reservasi Anda secara instan.
            </p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xl overflow-hidden">
            
            <!-- ROOM SUMMARY HEADER -->
            <div class="p-6 sm:p-8 bg-gradient-to-r from-slate-950 via-slate-900 to-indigo-950 text-white flex flex-col sm:flex-row items-center gap-6">
                <img src="{{ $roomImg }}" alt="{{ $room->name }}" class="w-24 h-24 rounded-2xl object-cover shrink-0 shadow-md">
                <div class="text-center sm:text-left space-y-1">
                    <span class="px-2.5 py-0.5 rounded-lg bg-amber-500 text-slate-950 text-[10px] font-black uppercase">
                        Kamar Terpilih
                    </span>
                    <h2 class="text-xl sm:text-2xl font-black font-heading">{{ $room->name }}</h2>
                    <p class="text-xs text-slate-300">
                        Lantai {{ $room->floor }} • {{ $room->size ?? 24 }} m² • {{ $globalProperty->address ?? 'Setiabudi, Bandung' }}
                    </p>
                </div>
            </div>

            <!-- INVOICE BREAKDOWN -->
            <div class="p-6 sm:p-8 space-y-6">
                <div class="space-y-3 border-b border-slate-100 pb-6 text-xs sm:text-sm">
                    <div class="flex justify-between text-slate-600">
                        <span>Nomor Invoice / Referensi</span>
                        <span class="font-bold text-slate-900 font-mono">#INV-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Periode Tagihan</span>
                        <span class="font-bold text-slate-900">{{ $payment->billing_month ? $payment->billing_month->format('F Y') : date('F Y') }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Jatuh Tempo Pembayaran</span>
                        <span class="font-bold text-slate-900">{{ $payment->due_date ? $payment->due_date->format('d M Y') : date('d M Y') }}</span>
                    </div>
                </div>

                <!-- TOTAL BOX -->
                <div class="p-5 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-between">
                    <div>
                        <div class="text-xs font-bold text-slate-700 uppercase tracking-wider">Total Tagihan (Bulan 1)</div>
                        <div class="text-[11px] text-slate-500">Termasuk fasilitas WiFi 100Mbps & Air Bersih</div>
                    </div>
                    <div class="text-2xl sm:text-3xl font-black text-slate-950 font-heading">
                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                    </div>
                </div>

                <!-- MIDTRANS CTA BUTTON -->
                <div class="pt-4 space-y-3">
                    <button id="pay-button" 
                            class="w-full py-4 rounded-2xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 hover:from-amber-500 hover:to-amber-600 hover:text-slate-950 text-white font-black text-sm shadow-xl hover:shadow-2xl transition-all duration-300 flex items-center justify-center gap-2">
                        <i class="fas fa-lock text-amber-400"></i>
                        <span>Bayar Sekarang (Buka Jendela Midtrans)</span>
                    </button>
                    
                    <div class="flex items-center justify-center gap-4 text-[11px] text-slate-400">
                        <div class="flex items-center gap-1.5"><i class="fas fa-shield-alt text-emerald-500"></i> Enkripsi 256-Bit SSL</div>
                        <div class="flex items-center gap-1.5"><i class="fas fa-check-circle text-amber-500"></i> Midtrans Verified Gateway</div>
                    </div>
                </div>
            </div>

        </div>

    </main>

    @include('landing.footer')

    <script type="text/javascript">
        const payButton = document.getElementById('pay-button');

        payButton.addEventListener('click', function () {
            payButton.disabled = true;
            payButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Membuka Gerbang Midtrans...';

            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    window.location.href = "{{ route('tenant.payment.finish', $payment->id) }}";
                },
                onPending: function(result) {
                    window.location.href = "{{ route('tenant.payment.finish', $payment->id) }}";
                },
                onError: function(result) {
                    alert('Pembayaran gagal atau dibatalkan. Silakan coba metode pembayaran lain.');
                    payButton.disabled = false;
                    payButton.innerHTML = '<i class="fas fa-lock mr-2 text-amber-400"></i><span>Bayar Sekarang</span>';
                },
                onClose: function() {
                    payButton.disabled = false;
                    payButton.innerHTML = '<i class="fas fa-lock mr-2 text-amber-400"></i><span>Bayar Sekarang</span>';
                }
            });
        });
    </script>
</body>
</html>
