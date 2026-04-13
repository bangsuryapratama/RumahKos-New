<x-app-layout>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">

            <!-- Header -->
            <div class="mb-6">
                <a href="{{ route('admin.rooms.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Daftar Kamar
                </a>

                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $room->name }}</h1>
                <p class="mt-1 text-sm text-gray-500">
                    {{ $room->property->name ?? '-' }}
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- LEFT -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Image -->
                    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                        @if ($room->image)
                            <img src="{{ asset('storage/'.$room->image) }}" class="w-full h-72 object-cover">
                        @else
                            <div class="h-72 flex items-center justify-center bg-gray-100 text-gray-400">
                                Tidak ada foto
                            </div>
                        @endif
                    </div>

                    <!-- Facilities -->
                    @if($room->facilities && $room->facilities->count())
                    <div class="bg-white rounded-xl shadow-sm border p-6">
                        <h2 class="font-semibold mb-3">Fasilitas</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($room->facilities as $facility)
                                <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm">
                                    {{ $facility->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Residents -->
                    @if($room->residents && $room->residents->count())
                    <div class="bg-white rounded-xl shadow-sm border p-6">
                        <h2 class="font-semibold mb-4">Penghuni</h2>

                        <div class="space-y-3">
                            @foreach ($room->residents as $resident)
                                @php $user = $resident->user; @endphp

                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                        <span class="text-purple-600 font-bold">
                                            {{ strtoupper(substr($user->name ?? 'U',0,1)) }}
                                        </span>
                                    </div>

                                    <div class="ml-3">
                                        <p class="font-medium">{{ $user->name ?? 'User tidak ditemukan' }}</p>
                                        <p class="text-sm text-gray-500">{{ $user->email ?? '' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>

                <!-- RIGHT -->
                <div class="space-y-6">

                    <!-- Status -->
                    <div class="bg-white rounded-xl shadow-sm border p-6">
                        <p class="text-sm text-gray-500 mb-2">Status</p>
                        <span class="px-3 py-1 rounded-lg text-sm font-semibold
                            {{ $room->status === 'available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $room->status === 'available' ? 'Tersedia' : 'Terisi' }}
                        </span>
                    </div>

                    <!-- Price -->
                    <div class="bg-blue-50 rounded-xl shadow-sm border p-6">
                        <p class="text-sm text-blue-700">Harga</p>
                        <p class="text-2xl font-bold text-blue-900">
                            Rp {{ number_format($room->price) }}
                        </p>
                        <p class="text-sm text-blue-600">
                            / {{ $room->billing_cycle === 'monthly' ? 'bulan' : 'tahun' }}
                        </p>
                    </div>

                    <!-- Details -->
                    <div class="bg-white rounded-xl shadow-sm border divide-y">
                        <div class="p-4">
                            <p class="text-sm text-gray-500">Lantai</p>
                            <p class="font-semibold">{{ $room->floor }}</p>
                        </div>
                        <div class="p-4">
                            <p class="text-sm text-gray-500">Ukuran</p>
                            <p class="font-semibold">{{ $room->size }}</p>
                        </div>
                        <div class="p-4">
                            <p class="text-sm text-gray-500">Periode</p>
                            <p class="font-semibold">
                                {{ $room->billing_cycle === 'monthly' ? 'Bulanan' : 'Tahunan' }}
                            </p>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>