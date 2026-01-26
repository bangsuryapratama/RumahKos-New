<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Welcome Card -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-lg p-8 mb-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Selamat Datang Kembali!</h1>
                        <p class="text-blue-100 text-lg">{{ auth()->user()->name }}</p>
                        <p class="text-blue-200 text-sm mt-1">Admin RumahKos</p>
                    </div>
                    <div class="hidden md:block">
                        <div class="bg-white/10 backdrop-blur-sm rounded-full p-6">
                            <i class="fa-solid fa-user-circle text-white text-4xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <i class="fa-solid fa-house text-blue-600"></i>
                        </div>
                        <i class="fa-solid fa-house text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-gray-500 text-sm font-medium mb-1">Total Kamar</h3>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalRooms ?? 0}}</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-green-100 p-3 rounded-lg">
                            <i class="fa-solid fa-check-circle text-green-600"></i>
                        </div>
                        <i class="fa-solid fa-check text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-gray-500 text-sm font-medium mb-1">Kamar Tersedia</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $availableRooms ?? 0 }}</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-orange-100 p-3 rounded-lg">
                            <i class="fa-solid fa-user-group text-orange-600"></i>
                        </div>
                        <i class="fa-solid fa-users text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-gray-500 text-sm font-medium mb-1">Kamar Terisi</h3>
                    <p class="text-3xl font-bold text-orange-600">{{ $occupiedRooms ?? 0 }}</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <i class="fa-solid fa-wallet text-purple-600"></i>
                        </div>
                        <i class="fa-solid fa-coins text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-gray-500 text-sm font-medium mb-1">Pendapatan</h3>
                    <p class="text-3xl font-bold text-purple-600">{{ $totalIncome ?? 0 }}</p>
                </div>
            </div>

            <!-- Quick Actions & Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-bolt text-yellow-500"></i>
                        Quick Actions
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('admin.rooms.create') }}" class="flex items-center gap-3 p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition group">
                            <div class="bg-blue-600 p-2 rounded-lg group-hover:scale-110 transition">
                                <i class="fa-solid fa-plus text-white text-sm"></i>
                            </div>
                            <span class="font-semibold text-gray-700">Tambah Kamar</span>
                        </a>

                        <a href="#" class="flex items-center gap-3 p-4 bg-green-50 rounded-lg hover:bg-green-100 transition group">
                            <div class="bg-green-600 p-2 rounded-lg group-hover:scale-110 transition">
                                <i class="fa-solid fa-calendar-check text-white text-sm"></i>
                            </div>
                            <span class="font-semibold text-gray-700">Kelola Booking</span>
                        </a>

                        <a href="#" class="flex items-center gap-3 p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition group">
                            <div class="bg-purple-600 p-2 rounded-lg group-hover:scale-110 transition">
                                <i class="fa-solid fa-pen-to-square text-white text-sm"></i>
                            </div>
                            <span class="font-semibold text-gray-700">Edit Website</span>
                        </a>

                        <a href="#" class="flex items-center gap-3 p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition group">
                            <div class="bg-orange-600 p-2 rounded-lg group-hover:scale-110 transition">
                                <i class="fa-solid fa-file-invoice-dollar text-white text-sm"></i>
                            </div>
                            <span class="font-semibold text-gray-700">Lihat Laporan</span>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-chart-line text-blue-600"></i>
                        Aktivitas Terbaru
                    </h3>
                    <div class="space-y-4 h-64 overflow-y-auto">
                        <div>
                            {{-- Example Activity --}}
                            <p class="text-gray-500">Tidak ada aktivitas terbaru.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
