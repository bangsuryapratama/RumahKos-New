<x-app-layout>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            
            {{-- Header --}}
            <div class="mb-6">
                <a href="{{ route('admin.facility_rooms.index') }}" 
                   class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali
                </a>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                    Kelola Fasilitas: {{ $room->name }}
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Pilih fasilitas yang tersedia di kamar ini
                </p>
            </div>

            {{-- Error Alert --}}
            @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
            @endif

            <form method="POST" action="{{ route('admin.facility_rooms.update', $room) }}" class="space-y-6">
                
                @csrf
                @method('PUT')

                {{-- Room Info --}}
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl shadow-sm border border-blue-200 p-6">
                    <div class="flex items-center">
                        @if($room->image)
                        <img src="{{ asset('storage/'.$room->image) }}" 
                             class="w-20 h-20 rounded-lg object-cover"
                             alt="{{ $room->name }}">
                        @else
                        <div class="w-20 h-20 rounded-lg bg-white flex items-center justify-center">
                            <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        @endif
                        <div class="ml-5">
                            <h3 class="text-xl font-bold text-gray-900">{{ $room->name }}</h3>
                            <p class="text-sm text-blue-700">{{ $room->property->name }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                    {{ $room->status == 'available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $room->status == 'available' ? 'Tersedia' : 'Terisi' }}
                                </span>
                                <span class="text-xs text-gray-600">Lantai {{ $room->floor }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Facilities Selection --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Pilih Fasilitas</h3>
                        <p class="text-sm text-gray-500 mt-1">Centang fasilitas yang tersedia di kamar</p>
                    </div>

                    <div class="p-6">
                        @if($facilities->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($facilities as $facility)
                            <label class="relative flex items-center p-4 bg-gray-50 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-400 transition-all group has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                                <input type="checkbox" 
                                       name="facilities[]" 
                                       value="{{ $facility->id }}"
                                       class="absolute top-4 right-4 w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer"
                                       @if($room->facilities->contains($facility->id)) checked @endif
                                       @if(old('facilities') && in_array($facility->id, old('facilities'))) checked @endif>
                                
                                <div class="flex items-center pointer-events-none pr-8">
                                    <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                        <i class="{{ $facility->icon }} text-2xl text-blue-600"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-semibold text-gray-900">{{ $facility->name }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $facility->icon }}</p>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        
                        @error('facilities')
                            <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('facilities.*')
                            <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        {{-- Quick Select --}}
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex flex-wrap gap-2 items-center">
                                <button type="button" 
                                        onclick="selectAll()"
                                        class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                    <i class="fas fa-check-double mr-1"></i>
                                    Pilih Semua
                                </button>
                                <button type="button" 
                                        onclick="deselectAll()"
                                        class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                    <i class="fas fa-times mr-1"></i>
                                    Hapus Pilihan
                                </button>
                                <span class="text-sm text-gray-500 ml-2">
                                    <span id="selected-count">0</span> dari {{ $facilities->count() }} dipilih
                                </span>
                            </div>
                        </div>
                        @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Belum ada fasilitas tersedia</p>
                            <a href="{{ route('admin.facilities.create') }}"
                               class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i>
                                Tambah Fasilitas
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex flex-col-reverse sm:flex-row sm:justify-between gap-3">
                    <a href="{{ route('admin.facility_rooms.index') }}"
                       class="inline-flex justify-center items-center px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex justify-center items-center px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Fasilitas
                    </button>
                </div>

            </form>

        </div>
    </div>

    {{-- Font Awesome CDN --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        // Select all facilities
        function selectAll() {
            const checkboxes = document.querySelectorAll('input[name="facilities[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            updateSelectedCount();
        }

        // Deselect all facilities
        function deselectAll() {
            const checkboxes = document.querySelectorAll('input[name="facilities[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            updateSelectedCount();
        }

        // Update selected count
        function updateSelectedCount() {
            const checkboxes = document.querySelectorAll('input[name="facilities[]"]');
            const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
            const countElement = document.getElementById('selected-count');
            if (countElement) {
                countElement.textContent = checkedCount;
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Update count on checkbox change
            const checkboxes = document.querySelectorAll('input[name="facilities[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedCount);
            });
            
            // Initial count
            updateSelectedCount();
        });
    </script>
</x-app-layout>