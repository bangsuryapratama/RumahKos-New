<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Saya - RumahKos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .modal {
            transition: opacity 0.25s ease;
        }
        body.modal-active {
            overflow-x: hidden;
            overflow-y: visible !important;
        }
    </style>
</head>
<body class="bg-gray-50">

    @include('landing.navbar')

    <section class="pt-20 sm:pt-24 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Booking & Pembayaran Saya</h1>
                <p class="text-sm sm:text-base text-gray-600">Kelola booking kamar dan riwayat pembayaran Anda</p>
            </div>

            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-lg sm:rounded-xl border border-green-200 text-sm sm:text-base">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-lg sm:rounded-xl border border-red-200 text-sm sm:text-base">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                </div>
            @endif

            @if($residents->isEmpty())
                {{-- No Bookings --}}
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-8 sm:p-12 text-center">
                    <div class="bg-gray-100 w-20 h-20 sm:w-24 sm:h-24 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-home text-3xl sm:text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">Belum Ada Booking</h3>
                    <p class="text-sm sm:text-base text-gray-600 mb-6">Anda belum memiliki riwayat booking kamar kos</p>
                    <a href="/#kamar" class="inline-block px-5 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg sm:rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 font-semibold text-sm sm:text-base shadow-md hover:shadow-lg active:scale-[0.98]">
                        <i class="fas fa-search mr-2"></i>Cari Kamar Tersedia
                    </a>
                </div>
            @else
                {{-- Bookings List --}}
                <div class="space-y-4 sm:space-y-6">
                    @foreach($residents as $resident)
                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-md sm:shadow-lg overflow-hidden">

                            {{-- Booking Header --}}
                            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-4 sm:p-6 text-white">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-xl sm:text-2xl font-bold mb-1 sm:mb-2 truncate">{{ $resident->room->name }}</h3>
                                        <p class="opacity-90 text-sm sm:text-base truncate">
                                            <i class="fas fa-building mr-1 sm:mr-2"></i>{{ $resident->room->property->name }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                                        @if($resident->status === 'active')
                                            <span class="px-3 sm:px-4 py-1.5 sm:py-2 bg-green-500 text-white rounded-full text-xs sm:text-sm font-semibold whitespace-nowrap">
                                                <i class="fas fa-check-circle mr-1"></i>Aktif
                                            </span>
                                        @elseif($resident->status === 'inactive')
                                            <span class="px-3 sm:px-4 py-1.5 sm:py-2 bg-yellow-500 text-white rounded-full text-xs sm:text-sm font-semibold whitespace-nowrap">
                                                <i class="fas fa-clock mr-1"></i>Menunggu
                                            </span>
                                        @elseif($resident->status === 'cancelled')
                                            <span class="px-3 sm:px-4 py-1.5 sm:py-2 bg-red-500 text-white rounded-full text-xs sm:text-sm font-semibold whitespace-nowrap">
                                                <i class="fas fa-ban mr-1"></i>Dibatalkan
                                            </span>
                                        @else
                                            <span class="px-3 sm:px-4 py-1.5 sm:py-2 bg-gray-500 text-white rounded-full text-xs sm:text-sm font-semibold whitespace-nowrap">
                                                {{ ucfirst($resident->status) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Booking Details --}}
                            <div class="p-4 sm:p-6">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-4 sm:mb-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-calendar-alt text-blue-600 text-lg sm:text-xl"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs sm:text-sm text-gray-600">Tanggal Mulai</div>
                                            <div class="font-semibold text-sm sm:text-base text-gray-900">{{ $resident->start_date->format('d M Y') }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-calendar-check text-purple-600 text-lg sm:text-xl"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs sm:text-sm text-gray-600">Tanggal Berakhir</div>
                                            <div class="font-semibold text-sm sm:text-base text-gray-900">{{ $resident->end_date->format('d M Y') }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-clock text-green-600 text-lg sm:text-xl"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs sm:text-sm text-gray-600">Durasi</div>
                                            <div class="font-semibold text-sm sm:text-base text-gray-900">{{ $resident->getDurationInMonths() }} Bulan</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Cancel Button (Only for inactive status) --}}
                                @if($resident->status === 'inactive')
                                    <div class="mb-4 sm:mb-6 pb-4 sm:pb-6 border-b border-gray-200">
                                        <button onclick="openCancelModal({{ $resident->id }})"
                                                class="w-full sm:w-auto px-4 sm:px-5 py-2 sm:py-2.5 bg-red-50 text-red-600 border-2 border-red-200 rounded-lg sm:rounded-xl hover:bg-red-100 hover:border-red-300 transition-all duration-200 font-semibold text-sm sm:text-base active:scale-[0.98]">
                                            <i class="fas fa-times-circle mr-2"></i>Batalkan Booking
                                        </button>
                                    </div>
                                @endif

                                {{-- Payment History --}}
                                <div class="border-t pt-4 sm:pt-6">
                                    <h4 class="font-bold text-base sm:text-lg text-gray-900 mb-3 sm:mb-4">Riwayat Pembayaran</h4>

                                    {{-- Mobile View --}}
                                    <div class="block sm:hidden space-y-3">
                                        @forelse($resident->payments as $payment)
                                            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <div class="font-semibold text-sm text-gray-900">{{ $payment->billing_month->format('F Y') }}</div>
                                                        <div class="text-xs text-gray-600">Jatuh tempo: {{ $payment->due_date->format('d M Y') }}</div>
                                                    </div>
                                                    @if($payment->status === 'paid')
                                                        <span class="px-2.5 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold whitespace-nowrap">
                                                            <i class="fas fa-check-circle"></i> Lunas
                                                        </span>
                                                    @elseif($payment->status === 'pending')
                                                        <span class="px-2.5 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold whitespace-nowrap">
                                                            <i class="fas fa-clock"></i> Pending
                                                        </span>
                                                    @elseif($payment->status === 'failed')
                                                        <span class="px-2.5 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold whitespace-nowrap">
                                                            <i class="fas fa-times-circle"></i> Gagal
                                                        </span>
                                                    @else
                                                        <span class="px-2.5 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold whitespace-nowrap">
                                                            {{ ucfirst($payment->status) }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                                                    <div class="font-bold text-sm text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                                                    @if($payment->status === 'pending')
                                                        <a href="{{ route('tenant.payment.midtrans', $payment->id) }}"
                                                           class="px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-semibold text-xs active:scale-[0.98]">
                                                            <i class="fas fa-credit-card mr-1"></i>Bayar
                                                        </a>
                                                    @elseif($payment->status === 'paid')
                                                        <span class="text-gray-400 text-xs">
                                                            <i class="fas fa-receipt mr-1"></i>{{ $payment->paid_at->format('d/m/Y') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center py-8 text-gray-500 text-sm">
                                                Tidak ada riwayat pembayaran
                                            </div>
                                        @endforelse
                                    </div>

                                    {{-- Desktop View --}}
                                    <div class="hidden sm:block overflow-x-auto">
                                        <table class="w-full">
                                            <thead>
                                                <tr class="bg-gray-50">
                                                    <th class="px-4 py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Periode</th>
                                                    <th class="px-4 py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Jatuh Tempo</th>
                                                    <th class="px-4 py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Jumlah</th>
                                                    <th class="px-4 py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Status</th>
                                                    <th class="px-4 py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                @forelse($resident->payments as $payment)
                                                    <tr class="hover:bg-gray-50 transition-colors">
                                                        <td class="px-4 py-3 text-xs sm:text-sm text-gray-900">
                                                            {{ $payment->billing_month->format('F Y') }}
                                                        </td>
                                                        <td class="px-4 py-3 text-xs sm:text-sm text-gray-600">
                                                            {{ $payment->due_date->format('d M Y') }}
                                                        </td>
                                                        <td class="px-4 py-3 text-xs sm:text-sm font-semibold text-gray-900">
                                                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                                        </td>
                                                        <td class="px-4 py-3">
                                                            @if($payment->status === 'paid')
                                                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold whitespace-nowrap">
                                                                    <i class="fas fa-check-circle mr-1"></i>Lunas
                                                                </span>
                                                            @elseif($payment->status === 'pending')
                                                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold whitespace-nowrap">
                                                                    <i class="fas fa-clock mr-1"></i>Pending
                                                                </span>
                                                            @elseif($payment->status === 'failed')
                                                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold whitespace-nowrap">
                                                                    <i class="fas fa-times-circle mr-1"></i>Gagal
                                                                </span>
                                                            @else
                                                                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold whitespace-nowrap">
                                                                    {{ ucfirst($payment->status) }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td class="px-4 py-3">
                                                            @if($payment->status === 'pending')
                                                                <a href="{{ route('tenant.payment.midtrans', $payment->id) }}"
                                                                   class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 font-semibold text-xs sm:text-sm transition-colors">
                                                                    <i class="fas fa-credit-card"></i>
                                                                    <span>Bayar Sekarang</span>
                                                                </a>
                                                            @elseif($payment->status === 'paid')
                                                                <span class="text-gray-400 text-xs sm:text-sm">
                                                                    <i class="fas fa-receipt mr-1"></i>{{ $payment->paid_at->format('d M Y') }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="px-4 py-8 text-center text-gray-500 text-sm">
                                                            Tidak ada riwayat pembayaran
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </section>

    {{-- Cancel Confirmation Modal --}}
    <div id="cancelModal" class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center z-50">
        <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50" onclick="closeCancelModal()"></div>

        <div class="modal-container bg-white w-11/12 sm:w-96 mx-auto rounded-xl sm:rounded-2xl shadow-2xl z-50 overflow-y-auto animate-fade-in-up">

            <div class="modal-content p-6 sm:p-8 text-center">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                    <i class="fas fa-exclamation-triangle text-3xl sm:text-4xl text-red-600"></i>
                </div>

                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2 sm:mb-3">Batalkan Booking?</h3>
                <p class="text-sm sm:text-base text-gray-600 mb-6 sm:mb-8">
                    Apakah Anda yakin ingin membatalkan booking ini? Tindakan ini tidak dapat dibatalkan.
                </p>

                <form id="cancelForm" method="POST" action="">
                    @csrf
                    @method('DELETE')

                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                        <button type="button"
                                onclick="closeCancelModal()"
                                class="flex-1 px-4 sm:px-6 py-2.5 sm:py-3 border-2 border-gray-300 text-gray-700 rounded-lg sm:rounded-xl font-semibold hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 text-sm sm:text-base active:scale-[0.98]">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 sm:px-6 py-2.5 sm:py-3 bg-red-600 text-white rounded-lg sm:rounded-xl font-semibold hover:bg-red-700 transition-all duration-200 shadow-md hover:shadow-lg text-sm sm:text-base active:scale-[0.98]">
                            Ya, Batalkan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    @include('landing.footer')

    <script>
        function openCancelModal(residentId) {
            const modal = document.getElementById('cancelModal');
            const form = document.getElementById('cancelForm');

            form.action = `/tenant/bookings/${residentId}`;

            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.classList.add('opacity-100', 'pointer-events-auto');
            document.body.classList.add('modal-active');
        }

        function closeCancelModal() {
            const modal = document.getElementById('cancelModal');

            modal.classList.add('opacity-0', 'pointer-events-none');
            modal.classList.remove('opacity-100', 'pointer-events-auto');
            document.body.classList.remove('modal-active');
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeCancelModal();
            }
        });
    </script>

</body>
</html>
