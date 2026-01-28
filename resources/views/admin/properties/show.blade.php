<x-app-layout>
<div class="max-w-6xl mx-auto py-10 px-4 space-y-8">

    {{-- Header --}}
    <div>
        <h1 class="text-3xl font-bold text-gray-800">
            {{ $property->name }}
        </h1>
        <p class="text-gray-500 text-sm mt-1">
            {{ $property->address }}
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- INFO --}}
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-2xl shadow p-6 space-y-3">
                <h2 class="font-semibold text-lg border-b pb-2">
                    Informasi Property
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <p>
                        <span class="font-medium text-gray-600">Owner</span><br>
                        {{ $property->owner->name }}
                    </p>

                    <p>
                        <span class="font-medium text-gray-600">Telepon</span><br>
                        {{ $property->phone ?? '-' }}
                    </p>

                    <p>
                        <span class="font-medium text-gray-600">WhatsApp</span><br>
                        {{ $property->whatsapp ?? '-' }}
                    </p>
                </div>
            </div>

            {{-- DESKRIPSI --}}
            <div class="bg-white rounded-2xl shadow p-6 space-y-2">
                <h2 class="font-semibold text-lg border-b pb-2">
                    Deskripsi
                </h2>

                <p class="text-gray-600 leading-relaxed">
                    {{ $property->description ?? 'Tidak ada deskripsi.' }}
                </p>
            </div>
        </div>

        {{-- GOOGLE MAPS --}}
        @if($property->maps_embed)
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="p-4 border-b font-semibold">
                Lokasi Property
            </div>

            <div class="relative bg-gray-100 h-64 sm:h-80 lg:h-full min-h-[320px]">
                {!! $property->maps_embed !!}
            </div>
        </div>
        @endif

    </div>

</div>
</x-app-layout>
