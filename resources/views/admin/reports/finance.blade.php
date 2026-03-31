<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Laporan Keuangan</h2>
                <p class="text-sm text-gray-500">Rekap pembayaran sewa kos</p>
            </div>
            {{-- Export Buttons --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.reports.finance.excel', request()->query()) }}"
                   class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-300 bg-emerald-50 px-3 py-2 text-xs font-medium text-emerald-700 transition hover:bg-emerald-100">
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
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl space-y-5 px-4 sm:px-6 lg:px-8">

            {{-- Stats --}}
            <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                <div class="rounded-xl border border-blue-100 bg-blue-50 p-4">
                    <p class="text-xs text-blue-500">Total Tagihan</p>
                    <p class="mt-1 text-lg font-bold text-blue-700 leading-tight">
                        Rp {{ number_format($stats['total_tagihan'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="rounded-xl border border-green-100 bg-green-50 p-4">
                    <p class="text-xs text-green-500">Sudah Lunas</p>
                    <p class="mt-1 text-lg font-bold text-green-700 leading-tight">
                        Rp {{ number_format($stats['total_lunas'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="rounded-xl border border-yellow-100 bg-yellow-50 p-4">
                    <p class="text-xs text-yellow-600">Pending</p>
                    <p class="mt-1 text-lg font-bold text-yellow-700 leading-tight">
                        {{ $stats['total_pending'] }} tagihan
                    </p>
                </div>
                <div class="rounded-xl border border-red-100 bg-red-50 p-4">
                    <p class="text-xs text-red-500">Gagal / Batal</p>
                    <p class="mt-1 text-lg font-bold text-red-700 leading-tight">
                        {{ $stats['total_failed'] }} tagihan
                    </p>
                </div>
            </div>

            {{-- Chart --}}
            @if ($revenueChart->isNotEmpty())
            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <p class="mb-4 text-sm font-medium text-gray-700">Pendapatan 12 Bulan Terakhir</p>
                <canvas id="revenueChart" height="70"></canvas>
            </div>
            @endif

            {{-- Filter Bar --}}
            <form method="GET" action="{{ route('admin.reports.finance') }}"
                  class="flex flex-wrap items-end gap-3 rounded-xl border border-gray-200 bg-white px-4 py-3">

                {{-- Search --}}
                <div class="relative min-w-0 flex-1" style="min-width: 180px;">
                    <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari penghuni..."
                        class="w-full rounded-lg border border-gray-300 py-2 pl-9 pr-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>

                {{-- Status --}}
                <select name="status"
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    <option value="">Semua Status</option>
                    <option value="paid"      {{ request('status') === 'paid'      ? 'selected' : '' }}>Lunas</option>
                    <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
                    <option value="failed"    {{ request('status') === 'failed'    ? 'selected' : '' }}>Gagal</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>

                {{-- Properti --}}
                <select name="property_id"
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    <option value="">Semua Properti</option>
                    @foreach ($properties as $p)
                        <option value="{{ $p->id }}" {{ request('property_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->name }}
                        </option>
                    @endforeach
                </select>

                {{-- Bulan --}}
                <input type="month" name="month" value="{{ request('month') }}"
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">

                {{-- Actions --}}
                <div class="flex gap-2">
                    <button type="submit"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700">
                        Terapkan
                    </button>
                    @if (request()->hasAny(['search','status','property_id','month','paid_from','paid_to']))
                        <a href="{{ route('admin.reports.finance') }}"
                            class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-600 transition hover:bg-gray-50">
                            ✕
                        </a>
                    @endif
                </div>

            </form>

            {{-- Table --}}
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-b border-gray-100 bg-gray-50">
                            <tr class="text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                <th class="px-4 py-3 w-8">#</th>
                                <th class="px-4 py-3">Penghuni</th>
                                <th class="px-4 py-3 hidden sm:table-cell">Kamar</th>
                                <th class="px-4 py-3 hidden md:table-cell">Bulan Tagih</th>
                                <th class="px-4 py-3 hidden md:table-cell">Jatuh Tempo</th>
                                <th class="px-4 py-3 text-right">Nominal</th>
                                <th class="px-4 py-3 hidden lg:table-cell">Tgl Bayar</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @php
                                $statusBadge = [
                                    'paid'      => 'bg-green-100 text-green-700',
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
                                <tr class="hover:bg-gray-50/60 transition-colors">
                                    <td class="px-4 py-3.5 text-xs text-gray-400">{{ $payments->firstItem() + $i }}</td>
                                    <td class="px-4 py-3.5">
                                        <p class="font-medium text-gray-900">{{ $payment->resident->user->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $payment->resident->user->email }}</p>
                                        {{-- Mobile info --}}
                                        <div class="mt-1 flex flex-wrap gap-x-3 text-xs text-gray-400 sm:hidden">
                                            <span>{{ $payment->resident->room->name ?? '-' }}</span>
                                            <span>{{ \Carbon\Carbon::parse($payment->billing_month)->format('M Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5 hidden sm:table-cell">
                                        <p class="font-medium text-gray-700">{{ $payment->resident->room->name ?? '-' }}</p>
                                        <p class="text-xs text-gray-400">{{ $payment->resident->room->property->name ?? '' }}</p>
                                    </td>
                                    <td class="px-4 py-3.5 text-gray-600 hidden md:table-cell">
                                        {{ \Carbon\Carbon::parse($payment->billing_month)->format('M Y') }}
                                    </td>
                                    <td class="px-4 py-3.5 hidden md:table-cell">
                                        @if ($payment->due_date)
                                            <span class="{{ $isOverdue ? 'font-medium text-red-500' : 'text-gray-600' }}">
                                                {{ \Carbon\Carbon::parse($payment->due_date)->format('d M Y') }}
                                            </span>
                                            @if ($isOverdue)
                                                <span class="ml-1 text-xs text-red-400">lewat</span>
                                            @endif
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-right font-semibold text-gray-800">
                                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3.5 text-gray-600 hidden lg:table-cell">
                                        {{ $payment->paid_at
                                            ? \Carbon\Carbon::parse($payment->paid_at)->format('d M Y')
                                            : '-' }}
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <span class="rounded-full px-2.5 py-1 text-xs font-medium
                                            {{ $statusBadge[$payment->status] ?? 'bg-gray-100 text-gray-500' }}">
                                            {{ $statusLabel[$payment->status] ?? $payment->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-16 text-center">
                                        <svg class="mx-auto mb-3 h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                                        </svg>
                                        <p class="text-sm text-gray-400">Tidak ada data pembayaran</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($payments->hasPages())
                    <div class="border-t border-gray-100 px-4 py-3">
                        {{ $payments->links() }}
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