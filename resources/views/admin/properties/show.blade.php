<x-app-layout>
<div class="max-w-4xl mx-auto py-10 space-y-6">

<h1 class="text-3xl font-bold">{{ $property->name }}</h1>

<div class="bg-white rounded-xl shadow p-6 space-y-2">
    <p><b>Owner:</b> {{ $property->owner->name }}</p>
    <p><b>Alamat:</b> {{ $property->address }}</p>
    <p><b>Telepon:</b> {{ $property->phone ?? '-' }}</p>
    <p><b>WhatsApp:</b> {{ $property->whatsapp ?? '-' }}</p>
    <p><b>Deskripsi:</b></p>
    <p class="text-gray-600">{{ $property->description }}</p>
</div>

@if($property->maps_embed)
<div class="rounded-xl overflow-hidden border">
    {!! $property->maps_embed !!}
</div>
@endif

</div>
</x-app-layout>
