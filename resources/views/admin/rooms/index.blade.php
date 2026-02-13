<x-app-layout>
<div class="py-6 sm:py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">Manajemen Kamar</h1>
                    <p class="text-sm sm:text-base text-gray-600">Kelola semua kamar properti Anda</p>
                </div>
                <a href="{{ route('admin.rooms.create') }}"
                   class="inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2 sm:py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg sm:rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all font-semibold text-sm sm:text-base shadow-md hover:shadow-lg active:scale-[0.98]">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Kamar</span>
                </a>
            </div>
        </div>

        {{-- Alert Messages --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-blue-50 text-blue-700 rounded-lg sm:rounded-xl border border-blue-200 text-sm sm:text-base">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-lg sm:rounded-xl border border-red-200 text-sm sm:text-base">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6">
            <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg sm:rounded-xl p-4 sm:p-5 text-white shadow-md">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-door-open text-2xl sm:text-3xl opacity-20"></i>
                    <span class="text-xs sm:text-sm opacity-90">Total</span>
                </div>
                <div class="text-2xl sm:text-3xl font-bold">{{ $totalRooms }}</div>
                <p class="text-xs sm:text-sm opacity-90 mt-1">Kamar</p>
            </div>

            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg sm:rounded-xl p-4 sm:p-5 text-white shadow-md">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-check-circle text-2xl sm:text-3xl opacity-20"></i>
                    <span class="text-xs sm:text-sm opacity-90">Status</span>
                </div>
                <div class="text-2xl sm:text-3xl font-bold">{{ $availableCount }}</div>
                <p class="text-xs sm:text-sm opacity-90 mt-1">Tersedia</p>
            </div>

            <div class="bg-gradient-to-br from-gray-700 to-gray-800 rounded-lg sm:rounded-xl p-4 sm:p-5 text-white shadow-md">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-user text-2xl sm:text-3xl opacity-20"></i>
                    <span class="text-xs sm:text-sm opacity-90">Status</span>
                </div>
                <div class="text-2xl sm:text-3xl font-bold">{{ $occupiedCount }}</div>
                <p class="text-xs sm:text-sm opacity-90 mt-1">Terisi</p>
            </div>

            <div class="bg-gradient-to-br from-blue-400 to-blue-500 rounded-lg sm:rounded-xl p-4 sm:p-5 text-white shadow-md col-span-2 lg:col-span-1">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-money-bill-wave text-2xl sm:text-3xl opacity-20"></i>
                    <span class="text-xs sm:text-sm opacity-90">Pendapatan</span>
                </div>
                <div class="text-xl sm:text-2xl font-bold">Rp {{ number_format($totalRevenue) }}</div>
                <p class="text-xs sm:text-sm opacity-90 mt-1">Est. /Bulan</p>
            </div>
        </div>

        {{-- Search & Filter --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-4 sm:p-6 mb-6">
            <form method="GET" action="{{ route('admin.rooms.index') }}" class="space-y-4 sm:space-y-0 sm:flex sm:gap-4 sm:flex-wrap">
                {{-- Search --}}
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Cari nama kamar..."
                               class="w-full px-3 sm:px-4 py-2 sm:py-2.5 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base">
                        {{-- <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i> --}}
                    </div>
                </div>

                {{-- Status Filter --}}
                <div class="w-full sm:w-48">
                    <select name="status"
                            class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base">
                        <option value="">Semua Status</option>
                        <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="occupied" {{ request('status') === 'occupied' ? 'selected' : '' }}>Terisi</option>
                    </select>
                </div>

                {{-- Sort --}}
                <div class="w-full sm:w-48">
                    <select name="sort"
                            class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base">
                        <option value="">Urutan Default</option>
                        <option value="name_asc"  {{ request('sort') === 'name_asc'   ? 'selected' : '' }}>Nama A-Z</option>
                        <option value="name_desc" {{ request('sort') === 'name_desc'  ? 'selected' : '' }}>Nama Z-A</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc'  ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_desc"{{ request('sort') === 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-2">
                    <button type="submit"
                            class="flex-1 sm:flex-none px-4 sm:px-6 py-2 sm:py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-semibold text-sm sm:text-base active:scale-[0.98]">
                        <i class="fas fa-search mr-2"></i>Cari
                    </button>
                    @if(request('search') || request('status') || request('sort'))
                        <a href="{{ route('admin.rooms.index') }}"
                           class="flex-1 sm:flex-none px-4 sm:px-6 py-2 sm:py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all font-semibold text-sm sm:text-base text-center active:scale-[0.98]">
                            <i class="fas fa-times mr-2"></i>Reset
                        </a>
                    @endif
                </div>
            </form>

            {{-- Active Filter Info --}}
            @if(request('search') || request('status') || request('sort'))
                <div class="mt-3 text-sm text-gray-600">
                    Menampilkan <span class="font-semibold">{{ $rooms->total() }}</span> dari <span class="font-semibold">{{ $totalRooms }}</span> kamar
                    @if(request('search'))
                        <span class="font-semibold"> • Pencarian: "{{ request('search') }}"</span>
                    @endif
                    @if(request('status'))
                        <span class="font-semibold"> • Status: {{ request('status') === 'available' ? 'Tersedia' : 'Terisi' }}</span>
                    @endif
                </div>
            @endif
        </div>

        {{-- Table / Card --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-md overflow-hidden">
            @if($rooms->count() > 0)

                {{-- Mobile Card View --}}
                <div class="block lg:hidden divide-y divide-gray-200">
                    @foreach($rooms as $room)
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start gap-3 mb-3">
                                {{-- Thumbnail --}}
                                @if($room->image)
                                    <img src="{{ asset('storage/'.$room->image) }}"
                                         class="w-14 h-14 rounded-xl object-cover flex-shrink-0"
                                         alt="{{ $room->name }}">
                                @else
                                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-xl flex-shrink-0">
                                        {{ substr($room->name, 0, 1) }}
                                    </div>
                                @endif

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <h3 class="font-semibold text-gray-900 truncate">{{ $room->name }}</h3>
                                            <p class="text-xs text-gray-600 truncate">{{ $room->property->name }}</p>
                                            <p class="text-xs text-gray-500">Lantai {{ $room->floor }} • {{ $room->size }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold whitespace-nowrap flex-shrink-0
                                            {{ $room->status === 'available' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                                            <i class="fas {{ $room->status === 'available' ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                            {{ $room->status === 'available' ? 'Tersedia' : 'Terisi' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Price Info --}}
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-3 mb-3 text-xs border border-blue-200">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">
                                        <i class="fas fa-money-bill-wave text-blue-600 mr-1"></i>
                                        <span class="font-semibold text-gray-900">Rp {{ number_format($room->price) }}</span>
                                        / {{ $room->billing_cycle === 'monthly' ? 'bulan' : 'tahun' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex gap-2">
                                <a href="{{ route('admin.rooms.show', $room) }}"
                                   class="flex-1 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-all text-xs font-semibold text-center border border-blue-200">
                                    <i class="fas fa-eye mr-1"></i>Lihat
                                </a>
                                <a href="{{ route('admin.rooms.edit', $room) }}"
                                   class="flex-1 px-3 py-1.5 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition-all text-xs font-semibold text-center border border-gray-200">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                                <button onclick="confirmDelete({{ $room->id }}, '{{ addslashes($room->name) }}')"
                                        class="flex-1 px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-all text-xs font-semibold text-center border border-red-200">
                                    <i class="fas fa-trash mr-1"></i>Hapus
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Desktop Table View --}}
                <div class="hidden lg:block overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kamar</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Properti</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($rooms as $room)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($room->image)
                                                <img src="{{ asset('storage/'.$room->image) }}"
                                                     class="w-10 h-10 rounded-lg object-cover flex-shrink-0"
                                                     alt="{{ $room->name }}">
                                            @else
                                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold flex-shrink-0">
                                                    {{ substr($room->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-semibold text-gray-900">{{ $room->name }}</div>
                                                <div class="text-sm text-gray-600">Lantai {{ $room->floor }} • {{ $room->size }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $room->property->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                            {{ $room->status === 'available' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                                            <i class="fas {{ $room->status === 'available' ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                            {{ $room->status === 'available' ? 'Tersedia' : 'Terisi' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="font-semibold text-gray-900">Rp {{ number_format($room->price) }}</div>
                                        <div class="text-xs text-gray-500">/ {{ $room->billing_cycle === 'monthly' ? 'bulan' : 'tahun' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.rooms.show', $room) }}"
                                               class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                               title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.rooms.edit', $room) }}"
                                               class="p-2 text-gray-600 hover:bg-gray-50 rounded-lg transition-all"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="confirmDelete({{ $room->id }}, '{{ addslashes($room->name) }}')"
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
                @if($rooms->hasPages())
                    <div class="px-4 py-3 sm:px-6 border-t border-gray-200">
                        {{ $rooms->appends(request()->query())->links() }}
                    </div>
                @endif

            @else
                {{-- Empty State --}}
                <div class="text-center py-12">
                    <i class="fas fa-door-open text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-600 mb-4">
                        @if(request('search') || request('status'))
                            Tidak ada kamar yang sesuai dengan filter
                        @else
                            Belum ada kamar
                        @endif
                    </p>
                    @if(!request('search') && !request('status'))
                        <a href="{{ route('admin.rooms.create') }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-semibold text-sm">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Kamar</span>
                        </a>
                    @endif
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
            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">Hapus Kamar?</h3>
            <p class="text-sm sm:text-base text-gray-600 mb-1">Anda akan menghapus kamar:</p>
            <p id="deleteRoomName" class="text-sm sm:text-base font-semibold text-gray-900 mb-6"></p>
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
function confirmDelete(roomId, roomName) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    document.getElementById('deleteRoomName').textContent = roomName;
    form.action = `/admin/rooms/${roomId}`;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDeleteModal();
});
</script>

</x-app-layout>