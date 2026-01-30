<x-app-layout>
<div class="max-w-5xl mx-auto py-6 sm:py-10 px-4">

    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Tambah Property</h1>
        <p class="text-sm sm:text-base text-gray-600">Tambahkan property baru ke sistem</p>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-lg sm:rounded-xl border border-green-200 text-sm sm:text-base">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-lg sm:rounded-xl border border-red-200 text-sm sm:text-base">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <strong>Terdapat kesalahan:</strong>
            <ul class="mt-2 ml-4 list-disc text-xs sm:text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.properties.store') }}"
          class="bg-white p-5 sm:p-6 md:p-8 rounded-xl sm:rounded-2xl shadow-md sm:shadow-lg space-y-5 sm:space-y-6">
        @csrf

        {{-- Owner --}}
        <div>
            <label class="block mb-2 text-sm font-semibold text-gray-700">
                Owner <span class="text-red-500">*</span>
            </label>
            <select name="owner_id"
                class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2 sm:py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm sm:text-base @error('owner_id') border-red-500 @enderror"
                required>
                <option value="">Pilih Owner</option>
                @foreach($owners as $owner)
                    <option value="{{ $owner->id }}" {{ old('owner_id') == $owner->id ? 'selected' : '' }}>
                        {{ $owner->name }}
                    </option>
                @endforeach
            </select>
            @error('owner_id')
                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nama & Telepon --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
            <div>
                <label class="block mb-2 text-sm font-semibold text-gray-700">
                    Nama Property <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2 sm:py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm sm:text-base @error('name') border-red-500 @enderror"
                       placeholder="Kos Melati Indah"
                       required>
                @error('name')
                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-2 text-sm font-semibold text-gray-700">
                    No Telepon <span class="text-red-500">*</span>
                </label>
                <input type="tel"
                       name="phone"
                       value="{{ old('phone') }}"
                       class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2 sm:py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm sm:text-base @error('phone') border-red-500 @enderror"
                       placeholder="08xxxxxxxx"
                       required>
                @error('phone')
                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- WhatsApp --}}
        <div>
            <label class="block mb-2 text-sm font-semibold text-gray-700">
                WhatsApp
            </label>
            <input type="tel"
                   name="whatsapp"
                   value="{{ old('whatsapp') }}"
                   class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2 sm:py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm sm:text-base @error('whatsapp') border-red-500 @enderror"
                   placeholder="08xxxxxxxx">
            @error('whatsapp')
                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Alamat --}}
        <div>
            <label class="block mb-2 text-sm font-semibold text-gray-700">
                Alamat <span class="text-red-500">*</span>
            </label>
            <textarea name="address"
                      rows="2"
                      class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2 sm:py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm sm:text-base @error('address') border-red-500 @enderror"
                      placeholder="Jl. Contoh No. 123, Bandung"
                      required>{{ old('address') }}</textarea>
            @error('address')
                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- MAP SEARCH --}}
        <div>
            <label class="block mb-2 text-sm font-semibold text-gray-700">
                Lokasi di Google Maps
            </label>
            <input type="text"
                   id="map-search"
                   class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2 sm:py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm sm:text-base"
                   placeholder="Cari lokasi kos di Google Maps (contoh: Kos Melati Bandung)">

            <input type="hidden" name="maps_embed" id="maps_embed" value="{{ old('maps_embed') }}">

            <div id="map-preview"
                 class="mt-4 rounded-lg sm:rounded-xl overflow-hidden hidden">
                <iframe id="map-iframe"
                        class="w-full h-48 sm:h-64 border-0"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>

            <p class="mt-2 text-xs sm:text-sm text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                Ketik nama atau alamat property dan tekan Enter untuk mencari lokasi
            </p>
        </div>

        {{-- Deskripsi --}}
        <div>
            <label class="block mb-2 text-sm font-semibold text-gray-700">
                Deskripsi
            </label>
            <textarea name="description"
                      rows="4"
                      class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2 sm:py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm sm:text-base @error('description') border-red-500 @enderror"
                      placeholder="Deskripsi singkat property...">{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 sm:pt-6 border-t border-gray-200">
            <a href="{{ route('admin.properties.index') }}"
               class="w-full sm:w-auto px-5 sm:px-6 py-2 sm:py-2.5 border-2 border-gray-300 text-gray-700 text-center rounded-lg sm:rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all font-semibold text-sm sm:text-base active:scale-[0.98]">
                <i class="fas fa-times mr-2"></i>Batal
            </a>

            <button type="submit"
                class="w-full sm:w-auto px-5 sm:px-6 py-2 sm:py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg sm:rounded-xl font-semibold text-sm sm:text-base transition-all shadow-md hover:shadow-lg active:scale-[0.98]">
                <i class="fas fa-save mr-2"></i>Simpan Property
            </button>
        </div>

    </form>
</div>

{{-- MAP SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapSearchInput = document.getElementById('map-search');
    const mapIframe = document.getElementById('map-iframe');
    const mapPreview = document.getElementById('map-preview');
    const mapsEmbedInput = document.getElementById('maps_embed');

    // Function to update map
    function updateMap(query) {
        if (!query || query.trim() === '') return;

        const encodedQuery = encodeURIComponent(query.trim());
        const iframeUrl = `https://www.google.com/maps?q=${encodedQuery}&output=embed`;

        mapIframe.src = iframeUrl;
        mapsEmbedInput.value = `<iframe src="${iframeUrl}" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>`;
        mapPreview.classList.remove('hidden');
    }

    // Event listener for Enter key
    mapSearchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            updateMap(this.value);
        }
    });

    // Event listener for input change (when user leaves the field)
    mapSearchInput.addEventListener('change', function() {
        updateMap(this.value);
    });

    // If there's old input value, show the map
    if (mapsEmbedInput.value) {
        mapPreview.classList.remove('hidden');
    }
});
</script>

{{-- Font Awesome (if not already included) --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</x-app-layout>
