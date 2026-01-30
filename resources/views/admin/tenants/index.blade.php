<x-app-layout>
<div class="py-6 sm:py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">Kelola Penghuni</h1>
                    <p class="text-sm sm:text-base text-gray-600">Manajemen data penghuni kos</p>
                </div>
                <a href="{{ route('admin.tenants.create') }}"
                   class="inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2 sm:py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg sm:rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all font-semibold text-sm sm:text-base shadow-md hover:shadow-lg active:scale-[0.98]">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Penghuni</span>
                </a>
            </div>
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

        {{-- Search & Filter --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-4 sm:p-6 mb-6">
            <form method="GET" action="{{ route('admin.tenants.index') }}" class="space-y-4 sm:space-y-0 sm:flex sm:gap-4">
                <div class="flex-1">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari nama, email, telepon, atau NIK..."
                           class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base">
                </div>
                <div class="w-full sm:w-48">
                    <select name="status"
                            class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                            class="flex-1 sm:flex-none px-4 sm:px-6 py-2 sm:py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-semibold text-sm sm:text-base active:scale-[0.98]">
                        <i class="fas fa-search mr-2"></i>Cari
                    </button>
                    @if(request('search') || request('status'))
                        <a href="{{ route('admin.tenants.index') }}"
                           class="flex-1 sm:flex-none px-4 sm:px-6 py-2 sm:py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all font-semibold text-sm sm:text-base text-center active:scale-[0.98]">
                            <i class="fas fa-times mr-2"></i>Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6">
            @php
                $totalTenants = \App\Models\User::where('role_id', 2)->count(); // 2 = tenant
                $activeTenants = \App\Models\Resident::where('status', 'active')->distinct('user_id')->count('user_id');
                $inactiveTenants = $totalTenants - $activeTenants;
                $pendingPayments = \App\Models\Payment::where('status', 'pending')->count();
            @endphp

            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg sm:rounded-xl p-4 sm:p-5 text-white shadow-md">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-users text-2xl sm:text-3xl opacity-20"></i>
                    <span class="text-xs sm:text-sm opacity-90">Total</span>
                </div>
                <div class="text-2xl sm:text-3xl font-bold">{{ $totalTenants }}</div>
                <p class="text-xs sm:text-sm opacity-90 mt-1">Penghuni</p>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg sm:rounded-xl p-4 sm:p-5 text-white shadow-md">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-user-check text-2xl sm:text-3xl opacity-20"></i>
                    <span class="text-xs sm:text-sm opacity-90">Status</span>
                </div>
                <div class="text-2xl sm:text-3xl font-bold">{{ $activeTenants }}</div>
                <p class="text-xs sm:text-sm opacity-90 mt-1">Aktif</p>
            </div>

            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg sm:rounded-xl p-4 sm:p-5 text-white shadow-md">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-user-clock text-2xl sm:text-3xl opacity-20"></i>
                    <span class="text-xs sm:text-sm opacity-90">Status</span>
                </div>
                <div class="text-2xl sm:text-3xl font-bold">{{ $inactiveTenants }}</div>
                <p class="text-xs sm:text-sm opacity-90 mt-1">Tidak Aktif</p>
            </div>

            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg sm:rounded-xl p-4 sm:p-5 text-white shadow-md">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-exclamation-circle text-2xl sm:text-3xl opacity-20"></i>
                    <span class="text-xs sm:text-sm opacity-90">Pembayaran</span>
                </div>
                <div class="text-2xl sm:text-3xl font-bold">{{ $pendingPayments }}</div>
                <p class="text-xs sm:text-sm opacity-90 mt-1">Pending</p>
            </div>
        </div>

        {{-- Tenants Table --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-md overflow-hidden">
            @if($tenants->count() > 0)
                {{-- Mobile View --}}
                <div class="block lg:hidden divide-y divide-gray-200">
                    @foreach($tenants as $tenant)
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-gray-900 truncate">{{ $tenant->name }}</h3>
                                    <p class="text-xs text-gray-600 truncate">{{ $tenant->email }}</p>
                                    @if($tenant->profile && $tenant->profile->phone)
                                        <p class="text-xs text-gray-600"><i class="fas fa-phone mr-1"></i>{{ $tenant->profile->phone }}</p>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    @if($tenant->resident && $tenant->resident->status === 'active')
                                        <span class="px-2.5 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold whitespace-nowrap">
                                            <i class="fas fa-check-circle"></i> Aktif
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold whitespace-nowrap">
                                            <i class="fas fa-times-circle"></i> Tidak Aktif
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if($tenant->resident)
                                <div class="bg-gray-50 rounded-lg p-3 mb-3 text-xs">
                                    <div class="flex items-center gap-2 text-gray-700">
                                        <i class="fas fa-home"></i>
                                        <span class="font-medium">{{ $tenant->resident->room->name }}</span>
                                        <span class="text-gray-500">•</span>
                                        <span>{{ $tenant->resident->room->property->name }}</span>
                                    </div>
                                </div>
                            @endif

                            <div class="flex gap-2">
                                <a href="{{ route('admin.tenants.show', $tenant) }}"
                                   class="flex-1 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-all text-xs font-semibold text-center">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </a>
                                <a href="{{ route('admin.tenants.edit', $tenant) }}"
                                   class="flex-1 px-3 py-1.5 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 transition-all text-xs font-semibold text-center">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                                <button onclick="confirmDelete({{ $tenant->id }})"
                                        class="flex-1 px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-all text-xs font-semibold text-center">
                                    <i class="fas fa-trash mr-1"></i>Hapus
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Desktop View --}}
                <div class="hidden lg:block overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Penghuni</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kontak</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kamar</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($tenants as $tenant)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-full flex items-center justify-center text-white font-semibold">
                                                {{ substr($tenant->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-900">{{ $tenant->name }}</div>
                                                <div class="text-sm text-gray-600">{{ $tenant->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        @if($tenant->profile)
                                            <div>{{ $tenant->profile->phone ?? '-' }}</div>
                                            <div class="text-xs text-gray-500">{{ $tenant->profile->identity_number ?? '-' }}</div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($tenant->resident)
                                            <div class="font-medium text-gray-900">{{ $tenant->resident->room->name }}</div>
                                            <div class="text-xs text-gray-600">{{ $tenant->resident->room->property->name }}</div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($tenant->resident && $tenant->resident->status === 'active')
                                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                                <i class="fas fa-check-circle"></i> Aktif
                                            </span>
                                        @else
                                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">
                                                <i class="fas fa-times-circle"></i> Tidak Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.tenants.show', $tenant) }}"
                                               class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                               title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.tenants.edit', $tenant) }}"
                                               class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition-all"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="confirmDelete({{ $tenant->id }})"
                                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-4 py-3 sm:px-6 border-t border-gray-200">
                    {{ $tenants->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-users text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-600 mb-4">Tidak ada data penghuni</p>
                    <a href="{{ route('admin.tenants.create') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-semibold text-sm">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Penghuni</span>
                    </a>
                </div>
            @endif
        </div>

    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl sm:rounded-2xl p-6 sm:p-8 max-w-md mx-4 shadow-2xl">
        <div class="text-center">
            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-3xl sm:text-4xl text-red-600"></i>
            </div>
            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">Hapus Penghuni?</h3>
            <p class="text-sm sm:text-base text-gray-600 mb-6">Tindakan ini akan menghapus semua data penghuni termasuk riwayat pembayaran. Apakah Anda yakin?</p>
            <form id="deleteForm" method="POST" class="flex flex-col sm:flex-row gap-3">
                @csrf
                @method('DELETE')
                <button type="button"
                        onclick="closeDeleteModal()"
                        class="flex-1 px-4 sm:px-6 py-2.5 sm:py-3 border-2 border-gray-300 text-gray-700 rounded-lg sm:rounded-xl font-semibold hover:bg-gray-50 transition-all text-sm sm:text-base">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 px-4 sm:px-6 py-2.5 sm:py-3 bg-red-600 text-white rounded-lg sm:rounded-xl font-semibold hover:bg-red-700 transition-all shadow-md text-sm sm:text-base">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function confirmDelete(tenantId) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    form.action = `/admin/tenants/${tenantId}`;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close modal on ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>

</x-app-layout>
