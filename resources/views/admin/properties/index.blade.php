<x-app-layout>
    <div class="py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-6 sm:mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">Manajemen Property</h1>
                        <p class="text-sm sm:text-base text-gray-600">
                            Kelola seluruh property kos yang terdaftar
                        </p>
                    </div>

                    <a href="{{ route('admin.properties.create') }}"
                       class="inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2 sm:py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg sm:rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all font-semibold text-sm sm:text-base shadow-md hover:shadow-lg active:scale-[0.98]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span>Tambah Property</span>
                    </a>
                </div>
            </div>

            {{-- Stats --}}
            @php
                $totalProperties = \App\Models\Property::count();
                $withOwner = \App\Models\Property::whereNotNull('owner_id')->count();
                $withPhone = \App\Models\Property::whereNotNull('phone')->count();
                $withWhatsapp = \App\Models\Property::whereNotNull('whatsapp')->count();
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6">

                <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl p-5 text-white shadow-md">
                    <div class="flex items-center justify-between mb-2">
                        <i class="fas fa-building text-3xl opacity-20"></i>
                        <span class="text-sm opacity-90">Total</span>
                    </div>
                    <div class="text-3xl font-bold">{{ $totalProperties }}</div>
                    <p class="text-sm opacity-90 mt-1">Semua Property</p>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-5 text-white shadow-md">
                    <div class="flex items-center justify-between mb-2">
                        <i class="fas fa-user text-3xl opacity-20"></i>
                        <span class="text-sm opacity-90">Owner</span>
                    </div>
                    <div class="text-3xl font-bold">{{ $withOwner }}</div>
                    <p class="text-sm opacity-90 mt-1">Ada Owner</p>
                </div>

                <div class="bg-gradient-to-br from-gray-700 to-gray-800 rounded-xl p-5 text-white shadow-md">
                    <div class="flex items-center justify-between mb-2">
                        <i class="fas fa-phone text-3xl opacity-20"></i>
                        <span class="text-sm opacity-90">Kontak</span>
                    </div>
                    <div class="text-3xl font-bold">{{ $withPhone }}</div>
                    <p class="text-sm opacity-90 mt-1">Punya Telepon</p>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-5 text-white shadow-md">
                    <div class="flex items-center justify-between mb-2">
                        <i class="fab fa-whatsapp text-3xl opacity-20"></i>
                        <span class="text-sm opacity-90">WhatsApp</span>
                    </div>
                    <div class="text-3xl font-bold">{{ $withWhatsapp }}</div>
                    <p class="text-sm opacity-90 mt-1">Terhubung WA</p>
                </div>
            </div>

            {{-- Property Table --}}
            <div class="bg-white rounded-xl shadow-md overflow-hidden">

                @if($properties->count() > 0)

                    {{-- Mobile View --}}
                    <div class="block lg:hidden divide-y divide-gray-200">
                        @foreach($properties as $property)
                            <div class="p-4 hover:bg-gray-50 transition">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                            {{ strtoupper(substr($property->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h3 class="font-semibold text-gray-900 truncate">
                                                {{ $property->name }}
                                            </h3>
                                            <p class="text-xs text-gray-600 truncate">
                                                {{ $property->owner->name ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-sm text-gray-600 mb-2">
                                    {{ Str::limit($property->address, 60) }}
                                </div>

                                <div class="flex gap-2 pt-3 border-t">
                                    <a href="{{ route('admin.properties.show', $property) }}"
                                       class="flex-1 px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-xs font-semibold text-center hover:bg-gray-200">
                                        Detail
                                    </a>

                                    <a href="{{ route('admin.properties.edit', $property) }}"
                                       class="flex-1 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-semibold text-center hover:bg-blue-100">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.properties.destroy', $property) }}"
                                          method="POST"
                                          onsubmit="return confirm('Hapus property ini?')"
                                          class="flex-1">
                                        @csrf @method('DELETE')
                                        <button class="w-full px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-semibold hover:bg-red-100">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Desktop View --}}
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Property</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Owner</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Alamat</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($properties as $property)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 font-semibold text-gray-900">
                                            {{ $property->name }}
                                        </td>

                                        <td class="px-6 py-4 text-gray-600">
                                            {{ $property->owner->name ?? '-' }}
                                        </td>

                                        <td class="px-6 py-4 text-gray-600 max-w-xs">
                                            {{ Str::limit($property->address, 70) }}
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('admin.properties.show', $property) }}"
                                                   class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <a href="{{ route('admin.properties.edit', $property) }}"
                                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form action="{{ route('admin.properties.destroy', $property) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Hapus property ini?')">
                                                    @csrf @method('DELETE')
                                                    <button class="p-2 text-red-500 hover:bg-red-50 rounded-lg">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="px-4 py-3 border-t">
                        {{ $properties->links() }}
                    </div>

                @else
                    <div class="text-center py-12">
                        <i class="fas fa-building text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-600 mb-4">Belum ada property</p>
                        <a href="{{ route('admin.properties.create') }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold text-sm">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Property</span>
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
