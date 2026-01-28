<x-app-layout>
<div class="max-w-5xl mx-auto py-10 px-4">

<h1 class="text-2xl font-bold mb-6">Edit Property</h1>

<form method="POST"
      action="{{ route('admin.properties.update', $property) }}"
      class="bg-white p-6 md:p-8 rounded-2xl shadow space-y-6">
    @csrf
    @method('PUT')

    {{-- OWNER --}}
    <div>
        <label class="block mb-1 text-sm font-medium">Owner</label>
        <select name="owner_id"
                class="w-full border rounded-xl px-4 py-2 focus:ring focus:ring-blue-200">
            @foreach($owners as $owner)
                <option value="{{ $owner->id }}"
                    @selected($property->owner_id == $owner->id)>
                    {{ $owner->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- NAMA --}}
    <div>
        <label class="block mb-1 text-sm font-medium">Nama Property</label>
        <input name="name" value="{{ $property->name }}"
               class="w-full border rounded-xl px-4 py-2">
    </div>

    {{-- TELEPON --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block mb-1 text-sm font-medium">Telepon</label>
            <input name="phone" value="{{ $property->phone }}"
                   class="w-full border rounded-xl px-4 py-2">
        </div>

        <div>
            <label class="block mb-1 text-sm font-medium">WhatsApp</label>
            <input name="whatsapp" value="{{ $property->whatsapp }}"
                   class="w-full border rounded-xl px-4 py-2">
        </div>
    </div>

    {{-- ALAMAT --}}
    <div>
        <label class="block mb-1 text-sm font-medium">Alamat</label>
        <textarea name="address" rows="2"
                  class="w-full border rounded-xl px-4 py-2">{{ $property->address }}</textarea>
    </div>

    {{-- MAP SEARCH --}}
    <div>
        <label class="block mb-1 text-sm font-medium">
            Lokasi Google Maps
        </label>

        <input id="map-search"
               class="w-full border rounded-xl px-4 py-2"
               placeholder="Cari lokasi property di Google Maps">

        {{-- hidden field --}}
        <input type="hidden" name="maps_embed" id="maps_embed"
               value="{{ $property->maps_embed }}">

        {{-- MAP PREVIEW --}}
        <div id="map-preview"
             class="mt-4 rounded-xl overflow-hidden {{ $property->maps_embed ? '' : 'hidden' }}">
            <div class="relative bg-gray-100 h-64 min-h-[320px]">
                {!! $property->maps_embed !!}
            </div>
        </div>
    </div>

    {{-- DESKRIPSI --}}
    <div>
        <label class="block mb-1 text-sm font-medium">Deskripsi</label>
        <textarea name="description" rows="4"
                  class="w-full border rounded-xl px-4 py-2">{{ $property->description }}</textarea>
    </div>

    {{-- ACTION --}}
    <div class="flex justify-end gap-3 pt-4 border-t">
        <a href="{{ route('admin.properties.index') }}"
           class="px-4 py-2 border rounded-xl hover:bg-gray-50">
            Batal
        </a>

        <button
            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl">
            Update
        </button>
    </div>
</form>
</div>

{{-- MAP SCRIPT --}}
<script>
document.getElementById('map-search').addEventListener('change', function () {
    const query = encodeURIComponent(this.value);
    const iframeUrl = `https://www.google.com/maps?q=${query}&output=embed`;

    document.getElementById('maps_embed').value =
        `<iframe src="${iframeUrl}" class="w-full h-full border-0"
         loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>`;

    document.getElementById('map-preview').innerHTML = `
        <div class="relative bg-gray-100 h-64 min-h-[320px]">
            <iframe src="${iframeUrl}"
                class="w-full h-full border-0"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    `;

    document.getElementById('map-preview').classList.remove('hidden');
});
</script>
</x-app-layout>
