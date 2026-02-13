<x-app-layout>
<div class="py-6 sm:py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">Master Fasilitas</h1>
                    <p class="text-sm sm:text-base text-gray-600">Kelola daftar fasilitas yang tersedia</p>
                </div>
                <a href="{{ route('admin.facilities.create') }}"
                   class="inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2 sm:py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg sm:rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all font-semibold text-sm sm:text-base shadow-md hover:shadow-lg active:scale-[0.98]">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Fasilitas</span>
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

        {{-- Search & Filter --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-4 sm:p-6 mb-6">
            <form method="GET" action="{{ route('admin.facilities.index') }}" class="space-y-4 sm:space-y-0 sm:flex sm:gap-4 sm:flex-wrap">
                {{-- Search --}}
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Cari nama fasilitas..."
                               class="w-full px-3 sm:px-4 py-2 sm:py-2.5 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base">
                    </div>
                </div>

                {{-- Usage Filter --}}
                <div class="w-full sm:w-48">
                    <select name="usage"
                            class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base">
                        <option value="">Semua Fasilitas</option>
                        <option value="used"   {{ request('usage') === 'used'   ? 'selected' : '' }}>Digunakan</option>
                        <option value="unused" {{ request('usage') === 'unused' ? 'selected' : '' }}>Tidak Digunakan</option>
                    </select>
                </div>

                {{-- Sort --}}
                <div class="w-full sm:w-48">
                    <select name="sort"
                            class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base">
                        <option value="">Terbaru</option>
                        <option value="name_asc"   {{ request('sort') === 'name_asc'   ? 'selected' : '' }}>Nama A-Z</option>
                        <option value="name_desc"  {{ request('sort') === 'name_desc'  ? 'selected' : '' }}>Nama Z-A</option>
                        <option value="most_used"  {{ request('sort') === 'most_used'  ? 'selected' : '' }}>Paling Banyak Dipakai</option>
                        <option value="least_used" {{ request('sort') === 'least_used' ? 'selected' : '' }}>Paling Sedikit Dipakai</option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-2">
                    <button type="submit"
                            class="flex-1 sm:flex-none px-4 sm:px-6 py-2 sm:py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-semibold text-sm sm:text-base active:scale-[0.98]">
                        <i class="fas fa-search mr-2"></i>Cari
                    </button>
                    @if(request('search') || request('usage') || request('sort'))
                        <a href="{{ route('admin.facilities.index') }}"
                           class="flex-1 sm:flex-none px-4 sm:px-6 py-2 sm:py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all font-semibold text-sm sm:text-base text-center active:scale-[0.98]">
                            <i class="fas fa-times mr-2"></i>Reset
                        </a>
                    @endif
                </div>
            </form>

            {{-- Active Filter Info --}}
            @if(request('search') || request('usage') || request('sort'))
                <div class="mt-3 text-sm text-gray-600">
                    Menampilkan <span class="font-semibold">{{ $facilities->total() }}</span> fasilitas
                    @if(request('search'))
                        <span class="font-semibold"> • Pencarian: "{{ request('search') }}"</span>
                    @endif
                    @if(request('usage'))
                        <span class="font-semibold"> • Filter: {{ request('usage') === 'used' ? 'Digunakan' : 'Tidak Digunakan' }}</span>
                    @endif
                </div>
            @endif
        </div>

        {{-- Table / Card --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-md overflow-hidden">
            @if($facilities->count() > 0)

                {{-- Mobile Card View --}}
                <div class="block lg:hidden divide-y divide-gray-200">
                    @foreach($facilities as $facility)
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start gap-3 mb-3">
                                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="{{ $facility->icon }} text-2xl text-white"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-gray-900">{{ $facility->name }}</h3>
                                    <p class="text-xs text-gray-500">{{ $facility->icon }}</p>
                                </div>
                            </div>

                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-3 mb-3 text-xs border border-blue-200">
                                <span class="text-gray-700">
                                    <i class="fas fa-door-open text-blue-600 mr-1"></i>
                                    Digunakan di <span class="font-semibold text-gray-900">{{ $facility->rooms_count }}</span> kamar
                                </span>
                            </div>

                            <div class="flex gap-2">
                                <a href="{{ route('admin.facilities.show', $facility) }}"
                                   class="flex-1 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-all text-xs font-semibold text-center border border-blue-200">
                                    <i class="fas fa-eye mr-1"></i>Lihat
                                </a>
                                <a href="{{ route('admin.facilities.edit', $facility) }}"
                                   class="flex-1 px-3 py-1.5 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition-all text-xs font-semibold text-center border border-gray-200">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                                <button onclick="confirmDelete({{ $facility->id }}, '{{ addslashes($facility->name) }}', {{ $facility->rooms_count }})"
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
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Icon</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Fasilitas</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Digunakan</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($facilities as $facility)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                            <i class="{{ $facility->icon }} text-white"></i>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900">{{ $facility->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $facility->icon }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                            {{ $facility->rooms_count > 0 ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500' }}">
                                            <i class="fas fa-door-open mr-1"></i>{{ $facility->rooms_count }} Kamar
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.facilities.show', $facility) }}"
                                               class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                               title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.facilities.edit', $facility) }}"
                                               class="p-2 text-gray-600 hover:bg-gray-50 rounded-lg transition-all"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="confirmDelete({{ $facility->id }}, '{{ addslashes($facility->name) }}', {{ $facility->rooms_count }})"
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
                @if($facilities->hasPages())
                    <div class="px-4 py-3 sm:px-6 border-t border-gray-200">
                        {{ $facilities->links() }}
                    </div>
                @endif

            @else
                {{-- Empty State --}}
                <div class="text-center py-12">
                    <i class="fas fa-list-check text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-600 mb-4">
                        @if(request('search') || request('usage'))
                            Tidak ada fasilitas yang sesuai dengan filter
                        @else
                            Belum ada fasilitas
                        @endif
                    </p>
                    @if(!request('search') && !request('usage'))
                        <a href="{{ route('admin.facilities.create') }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-semibold text-sm">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Fasilitas</span>
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
            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">Hapus Fasilitas?</h3>
            <p class="text-sm sm:text-base text-gray-600 mb-1">Anda akan menghapus fasilitas:</p>
            <p id="deleteFacilityName" class="text-sm sm:text-base font-semibold text-gray-900 mb-1"></p>
            <p id="deleteFacilityInfo" class="text-xs text-red-500 mb-6"></p>
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
function confirmDelete(facilityId, facilityName, roomsCount) {
    const modal = document.getElementById('deleteModal');
    const form  = document.getElementById('deleteForm');
    document.getElementById('deleteFacilityName').textContent = facilityName;
    document.getElementById('deleteFacilityInfo').textContent =
        roomsCount > 0 ? `Fasilitas ini digunakan di ${roomsCount} kamar dan akan ikut dihapus.` : '';
    form.action = `/admin/facilities/${facilityId}`;
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