<x-app-layout>
<div class="py-6 sm:py-10">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-8">

    {{-- ── Welcome Card ── --}}
    <div class="relative overflow-hidden bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 rounded-xl shadow-lg p-6 sm:p-8 text-white">
        {{-- Decorative circle --}}
        <div class="absolute -top-10 -right-10 w-48 h-48 bg-white/5 rounded-full pointer-events-none"></div>
        <div class="absolute -bottom-8 right-20 w-28 h-28 bg-blue-500/10 rounded-full pointer-events-none"></div>

        <div class="relative flex items-center justify-between gap-4">
            <div>
                <p class="text-gray-400 text-xs sm:text-sm uppercase tracking-widest mb-1">Admin Panel</p>
                <h1 class="text-xl sm:text-3xl font-bold">Selamat Datang Kembali 👋</h1>
                <p class="text-blue-300 text-sm sm:text-base font-semibold mt-1">{{ auth()->user()->name }}</p>
                <p class="text-gray-500 text-xs mt-0.5">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
            <div class="hidden sm:flex flex-col items-center gap-1 bg-white/10 backdrop-blur-sm rounded-xl px-5 py-3">
                <span class="text-2xl font-bold text-white">{{ $occupancyRate }}%</span>
                <span class="text-gray-400 text-xs">Occupancy</span>
            </div>
        </div>
    </div>

    {{-- ── Stats Grid ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">

        {{-- Total Kamar --}}
        <div class="bg-white rounded-xl shadow-sm p-4 sm:p-5 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="bg-slate-100 p-2 rounded-lg">
                    <i class="fa-solid fa-house text-slate-600 text-base sm:text-lg"></i>
                </div>
                <span class="text-xs text-gray-400 font-medium">Total</span>
            </div>
            <p class="text-gray-500 text-xs font-semibold mb-1">Total Kamar</p>
            <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $totalRooms }}</p>
            <div class="mt-2 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-slate-400 rounded-full" style="width: 100%"></div>
            </div>
        </div>

        {{-- Kamar Tersedia --}}
        <div class="bg-white rounded-xl shadow-sm p-4 sm:p-5 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="bg-green-100 p-2 rounded-lg">
                    <i class="fa-solid fa-circle-check text-green-600 text-base sm:text-lg"></i>
                </div>
                <span class="text-xs text-green-500 font-medium bg-green-50 px-2 py-0.5 rounded-full">Tersedia</span>
            </div>
            <p class="text-gray-500 text-xs font-semibold mb-1">Kamar Tersedia</p>
            <p class="text-2xl sm:text-3xl font-bold text-green-600">{{ $availableRooms }}</p>
            <div class="mt-2 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                @if($totalRooms > 0)
                <div class="h-full bg-green-400 rounded-full transition-all duration-700"
                     style="width: {{ round(($availableRooms / $totalRooms) * 100) }}%"></div>
                @endif
            </div>
        </div>

        {{-- Kamar Terisi --}}
        <div class="bg-white rounded-xl shadow-sm p-4 sm:p-5 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="bg-blue-100 p-2 rounded-lg">
                    <i class="fa-solid fa-user-group text-blue-600 text-base sm:text-lg"></i>
                </div>
                <span class="text-xs text-blue-500 font-medium bg-blue-50 px-2 py-0.5 rounded-full">Terisi</span>
            </div>
            <p class="text-gray-500 text-xs font-semibold mb-1">Kamar Terisi</p>
            <p class="text-2xl sm:text-3xl font-bold text-blue-600">{{ $occupiedRooms }}</p>
            <div class="mt-2 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                @if($totalRooms > 0)
                <div class="h-full bg-blue-400 rounded-full transition-all duration-700"
                     style="width: {{ round(($occupiedRooms / $totalRooms) * 100) }}%"></div>
                @endif
            </div>
        </div>

        {{-- Penghuni Aktif --}}
        <div class="bg-white rounded-xl shadow-sm p-4 sm:p-5 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="bg-purple-100 p-2 rounded-lg">
                    <i class="fa-solid fa-person text-purple-600 text-base sm:text-lg"></i>
                </div>
                <span class="text-xs text-purple-500 font-medium bg-purple-50 px-2 py-0.5 rounded-full">Aktif</span>
            </div>
            <p class="text-gray-500 text-xs font-semibold mb-1">Penghuni Aktif</p>
            <p class="text-2xl sm:text-3xl font-bold text-purple-600">{{ $activeTenants }}</p>
            <div class="mt-2 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                @if($totalRooms > 0)
                <div class="h-full bg-purple-400 rounded-full transition-all duration-700"
                     style="width: {{ $totalRooms > 0 ? round(($activeTenants / $totalRooms) * 100) : 0 }}%"></div>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Revenue Cards ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-5">

        {{-- Pendapatan Lunas --}}
        <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-xl shadow-md p-5 sm:p-6 text-white">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-white/20 p-2.5 rounded-lg">
                    <i class="fa-solid fa-wallet text-white text-lg"></i>
                </div>
                <div>
                    <p class="text-emerald-100 text-xs font-semibold uppercase tracking-wider">Total Pendapatan</p>
                    <p class="text-emerald-200 text-xs">Hanya pembayaran lunas</p>
                </div>
            </div>
            <p class="text-2xl sm:text-3xl font-bold">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </p>
            <div class="mt-3 flex items-center gap-2">
                <span class="bg-white/20 text-white text-xs px-2 py-0.5 rounded-full">
                    <i class="fa-solid fa-circle-check text-xs mr-1"></i>Lunas / Paid
                </span>
            </div>
        </div>

        {{-- Pembayaran Tertunda --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 sm:p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-amber-100 p-2.5 rounded-lg">
                    <i class="fa-solid fa-clock text-amber-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-xs font-semibold uppercase tracking-wider">Pembayaran Tertunda</p>
                    <p class="text-gray-400 text-xs">Menunggu konfirmasi</p>
                </div>
            </div>
            <p class="text-2xl sm:text-3xl font-bold text-amber-600">
                Rp {{ number_format($pendingRevenue, 0, ',', '.') }}
            </p>
            <div class="mt-3 flex items-center gap-2">
                <span class="bg-amber-50 text-amber-700 text-xs px-2 py-0.5 rounded-full border border-amber-200">
                    <i class="fa-solid fa-hourglass-half text-xs mr-1"></i>{{ $pendingCount }} transaksi pending
                </span>
            </div>
        </div>
    </div>

    {{-- ── Chart + Upcoming Due ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">

        {{-- Grafik Pendapatan Bulanan --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-5 sm:p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-chart-bar text-blue-500 text-sm"></i>
                    Pendapatan 6 Bulan Terakhir
                </h3>
                <span class="text-xs text-gray-400 bg-gray-50 px-3 py-1 rounded-full border border-gray-100">Hanya lunas</span>
            </div>

            @if($monthlyRevenue->isEmpty())
                <div class="flex flex-col items-center justify-center h-40 text-gray-400">
                    <i class="fa-solid fa-chart-line text-3xl mb-2 text-gray-200"></i>
                    <p class="text-sm">Belum ada data pendapatan</p>
                </div>
            @else
                <div class="relative h-48 sm:h-56">
                    <canvas id="revenueChart"></canvas>
                </div>
            @endif
        </div>

        {{-- Jatuh Tempo Minggu Ini --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 sm:p-6">
            <h3 class="text-base font-bold text-gray-900 flex items-center gap-2 mb-4">
                <i class="fa-solid fa-bell text-red-400 text-sm"></i>
                Jatuh Tempo (7 Hari)
            </h3>

            <div class="space-y-3 max-h-64 overflow-y-auto pr-1">
                @forelse($upcomingDue as $due)
                    <div class="flex items-start gap-3 p-3 bg-red-50 rounded-lg border border-red-100">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-exclamation text-red-500 text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-800 truncate">
                                {{ $due->resident->user->name ?? '-' }}
                            </p>
                            <p class="text-xs text-gray-500 truncate">{{ $due->resident->room->name ?? '-' }}</p>
                            <p class="text-xs text-red-600 font-medium mt-0.5">
                                Rp {{ number_format($due->amount, 0, ',', '.') }}
                                &bull; {{ \Carbon\Carbon::parse($due->due_date)->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-10 text-center text-gray-400">
                        <i class="fa-solid fa-circle-check text-3xl mb-2 text-green-200"></i>
                        <p class="text-sm font-medium text-gray-500">Semua pembayaran on track</p>
                        <p class="text-xs mt-1">Tidak ada yang jatuh tempo minggu ini</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── Quick Actions + Aktivitas Terbaru ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">

        {{-- Quick Actions --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 sm:p-6">
            <h3 class="text-base font-bold text-gray-900 flex items-center gap-2 mb-5">
                <i class="fa-solid fa-bolt text-yellow-400 text-sm"></i>
                Quick Actions
            </h3>
            <div class="grid grid-cols-2 gap-3">

                <a href="{{ route('admin.rooms.index') }}"
                   class="group flex flex-col items-center gap-2 p-4 bg-blue-50 hover:bg-blue-100 border border-blue-100 hover:border-blue-200 rounded-xl transition-all text-center">
                    <div class="w-10 h-10 bg-blue-600 group-hover:bg-blue-700 rounded-xl flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-plus text-white text-sm"></i>
                    </div>
                    <span class="text-xs font-semibold text-gray-700">Tambah Kamar</span>
                </a>

                <a href="{{ route('admin.tenants.index') }}"
                   class="group flex flex-col items-center gap-2 p-4 bg-purple-50 hover:bg-purple-100 border border-purple-100 hover:border-purple-200 rounded-xl transition-all text-center">
                    <div class="w-10 h-10 bg-purple-600 group-hover:bg-purple-700 rounded-xl flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-users text-white text-sm"></i>
                    </div>
                    <span class="text-xs font-semibold text-gray-700">Kelola Penghuni</span>
                </a>

                <a href="{{ route('admin.reports.tenants') }}"
                   class="group flex flex-col items-center gap-2 p-4 bg-emerald-50 hover:bg-emerald-100 border border-emerald-100 hover:border-emerald-200 rounded-xl transition-all text-center">
                    <div class="w-10 h-10 bg-emerald-600 group-hover:bg-emerald-700 rounded-xl flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-users-viewfinder text-white text-sm"></i>
                    </div>
                    <span class="text-xs font-semibold text-gray-700">Laporan Penghuni</span>
                </a>

                <a href="{{ route('admin.reports.finance') }}"
                   class="group flex flex-col items-center gap-2 p-4 bg-amber-50 hover:bg-amber-100 border border-amber-100 hover:border-amber-200 rounded-xl transition-all text-center">
                    <div class="w-10 h-10 bg-amber-500 group-hover:bg-amber-600 rounded-xl flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-file-invoice-dollar text-white text-sm"></i>
                    </div>
                    <span class="text-xs font-semibold text-gray-700">Laporan Keuangan</span>
                </a>
            </div>
        </div>

        {{-- Aktivitas Terbaru --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 sm:p-6">
            <h3 class="text-base font-bold text-gray-900 flex items-center gap-2 mb-5">
                <i class="fa-solid fa-timeline text-blue-400 text-sm"></i>
                Aktivitas Terbaru
            </h3>

            <div class="space-y-3 max-h-64 overflow-y-auto pr-1">
                @forelse($recentActivities as $activity)
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0
                            {{ $activity['color'] === 'green' ? 'bg-emerald-100' : 'bg-blue-100' }}">
                            <i class="fa-solid fa-{{ $activity['icon'] }} text-xs
                                {{ $activity['color'] === 'green' ? 'text-emerald-600' : 'text-blue-600' }}"></i>
                        </div>
                        <div class="flex-1 min-w-0 border-b border-gray-50 pb-3">
                            <p class="text-xs font-semibold text-gray-800 leading-snug">{{ $activity['title'] }}</p>
                            <p class="text-xs text-gray-500">{{ $activity['sub'] }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $activity['time'] }}</p>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-10 text-center text-gray-400">
                        <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                            <i class="fa-solid fa-inbox text-gray-300 text-2xl"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-500">Belum ada aktivitas</p>
                        <p class="text-xs text-gray-400 mt-1">Aktivitas akan muncul di sini</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
</div>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('revenueChart');
    if (!ctx) return;

    @php
        $labels = $monthlyRevenue->pluck('month')->toJson();
        $data   = $monthlyRevenue->pluck('total')->toJson();
    @endphp

    const labels = {!! $labels !!};
    const data   = {!! $data !!};

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: data,
                backgroundColor: 'rgba(16, 185, 129, 0.15)',
                borderColor: 'rgba(16, 185, 129, 0.8)',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
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
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.04)' },
                    ticks: {
                        font: { size: 11 },
                        callback: v => 'Rp ' + (v / 1000000).toFixed(0) + 'jt',
                        color: '#9ca3af',
                    },
                    border: { display: false }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 }, color: '#9ca3af' },
                    border: { display: false }
                }
            }
        }
    });
});
</script>
</x-app-layout>