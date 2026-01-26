<x-app-layout>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            
            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Kelola Fasilitas Kamar</h1>
                    <p class="mt-1 text-sm text-gray-500">Atur fasilitas yang tersedia di setiap kamar</p>
                </div>
                <a href="{{ route('admin.facility_rooms.create') }}"
                   class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Assign Fasilitas
                </a>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            {{-- Error Message --}}
            @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
            @endif

            {{-- Quick Links --}}
            <div class="mb-6 flex flex-wrap gap-3">
                <a href="{{ route('admin.facilities.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Master Fasilitas
                </a>
                <a href="{{ route('admin.rooms.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Daftar Kamar
                </a>
            </div>


            {{-- Room Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($rooms as $room)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                    
                    {{-- Room Image --}}
                    @if($room->image)
                    <img src="{{ asset('storage/'.$room->image) }}" 
                         class="w-full h-48 object-cover"
                         alt="{{ $room->name }}">
                    @else
                    <div class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    @endif

                    <div class="p-5">
                        {{-- Room Info --}}
                        <div class="mb-4">
                            <h3 class="font-semibold text-gray-900 text-lg">{{ $room->name }}</h3>
                            <p class="text-sm text-gray-500 mt-0.5">{{ $room->property->name }}</p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                    {{ $room->status == 'available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $room->status == 'available' ? 'Tersedia' : 'Terisi' }}
                                </span>
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    Lantai {{ $room->floor }}
                                </span>
                            </div>
                        </div>

                        {{-- Facilities Badge Count --}}
                        <div class="mb-4 pb-4 border-b border-gray-100">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 font-medium">Total Fasilitas:</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold 
                                    {{ $room->facilities->count() > 0 ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500' }}">
                                    @if($room->facilities->count() > 0)
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @endif
                                    {{ $room->facilities->count() }} Item
                                </span>
                            </div>
                        </div>

                        {{-- Facilities List --}}
                        @if($room->facilities->count() > 0)
                        <div class="mb-4">
                            <p class="text-xs font-medium text-gray-500 mb-2">Fasilitas Tersedia:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($room->facilities->take(5) as $facility)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                    <i class="{{ $facility->icon }} mr-1.5 text-sm"></i>
                                    {{ $facility->name }}
                                </span>
                                @endforeach
                                @if($room->facilities->count() > 5)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-700">
                                    +{{ $room->facilities->count() - 5 }} lainnya
                                </span>
                                @endif
                            </div>
                        </div>
                        @else
                        <div class="mb-4 py-3 px-4 bg-yellow-50 border border-yellow-100 rounded-lg">
                            <p class="text-xs text-yellow-700 flex items-center">
                                <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                Belum ada fasilitas yang di-assign
                            </p>
                        </div>
                        @endif

                        {{-- Actions --}}
                        <div class="flex gap-2">
                            <a href="{{ route('admin.facility_rooms.edit', $room) }}"
                               class="flex-1 inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Kelola
                            </a>
                            @if($room->facilities->count() > 0)
                            <form action="{{ route('admin.facility_rooms.destroy', $room) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus SEMUA fasilitas dari {{ $room->name }}?\n\nJumlah fasilitas: {{ $room->facilities->count() }} item')"
                                  class="inline-flex">
                                @csrf 
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center justify-center px-3 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors shadow-sm"
                                        title="Hapus Semua Fasilitas">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Belum ada kamar</h3>
                    <p class="mt-2 text-sm text-gray-500">Mulai dengan menambahkan kamar terlebih dahulu</p>
                    <div class="mt-6 flex gap-3 justify-center">
                        <a href="{{ route('admin.rooms.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Kamar
                        </a>
                        <a href="{{ route('admin.facilities.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Fasilitas
                        </a>
                    </div>
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($rooms->hasPages())
            <div class="mt-6">
                {{ $rooms->links() }}
            </div>
            @endif

        </div>
    </div>

    {{-- Font Awesome CDN --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</x-app-layout>