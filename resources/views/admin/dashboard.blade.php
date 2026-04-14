<x-app-layout>
    <div class="py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">Dashboard Admin</h1>
                <p class="text-sm sm:text-base text-gray-600">Ringkasan statistik dan aktivitas terbaru sistem</p>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6">

                <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl p-5 text-white shadow-md">
                    <div class="flex justify-between mb-2">
                        <i class="fas fa-door-open text-3xl opacity-20"></i>
                        <span class="text-sm">Kamar</span>
                    </div>
                    <div class="text-3xl font-bold">{{ $totalRooms }}</div>
                    <p class="text-sm opacity-90">Total Kamar</p>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-5 text-white shadow-md">
                    <div class="flex justify-between mb-2">
                        <i class="fas fa-bed text-3xl opacity-20"></i>
                        <span class="text-sm">Terisi</span>
                    </div>
                    <div class="text-3xl font-bold">{{ $occupiedRooms }}</div>
                    <p class="text-sm opacity-90">Kamar Terisi</p>
                </div>

                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl p-5 text-white shadow-md">
                    <div class="flex justify-between mb-2">
                        <i class="fas fa-clock text-3xl opacity-20"></i>
                        <span class="text-sm">Pending</span>
                    </div>
                    <div class="text-3xl font-bold">{{ $pendingCount }}</div>
                    <p class="text-sm opacity-90">Belum Bayar</p>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-5 text-white shadow-md">
                    <div class="flex justify-between mb-2">
                        <i class="fas fa-wallet text-3xl opacity-20"></i>
                        <span class="text-sm">Revenue</span>
                    </div>
                    <div class="text-3xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                    <p class="text-sm opacity-90">Total Pendapatan</p>
                </div>
            </div>

            {{-- ══ CARD NUNGGAK ══ --}}
            @php
                $overduePayments = \App\Models\Payment::with(['resident.user', 'resident.room'])
                    ->where('status', 'pending')
                    ->where('due_date', '<', now())
                    ->orderBy('due_date', 'asc')
                    ->get();
                $overdueTenantsCount = $overduePayments->pluck('resident_id')->unique()->count();
                $overdueTotalAmount  = $overduePayments->sum('amount');
            @endphp

            @if($overduePayments->count() > 0)
                <div class="mb-6 bg-red-50 border border-red-300 rounded-xl overflow-hidden shadow-sm">
                    {{-- Header --}}
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-5 py-4 bg-red-100 border-b border-red-200">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-red-600 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-bold text-red-800 text-sm sm:text-base">
                                    {{ $overdueTenantsCount }} penghuni nunggak — {{ $overduePayments->count() }} tagihan belum dibayar
                                </p>
                                <p class="text-xs text-red-600">
                                    Total tunggakan: <span class="font-bold">Rp {{ number_format($overdueTotalAmount, 0, ',', '.') }}</span>
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('admin.tenants.index', ['status' => 'overdue']) }}"
                           class="inline-flex items-center gap-1.5 text-xs font-semibold text-red-700 hover:text-red-900 underline whitespace-nowrap">
                            Lihat semua <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>

                    {{-- List (max 5) --}}
                    <div class="divide-y divide-red-100">
                        @foreach($overduePayments->take(5) as $op)
                            <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-3 hover:bg-red-100 transition-colors">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 bg-red-200 rounded-full flex items-center justify-center flex-shrink-0 text-red-700 font-bold text-sm">
                                        {{ strtoupper(substr($op->resident->user->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">
                                            {{ $op->resident->user->name ?? '-' }}
                                        </p>
                                        <p class="text-xs text-gray-500 truncate">
                                            {{ $op->resident->room->name ?? '-' }} •
                                            {{ $op->billing_month->format('F Y') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 flex-shrink-0">
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-900">Rp {{ number_format($op->amount, 0, ',', '.') }}</p>
                                        <p class="text-xs text-red-500 font-medium">{{ $op->due_date->diffForHumans() }}</p>
                                    </div>
                                    <a href="{{ route('admin.tenants.show', $op->resident->user_id) }}"
                                       class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition-colors">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($overduePayments->count() > 5)
                        <div class="px-5 py-3 bg-red-100 border-t border-red-200 text-center">
                            <a href="{{ route('admin.tenants.index', ['status' => 'overdue']) }}"
                               class="text-xs font-semibold text-red-700 hover:text-red-900 hover:underline">
                                + {{ $overduePayments->count() - 5 }} tagihan lainnya
                            </a>
                        </div>
                    @endif
                </div>
            @endif
            {{-- ══ END CARD NUNGGAK ══ --}}

            {{-- GRID --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Chart --}}
                <div class="lg:col-span-2 bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800">Pendapatan 6 Bulan</h2>
                    <canvas id="revenueChart" height="100"></canvas>
                </div>

                {{-- Occupancy --}}
                <div class="bg-white rounded-xl shadow-md p-6 flex flex-col justify-center items-center">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Occupancy Rate</h2>
                    <div class="text-5xl font-bold text-blue-600 mb-2">{{ $occupancyRate }}%</div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full" style="width: {{ $occupancyRate }}%"></div>
                    </div>
                </div>

            </div>

            {{-- TABLE + ACTIVITY --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">

                {{-- Upcoming Payment --}}
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800">Jatuh Tempo</h2>
                    @forelse($upcomingDue as $item)
                        @php $isPast = \Carbon\Carbon::parse($item->due_date)->isPast(); @endphp
                        <div class="flex justify-between py-2 border-b {{ $isPast ? 'bg-red-50 -mx-2 px-2 rounded' : '' }}">
                            <div>
                                <p class="font-semibold text-gray-800 flex items-center gap-2">
                                    {{ $item->resident->user->name ?? '-' }}
                                    @if($isPast)
                                        <span class="text-xs px-1.5 py-0.5 bg-red-100 text-red-600 rounded-full font-semibold">Terlambat</span>
                                    @endif
                                </p>
                                <p class="text-sm text-gray-500">{{ $item->resident->room->name ?? '-' }}</p>
                            </div>
                            <span class="text-sm {{ $isPast ? 'text-red-600 font-bold' : 'text-red-500 font-semibold' }}">
                                {{ \Carbon\Carbon::parse($item->due_date)->format('d M') }}
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">Tidak ada data</p>
                    @endforelse
                </div>

                {{-- Activity --}}
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800">Aktivitas Terbaru</h2>
                    @foreach($recentActivities as $act)
                        <div class="flex items-start gap-3 mb-3">
                            <div class="w-9 h-9 rounded-full bg-{{ $act['color'] }}-100 flex items-center justify-center">
                                <i class="fas fa-{{ $act['icon'] }} text-{{ $act['color'] }}-500"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $act['title'] }}</p>
                                <p class="text-xs text-gray-500">{{ $act['sub'] }} • {{ $act['time'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyRevenue->pluck('month')) !!},
                datasets: [{
                    label: 'Pendapatan',
                    data: {!! json_encode($monthlyRevenue->pluck('total')) !!},
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } }
            }
        });
    </script>
</x-app-layout>