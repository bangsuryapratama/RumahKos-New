<x-app-layout>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            
            {{-- Header --}}
            <div class="mb-6">
                <a href="{{ route('admin.facilities.index') }}" 
                   class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Master Fasilitas
                </a>
                
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $facility->name }}</h1>
                        <p class="mt-1 text-sm text-gray-500">Detail informasi fasilitas</p>
                    </div>
                    
                    <div class="flex gap-3">
                        <a href="{{ route('admin.facilities.edit', $facility) }}"
                           class="inline-flex items-center px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                        
                        <form action="{{ route('admin.facilities.destroy', $facility) }}"
                              method="POST"
                              onsubmit="return confirm('Yakin hapus fasilitas {{ $facility->name }}? Akan dihapus dari semua kamar.')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors shadow-sm">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Icon Card --}}
                <div class="space-y-6">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-sm border border-blue-200 p-8">
                        <div class="flex flex-col items-center justify-center text-center">
                            <div class="w-32 h-32 bg-white rounded-full flex items-center justify-center shadow-lg mb-4">
                                <i class="{{ $facility->icon }} text-6xl text-blue-600"></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900">{{ $facility->name }}</h2>
                            <p class="text-sm text-gray-500 mt-1">{{ $facility->icon }}</p>
                        </div>
                    </div>

                    {{-- Stats --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 p-3 bg-purple-100 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500">Digunakan di</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $facility->rooms->count() }} Kamar</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Rooms List --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Kamar yang Menggunakan Fasilitas Ini</h3>
                        </div>
                        
                        @if($facility->rooms->count() > 0)
                        <div class="divide-y divide-gray-200">
                            @foreach($facility->rooms as $room)
                            <div class="p-5 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center flex-1">
                                        @if($room->image)
                                        <img src="{{ asset('storage/'.$room->image) }}" 
                                             class="w-16 h-16 rounded-lg object-cover"
                                             alt="{{ $room->name }}">
                                        @else
                                        <div class="w-16 h-16 rounded-lg bg-gray-200 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                            </svg>
                                        </div>
                                        @endif
                                        
                                        <div class="ml-4">
                                            <h4 class="font-semibold text-gray-900">{{ $room->name }}</h4>
                                            <p class="text-sm text-gray-500">{{ $room->property->name }}</p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                    {{ $room->status == 'available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                    {{ $room->status == 'available' ? 'Tersedia' : 'Terisi' }}
                                                </span>
                                                <span class="text-xs text-gray-500">Lantai {{ $room->floor }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('admin.rooms.show', $room) }}"
                                       class="ml-4 p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="p-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Belum ada kamar yang menggunakan fasilitas ini</p>
                            <a href="{{ route('admin.facility_rooms.create') }}"
                               class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                Assign ke Kamar
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- Font Awesome CDN --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</x-app-layout>