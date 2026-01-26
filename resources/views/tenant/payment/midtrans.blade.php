<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Midtrans</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" 
            data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>
<body class="bg-gray-50">

    {{-- Navbar --}}
    @include('landing.navbar')

    <div class="min-h-screen flex items-center justify-center px-4 py-24">
        <div class="max-w-md w-full">
            
            {{-- Payment Card --}}
            <div class="bg-white rounded-2xl shadow-xl p-8">
                
                {{-- Header --}}
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-credit-card text-4xl text-white"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Pembayaran Sewa</h1>
                    <p class="text-gray-600">{{ $room->name }}</p>
                </div>

                {{-- Payment Details --}}
                <div class="space-y-4 mb-8">
                    <div class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl p-5 border border-blue-100">
                        <div class="flex justify-between items-center mb-3 pb-3 border-b border-gray-200">
                            <span class="text-sm font-medium text-gray-600">Kamar</span>
                            <span class="font-bold text-gray-900">{{ $room->name }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-3 pb-3 border-b border-gray-200">
                            <span class="text-sm font-medium text-gray-600">Periode Sewa</span>
                            <div class="text-right">
                                <div class="font-semibold text-gray-900 text-sm">
                                    {{ \Carbon\Carbon::parse($resident->start_date)->format('d M Y') }}
                                </div>
                                <div class="text-xs text-gray-500">sampai</div>
                                <div class="font-semibold text-gray-900 text-sm">
                                    {{ \Carbon\Carbon::parse($resident->end_date)->format('d M Y') }}
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between items-center pt-3">
                            <span class="text-base font-bold text-gray-700">Total Pembayaran</span>
                            <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Payment Methods Info --}}
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <div class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-info-circle text-blue-600"></i>
                        Metode Pembayaran Tersedia:
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex items-center gap-2 text-xs text-gray-700">
                            <i class="fas fa-credit-card text-blue-600"></i>
                            <span>Kartu Kredit</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-700">
                            <i class="fas fa-university text-blue-600"></i>
                            <span>Transfer Bank</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-700">
                            <i class="fas fa-wallet text-blue-600"></i>
                            <span>E-Wallet</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-700">
                            <i class="fas fa-mobile-alt text-blue-600"></i>
                            <span>QRIS</span>
                        </div>
                    </div>
                </div>

                {{-- Payment Button --}}
                <button id="pay-button" 
                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-4 rounded-xl font-bold hover:from-blue-700 hover:to-purple-700 transition shadow-lg hover:shadow-xl transform hover:scale-105 duration-200">
                    <i class="fas fa-lock mr-2"></i>Bayar Sekarang
                </button>

                {{-- Security Info --}}
                <div class="mt-6 space-y-2">
                    <div class="flex items-center justify-center gap-2 text-xs text-gray-500">
                        <i class="fas fa-shield-alt text-green-600"></i>
                        <span>Pembayaran aman & terenkripsi 256-bit SSL</span>
                    </div>
                    <div class="flex items-center justify-center gap-2 text-xs text-gray-500">
                        <i class="fas fa-check-circle text-green-600"></i>
                        <span>Powered by Midtrans Payment Gateway</span>
                    </div>
                </div>

                {{-- Back Link --}}
                <div class="mt-6 text-center">
                    <a href="{{ route('tenant.bookings.index') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali ke Dashboard
                    </a>
                </div>

            </div>

            {{-- Payment Logos --}}
            <div class="text-center mt-8">
                <p class="text-xs text-gray-400 mb-3">Didukung oleh:</p>
                <div class="flex justify-center items-center gap-4 flex-wrap">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/200px-Visa_Inc._logo.svg.png" alt="Visa" class="h-6 opacity-60">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/200px-Mastercard-logo.svg.png" alt="Mastercard" class="h-6 opacity-60">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/American_Express_logo_%282018%29.svg/200px-American_Express_logo_%282018%29.svg.png" alt="Amex" class="h-5 opacity-60">
                </div>
            </div>

        </div>
    </div>

    {{-- Footer --}}
    @include('landing.footer')

    <script type="text/javascript">
        document.getElementById('pay-button').addEventListener('click', function () {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    console.log('Payment success:', result);
                    window.location.href = '{{ route("tenant.payment.finish", $payment->id) }}';
                },
                onPending: function(result) {
                    console.log('Payment pending:', result);
                    window.location.href = '{{ route("tenant.payment.finish", $payment->id) }}';
                },
                onError: function(result) {
                    console.log('Payment error:', result);
                    alert('Pembayaran gagal. Silakan coba lagi.');
                    window.location.href = '{{ route("tenant.payment.midtrans", $payment->id) }}';
                },
                onClose: function() {
                    console.log('Payment popup closed');
                    // User closed without completing payment
                }
            });
        });

        // Auto trigger payment popup on page load (optional)
        // Uncomment if you want automatic popup
        // window.addEventListener('load', function() {
        //     setTimeout(function() {
        //         document.getElementById('pay-button').click();
        //     }, 500);
        // });
    </script>

</body>
</html>