<x-app-layout>
<div class="py-6 sm:py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6 sm:mb-8">
            <a href="{{ route('admin.facility_rooms.index') }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-900 transition-colors mb-4">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">Assign Fasilitas ke Kamar</h1>
            <p class="text-sm sm:text-base text-gray-600">Pilih satu atau banyak kamar sekaligus, lalu centang fasilitasnya</p>
        </div>

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-lg sm:rounded-xl border border-red-200 text-sm">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.facility_rooms.store') }}" class="space-y-5">
            @csrf

            {{-- Mode Toggle --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-5 sm:p-6">
                <p class="text-sm font-semibold text-gray-700 mb-3">Mode Assign</p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <label class="flex-1 flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition-all has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 border-gray-200">
                        <input type="radio" name="assign_mode" value="single" checked class="text-blue-600" onchange="toggleMode('single')">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Satu Kamar</p>
                            <p class="text-xs text-gray-500">Assign ke 1 kamar, replace/sync</p>
                        </div>
                    </label>
                    <label class="flex-1 flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition-all has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 border-gray-200">
                        <input type="radio" name="assign_mode" value="bulk" class="text-blue-600" onchange="toggleMode('bulk')">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Banyak Kamar <span class="text-blue-600 font-bold">Bulk</span></p>
                            <p class="text-xs text-gray-500">Assign ke beberapa kamar sekaligus</p>
                        </div>
                    </label>
                </div>

                {{-- Merge mode (only shown in bulk) --}}
                <div id="mergeSection" class="hidden mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs font-semibold text-gray-600 mb-2">Bagaimana jika kamar sudah punya fasilitas?</p>
                    <div class="flex flex-col sm:flex-row gap-2">
                        <label class="flex items-center gap-2 text-sm cursor-pointer">
                            <input type="radio" name="merge_mode" value="sync" checked class="text-blue-600">
                            <span><span class="font-semibold">Replace</span> – Ganti semua dengan yang baru</span>
                        </label>
                        <label class="flex items-center gap-2 text-sm cursor-pointer">
                            <input type="radio" name="merge_mode" value="attach" class="text-blue-600">
                            <span><span class="font-semibold">Tambahkan</span> – Gabungkan dengan yang sudah ada</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Single Room Select --}}
            <div id="singleRoomSection" class="bg-white rounded-lg sm:rounded-xl shadow-md p-5 sm:p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Pilih Kamar</h3>
                <select name="room_id" id="room_id"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                    <option value="">-- Pilih Kamar --</option>
                    @foreach($rooms as $r)
                        <option value="{{ $r->id }}" @selected(old('room_id') == $r->id)>
                            {{ $r->name }} — {{ $r->property->name }}
                            ({{ $r->status === 'available' ? 'Tersedia' : 'Terisi' }})
                        </option>
                    @endforeach
                </select>
                @error('room_id')
                    <p class="mt-1.5 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>

            {{-- Bulk Room Select --}}
            <div id="bulkRoomSection" class="hidden bg-white rounded-lg sm:rounded-xl shadow-md overflow-hidden">
                <div class="px-5 sm:px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between gap-3">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700">Pilih Kamar <span class="text-blue-600">(Bulk)</span></h3>
                        <p class="text-xs text-gray-500 mt-0.5">Klik kamar untuk memilih/batal pilih</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span id="roomSelectedCount" class="text-xs font-semibold text-blue-600 bg-blue-50 border border-blue-200 px-2 py-1 rounded-lg">0 dipilih</span>
                        <button type="button" onclick="selectAllRooms()" class="text-xs font-semibold text-blue-600 hover:underline">Semua</button>
                        <button type="button" onclick="deselectAllRooms()" class="text-xs font-semibold text-gray-500 hover:underline">Reset</button>
                    </div>
                </div>

                {{-- Search rooms --}}
                <div class="px-5 sm:px-6 pt-4">
                    <div class="relative">
                        <input type="text" id="roomSearch" placeholder="Cari nama kamar..."
                               class="w-full px-4 py-2 pl-9 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500"
                               oninput="filterRooms()">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    </div>
                </div>

                <div id="bulkRoomGrid" class="p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 max-h-72 overflow-y-auto">
                    @foreach($rooms as $r)
                        <label class="room-card flex items-center gap-3 p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-400 transition-all has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50"
                               data-name="{{ strtolower($r->name . ' ' . $r->property->name) }}">
                            <input type="checkbox" name="room_ids[]" value="{{ $r->id }}"
                                   class="room-bulk-check text-blue-600 w-4 h-4 rounded"
                                   onchange="updateRoomCount()"
                                   @if(old('room_ids') && in_array($r->id, old('room_ids'))) checked @endif>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $r->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $r->property->name }}</p>
                            </div>
                            <span class="text-xs px-1.5 py-0.5 rounded font-medium flex-shrink-0
                                {{ $r->status === 'available' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $r->status === 'available' ? 'Tersedia' : 'Terisi' }}
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('room_ids')
                    <p class="px-5 pb-4 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>

            {{-- Facilities --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-md overflow-hidden">
                <div class="px-5 sm:px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between gap-3">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700">Pilih Fasilitas</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Centang fasilitas yang tersedia di kamar</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span id="facilitySelectedCount" class="text-xs font-semibold text-blue-600 bg-blue-50 border border-blue-200 px-2 py-1 rounded-lg">0 dipilih</span>
                        <button type="button" onclick="selectAllFacilities()" class="text-xs font-semibold text-blue-600 hover:underline">Semua</button>
                        <button type="button" onclick="deselectAllFacilities()" class="text-xs font-semibold text-gray-500 hover:underline">Reset</button>
                    </div>
                </div>

                @if($facilities->count() > 0)
                    {{-- Search facilities --}}
                    <div class="px-5 sm:px-6 pt-4">
                        <div class="relative">
                            <input type="text" id="facilitySearch" placeholder="Cari fasilitas..."
                                   class="w-full px-4 py-2 pl-9 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500"
                                   oninput="filterFacilities()">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        </div>
                    </div>

                    <div id="facilityGrid" class="p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($facilities as $facility)
                            <label class="facility-card relative flex items-center p-3 bg-gray-50 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-400 transition-all has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50"
                                   data-name="{{ strtolower($facility->name) }}">
                                <input type="checkbox" name="facilities[]" value="{{ $facility->id }}"
                                       class="facility-check absolute top-3 right-3 w-4 h-4 text-blue-600 border-gray-300 rounded"
                                       onchange="updateFacilityCount()"
                                       @if(old('facilities') && in_array($facility->id, old('facilities'))) checked @endif>
                                <div class="flex items-center gap-3 pr-6">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="{{ $facility->icon }} text-white text-sm"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $facility->name }}</p>
                                        <p class="text-xs text-gray-400 font-mono truncate">{{ $facility->icon }}</p>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    @error('facilities')
                        <p class="px-5 pb-4 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                @else
                    <div class="p-12 text-center">
                        <i class="fas fa-list-check text-4xl text-gray-300 mb-3 block"></i>
                        <p class="text-gray-600 mb-4 text-sm">Belum ada fasilitas tersedia</p>
                        <a href="{{ route('admin.facilities.create') }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-all">
                            <i class="fas fa-plus"></i> Tambah Fasilitas
                        </a>
                    </div>
                @endif
            </div>

            {{-- Summary Bar --}}
            <div id="summaryBar" class="hidden sticky bottom-4 bg-white border-2 border-blue-200 rounded-xl shadow-xl p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="text-sm text-gray-700">
                    <i class="fas fa-circle-info text-blue-500 mr-1.5"></i>
                    Akan assign <span id="summaryFacility" class="font-bold text-blue-700">0 fasilitas</span>
                    ke <span id="summaryRoom" class="font-bold text-blue-700">0 kamar</span>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.facility_rooms.index') }}"
                       class="px-4 py-2 text-sm font-semibold text-gray-700 border-2 border-gray-300 rounded-lg hover:bg-gray-50 transition-all">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-5 py-2 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-md active:scale-[0.98]">
                        <i class="fas fa-check mr-1.5"></i> Assign Sekarang
                    </button>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex flex-col-reverse sm:flex-row sm:justify-between gap-3 pb-4">
                <a href="{{ route('admin.facility_rooms.index') }}"
                   class="inline-flex justify-center items-center gap-2 px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border-2 border-gray-300 rounded-lg sm:rounded-xl hover:bg-gray-50 transition-all active:scale-[0.98]">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit"
                        class="inline-flex justify-center items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg sm:rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all shadow-md hover:shadow-lg active:scale-[0.98]">
                    <i class="fas fa-check"></i> Assign Fasilitas
                </button>
            </div>

        </form>
    </div>
