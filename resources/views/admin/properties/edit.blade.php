<x-app-layout>
<div class="max-w-3xl mx-auto py-10">

<h1 class="text-2xl font-bold mb-6">Edit Property</h1>

<form method="POST"
      action="{{ route('admin.properties.update', $property) }}"
      class="bg-white p-6 rounded-xl shadow space-y-4">
    @csrf
    @method('PUT')

    <select name="owner_id" class="w-full border rounded-lg px-4 py-2">
        @foreach($owners as $owner)
            <option value="{{ $owner->id }}"
                @selected($property->owner_id == $owner->id)>
                {{ $owner->name }}
            </option>
        @endforeach
    </select>

    <input name="name" value="{{ $property->name }}"
           class="w-full border rounded-lg px-4 py-2">

    <textarea name="address"
              class="w-full border rounded-lg px-4 py-2">{{ $property->address }}</textarea>

    <input name="phone" value="{{ $property->phone }}"
           class="w-full border rounded-lg px-4 py-2">

    <input name="whatsapp" value="{{ $property->whatsapp }}"
           class="w-full border rounded-lg px-4 py-2">

    <textarea name="maps_embed"
              class="w-full border rounded-lg px-4 py-2">{{ $property->maps_embed }}</textarea>

    <textarea name="description"
              class="w-full border rounded-lg px-4 py-2">{{ $property->description }}</textarea>

    <div class="flex justify-end gap-3">
        <a href="{{ route('admin.properties.index') }}"
           class="px-4 py-2 border rounded-lg">Batal</a>

        <button class="px-5 py-2 bg-blue-600 text-white rounded-lg">
            Update
        </button>
    </div>
</form>
</div>
</x-app-layout>
