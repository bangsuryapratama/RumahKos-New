<x-app-layout>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            
            {{-- Header --}}
            <div class="mb-6">
                <a href="{{ route('admin.rooms.index') }}" 
                   class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Daftar Kamar
                </a>
                
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $room->name }}</h1>
                        <p class="mt-1 text-sm text-gray-500 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            {{ $room->property->name }}
                        </p>
                    </div>
                    
                    <div class="flex gap-3">
                        <a href="{{ route('admin.rooms.edit', $room) }}"
                           class="inline-flex items-center px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                        
                        <form action="{{ route('admin.rooms.destroy', $room) }}"
                              method="POST"
                              onsubmit="return confirm('Yakin hapus kamar {{ $room->name }}? Tindakan ini tidak dapat dibatalkan.')">
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

            {{-- Main Content Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Left Column: Image & Main Info --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- Room Image --}}
                    @if ($room->image)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <img src="{{ asset('storage/'.$room->image) }}"
                             class="w-full h-64 sm:h-96 object-cover"
                             alt="{{ $room->name }}">
                    </div>
                    @else
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="w-full h-64 sm:h-96 bg-gray-100 flex items-center justify-center">
                            <div class="text-center">
                                <svg class="mx-auto h-20 w-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">Tidak ada foto</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Facilities --}}
                    @if($room->facilities && $room->facilities->count())
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center mb-4">
                            <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <h2 class="text-lg font-semibold text-gray-900">Fasilitas</h2>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($room->facilities as $facility)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ $facility->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Residents --}}
                    @if($room->residents && $room->residents->count())
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center mb-4">
                            <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <h2 class="text-lg font-semibold text-gray-900">Penghuni</h2>
                        </div>
                        <div class="space-y-3">
                            @foreach ($room->residents as $resident)
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                        <span class="text-purple-600 font-semibold text-sm">
                                            {{ strtoupper(substr($resident->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-medium text-gray-900">{{ $resident->name }}</p>
                                        @if($resident->email)
                                        <p class="text-sm text-gray-500">{{ $resident->email }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>

                {{-- Right Column: Stats Cards --}}
                <div class="space-y-6">
                    
                    {{-- Status Card --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <p class="text-sm font-medium text-gray-500 mb-3">Status Kamar</p>
                        <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold
                            {{ $room->status == 'available' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200' }}">
                            <span class="w-2 h-2 rounded-full mr-2 {{ $room->status == 'available' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                            {{ $room->status == 'available' ? 'Tersedia' : 'Terisi' }}
                        </span>
                    </div>

                    {{-- Price Card --}}
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-sm border border-blue-200 p-6">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm font-medium text-blue-700">Harga Sewa</p>
                        </div>
                        <p class="text-3xl font-bold text-blue-900">Rp {{ number_format($room->price) }}</p>
                        <p class="text-sm text-blue-600 mt-1">/ {{ $room->billing_cycle == 'monthly' ? 'bulan' : 'tahun' }}</p>
                    </div>

                    {{-- Room Details Cards --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden divide-y divide-gray-200">
                        
                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 p-2 bg-purple-100 rounded-lg">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-gray-500">Lantai</p>
                                        <p class="font-semibold text-gray-900">Lantai {{ $room->floor }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 p-2 bg-amber-100 rounded-lg">
                                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-gray-500">Ukuran</p>
                                        <p class="font-semibold text-gray-900">{{ $room->size }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 p-2 bg-green-100 rounded-lg">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-gray-500">Periode</p>
                                        <p class="font-semibold text-gray-900">{{ $room->billing_cycle == 'monthly' ? 'Bulanan' : 'Tahunan' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Quick Info --}}
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl shadow-sm border border-gray-200 p-5">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Informasi Cepat</h3>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <span>ID: {{ $room->id }}</span>
                            </div>
                            @if($room->facilities)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>{{ $room->facilities->count() }} Fasilitas</span>
                            </div>
                            @endif
                            @if($room->residents)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <span>{{ $room->residents->count() }} Penghuni</span>
                            </div>
                            @endif
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>