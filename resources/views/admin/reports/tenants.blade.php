<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Laporan Penghuni</h2>
                <p class="text-sm text-gray-500">Rekap data seluruh penghuni kos</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 sm:gap-6">
                @php
                    $cards = [
                        ['label' => 'Total',     'value' => $stats['total'],     'icon' => 'fa-users',        'from' => 'from-gray-800', 'to' => 'to-gray-900'],
                        ['label' => 'Aktif',     'value' => $stats['active'],    'icon' => 'fa-user-check',   'from' => 'from-blue-500', 'to' => 'to-blue-600'],
                        ['label' => 'Inactive',  'value' => $stats['inactive'],  'icon' => 'fa-user-clock',   'from' => 'from-gray-700', 'to' => 'to-gray-800'],
                        ['label' => 'Expired',   'value' => $stats['expired'],   'icon' => 'fa-user-times',   'from' => 'from-blue-400', 'to' => 'to-blue-500'],
                        ['label' => 'Cancelled', 'value' => $stats['cancelled'], 'icon' => 'fa-user-minus',   'from' => 'from-gray-600', 'to' => 'to-gray-700'],
                    ];
                @endphp
                @foreach ($cards as $c)
                    <div class="bg-gradient-to-br {{ $c['from'] }} {{ $c['to'] }} rounded-lg sm:rounded-xl p-4 sm:p-5 text-white shadow-md">
                        <div class="flex items-center justify-between mb-2">
                            <i class="fas {{ $c['icon'] }} text-2xl sm:text-3xl opacity-20"></i>
                            <span class="text-xs sm:text-sm opacity-90">{{ $c['label'] }}</span>
                        </div>
                        <div class="text-2xl sm:text-3xl font-bold">{{ $c['value'] }}</div>
                        <p class="text-xs sm:text-sm opacity-90 mt-1">Penghuni</p>
                    </div>
                @endforeach
            </div>

            {{-- Filter Bar --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-4 sm:p-6">
                <form method="GET" action="{{ route('admin.reports.tenants') }}"
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
                            <option value="active"    {{ request('status') === 'active'    ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive"  {{ request('status') === 'inactive'  ? 'selected' : '' }}>Inactive</option>
                            <option value="expired"   {{ request('status') === 'expired'   ? 'selected' : '' }}>Expired</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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

                    <div class="flex items-center gap-1.5">
                        <span class="text-xs text-gray-500 whitespace-nowrap">Masuk</span>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="px-2.5 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <span class="text-xs text-gray-400">–</span>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="px-2.5 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>

                    <div class="flex gap-2">
                        <button type="submit"
                            class="flex-1 sm:flex-none px-4 sm:px-6 py-2 sm:py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-semibold text-sm sm:text-base active:scale-[0.98]">
                            <i class="fas fa-search mr-2"></i>Cari
                        </button>
                        @if (request()->hasAny(['search','status','property_id','date_from','date_to']))
                            <a href="{{ route('admin.reports.tenants') }}"
                                class="flex-1 sm:flex-none px-4 sm:px-6 py-2 sm:py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all font-semibold text-sm sm:text-base text-center active:scale-[0.98]">
                                <i class="fas fa-times mr-2"></i>Reset
                            </a>
                        @endif
                    </div>

                </form>

                @if(request()->hasAny(['search','status','property_id','date_from','date_to']))
                    <div class="mt-3 text-sm text-gray-600">
                        Menampilkan <span class="font-semibold">{{ $residents->total() }}</span> penghuni
                        @if(request('search'))
                            <span class="font-semibold"> • Pencarian: "{{ request('search') }}"</span>
                        @endif
                        @if(request('status'))
                            <span class="font-semibold"> • Status: {{ request('status') }}</span>
                        @endif
                    </div>
                @endif
            </div>
            
            <div class="flex items-center gap-2 ml-2">
                <a href="{{ route('admin.reports.tenants.excel', request()->query()) }}"
                   class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-300 bg-emerald-50 px-3 py-2 text-xs font-medium text-emerald-700 transition hover:bg-emerald-100">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Export CSV
                </a>
                <a href="{{ route('admin.reports.tenants.pdf', request()->query()) }}"
                   class="inline-flex items-center gap-1.5 rounded-lg border border-red-300 bg-red-50 px-3 py-2 text-xs font-medium text-red-700 transition hover:bg-red-100">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Export PDF
                </a>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-md overflow-hidden">
                @if($residents->count() > 0)

                    {{-- Mobile Card View --}}
                    <div class="block lg:hidden divide-y divide-gray-200">
                        @foreach ($residents as $i => $resident)
                            @php
                                $paid     = $resident->payments->where('status', 'paid')->count();
                                $total    = $resident->payments->count();
                                $duration = \Carbon\Carbon::parse($resident->start_date)->diffInMonths(\Carbon\Carbon::parse($resident->end_date));
                                $badge = match($resident->status) {
                                    'active'    => 'bg-blue-100 text-blue-700',
                                    'inactive'  => 'bg-yellow-100 text-yellow-700',
                                    'expired'   => 'bg-gray-100 text-gray-600',
                                    'cancelled' => 'bg-red-100 text-red-600',
                                    default     => 'bg-gray-100 text-gray-500',
                                };
                                $label = match($resident->status) {
                                    'active'    => 'Aktif',
                                    'inactive'  => 'Inactive',
                                    'expired'   => 'Expired',
                                    'cancelled' => 'Cancelled',
                                    default     => $resident->status,
                                };
                            @endphp
                            <div class="p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start justify-between gap-3 mb-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                                            {{ substr($resident->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $resident->user->name }}</p>
                                            <p class="text-xs text-gray-400">{{ $resident->user->email }}</p>
                                            <p class="text-xs text-gray-500 mt-0.5">
                                                {{ $resident->room->name ?? '-' }} • {{ $resident->room->property->name ?? '' }}
                                            </p>
                                        </div>
                                    </div>
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold flex-shrink-0 {{ $badge }}">
                                        {{ $label }}
                                    </span>
                                </div>
                                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-3 text-xs border border-blue-200">
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-500">Masuk</span>
                                        <span class="text-gray-700">{{ \Carbon\Carbon::parse($resident->start_date)->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-gray-500">Keluar</span>
                                        <span class="text-gray-700">{{ \Carbon\Carbon::parse($resident->end_date)->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-gray-500">Durasi</span>
                                        <span class="text-gray-700">{{ $duration }} bulan</span>
                                    </div>
                                    <div class="flex items-center justify-between mt-2">
                                        <span class="text-gray-500">Pembayaran</span>
                                        <div class="flex items-center gap-1.5">
                                            <div class="h-1.5 w-16 overflow-hidden rounded-full bg-gray-200">
                                                <div class="h-full rounded-full bg-blue-500"
                                                     style="width: {{ $total > 0 ? round($paid/$total*100) : 0 }}%"></div>
                                            </div>
                                            <span class="text-gray-600 font-medium">{{ $paid }}/{{ $total }}</span>
                                        </div>
                                    </div>
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
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tgl Masuk</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tgl Keluar</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Durasi</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Bayar</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($residents as $i => $resident)
                                    @php
                                        $paid     = $resident->payments->where('status', 'paid')->count();
                                        $total    = $resident->payments->count();
                                        $duration = \Carbon\Carbon::parse($resident->start_date)->diffInMonths(\Carbon\Carbon::parse($resident->end_date));
                                        $badge = match($resident->status) {
                                            'active'    => 'bg-blue-100 text-blue-700',
                                            'inactive'  => 'bg-yellow-100 text-yellow-700',
                                            'expired'   => 'bg-gray-100 text-gray-600',
                                            'cancelled' => 'bg-red-100 text-red-600',
                                            default     => 'bg-gray-100 text-gray-500',
                                        };
                                        $label = match($resident->status) {
                                            'active'    => 'Aktif',
                                            'inactive'  => 'Inactive',
                                            'expired'   => 'Expired',
                                            'cancelled' => 'Cancelled',
                                            default     => $resident->status,
                                        };
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 text-xs text-gray-400">{{ $residents->firstItem() + $i }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                                                    {{ substr($resident->user->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-gray-900">{{ $resident->user->name }}</div>
                                                    <div class="text-xs text-gray-500">{{ $resident->user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-800">{{ $resident->room->name ?? '-' }}</div>
                                            <div class="text-xs text-gray-500">{{ $resident->room->property->name ?? '' }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($resident->start_date)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($resident->end_date)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $duration }} bln</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-1.5">
                                                <div class="h-1.5 w-16 overflow-hidden rounded-full bg-gray-100">
                                                    <div class="h-full rounded-full bg-blue-500 transition-all"
                                                         style="width: {{ $total > 0 ? round($paid/$total*100) : 0 }}%"></div>
                                                </div>
                                                <span class="text-xs text-gray-500">{{ $paid }}/{{ $total }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                                                {{ $label }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-16 text-center">
                                            <i class="fas fa-users text-4xl text-gray-300 mb-3"></i>
                                            <p class="text-gray-500">Tidak ada data penghuni</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($residents->hasPages())
                        <div class="px-4 py-3 sm:px-6 border-t border-gray-200">
                            {{ $residents->appends(request()->query())->links() }}
                        </div>
                    @endif

                @else
                    <div class="text-center py-12">
                        <i class="fas fa-users text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 mb-4">Tidak ada data penghuni</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>