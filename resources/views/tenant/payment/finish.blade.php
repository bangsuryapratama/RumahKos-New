<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">

    {{-- Navbar --}}
    @include('landing.navbar')

    <div class="min-h-screen flex items-center justify-center px-4 py-24">
        <div class="max-w-lg w-full">
            
            <div class="bg-white rounded-2xl shadow-2xl p-8 text-center">
                
                @if($payment->status === 'paid')
                    {{-- Success --}}
                    <div class="relative">
                        <div class="w-24 h-24 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl animate-bounce">
                            <i class="fas fa-check text-5xl text-white"></i>
                        </div>
                        <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-32 h-32 bg-green-200 rounded-full blur-3xl opacity-30 -z-10"></div>
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">Pembayaran Berhasil!</h1>
                    <p class="text-gray-600 mb-8 text-lg">
                        Selamat! Pembayaran Anda telah dikonfirmasi. <br>
                        Kamar Anda sudah siap untuk ditempati.
                    </p>
                    
                @elseif($payment->status === 'pending')
                    {{-- Pending --}}
                    <div class="relative">
                        <div class="w-24 h-24 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl">
                            <i class="fas fa-clock text-5xl text-white"></i>
                        </div>
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">Menunggu Pembayaran</h1>
                    <p class="text-gray-600 mb-8 text-lg">
                        Pembayaran Anda sedang diproses. <br>
                        Silakan selesaikan pembayaran sesuai instruksi yang diberikan.
                    </p>
                    
                @else
                    {{-- Failed --}}
                    <div class="relative">
                        <div class="w-24 h-24 bg-gradient-to-br from-red-400 to-red-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl">
                            <i class="fas fa-times text-5xl text-white"></i>
                        </div>
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">Pembayaran Gagal</h1>
                    <p class="text-gray-600 mb-8 text-lg">
                        Maaf, pembayaran Anda tidak dapat diproses. <br>
                        Silakan coba lagi atau hubungi customer service.
                    </p>
                @endif

                {{-- Payment Info Card --}}
                <div class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl p-6 mb-8 text-left border border-blue-100">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Status Pembayaran</span>
                            <span class="font-bold px-4 py-2 rounded-full text-sm
                                {{ $payment->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $payment->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}">
                                @if($payment->status === 'paid')
                                    <i class="fas fa-check-circle mr-1"></i> Lunas
                                @elseif($payment->status === 'pending')
                                    <i class="fas fa-clock mr-1"></i> Pending
                                @else
                                    <i class="fas fa-times-circle mr-1"></i> Gagal
                                @endif
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                            <span class="text-sm font-medium text-gray-600">Total Pembayaran</span>
                            <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </span>
                        </div>
                        
                        @if($payment->transaction_id)
                        <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                            <span class="text-sm font-medium text-gray-600">ID Transaksi</span>
                            <span class="font-mono text-xs text-gray-700 bg-gray-100 px-3 py-1 rounded">
                                {{ $payment->transaction_id }}
                            </span>
                        </div>
                        @endif

                        @if($payment->paid_at)
                        <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                            <span class="text-sm font-medium text-gray-600">Tanggal Bayar</span>
                            <span class="font-semibold text-gray-900">
                                {{ \Carbon\Carbon::parse($payment->paid_at)->format('d M Y, H:i') }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <div class="space-y-3">
                    @if($payment->status === 'paid')
                        <a href="{{ route('tenant.dashboard') }}" 
                           class="block w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-4 rounded-xl font-bold hover:from-blue-700 hover:to-purple-700 transition shadow-lg transform hover:scale-105 duration-200">
                            <i class="fas fa-home mr-2"></i>Lihat Dashboard Saya
                        </a>
                    @else
                        <a href="{{ route('tenant.payment.midtrans', $payment->id) }}" 
                           class="block w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-4 rounded-xl font-bold hover:from-blue-700 hover:to-purple-700 transition shadow-lg transform hover:scale-105 duration-200">
                            <i class="fas fa-redo mr-2"></i>Coba Bayar Lagi
                        </a>
                    @endif
                    
                    <a href="{{ route('tenant.bookings.index') }}" 
                       class="block w-full border-2 border-gray-300 text-gray-700 py-4 rounded-xl font-bold hover:bg-gray-50 transition">
                        <i class="fas fa-list mr-2"></i>Lihat Riwayat Booking
                    </a>
                </div>

                {{-- Help Section --}}
                @if($payment->status !== 'paid')
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-600 mb-3">
                        <i class="fas fa-headset mr-2"></i>Butuh bantuan?
                    </p>
                    <div class="flex justify-center gap-4">
                        @if($contact && $contact->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contact->whatsapp) }}" 
                           target="_blank"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-lg text-sm font-medium hover:bg-green-600 transition">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                        @endif
                        @if($contact && $contact->phone)
                        <a href="tel:{{ $contact->phone }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600 transition">
                            <i class="fas fa-phone"></i> Telepon
                        </a>
                        @endif
                    </div>
                </div>
                @endif

            </div>

        </div>
    </div>

    {{-- Footer --}}
    @include('landing.footer')

</body>
</html>