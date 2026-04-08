<x-app-layout>

<style>
.dash-fade-in {
    opacity: 0;
    transform: translateY(12px);
    animation: dashFadeIn 0.4s ease forwards;
}
@keyframes dashFadeIn {
    to { opacity: 1; transform: translateY(0); }
}
.dash-fade-in:nth-child(1) { animation-delay: 0.05s; }
.dash-fade-in:nth-child(2) { animation-delay: 0.10s; }
.dash-fade-in:nth-child(3) { animation-delay: 0.15s; }
.dash-fade-in:nth-child(4) { animation-delay: 0.20s; }
.stat-bar { transition: width 1s cubic-bezier(0.4,0,0.2,1); }
</style>

<div class="min-h-screen bg-gray-50">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-5 sm:space-y-6">

    {{-- ══ WELCOME BANNER ══════════════════════════════════════════════ --}}
    <div class="relative overflow-hidden rounded-2xl bg-gray-900 px-6 py-7 sm:px-8 sm:py-8 shadow-sm">
        <div class="pointer-events-none absolute -top-16 -right-16 h-56 w-56 rounded-full bg-blue-600/20"></div>
        <div class="pointer-events-none absolute bottom-0 right-32 h-32 w-32 rounded-full bg-blue-400/10"></div>
        <div class="pointer-events-none absolute top-4 right-8 h-4 w-4 rounded-full bg-blue-400/30"></div>

        <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
            <div>
                <p class="text-blue-400 text-xs font-semibold uppercase tracking-widest mb-2">Admin Panel · RumahKos</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-white leading-tight">
                    Selamat Datang Kembali,<br>
                    <span class="text-blue-400">{{ auth()->user()->name }}</span> 👋
                </h1>
                <p class="mt-2 text-gray-400 text-sm">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
            <div class="flex-shrink-0 self-start sm:self-auto">
                <div class="inline-flex flex-col items-center bg-white/10 border border-white/10 rounded-2xl px-6 py-4 text-white">
                    <span class="text-3xl font-extrabold leading-none tracking-tight">{{ $occupancyRate }}<span class="text-lg font-bold">%</span></span>
                    <span class="text-gray-400 text-xs mt-1 font-medium">Tingkat Hunian</span>
                    <div class="mt-2 w-20 h-1.5 bg-white/10 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-400 rounded-full stat-bar" style="width: {{ $occupancyRate }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ STATS GRID ═══════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">

        <div class="dash-fade-in bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="h-10 w-10 rounded-xl bg-slate-100 flex items-center justify-center">
                    <i class="fa-solid fa-building text-slate-500 text-sm"></i>
                </div>
                <span class="text-xs font-semibold text-slate-400 bg-slate-50 px-2 py-0.5 rounded-full">Total</span>
            </div>
            <p class="text-xs text-gray-400 font-medium mb-1">Total Kamar</p>
            <p class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ $totalRooms }}</p>
            <div class="mt-3 h-1 bg-slate-100 rounded-full">
                <div class="h-full bg-slate-300 rounded-full w-full"></div>
            </div>
        </div>

        <div class="dash-fade-in bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="h-10 w-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                </div>
                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Tersedia</span>
            </div>
            <p class="text-xs text-gray-400 font-medium mb-1">Kamar Tersedia</p>
            <p class="text-3xl font-extrabold text-emerald-600 tracking-tight">{{ $availableRooms }}</p>
            <div class="mt-3 h-1 bg-emerald-50 rounded-full overflow-hidden">
                @if($totalRooms > 0)
                <div class="h-full bg-emerald-400 rounded-full stat-bar" style="width:{{ round(($availableRooms/$totalRooms)*100) }}%"></div>
                @endif
            </div>
        </div>

        <div class="dash-fade-in bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="h-10 w-10 rounded-xl bg-blue-50 flex items-center justify-center">
                    <i class="fa-solid fa-user-group text-blue-500 text-sm"></i>
                </div>
                <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">Terisi</span>
            </div>
            <p class="text-xs text-gray-400 font-medium mb-1">Kamar Terisi</p>
            <p class="text-3xl font-extrabold text-blue-600 tracking-tight">{{ $occupiedRooms }}</p>
            <div class="mt-3 h-1 bg-blue-50 rounded-full overflow-hidden">
                @if($totalRooms > 0)
                <div class="h-full bg-blue-400 rounded-full stat-bar" style="width:{{ round(($occupiedRooms/$totalRooms)*100) }}%"></div>
                @endif
            </div>
        </div>

        <div class="dash-fade-in bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="h-10 w-10 rounded-xl bg-violet-50 flex items-center justify-center">
                    <i class="fa-solid fa-person text-violet-500 text-sm"></i>
                </div>
                <span class="text-xs font-semibold text-violet-600 bg-violet-50 px-2 py-0.5 rounded-full">Aktif</span>
            </div>
            <p class="text-xs text-gray-400 font-medium mb-1">Penghuni Aktif</p>
            <p class="text-3xl font-extrabold text-violet-600 tracking-tight">{{ $activeTenants }}</p>
            <div class="mt-3 h-1 bg-violet-50 rounded-full overflow-hidden">
                @if($totalRooms > 0)
                <div class="h-full bg-violet-400 rounded-full stat-bar" style="width:{{ $totalRooms > 0 ? round(($activeTenants/$totalRooms)*100) : 0 }}%"></div>
                @endif
            </div>
        </div>
    </div>

    {{-- ══ REVENUE CARDS ════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">

        <div class="relative overflow-hidden rounded-2xl bg-emerald-600 p-5 sm:p-6 shadow-sm">
            <div class="pointer-events-none absolute -bottom-8 -right-8 h-36 w-36 rounded-full bg-white/10"></div>
            <div class="pointer-events-none absolute top-4 right-20 h-3 w-3 rounded-full bg-white/20"></div>
            <div class="relative flex items-start gap-4">
                <div class="h-11 w-11 flex-shrink-0 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fa-solid fa-wallet text-white text-base"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-emerald-100 text-xs font-semibold uppercase tracking-wider">Total Pendapatan</p>
                    <p class="text-white text-2xl sm:text-3xl font-extrabold mt-1 tracking-tight leading-none">
                        Rp&nbsp;{{ number_format($totalRevenue, 0, ',', '.') }}
                    </p>
                    <span class="mt-3 inline-flex items-center gap-1.5 bg-white/20 text-white text-xs px-2.5 py-1 rounded-full font-medium">
                        <i class="fa-solid fa-circle-check text-xs"></i> Hanya transaksi lunas
                    </span>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-2xl bg-white border border-gray-100 p-5 sm:p-6 shadow-sm">
            <div class="pointer-events-none absolute -bottom-8 -right-8 h-36 w-36 rounded-full bg-amber-50"></div>
            <div class="relative flex items-start gap-4">
                <div class="h-11 w-11 flex-shrink-0 rounded-xl bg-amber-100 flex items-center justify-center">
                    <i class="fa-solid fa-clock text-amber-500 text-base"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Pembayaran Tertunda</p>
                    <p class="text-amber-500 text-2xl sm:text-3xl font-extrabold mt-1 tracking-tight leading-none">
                        Rp&nbsp;{{ number_format($pendingRevenue, 0, ',', '.') }}
                    </p>
                    <span class="mt-3 inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 text-xs px-2.5 py-1 rounded-full font-medium border border-amber-200">
                        <i class="fa-solid fa-hourglass-half text-xs"></i> {{ $pendingCount }} transaksi menunggu
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ CHART + JATUH TEMPO ══════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-4">

        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sm:p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Pendapatan 6 Bulan Terakhir</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Berdasarkan pembayaran lunas</p>
                </div>
                <div class="h-8 w-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                    <i class="fa-solid fa-chart-bar text-emerald-400 text-xs"></i>
                </div>
            </div>

            @if($monthlyRevenue->isEmpty())
                <div class="flex flex-col items-center justify-center h-44 text-center">
                    <div class="h-14 w-14 rounded-2xl bg-gray-50 flex items-center justify-center mb-3">
                        <i class="fa-solid fa-chart-line text-gray-200 text-2xl"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-400">Belum ada data pendapatan</p>
                    <p class="text-xs text-gray-300 mt-1">Akan muncul setelah ada pembayaran lunas</p>
                </div>
            @else
                <div class="relative h-44 sm:h-52">
                    <canvas id="revenueChart"></canvas>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Jatuh Tempo</h3>
                    <p class="text-xs text-gray-400 mt-0.5">7 hari ke depan</p>
                </div>
                <div class="h-8 w-8 rounded-lg bg-red-50 flex items-center justify-center">
                    <i class="fa-solid fa-bell text-red-400 text-xs"></i>
                </div>
            </div>
            <div class="space-y-2.5 max-h-52 overflow-y-auto">
                @forelse($upcomingDue as $due)
                    <div class="flex items-center gap-3 p-3 bg-red-50 rounded-xl border border-red-100">
                        <div class="h-8 w-8 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-triangle-exclamation text-red-400 text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-gray-800 truncate">{{ $due->resident->user->name ?? '-' }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $due->resident->room->name ?? '-' }}</p>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="text-xs font-semibold text-red-600">Rp {{ number_format($due->amount, 0, ',', '.') }}</span>
                                <span class="text-gray-200">·</span>
                                <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($due->due_date)->format('d M') }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-8 text-center">
                        <div class="h-12 w-12 rounded-2xl bg-emerald-50 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-circle-check text-emerald-300 text-xl"></i>
                        </div>
                        <p class="text-sm font-semibold text-gray-500">Semua on track!</p>
                        <p class="text-xs text-gray-400 mt-1">Tidak ada yang jatuh tempo</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ══ QUICK ACTIONS + AKTIVITAS ════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4">

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sm:p-6">
            <div class="flex items-center gap-2.5 mb-5">
                <div class="h-8 w-8 rounded-lg bg-yellow-50 flex items-center justify-center">
                    <i class="fa-solid fa-bolt text-yellow-400 text-xs"></i>
                </div>
                <h3 class="text-sm font-bold text-gray-900">Aksi Cepat</h3>
            </div>
            <div class="grid grid-cols-2 gap-2.5">
                <a href="{{ route('admin.rooms.index') }}"
                   class="group flex items-center gap-3 p-3.5 rounded-xl bg-gray-50 hover:bg-blue-50 border border-gray-100 hover:border-blue-100 transition-all">
                    <div class="h-9 w-9 rounded-xl bg-blue-600 group-hover:bg-blue-700 flex items-center justify-center flex-shrink-0 transition-colors">
                        <i class="fa-solid fa-plus text-white text-xs"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-700 group-hover:text-blue-700 transition-colors leading-snug">Tambah Kamar</p>
                        <p class="text-xs text-gray-400 hidden sm:block">Kelola unit</p>
                    </div>
                </a>
                <a href="{{ route('admin.tenants.index') }}"
                   class="group flex items-center gap-3 p-3.5 rounded-xl bg-gray-50 hover:bg-violet-50 border border-gray-100 hover:border-violet-100 transition-all">
                    <div class="h-9 w-9 rounded-xl bg-violet-600 group-hover:bg-violet-700 flex items-center justify-center flex-shrink-0 transition-colors">
                        <i class="fa-solid fa-users text-white text-xs"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-700 group-hover:text-violet-700 transition-colors leading-snug">Penghuni</p>
                        <p class="text-xs text-gray-400 hidden sm:block">Kelola tenant</p>
                    </div>
                </a>
                <a href="{{ route('admin.reports.tenants') }}"
                   class="group flex items-center gap-3 p-3.5 rounded-xl bg-gray-50 hover:bg-emerald-50 border border-gray-100 hover:border-emerald-100 transition-all">
                    <div class="h-9 w-9 rounded-xl bg-emerald-600 group-hover:bg-emerald-700 flex items-center justify-center flex-shrink-0 transition-colors">
                        <i class="fa-solid fa-users-viewfinder text-white text-xs"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-700 group-hover:text-emerald-700 transition-colors leading-snug">Lap. Penghuni</p>
                        <p class="text-xs text-gray-400 hidden sm:block">Lihat laporan</p>
                    </div>
                </a>
                <a href="{{ route('admin.reports.finance') }}"
                   class="group flex items-center gap-3 p-3.5 rounded-xl bg-gray-50 hover:bg-amber-50 border border-gray-100 hover:border-amber-100 transition-all">
                    <div class="h-9 w-9 rounded-xl bg-amber-500 group-hover:bg-amber-600 flex items-center justify-center flex-shrink-0 transition-colors">
                        <i class="fa-solid fa-file-invoice-dollar text-white text-xs"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-700 group-hover:text-amber-700 transition-colors leading-snug">Lap. Keuangan</p>
                        <p class="text-xs text-gray-400 hidden sm:block">Rekap dana</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sm:p-6">
            <div class="flex items-center gap-2.5 mb-5">
                <div class="h-8 w-8 rounded-lg bg-blue-50 flex items-center justify-center">
                    <i class="fa-solid fa-timeline text-blue-400 text-xs"></i>
                </div>
                <h3 class="text-sm font-bold text-gray-900">Aktivitas Terbaru</h3>
            </div>
            <div class="space-y-1 max-h-52 overflow-y-auto">
                @forelse($recentActivities as $activity)
                    <div class="flex items-start gap-3 py-2.5 border-b border-gray-50 last:border-0">
                        <div class="h-8 w-8 rounded-lg flex items-center justify-center flex-shrink-0
                            {{ $activity['color'] === 'green' ? 'bg-emerald-50' : 'bg-blue-50' }}">
                            <i class="fa-solid fa-{{ $activity['icon'] }} text-xs
                                {{ $activity['color'] === 'green' ? 'text-emerald-500' : 'text-blue-500' }}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-800 leading-snug truncate">{{ $activity['title'] }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $activity['sub'] }}</p>
                        </div>
                        <span class="text-xs text-gray-300 flex-shrink-0 pt-0.5 whitespace-nowrap">{{ $activity['time'] }}</span>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-10 text-center">
                        <div class="h-12 w-12 rounded-2xl bg-gray-50 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-inbox text-gray-200 text-xl"></i>
                        </div>
                        <p class="text-sm font-semibold text-gray-400">Belum ada aktivitas</p>
                        <p class="text-xs text-gray-300 mt-1">Akan muncul di sini otomatis</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('revenueChart');
    if (!ctx) return;

    @php
        $labels = $monthlyRevenue->pluck('month')->toJson();
        $data   = $monthlyRevenue->pluck('total')->toJson();
    @endphp

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! $labels !!},
            datasets: [{
                data: {!! $data !!},
                backgroundColor: 'rgba(16,185,129,0.12)',
                borderColor: 'rgba(16,185,129,0.75)',
                borderWidth: 2,
                borderRadius: 10,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#111827',
                    titleColor: '#9ca3af',
                    bodyColor: '#fff',
                    titleFont: { size: 11 },
                    bodyFont: { size: 13, weight: 'bold' },
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: {
                        label: ctx => ' Rp ' + ctx.parsed.y.toLocaleString('id-ID'),
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.04)' },
                    ticks: {
                        font: { size: 10 },
                        callback: v => {
                            if (v >= 1000000) return 'Rp ' + (v/1000000).toFixed(0) + 'jt';
                            if (v >= 1000) return 'Rp ' + (v/1000).toFixed(0) + 'rb';
                            return 'Rp ' + v;
                        },
                        color: '#d1d5db',
                        maxTicksLimit: 5,
                    },
                    border: { display: false }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10 }, color: '#d1d5db' },
                    border: { display: false }
                }
            }
        }
    });
});
</script>
</x-app-layout>