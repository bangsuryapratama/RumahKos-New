<x-app-layout>
<div class="max-w-3xl mx-auto py-10">

<h1 class="text-2xl font-bold mb-6">Tambah Property</h1>

<form method="POST" action="{{ route('admin.properties.store') }}"
      class="bg-white p-6 rounded-xl shadow space-y-4">
    @csrf

    <select name="owner_id" class="w-full border rounded-lg px-4 py-2">
        <option value="">Pilih Owner</option>
        @foreach($owners as $owner)
            <option value="{{ $owner->id }}">{{ $owner->name }}</option>
        @endforeach
    </select>

    <input name="name" class="w-full border rounded-lg px-4 py-2"
           placeholder="Nama Property">

    <textarea name="address" class="w-full border rounded-lg px-4 py-2"
              placeholder="Alamat"></textarea>

    <input name="phone" class="w-full border rounded-lg px-4 py-2"
           placeholder="No Telepon">

    <input name="whatsapp" class="w-full border rounded-lg px-4 py-2"
           placeholder="No WhatsApp">

    <textarea name="maps_embed" class="w-full border rounded-lg px-4 py-2"
              placeholder="Embed Google Maps (iframe)"></textarea>

    <textarea name="description" class="w-full border rounded-lg px-4 py-2"
              placeholder="Deskripsi"></textarea>

    <div class="flex justify-end gap-3">
        <a href="{{ route('admin.properties.index') }}"
           class="px-4 py-2 border rounded-lg">Batal</a>

        <button class="px-5 py-2 bg-blue-600 text-white rounded-lg">
            Simpan
        </button>
    </div>
</form>
</div>
</x-app-layout>