</div>

<script>
let currentMode = 'single';

function toggleMode(mode) {
    currentMode = mode;
    const single    = document.getElementById('singleRoomSection');
    const bulk      = document.getElementById('bulkRoomSection');
    const merge     = document.getElementById('mergeSection');
    const roomIdSel = document.getElementById('room_id');

    if (mode === 'bulk') {
        single.classList.add('hidden');
        bulk.classList.remove('hidden');
        merge.classList.remove('hidden');
        roomIdSel.removeAttribute('required');
    } else {
        single.classList.remove('hidden');
        bulk.classList.add('hidden');
        merge.classList.add('hidden');
        roomIdSel.setAttribute('required', '');
    }
    updateSummary();
}

// ---- Room helpers ----
function selectAllRooms() {
    document.querySelectorAll('.room-bulk-check').forEach(c => c.checked = true);
    updateRoomCount();
}
function deselectAllRooms() {
    document.querySelectorAll('.room-bulk-check').forEach(c => c.checked = false);
    updateRoomCount();
}
function updateRoomCount() {
    const n = document.querySelectorAll('.room-bulk-check:checked').length;
    document.getElementById('roomSelectedCount').textContent = n + ' dipilih';
    updateSummary();
}
function filterRooms() {
    const q = document.getElementById('roomSearch').value.toLowerCase();
    document.querySelectorAll('.room-card').forEach(card => {
        card.style.display = card.dataset.name.includes(q) ? '' : 'none';
    });
}

