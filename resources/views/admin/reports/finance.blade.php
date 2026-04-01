<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Laporan Keuangan</h2>
                <p class="text-sm text-gray-500">Rekap pembayaran sewa kos</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg sm:rounded-xl p-4 sm:p-5 text-white shadow-md">
                    <div class="flex items-center justify-between mb-2">
                        <i class="fas fa-file-invoice-dollar text-2xl sm:text-3xl opacity-20"></i>
                        <span class="text-xs sm:text-sm opacity-90">Total</span>
                    </div>
                    <div class="text-lg sm:text-xl font-bold leading-tight">
                        Rp {{ number_format($stats['total_tagihan'], 0, ',', '.') }}
                    </div>
                    <p class="text-xs sm:text-sm opacity-90 mt-1">Tagihan</p>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg sm:rounded-xl p-4 sm:p-5 text-white shadow-md">
                    <div class="flex items-center justify-between mb-2">
                        <i class="fas fa-check-circle text-2xl sm:text-3xl opacity-20"></i>
                        <span class="text-xs sm:text-sm opacity-90">Lunas</span>
                    </div>
                    <div class="text-lg sm:text-xl font-bold leading-tight">
                        Rp {{ number_format($stats['total_lunas'], 0, ',', '.') }}
                    </div>
                    <p class="text-xs sm:text-sm opacity-90 mt-1">Sudah Dibayar</p>
                </div>

                <div class="bg-gradient-to-br from-gray-700 to-gray-800 rounded-lg sm:rounded-xl p-4 sm:p-5 text-white shadow-md">
                    <div class="flex items-center justify-between mb-2">
                        <i class="fas fa-clock text-2xl sm:text-3xl opacity-20"></i>
                        <span class="text-xs sm:text-sm opacity-90">Pending</span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-bold">{{ $stats['total_pending'] }}</div>
                    <p class="text-xs sm:text-sm opacity-90 mt-1">Tagihan</p>
                </div>

                <div class="bg-gradient-to-br from-blue-400 to-blue-500 rounded-lg sm:rounded-xl p-4 sm:p-5 text-white shadow-md col-span-2 lg:col-span-1">
                    <div class="flex items-center justify-between mb-2">
                        <i class="fas fa-times-circle text-2xl sm:text-3xl opacity-20"></i>
                        <span class="text-xs sm:text-sm opacity-90">Gagal/Batal</span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-bold">{{ $stats['total_failed'] }}</div>
                    <p class="text-xs sm:text-sm opacity-90 mt-1">Tagihan</p>
                </div>
            </div>

          <div class="flex items-center gap-2 ml-2">
                <a href="{{ route('admin.reports.finance.excel', request()->query()) }}"
                   class="inline-flex items-center gap-2 rounded-lg border border-emerald-300 bg-emerald-50 px-3 py-2 text-xs font-medium text-emerald-700 transition hover:bg-emerald-100">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Export CSV
                </a>
                <a href="{{ route('admin.reports.finance.pdf', request()->query()) }}"
                   class="inline-flex items-center gap-1.5 rounded-lg border border-red-300 bg-red-50 px-3 py-2 text-xs font-medium text-red-700 transition hover:bg-red-100">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Export PDF
                </a>
            </div>

            {{-- Filter Bar --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-4 sm:p-6">
                <form method="GET" action="{{ route('admin.reports.finance') }}"
                      class="space-y-4 sm:space-y-0 sm:flex sm:gap-4 sm:flex-wrap items-end">

                    <div class="flex-1 min-w-[200px]">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari penghuni..."
                                class="w-full px-4 py-2 sm:py-2.5 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base">
                        </div>
                    </div>

                    <div class="w-full sm:w-44">
                        <select name="status"
                            class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base">
                            <option value="">Semua Status</option>
                            <option value="paid"      {{ request('status') === 'paid'      ? 'selected' : '' }}>Lunas</option>
                            <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
                            <option value="failed"    {{ request('status') === 'failed'    ? 'selected' : '' }}>Gagal</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>

                    <div class="w-full sm:w-44">
                        <select name="property_id"
                            class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base">
                            <option value="">Semua Properti</option>
                            @foreach ($properties as $p)
                                <option value="{{ $p->id }}" {{ request('property_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full sm:w-auto">
                        <input type="month" name="month" value="{{ request('month') }}"
                            class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base">
                    </div>

                    <div class="flex gap-2">
                        <button type="submit"
                            class="flex-1 sm:flex-none px-4 sm:px-6 py-2 sm:py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-semibold text-sm sm:text-base active:scale-[0.98]">
                            <i class="fas fa-search mr-2"></i>Cari
                        </button>
                        @if (request()->hasAny(['search','status','property_id','month']))
                            <a href="{{ route('admin.reports.finance') }}"
                                class="flex-1 sm:flex-none px-4 sm:px-6 py-2 sm:py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all font-semibold text-sm sm:text-base text-center active:scale-[0.98]">
                                <i class="fas fa-times mr-2"></i>Reset
                            </a>
                        @endif
                    </div>

                </form>

                @if(request()->hasAny(['search','status','property_id','month']))
                    <div class="mt-3 text-sm text-gray-600">
                        Menampilkan <span class="font-semibold">{{ $payments->total() }}</span> tagihan
                        @if(request('search'))
                            <span class="font-semibold"> • Pencarian: "{{ request('search') }}"</span>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-md overflow-hidden">
                @if($payments->count() > 0)

                    {{-- Mobile Card View --}}
                    <div class="block lg:hidden divide-y divide-gray-200">
                        @php
                            $statusBadge = [
                                'paid'      => 'bg-blue-100 text-blue-700',
                                'pending'   => 'bg-yellow-100 text-yellow-700',
                                'failed'    => 'bg-red-100 text-red-600',
                                'cancelled' => 'bg-gray-100 text-gray-500',
                            ];
                            $statusLabel = [
                                'paid'      => 'Lunas',
                                'pending'   => 'Pending',
                                'failed'    => 'Gagal',
                                'cancelled' => 'Batal',
                            ];
                        @endphp
                        @foreach ($payments as $i => $payment)
                            @php
                                $isOverdue = $payment->status === 'pending'
                                    && $payment->due_date
                                    && \Carbon\Carbon::parse($payment->due_date)->isPast();
                            @endphp
                            <div class="p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start justify-between gap-3 mb-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                                            {{ substr($payment->resident->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $payment->resident->user->name }}</p>
                                            <p class="text-xs text-gray-400">{{ $payment->resident->user->email }}</p>
                                            <p class="text-xs text-gray-500 mt-0.5">
                                                {{ $payment->resident->room->name ?? '-' }} •
                                                {{ \Carbon\Carbon::parse($payment->billing_month)->format('M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold flex-shrink-0
                                        {{ $statusBadge[$payment->status] ?? 'bg-gray-100 text-gray-500' }}">
                                        {{ $statusLabel[$payment->status] ?? $payment->status }}
                                    </span>
                                </div>
                                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-3 text-xs border border-blue-200">
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-500">Nominal</span>
                                        <span class="font-bold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                    </div>
                                    @if($payment->due_date)
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-gray-500">Jatuh Tempo</span>
                                        <span class="{{ $isOverdue ? 'text-red-500 font-semibold' : 'text-gray-700' }}">
                                            {{ \Carbon\Carbon::parse($payment->due_date)->format('d M Y') }}
                                            @if($isOverdue) <span class="text-red-400">(lewat)</span> @endif
                                        </span>
                                    </div>
                                    @endif
                                    @if($payment->paid_at)
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-gray-500">Tgl Bayar</span>
                                        <span class="text-gray-700">{{ \Carbon\Carbon::parse($payment->paid_at)->format('d M Y') }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Desktop Table View --}}
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-8">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Penghuni</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kamar</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Bulan Tagih</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jatuh Tempo</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Nominal</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tgl Bayar</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php
                                    $statusBadge = [
                                        'paid'      => 'bg-blue-100 text-blue-700',
                                        'pending'   => 'bg-yellow-100 text-yellow-700',
                                        'failed'    => 'bg-red-100 text-red-600',
                                        'cancelled' => 'bg-gray-100 text-gray-500',
                                    ];
                                    $statusLabel = [
                                        'paid'      => 'Lunas',
                                        'pending'   => 'Pending',
                                        'failed'    => 'Gagal',
                                        'cancelled' => 'Batal',
                                    ];
                                @endphp
                                @forelse ($payments as $i => $payment)
                                    @php
                                        $isOverdue = $payment->status === 'pending'
                                            && $payment->due_date
                                            && \Carbon\Carbon::parse($payment->due_date)->isPast();
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 text-xs text-gray-400">{{ $payments->firstItem() + $i }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                                                    {{ substr($payment->resident->user->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-gray-900">{{ $payment->resident->user->name }}</div>
                                                    <div class="text-xs text-gray-500">{{ $payment->resident->user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-800">{{ $payment->resident->room->name ?? '-' }}</div>
                                            <div class="text-xs text-gray-500">{{ $payment->resident->room->property->name ?? '' }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($payment->billing_month)->format('M Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            @if ($payment->due_date)
                                                <span class="{{ $isOverdue ? 'font-semibold text-red-500' : 'text-gray-600' }}">
                                                    {{ \Carbon\Carbon::parse($payment->due_date)->format('d M Y') }}
                                                </span>
                                                @if ($isOverdue)
                                                    <span class="ml-1 text-xs text-red-400">lewat</span>
                                                @endif
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="font-bold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('d M Y') : '-' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                {{ $statusBadge[$payment->status] ?? 'bg-gray-100 text-gray-500' }}">
                                                <i class="fas {{ $payment->status === 'paid' ? 'fa-check-circle' : ($payment->status === 'pending' ? 'fa-clock' : 'fa-times-circle') }} mr-1"></i>
                                                {{ $statusLabel[$payment->status] ?? $payment->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-16 text-center">
                                            <i class="fas fa-file-invoice text-4xl text-gray-300 mb-3"></i>
                                            <p class="text-gray-500">Tidak ada data pembayaran</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($payments->hasPages())
                        <div class="px-4 py-3 sm:px-6 border-t border-gray-200">
                            {{ $payments->appends(request()->query())->links() }}
                        </div>
                    @endif

                @else
                    <div class="text-center py-12">
                        <i class="fas fa-file-invoice text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 mb-4">Tidak ada data pembayaran</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    @if ($revenueChart->isNotEmpty())
        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
        <script>
            new Chart(document.getElementById('revenueChart'), {
                type: 'bar',
                data: {
                    labels: @json($revenueChart->keys()->map(fn($m) => \Carbon\Carbon::parse($m)->translatedFormat('M Y'))),
                    datasets: [{
                        data: @json($revenueChart->values()),
                        backgroundColor: 'rgba(59,130,246,0.12)',
                        borderColor: 'rgba(59,130,246,0.7)',
                        borderWidth: 1.5,
                        borderRadius: 6,
                        hoverBackgroundColor: 'rgba(59,130,246,0.25)',
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID'),
                            }
                        }
                    },
                    scales: {
                        y: {
                            ticks: { callback: v => 'Rp ' + (v/1e6).toFixed(0) + 'jt', font: { size: 11 } },
                            grid: { color: 'rgba(0,0,0,0.04)' },
                            border: { display: false },
                        },
                        x: {
                            ticks: { font: { size: 11 } },
                            grid: { display: false },
                            border: { display: false },
                        }
                    }
                }
            });
        </script>
        @endpush
    @endif
</x-app-layout>