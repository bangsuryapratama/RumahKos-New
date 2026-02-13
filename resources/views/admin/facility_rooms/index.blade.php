<x-app-layout>
<div class="py-6 sm:py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">Kelola Fasilitas Kamar</h1>
                    <p class="text-sm sm:text-base text-gray-600">Atur fasilitas yang tersedia di setiap kamar</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.facilities.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-all active:scale-[0.98]">
                        <i class="fas fa-list-check"></i>
                        <span class="hidden sm:inline">Master Fasilitas</span>
                    </a>
                    <a href="{{ route('admin.rooms.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-all active:scale-[0.98]">
                        <i class="fas fa-door-open"></i>
                        <span class="hidden sm:inline">Daftar Kamar</span>
                    </a>
                    <a href="{{ route('admin.facility_rooms.create') }}"
                       class="inline-flex items-center gap-2 px-4 sm:px-5 py-2 sm:py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-semibold rounded-lg sm:rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all shadow-md hover:shadow-lg active:scale-[0.98]">
                        <i class="fas fa-plus"></i>
                        <span>Assign Fasilitas</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Alerts --}}
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

        {{-- Room Cards --}}
        @if($rooms->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach($rooms as $room)
                <div class="bg-white rounded-lg sm:rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">

                    {{-- Room Image --}}
                    @if($room->image)
                        <img src="{{ asset('storage/'.$room->image) }}"
                             class="w-full h-44 object-cover"
                             alt="{{ $room->name }}">
                    @else
                        <div class="w-full h-44 bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                            <span class="text-5xl font-bold text-white opacity-30">{{ substr($room->name, 0, 1) }}</span>
                        </div>
                    @endif

                    <div class="p-4 sm:p-5">

                        {{-- Room Info --}}
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <div class="min-w-0">
                                <h3 class="font-semibold text-gray-900 truncate">{{ $room->name }}</h3>
                                <p class="text-xs text-gray-500 truncate">{{ $room->property->name }}</p>
                            </div>
                            <div class="flex flex-col items-end gap-1 flex-shrink-0">
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                    {{ $room->status === 'available' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                                    <i class="fas {{ $room->status === 'available' ? 'fa-check-circle' : 'fa-times-circle' }} mr-0.5"></i>
                                    {{ $room->status === 'available' ? 'Tersedia' : 'Terisi' }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    <i class="fas fa-stairs mr-0.5"></i> Lantai {{ $room->floor }}
                                </span>
                            </div>
                        </div>

                        {{-- Facilities Count --}}
                        <div class="flex items-center justify-between py-2.5 px-3 rounded-lg mb-3
                            {{ $room->facilities->count() > 0 ? 'bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200' : 'bg-gray-50 border border-gray-200' }}">
                            <span class="text-xs font-medium text-gray-600">
                                <i class="fas fa-list-check mr-1.5 {{ $room->facilities->count() > 0 ? 'text-blue-500' : 'text-gray-400' }}"></i>
                                Total Fasilitas
                            </span>
                            <span class="text-sm font-bold {{ $room->facilities->count() > 0 ? 'text-blue-700' : 'text-gray-400' }}">
                                {{ $room->facilities->count() }} item
                            </span>
                        </div>

                        {{-- Facilities Chips --}}
                        @if($room->facilities->count() > 0)
                            <div class="flex flex-wrap gap-1.5 mb-4">
                                @foreach($room->facilities->take(4) as $facility)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-xs font-medium">
                                        <i class="{{ $facility->icon }} text-xs"></i>
                                        {{ $facility->name }}
                                    </span>
                                @endforeach
                                @if($room->facilities->count() > 4)
                                    <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-medium">
                                        +{{ $room->facilities->count() - 4 }} lainnya
                                    </span>
                                @endif
                            </div>
                        @else
                            <div class="flex items-center gap-2 px-3 py-2.5 bg-amber-50 border border-amber-200 rounded-lg mb-4">
                                <i class="fas fa-triangle-exclamation text-amber-500 text-sm flex-shrink-0"></i>
                                <p class="text-xs text-amber-700">Belum ada fasilitas di-assign</p>
                            </div>
                        @endif

                        {{-- Actions --}}
                        <div class="flex gap-2">
                            <a href="{{ route('admin.facility_rooms.edit', $room) }}"
                               class="flex-1 px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all text-xs font-semibold text-center active:scale-[0.98]">
                                <i class="fas fa-pen-to-square mr-1"></i>Kelola
                            </a>
                            @if($room->facilities->count() > 0)
                                <button onclick="confirmClear({{ $room->id }}, '{{ addslashes($room->name) }}', {{ $room->facilities->count() }})"
                                        class="px-3 py-1.5 bg-red-50 text-red-600 border border-red-200 rounded-lg hover:bg-red-100 transition-all text-xs font-semibold active:scale-[0.98]"
                                        title="Hapus Semua Fasilitas">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($rooms->hasPages())
                <div class="mt-6">
                    {{ $rooms->links() }}
                </div>
            @endif

        @else
            {{-- Empty State --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-12 text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-door-open text-3xl text-blue-500"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">Belum ada kamar</h3>
                <p class="text-sm text-gray-500 mb-6">Mulai dengan menambahkan kamar terlebih dahulu</p>
                <div class="flex gap-3 justify-center">
                    <a href="{{ route('admin.rooms.create') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-semibold rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-md active:scale-[0.98]">
                        <i class="fas fa-plus"></i> Tambah Kamar
                    </a>
                    <a href="{{ route('admin.facilities.create') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border-2 border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-all active:scale-[0.98]">
                        <i class="fas fa-plus"></i> Tambah Fasilitas
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>

{{-- Delete Modal --}}
<div id="clearModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl sm:rounded-2xl p-6 sm:p-8 max-w-md mx-4 shadow-2xl">
        <div class="text-center">
            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-3xl sm:text-4xl text-red-600"></i>
            </div>
            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">Hapus Semua Fasilitas?</h3>
            <p class="text-sm sm:text-base text-gray-600 mb-1">Kamar: <span id="clearRoomName" class="font-semibold text-gray-900"></span></p>
            <p id="clearRoomInfo" class="text-xs text-red-500 mb-6"></p>
            <form id="clearForm" method="POST" class="flex flex-col sm:flex-row gap-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeClearModal()"
                        class="flex-1 px-4 sm:px-6 py-2.5 sm:py-3 border-2 border-gray-300 text-gray-700 rounded-lg sm:rounded-xl font-semibold hover:bg-gray-50 transition-all text-sm sm:text-base">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 px-4 sm:px-6 py-2.5 sm:py-3 bg-red-600 text-white rounded-lg sm:rounded-xl font-semibold hover:bg-red-700 transition-all shadow-md text-sm sm:text-base">
                    Ya, Hapus Semua
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function confirmClear(roomId, roomName, count) {
    const modal = document.getElementById('clearModal');
    document.getElementById('clearRoomName').textContent = roomName;
    document.getElementById('clearRoomInfo').textContent = `${count} fasilitas akan dihapus dari kamar ini.`;
    document.getElementById('clearForm').action = `/admin/facility-rooms/${roomId}`;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeClearModal() {
    const modal = document.getElementById('clearModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeClearModal(); });
</script>

</x-app-layout>