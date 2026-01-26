<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Saya - RumahKos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">

    @include('landing.navbar')

    <section class="pt-24 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Booking & Pembayaran Saya</h1>
                <p class="text-gray-600">Kelola booking kamar dan riwayat pembayaran Anda</p>
            </div>

            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl border border-green-200">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-xl border border-red-200">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                </div>
            @endif

            @if($residents->isEmpty())
                {{-- No Bookings --}}
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                    <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-home text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Belum Ada Booking</h3>
                    <p class="text-gray-600 mb-6">Anda belum memiliki riwayat booking kamar kos</p>
                    <a href="/#kamar" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-semibold">
                        <i class="fas fa-search mr-2"></i>Cari Kamar Tersedia
                    </a>
                </div>
            @else
                {{-- Bookings List --}}
                <div class="space-y-6">
                    @foreach($residents as $resident)
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                            
                            {{-- Booking Header --}}
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div>
                                        <h3 class="text-2xl font-bold mb-2">{{ $resident->room->name }}</h3>
                                        <p class="opacity-90"><i class="fas fa-building mr-2"></i>{{ $resident->room->property->name }}</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        @if($resident->status === 'active')
                                            <span class="px-4 py-2 bg-green-500 text-white rounded-full text-sm font-semibold">
                                                <i class="fas fa-check-circle mr-1"></i>Aktif
                                            </span>
                                        @elseif($resident->status === 'inactive')
                                            <span class="px-4 py-2 bg-yellow-500 text-white rounded-full text-sm font-semibold">
                                                <i class="fas fa-clock mr-1"></i>Menunggu Pembayaran
                                            </span>
                                        @else
                                            <span class="px-4 py-2 bg-gray-500 text-white rounded-full text-sm font-semibold">
                                                {{ ucfirst($resident->status) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Booking Details --}}
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                            <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm text-gray-600">Tanggal Mulai</div>
                                            <div class="font-semibold text-gray-900">{{ $resident->start_date->format('d M Y') }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                            <i class="fas fa-calendar-check text-purple-600 text-xl"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm text-gray-600">Tanggal Berakhir</div>
                                            <div class="font-semibold text-gray-900">{{ $resident->end_date->format('d M Y') }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                            <i class="fas fa-clock text-green-600 text-xl"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm text-gray-600">Durasi</div>
                                            <div class="font-semibold text-gray-900">{{ $resident->getDurationInMonths() }} Bulan</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Payment History --}}
                                <div class="border-t pt-6">
                                    <h4 class="font-bold text-lg text-gray-900 mb-4">Riwayat Pembayaran</h4>
                                    
                                    <div class="overflow-x-auto">
                                        <table class="w-full">
                                            <thead>
                                                <tr class="bg-gray-50">
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Periode</th>
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Jatuh Tempo</th>
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Jumlah</th>
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                @forelse($resident->payments as $payment)
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-4 py-3 text-sm text-gray-900">
                                                            {{ $payment->billing_month->format('F Y') }}
                                                        </td>
                                                        <td class="px-4 py-3 text-sm text-gray-600">
                                                            {{ $payment->due_date->format('d M Y') }}
                                                        </td>
                                                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">
                                                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                                        </td>
                                                        <td class="px-4 py-3">
                                                            @if($payment->status === 'paid')
                                                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                                                    <i class="fas fa-check-circle mr-1"></i>Lunas
                                                                </span>
                                                            @elseif($payment->status === 'pending')
                                                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
                                                                    <i class="fas fa-clock mr-1"></i>Pending
                                                                </span>
                                                            @elseif($payment->status === 'failed')
                                                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                                                    <i class="fas fa-times-circle mr-1"></i>Gagal
                                                                </span>
                                                            @else
                                                                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">
                                                                    {{ ucfirst($payment->status) }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td class="px-4 py-3">
                                                            @if($payment->status === 'pending')
                                                                <a href="{{ route('tenant.payment.midtrans', $payment->id) }}" 
                                                                   class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                                                                    <i class="fas fa-credit-card mr-1"></i>Bayar Sekarang
                                                                </a>
                                                            @elseif($payment->status === 'paid')
                                                                <span class="text-gray-400 text-sm">
                                                                    <i class="fas fa-receipt mr-1"></i>{{ $payment->paid_at->format('d M Y') }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
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

    @include('landing.footer')

</body>
</html>