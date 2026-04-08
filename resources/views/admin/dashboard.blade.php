<x-app-layout>
    <div class="py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">
                    Dashboard Admin
                </h1>
                <p class="text-sm sm:text-base text-gray-600">
                    Ringkasan statistik dan aktivitas terbaru sistem
                </p>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6">

                {{-- Total Kamar --}}
                <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl p-5 text-white shadow-md">
                    <div class="flex justify-between mb-2">
                        <i class="fas fa-door-open text-3xl opacity-20"></i>
                        <span class="text-sm">Kamar</span>
                    </div>
                    <div class="text-3xl font-bold">{{ $totalRooms }}</div>
                    <p class="text-sm opacity-90">Total Kamar</p>
                </div>

                {{-- Terisi --}}
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-5 text-white shadow-md">
                    <div class="flex justify-between mb-2">
                        <i class="fas fa-bed text-3xl opacity-20"></i>
                        <span class="text-sm">Terisi</span>
                    </div>
                    <div class="text-3xl font-bold">{{ $occupiedRooms }}</div>
                    <p class="text-sm opacity-90">Kamar Terisi</p>
                </div>

                {{-- Pending --}}
                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl p-5 text-white shadow-md">
                    <div class="flex justify-between mb-2">
                        <i class="fas fa-clock text-3xl opacity-20"></i>
                        <span class="text-sm">Pending</span>
                    </div>
                    <div class="text-3xl font-bold">{{ $pendingCount }}</div>
                    <p class="text-sm opacity-90">Belum Bayar</p>
                </div>

                {{-- Revenue --}}
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-5 text-white shadow-md">
                    <div class="flex justify-between mb-2">
                        <i class="fas fa-wallet text-3xl opacity-20"></i>
                        <span class="text-sm">Revenue</span>
                    </div>
                    <div class="text-3xl font-bold">
                        Rp {{ number_format($totalRevenue,0,',','.') }}
                    </div>
                    <p class="text-sm opacity-90">Total Pendapatan</p>
                </div>
            </div>

            {{-- GRID --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Chart --}}
                <div class="lg:col-span-2 bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800">
                        Pendapatan 6 Bulan
                    </h2>

                    <canvas id="revenueChart" height="100"></canvas>
                </div>

                {{-- Occupancy --}}
                <div class="bg-white rounded-xl shadow-md p-6 flex flex-col justify-center items-center">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">
                        Occupancy Rate
                    </h2>

                    <div class="text-5xl font-bold text-blue-600 mb-2">
                        {{ $occupancyRate }}%
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full"
                             style="width: {{ $occupancyRate }}%"></div>
                    </div>
                </div>

            </div>

            {{-- TABLE + ACTIVITY --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">

                {{-- Upcoming Payment --}}
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800">
                        Jatuh Tempo
                    </h2>

                    @forelse($upcomingDue as $item)
                        <div class="flex justify-between py-2 border-b">
                            <div>
                                <p class="font-semibold text-gray-800">
                                    {{ $item->resident->user->name ?? '-' }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $item->resident->room->name ?? '-' }}
                                </p>
                            </div>
                            <span class="text-sm text-red-500 font-semibold">
                                {{ \Carbon\Carbon::parse($item->due_date)->format('d M') }}
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">Tidak ada data</p>
                    @endforelse
                </div>

                {{-- Activity --}}
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800">
                        Aktivitas Terbaru
                    </h2>

                    @foreach($recentActivities as $act)
                        <div class="flex items-start gap-3 mb-3">
                            <div class="w-9 h-9 rounded-full bg-{{ $act['color'] }}-100 flex items-center justify-center">
                                <i class="fas fa-{{ $act['icon'] }} text-{{ $act['color'] }}-500"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ $act['title'] }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $act['sub'] }} • {{ $act['time'] }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

        </div>
    </div>

    {{-- Chart JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('revenueChart');

        new Chart(ctx, {
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
                plugins: {
                    legend: { display: false }
                }
            }
        });
    </script>
</x-app-layout>