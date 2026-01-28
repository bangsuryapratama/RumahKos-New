<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8 space-y-6">

        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Property</h1>
                <p class="text-sm text-gray-500">Kelola semua properti kos</p>
            </div>

            <a href="{{ route('admin.properties.create') }}"
               class="inline-flex items-center justify-center
                      px-4 py-2 bg-blue-600 text-white rounded-lg
                      hover:bg-blue-700 transition text-sm">
                + Tambah Property
            </a>
        </div>

        {{-- TABLE --}}
        <div class="bg-white border rounded-xl shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left">Nama</th>
                        <th class="px-6 py-3 text-left">Owner</th>
                        <th class="px-6 py-3 text-left">Kontak</th>
                        <th class="px-6 py-3 text-left">Alamat</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                @forelse($properties as $property)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ $property->name }}
                        </td>

                        <td class="px-6 py-4 text-gray-700">
                            {{ $property->owner->name ?? '-' }}
                        </td>

                        {{-- KONTAK (FIX ALIGNMENT) --}}
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-2">

                                {{-- PHONE --}}
                                @if($property->phone)
                                <div class="flex items-center gap-3">
                                    <p class="fa-solid fa-phone text-lg"></p>
                                    <span class="text-gray-700 leading-none">
                                        {{ $property->phone }}
                                    </span>
                                </div>
                                @endif

                                {{-- WHATSAPP --}}
                                @if($property->whatsapp)
                                <div class="flex items-center gap-3">
                                    <i class="fab fa-whatsapp text-lg"></i>
                                    <span class="text-gray-700 leading-none">
                                        {{ $property->whatsapp }}
                                    </span>
                                </div>
                                @endif

                            </div>
                        </td>

                        <td class="px-6 py-4 text-gray-600 max-w-xs">
                            {{ Str::limit($property->address, 50) }}
                        </td>

                        {{-- AKSI --}}
                        <td class="px-6 py-4 text-right">
                            <div class="inline-flex gap-3 text-sm">
                                <a href="{{ route('admin.properties.show', $property) }}"
                                   class="text-blue-600 hover:underline">Detail</a>

                                <a href="{{ route('admin.properties.edit', $property) }}"
                                   class="text-yellow-600 hover:underline">Edit</a>

                                <form method="POST"
                                      action="{{ route('admin.properties.destroy', $property) }}"
                                      onsubmit="return confirm('Yakin hapus property ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-10 text-center text-gray-500">
                            Belum ada property
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <div class="p-4 border-t">
                {{ $properties->links() }}
            </div>
        </div>

    </div>
</x-app-layout>
