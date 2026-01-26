<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - {{ $room->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>
<body class="bg-gray-50">

    @include('landing.navbar')

    <section class="pt-24 pb-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Pembayaran Sewa Kos</h1>
                <p class="text-gray-600">Selesaikan pembayaran Anda dengan aman</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Payment Summary --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
                        
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Detail Pembayaran</h2>

                        {{-- Room Info --}}
                        <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-xl p-4 mb-6">
                            <div class="flex gap-4">
                                <div class="w-20 h-20 bg-white rounded-lg overflow-hidden flex-shrink-0">
                                    @if($room->image)
                                        <img src="{{ asset('storage/' . $room->image) }}" 
                                             alt="{{ $room->name }}" class="w-full h-full object-cover">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=200&h=200&fit=crop" 
                                             alt="{{ $room->name }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-900 mb-1">{{ $room->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $room->property->name }}</p>
                                    <p class="text-sm text-gray-600"><i class="fas fa-layer-group mr-1"></i>Lantai {{ $room->floor }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Payment Details --}}
                        <div class="space-y-3 mb-6 pb-6 border-b">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Periode Pembayaran</span>
                                <span class="font-semibold text-gray-900">{{ $payment->billing_month->format('F Y') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Jatuh Tempo</span>
                                <span class="font-semibold text-gray-900">{{ $payment->due_date->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Harga Sewa</span>
                                <span class="font-semibold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- Total --}}
                        <div class="bg-gradient-to-br from-blue-50 to-purple-50 p-4 rounded-lg border border-blue-100 mb-6">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700 font-semibold">Total Pembayaran</span>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-blue-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- Payment Methods Info --}}
                        <div class="bg-gray-50 rounded-xl p-4 mb-6">
                            <div class="font-semibold text-gray-900 mb-3">Metode Pembayaran yang Tersedia:</div>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
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
                                class="w-full px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl font-bold hover:from-blue-700 hover:to-blue-800 transition shadow-lg hover:shadow-xl">
                            <i class="fas fa-lock mr-2"></i>Bayar Sekarang
                        </button>

                        <p class="text-xs text-gray-500 text-center mt-4">
                            <i class="fas fa-shield-alt mr-1"></i>Pembayaran Anda dijamin aman oleh Midtrans
                        </p>

                    </div>
                </div>

                {{-- Sidebar Info --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                        
                        <h3 class="font-bold text-gray-900 mb-4">Informasi Penting</h3>
                        
                        <div class="space-y-4 text-sm text-gray-600">
                            <div class="flex gap-3">
                                <i class="fas fa-shield-alt text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Pembayaran Aman</div>
                                    <div>Transaksi Anda dilindungi dengan enkripsi SSL</div>
                                </div>
                            </div>
                            
                            <div class="flex gap-3">
                                <i class="fas fa-clock text-blue-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Konfirmasi Otomatis</div>
                                    <div>Pembayaran langsung terverifikasi secara otomatis</div>
                                </div>
                            </div>
                            
                            <div class="flex gap-3">
                                <i class="fas fa-bell text-purple-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Notifikasi Real-time</div>
                                    <div>Anda akan menerima notifikasi status pembayaran</div>
                                </div>
                            </div>
                            
                            <div class="flex gap-3">
                                <i class="fas fa-headset text-orange-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Dukungan 24/7</div>
                                    <div>Tim kami siap membantu kapan saja</div>
                                </div>
                            </div>
                        </div>

                        @if($contact && $contact->whatsapp)
                            <div class="mt-6 pt-6 border-t">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contact->whatsapp) }}" 
                                   target="_blank"
                                   class="flex items-center justify-center gap-2 px-4 py-3 bg-green-500 text-white rounded-xl hover:bg-green-600 transition font-semibold">
                                    <i class="fab fa-whatsapp"></i>
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
                },
                onClose: function() {
                    console.log('Customer closed the popup without finishing payment');
                }
            });
        });
    </script>

</body>
</html>