// ---- Facility helpers ----
function selectAllFacilities() {
    document.querySelectorAll('.facility-check').forEach(c => c.checked = true);
    updateFacilityCount();
}
function deselectAllFacilities() {
    document.querySelectorAll('.facility-check').forEach(c => c.checked = false);
    updateFacilityCount();
}
function updateFacilityCount() {
    const n = document.querySelectorAll('.facility-check:checked').length;
    document.getElementById('facilitySelectedCount').textContent = n + ' dipilih';
    updateSummary();
}
function filterFacilities() {
    const q = document.getElementById('facilitySearch').value.toLowerCase();
    document.querySelectorAll('.facility-card').forEach(card => {
        card.style.display = card.dataset.name.includes(q) ? '' : 'none';
    });
}

// ---- Summary bar ----
function updateSummary() {
    const fCount = document.querySelectorAll('.facility-check:checked').length;
    const rCount = currentMode === 'bulk'
        ? document.querySelectorAll('.room-bulk-check:checked').length
        : (document.getElementById('room_id').value ? 1 : 0);

    const bar = document.getElementById('summaryBar');
    if (fCount > 0 && rCount > 0) {
        bar.classList.remove('hidden');
        document.getElementById('summaryFacility').textContent = fCount + ' fasilitas';
        document.getElementById('summaryRoom').textContent     = rCount + ' kamar';
    } else {
        bar.classList.add('hidden');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('room_id').addEventListener('change', updateSummary);
    document.querySelectorAll('.facility-check').forEach(c => c.addEventListener('change', updateFacilityCount));
    updateFacilityCount();
    updateRoomCount();
});
</script>

</x-app-layout>