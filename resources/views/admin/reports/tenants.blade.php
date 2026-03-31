<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Laporan Penghuni</h2>
                <p class="text-sm text-gray-500">Rekap data seluruh penghuni kos</p>
            </div>
            {{-- Export Buttons --}}
            <div class="flex items-center gap-2">
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
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl space-y-5 px-4 sm:px-6 lg:px-8">

            {{-- Stats --}}
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-5">
                @php
                    $cards = [
                        ['label' => 'Total',     'value' => $stats['total'],     'bg' => 'bg-gray-50',   'text' => 'text-gray-800',  'border' => 'border-gray-200'],
                        ['label' => 'Aktif',     'value' => $stats['active'],    'bg' => 'bg-green-50',  'text' => 'text-green-700', 'border' => 'border-green-200'],
                        ['label' => 'Inactive',  'value' => $stats['inactive'],  'bg' => 'bg-yellow-50', 'text' => 'text-yellow-700','border' => 'border-yellow-200'],
                        ['label' => 'Expired',   'value' => $stats['expired'],   'bg' => 'bg-slate-50',  'text' => 'text-slate-600', 'border' => 'border-slate-200'],
                        ['label' => 'Cancelled', 'value' => $stats['cancelled'], 'bg' => 'bg-red-50',    'text' => 'text-red-700',   'border' => 'border-red-200'],
                    ];
                @endphp
                @foreach ($cards as $c)
                    <div class="rounded-xl border {{ $c['border'] }} {{ $c['bg'] }} p-4 text-center">
                        <p class="text-xl font-bold {{ $c['text'] }}">{{ $c['value'] }}</p>
                        <p class="mt-0.5 text-xs text-gray-500">{{ $c['label'] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Filter Bar --}}
            <form method="GET" action="{{ route('admin.reports.tenants') }}"
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
                    <option value="active"    {{ request('status') === 'active'    ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive"  {{ request('status') === 'inactive'  ? 'selected' : '' }}>Inactive</option>
                    <option value="expired"   {{ request('status') === 'expired'   ? 'selected' : '' }}>Expired</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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

                {{-- Tanggal Masuk --}}
                <div class="flex items-center gap-1.5">
                    <span class="text-xs text-gray-400">Masuk</span>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                        class="rounded-lg border border-gray-300 px-2.5 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    <span class="text-xs text-gray-400">–</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                        class="rounded-lg border border-gray-300 px-2.5 py-2 text-sm focus:border-blue-500 focus:outline-none">
                </div>

                {{-- Actions --}}
                <div class="flex gap-2">
                    <button type="submit"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700">
                        Terapkan
                    </button>
                    @if (request()->hasAny(['search','status','property_id','date_from','date_to']))
                        <a href="{{ route('admin.reports.tenants') }}"
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
                                <th class="px-4 py-3 hidden md:table-cell">Tgl Masuk</th>
                                <th class="px-4 py-3 hidden md:table-cell">Tgl Keluar</th>
                                <th class="px-4 py-3 hidden lg:table-cell">Durasi</th>
                                <th class="px-4 py-3 hidden lg:table-cell">Bayar</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($residents as $i => $resident)
                                @php
                                    $paid     = $resident->payments->where('status', 'paid')->count();
                                    $total    = $resident->payments->count();
                                    $duration = \Carbon\Carbon::parse($resident->start_date)
                                        ->diffInMonths(\Carbon\Carbon::parse($resident->end_date));
                                    $badge = match($resident->status) {
                                        'active'    => 'bg-green-100 text-green-700',
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
                                <tr class="hover:bg-gray-50/60 transition-colors">
                                    <td class="px-4 py-3.5 text-xs text-gray-400">{{ $residents->firstItem() + $i }}</td>
                                    <td class="px-4 py-3.5">
                                        <p class="font-medium text-gray-900">{{ $resident->user->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $resident->user->email }}</p>
                                        {{-- Mobile: extra info --}}
                                        <div class="mt-1 flex flex-wrap gap-x-3 text-xs text-gray-400 sm:hidden">
                                            <span>{{ $resident->room->name ?? '-' }}</span>
                                            <span>{{ \Carbon\Carbon::parse($resident->start_date)->format('d M Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5 hidden sm:table-cell">
                                        <p class="font-medium text-gray-700">{{ $resident->room->name ?? '-' }}</p>
                                        <p class="text-xs text-gray-400">{{ $resident->room->property->name ?? '' }}</p>
                                    </td>
                                    <td class="px-4 py-3.5 text-gray-600 hidden md:table-cell">
                                        {{ \Carbon\Carbon::parse($resident->start_date)->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-3.5 text-gray-600 hidden md:table-cell">
                                        {{ \Carbon\Carbon::parse($resident->end_date)->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-3.5 text-gray-600 hidden lg:table-cell">{{ $duration }} bln</td>
                                    <td class="px-4 py-3.5 hidden lg:table-cell">
                                        <div class="flex items-center gap-1.5">
                                            <div class="h-1.5 w-16 overflow-hidden rounded-full bg-gray-100">
                                                <div class="h-full rounded-full bg-green-500 transition-all"
                                                     style="width: {{ $total > 0 ? round($paid/$total*100) : 0 }}%"></div>
                                            </div>
                                            <span class="text-xs text-gray-500">{{ $paid }}/{{ $total }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $badge }}">
                                            {{ $label }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-16 text-center">
                                        <svg class="mx-auto mb-3 h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <p class="text-sm text-gray-400">Tidak ada data penghuni</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($residents->hasPages())
                    <div class="border-t border-gray-100 px-4 py-3">
                        {{ $residents->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>