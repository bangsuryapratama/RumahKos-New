<x-app-layout>
<div class="max-w-5xl mx-auto py-10 px-4">

    <h1 class="text-2xl font-bold mb-6">Tambah Property</h1>

    <form method="POST" action="{{ route('admin.properties.store') }}"
          class="bg-white p-6 md:p-8 rounded-2xl shadow space-y-6">
        @csrf

        {{-- Owner --}}
        <div>
            <label class="block mb-1 text-sm font-medium">Owner</label>
            <select name="owner_id"
                class="w-full border rounded-xl px-4 py-2 focus:ring focus:ring-blue-200">
                <option value="">Pilih Owner</option>
                @foreach($owners as $owner)
                    <option value="{{ $owner->id }}">{{ $owner->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Nama & Telepon --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-1 text-sm font-medium">Nama Property</label>
                <input name="name"
                       class="w-full border rounded-xl px-4 py-2 focus:ring focus:ring-blue-200"
                       placeholder="Kos Melati Indah">
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium">No Telepon</label>
                <input name="phone"
                       class="w-full border rounded-xl px-4 py-2"
                       placeholder="08xxxxxxxx">
            </div>
        </div>

        {{-- WhatsApp --}}
        <div>
            <label class="block mb-1 text-sm font-medium">WhatsApp</label>
            <input name="whatsapp"
                   class="w-full border rounded-xl px-4 py-2"
                   placeholder="08xxxxxxxx">
        </div>

        {{-- Alamat --}}
        <div>
            <label class="block mb-1 text-sm font-medium">Alamat</label>
            <textarea name="address" rows="2"
                      class="w-full border rounded-xl px-4 py-2"
                      placeholder="Jl. Contoh No. 123, Bandung"></textarea>
        </div>

        {{-- MAP SEARCH --}}
        <div>
            <label class="block mb-1 text-sm font-medium">Cari Lokasi (Google Maps)</label>
            <input id="map-search"
                   class="w-full border rounded-xl px-4 py-2"
                   placeholder="Cari lokasi kos di Google Maps">

            <input type="hidden" name="maps_embed" id="maps_embed">

            <div id="map-preview"
                 class="mt-4 rounded-xl overflow-hidden hidden">
                <iframe id="map-iframe"
                        class="w-full h-64 border-0"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>

        {{-- Deskripsi --}}
        <div>
            <label class="block mb-1 text-sm font-medium">Deskripsi</label>
            <textarea name="description" rows="4"
                      class="w-full border rounded-xl px-4 py-2"
                      placeholder="Deskripsi singkat property..."></textarea>
        </div>

        {{-- Action --}}
        <div class="flex justify-end gap-3 pt-4 border-t">
            <a href="{{ route('admin.properties.index') }}"
               class="px-4 py-2 border rounded-xl hover:bg-gray-50">
                Batal
            </a>

            <button
                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl">
                Simpan
            </button>
        </div>

    </form>
</div>

{{-- MAP SCRIPT --}}
<script>
document.getElementById('map-search').addEventListener('change', function () {
    const query = encodeURIComponent(this.value);

    const iframeUrl =
        `https://www.google.com/maps?q=${query}&output=embed`;

    document.getElementById('map-iframe').src = iframeUrl;
    document.getElementById('maps_embed').value =
        `<iframe src="${iframeUrl}" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>`;

    document.getElementById('map-preview').classList.remove('hidden');
});
</script>
</x-app-layout>
