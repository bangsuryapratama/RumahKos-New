<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pembayaran - RumahKos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scaleIn {
            from {
                transform: scale(0.5);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        .animate-scale-in {
            animation: scaleIn 0.5s ease-out;
        }
    </style>
</head>
<body class="bg-gray-50">

    @include('landing.navbar')

    <section class="pt-20 sm:pt-24 pb-12 min-h-screen flex items-center">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 w-full">

            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg sm:shadow-xl p-6 sm:p-8 md:p-12 text-center animate-fade-in-up">

                @if($payment->status === 'paid')
                    {{-- Success --}}
                    <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gradient-to-br from-green-100 to-emerald-100 rounded-full flex items-center justify-center mx-auto mb-5 sm:mb-6 animate-scale-in shadow-lg">
                        <i class="fas fa-check-circle text-4xl sm:text-5xl text-green-600"></i>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2 sm:mb-3">Pembayaran Berhasil!</h1>
                    <p class="text-sm sm:text-base text-gray-600 mb-6 sm:mb-8">Terima kasih, pembayaran Anda telah berhasil diproses</p>

                @elseif($payment->status === 'pending')
                    {{-- Pending --}}
                    <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gradient-to-br from-yellow-100 to-amber-100 rounded-full flex items-center justify-center mx-auto mb-5 sm:mb-6 animate-scale-in shadow-lg">
                        <i class="fas fa-clock text-4xl sm:text-5xl text-yellow-600"></i>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2 sm:mb-3">Pembayaran Diproses</h1>
                    <p class="text-sm sm:text-base text-gray-600 mb-6 sm:mb-8">Pembayaran Anda sedang diverifikasi, harap tunggu beberapa saat</p>

                @else
                    {{-- Failed --}}
                    <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gradient-to-br from-red-100 to-rose-100 rounded-full flex items-center justify-center mx-auto mb-5 sm:mb-6 animate-scale-in shadow-lg">
                        <i class="fas fa-times-circle text-4xl sm:text-5xl text-red-600"></i>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2 sm:mb-3">Pembayaran Gagal</h1>
                    <p class="text-sm sm:text-base text-gray-600 mb-6 sm:mb-8">Maaf, pembayaran Anda tidak dapat diproses</p>
                @endif

                {{-- Payment Details --}}
                <div class="bg-gray-50 rounded-lg sm:rounded-xl p-4 sm:p-6 mb-6 sm:mb-8 text-left">
                    <h3 class="font-bold text-gray-900 mb-3 sm:mb-4 text-base sm:text-lg">Detail Pembayaran</h3>

                    <div class="space-y-2.5 sm:space-y-3 text-xs sm:text-sm">
                        <div class="flex justify-between items-start gap-2">
                            <span class="text-gray-600">Nomor Pesanan</span>
                            <span class="font-semibold text-gray-900 text-right break-all">{{ $payment->order_id ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-start gap-2">
                            <span class="text-gray-600">Periode</span>
                            <span class="font-semibold text-gray-900 text-right">{{ $payment->billing_month->format('F Y') }}</span>
                        </div>
                        <div class="flex justify-between items-start gap-2">
                            <span class="text-gray-600">Kamar</span>
                            <span class="font-semibold text-gray-900 text-right">{{ $payment->resident->room->name }}</span>
                        </div>
                        <div class="flex justify-between items-center gap-2 border-t border-gray-200 pt-2.5 sm:pt-3 mt-3">
                            <span class="text-gray-900 font-semibold text-sm sm:text-base">Total Pembayaran</span>
                            <span class="text-base sm:text-lg font-bold text-blue-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                        </div>
                        @if($payment->status === 'paid' && $payment->paid_at)
                            <div class="flex justify-between items-start gap-2">
                                <span class="text-gray-600">Tanggal Bayar</span>
                                <span class="font-semibold text-gray-900 text-right">{{ $payment->paid_at->format('d M Y H:i') }}</span>
                            </div>
                        @endif
                        @if($payment->transaction_id)
                            <div class="flex justify-between items-start gap-2">
                                <span class="text-gray-600">ID Transaksi</span>
                                <span class="font-mono text-xs text-gray-900 text-right break-all">{{ $payment->transaction_id }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
                    <a href="{{ route('tenant.bookings.index') }}"
                       class="px-5 sm:px-6 py-2.5 sm:py-3 bg-blue-600 text-white rounded-lg sm:rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg text-sm sm:text-base active:scale-[0.98]">
                        <i class="fas fa-receipt mr-2"></i>Lihat Riwayat Pembayaran
                    </a>
                    <a href="{{ route('tenant.dashboard') }}"
                       class="px-5 sm:px-6 py-2.5 sm:py-3 border-2 border-gray-300 text-gray-700 rounded-lg sm:rounded-xl font-semibold hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 text-sm sm:text-base active:scale-[0.98]">
                        <i class="fas fa-home mr-2"></i>Kembali ke Dashboard
                    </a>
                </div>

                @if($payment->status === 'failed')
                    <div class="mt-5 sm:mt-6">
                        <a href="{{ route('tenant.payment.midtrans', $payment->id) }}"
                           class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 font-semibold text-sm sm:text-base transition-colors">
                            <i class="fas fa-redo"></i>
                            <span>Coba Bayar Lagi</span>
                        </a>
                    </div>
                @endif

                {{-- Contact Support --}}
                @if($payment->status === 'pending' || $payment->status === 'failed')
                    <div class="mt-6 sm:mt-8 pt-5 sm:pt-6 border-t border-gray-200">
                        <p class="text-xs sm:text-sm text-gray-600 mb-3">Butuh bantuan?</p>
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4">
                            @if($contact && $contact->whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contact->whatsapp) }}"
                                   target="_blank"
                                   class="inline-flex items-center gap-2 px-4 sm:px-5 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-all duration-200 font-semibold text-sm shadow-sm hover:shadow-md active:scale-[0.98]">
                                    <i class="fab fa-whatsapp text-base sm:text-lg"></i>
                                    <span>Hubungi via WhatsApp</span>
                                </a>
                            @endif
                            @if($contact && $contact->phone)
                                <a href="tel:{{ $contact->phone }}"
                                   class="inline-flex items-center gap-2 px-4 sm:px-5 py-2 border-2 border-blue-500 text-blue-600 rounded-lg hover:bg-blue-50 transition-all duration-200 font-semibold text-sm active:scale-[0.98]">
                                    <i class="fas fa-phone text-sm sm:text-base"></i>
                                    <span>Telepon Kami</span>
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
