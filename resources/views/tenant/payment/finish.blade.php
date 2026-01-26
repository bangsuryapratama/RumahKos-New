<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pembayaran - RumahKos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">

    @include('landing.navbar')

    <section class="pt-24 pb-12 min-h-screen flex items-center">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            
            <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12 text-center">
                
                @if($payment->status === 'paid')
                    {{-- Success --}}
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-check-circle text-5xl text-green-600"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-3">Pembayaran Berhasil!</h1>
                    <p class="text-gray-600 mb-8">Terima kasih, pembayaran Anda telah berhasil diproses</p>
                    
                @elseif($payment->status === 'pending')
                    {{-- Pending --}}
                    <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-clock text-5xl text-yellow-600"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-3">Pembayaran Diproses</h1>
                    <p class="text-gray-600 mb-8">Pembayaran Anda sedang diverifikasi, harap tunggu beberapa saat</p>
                    
                @else
                    {{-- Failed --}}
                    <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-times-circle text-5xl text-red-600"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-3">Pembayaran Gagal</h1>
                    <p class="text-gray-600 mb-8">Maaf, pembayaran Anda tidak dapat diproses</p>
                @endif

                {{-- Payment Details --}}
                <div class="bg-gray-50 rounded-xl p-6 mb-8 text-left">
                    <h3 class="font-bold text-gray-900 mb-4">Detail Pembayaran</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Nomor Pesanan</span>
                            <span class="font-semibold text-gray-900">{{ $payment->order_id ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Periode</span>
                            <span class="font-semibold text-gray-900">{{ $payment->billing_month->format('F Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Kamar</span>
                            <span class="font-semibold text-gray-900">{{ $payment->resident->room->name }}</span>
                        </div>
                        <div class="flex justify-between border-t pt-3">
                            <span class="text-gray-900 font-semibold">Total Pembayaran</span>
                            <span class="text-lg font-bold text-blue-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                        </div>
                        @if($payment->status === 'paid')
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tanggal Bayar</span>
                                <span class="font-semibold text-gray-900">{{ $payment->paid_at->format('d M Y H:i') }}</span>
                            </div>
                        @endif
                        @if($payment->transaction_id)
                            <div class="flex justify-between">
                                <span class="text-gray-600">ID Transaksi</span>
                                <span class="font-mono text-xs text-gray-900">{{ $payment->transaction_id }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('tenant.bookings.index') }}" 
                       class="px-6 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition">
                        <i class="fas fa-receipt mr-2"></i>Lihat Riwayat Pembayaran
                    </a>
                    <a href="{{ route('tenant.dashboard') }}" 
                       class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition">
                        <i class="fas fa-home mr-2"></i>Kembali ke Dashboard
                    </a>
                </div>

                @if($payment->status === 'failed')
                    <div class="mt-6">
                        <a href="{{ route('tenant.payment.midtrans', $payment->id) }}" 
                           class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                            <i class="fas fa-redo mr-1"></i>Coba Bayar Lagi
                        </a>
                    </div>
                @endif

                {{-- Contact Support --}}
                @if($payment->status === 'pending' || $payment->status === 'failed')
                    <div class="mt-8 pt-6 border-t text-sm text-gray-600">
                        <p class="mb-2">Butuh bantuan?</p>
                        <div class="flex items-center justify-center gap-4">
                            @if($contact && $contact->whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contact->whatsapp) }}" 
                                   target="_blank"
                                   class="text-green-600 hover:text-green-700 font-semibold">
                                    <i class="fab fa-whatsapp mr-1"></i>WhatsApp
                                </a>
                            @endif
                            @if($contact && $contact->phone)
                                <a href="tel:{{ $contact->phone }}" 
                                   class="text-blue-600 hover:text-blue-700 font-semibold">
                                    <i class="fas fa-phone mr-1"></i>Telepon
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </section>

    @include('landing.footer')

</body>
</html>