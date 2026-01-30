<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - {{ $room->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <style>
        @media (max-width: 640px) {
            .payment-container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }
    </style>
</head>
<body class="bg-gray-50">

    @include('landing.navbar')

    <section class="pt-20 pb-12 sm:pt-24 sm:pb-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 payment-container">

            {{-- Header --}}
            <div class="mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Pembayaran</h1>
                <p class="text-sm sm:text-base text-gray-600">Selesaikan pembayaran dengan aman</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

                {{-- Payment Summary --}}
                <div class="lg:col-span-2 order-2 lg:order-1">
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm sm:shadow-lg p-5 sm:p-6 md:p-8">

                        <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-5 sm:mb-6">Detail Pembayaran</h2>

                        {{-- Room Info --}}
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg sm:rounded-xl p-4 mb-5 sm:mb-6">
                            <div class="flex gap-3 sm:gap-4">
                                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-white rounded-lg overflow-hidden flex-shrink-0 shadow-sm">
                                    @if($room->image)
                                        <img src="{{ asset('storage/' . $room->image) }}"
                                             alt="{{ $room->name }}" class="w-full h-full object-cover">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=200&h=200&fit=crop"
                                             alt="{{ $room->name }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-gray-900 mb-1 text-sm sm:text-base truncate">{{ $room->name }}</h3>
                                    <p class="text-xs sm:text-sm text-gray-600 truncate">{{ $room->property->name }}</p>
                                    <p class="text-xs sm:text-sm text-gray-600 mt-1">
                                        <i class="fas fa-layer-group mr-1"></i>Lantai {{ $room->floor }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Payment Details --}}
                        <div class="space-y-3 mb-5 sm:mb-6 pb-5 sm:pb-6 border-b border-gray-200">
                            <div class="flex justify-between items-start gap-2 text-sm sm:text-base">
                                <span class="text-gray-600">Periode</span>
                                <span class="font-semibold text-gray-900 text-right">{{ $payment->billing_month->format('F Y') }}</span>
                            </div>
                            <div class="flex justify-between items-start gap-2 text-sm sm:text-base">
                                <span class="text-gray-600">Jatuh Tempo</span>
                                <span class="font-semibold text-gray-900 text-right">{{ $payment->due_date->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between items-start gap-2 text-sm sm:text-base">
                                <span class="text-gray-600">Harga Sewa</span>
                                <span class="font-semibold text-gray-900 text-right">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- Total --}}
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-4 sm:p-5 rounded-lg border border-blue-100 mb-5 sm:mb-6">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700 font-semibold text-sm sm:text-base">Total Pembayaran</span>
                                <div class="text-xl sm:text-2xl font-bold text-blue-600">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        {{-- Payment Methods Info --}}
                        <div class="bg-gray-50 rounded-lg sm:rounded-xl p-4 mb-5 sm:mb-6">
                            <div class="font-semibold text-gray-900 mb-3 text-sm sm:text-base">Metode Pembayaran:</div>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3 text-xs sm:text-sm">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-credit-card text-blue-600"></i>
                                    <span class="text-gray-700">Kartu Kredit</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-university text-blue-600"></i>
                                    <span class="text-gray-700">Transfer Bank</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-wallet text-blue-600"></i>
                                    <span class="text-gray-700">E-Wallet</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-store text-blue-600"></i>
                                    <span class="text-gray-700">Gerai</span>
                                </div>
                            </div>
                        </div>

                        {{-- Pay Button --}}
                        <button id="pay-button"
                                class="w-full px-6 py-3 sm:py-4 bg-blue-600 text-white rounded-lg sm:rounded-xl font-bold hover:bg-blue-700 transition-all duration-200 shadow-md hover:shadow-lg active:scale-[0.98] text-sm sm:text-base">
                            <i class="fas fa-lock mr-2"></i>Bayar Sekarang
                        </button>

                        <p class="text-xs text-gray-500 text-center mt-3 sm:mt-4">
                            <i class="fas fa-shield-alt mr-1"></i>Pembayaran dijamin aman oleh Midtrans
                        </p>

                    </div>
                </div>

                {{-- Sidebar Info --}}
                <div class="lg:col-span-1 order-1 lg:order-2">
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm sm:shadow-lg p-5 sm:p-6 lg:sticky lg:top-24">

                        <h3 class="font-bold text-gray-900 mb-4 text-base sm:text-lg">Informasi Penting</h3>

                        <div class="space-y-4 text-sm">
                            <div class="flex gap-3">
                                <i class="fas fa-shield-alt text-green-600 mt-0.5 flex-shrink-0"></i>
                                <div class="text-gray-600">
                                    <div class="font-semibold text-gray-900 mb-0.5">Pembayaran Aman</div>
                                    <div class="text-xs sm:text-sm">Transaksi dilindungi enkripsi SSL</div>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <i class="fas fa-clock text-blue-600 mt-0.5 flex-shrink-0"></i>
                                <div class="text-gray-600">
                                    <div class="font-semibold text-gray-900 mb-0.5">Konfirmasi Otomatis</div>
                                    <div class="text-xs sm:text-sm">Verifikasi otomatis & real-time</div>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <i class="fas fa-bell text-purple-600 mt-0.5 flex-shrink-0"></i>
                                <div class="text-gray-600">
                                    <div class="font-semibold text-gray-900 mb-0.5">Notifikasi Real-time</div>
                                    <div class="text-xs sm:text-sm">Update status pembayaran instan</div>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <i class="fas fa-headset text-orange-600 mt-0.5 flex-shrink-0"></i>
                                <div class="text-gray-600">
                                    <div class="font-semibold text-gray-900 mb-0.5">Dukungan 24/7</div>
                                    <div class="text-xs sm:text-sm">Tim siap membantu kapan saja</div>
                                </div>
                            </div>
                        </div>

                        @if($contact && $contact->whatsapp)
                            <div class="mt-5 sm:mt-6 pt-5 sm:pt-6 border-t border-gray-200">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contact->whatsapp) }}"
                                   target="_blank"
                                   class="flex items-center justify-center gap-2 px-4 py-2.5 sm:py-3 bg-green-500 text-white rounded-lg sm:rounded-xl hover:bg-green-600 transition-all duration-200 font-semibold text-sm sm:text-base shadow-sm hover:shadow-md active:scale-[0.98]">
                                    <i class="fab fa-whatsapp text-lg"></i>
                                    Hubungi via WhatsApp
                                </a>
                            </div>
                        @endif

                    </div>
                </div>

            </div>

        </div>
    </section>

    @include('landing.footer')

    <script type="text/javascript">
        const payButton = document.getElementById('pay-button');

        payButton.addEventListener('click', function () {
            // Disable button to prevent double click
            payButton.disabled = true;
            payButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';

            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    console.log('Payment success:', result);
                    window.location.href = "{{ route('tenant.payment.finish', $payment->id) }}";
                },
                onPending: function(result) {
                    console.log('Payment pending:', result);
                    window.location.href = "{{ route('tenant.payment.finish', $payment->id) }}";
                },
                onError: function(result) {
                    console.log('Payment error:', result);
                    alert('Pembayaran gagal! Silakan coba lagi.');
                    // Re-enable button
                    payButton.disabled = false;
                    payButton.innerHTML = '<i class="fas fa-lock mr-2"></i>Bayar Sekarang';
                },
                onClose: function() {
                    console.log('Customer closed the popup without finishing payment');
                    // Re-enable button
                    payButton.disabled = false;
                    payButton.innerHTML = '<i class="fas fa-lock mr-2"></i>Bayar Sekarang';
                }
            });
        });
    </script>

</body>
</html>
