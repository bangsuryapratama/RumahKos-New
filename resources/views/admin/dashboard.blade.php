<x-app-layout>
    <div class="py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Welcome Card --}}
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-lg sm:rounded-xl shadow-lg p-6 sm:p-8 mb-6 sm:mb-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Selamat Datang Kembali!</h1>
                        <p class="text-gray-200 text-base sm:text-lg font-medium">{{ auth()->user()->name }}</p>
                        <p class="text-gray-400 text-xs sm:text-sm mt-1">Admin RumahKos</p>
                    </div>
                    <div class="hidden md:block">
                        <div class="bg-white/10 backdrop-blur-sm rounded-full p-5 sm:p-6">
                            <i class="fa-solid fa-user-circle text-white text-3xl sm:text-4xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
                <div class="bg-white rounded-lg sm:rounded-xl shadow-sm p-4 sm:p-6 border border-gray-100 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <div class="bg-blue-100 p-2.5 sm:p-3 rounded-lg">
                            <i class="fa-solid fa-house text-blue-600 text-lg sm:text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-gray-600 text-xs sm:text-sm font-semibold mb-1">Total Kamar</h3>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $totalRooms ?? 0}}</p>
                </div>

                <div class="bg-white rounded-lg sm:rounded-xl shadow-sm p-4 sm:p-6 border border-gray-100 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <div class="bg-blue-100 p-2.5 sm:p-3 rounded-lg">
                            <i class="fa-solid fa-check-circle text-blue-600 text-lg sm:text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-gray-600 text-xs sm:text-sm font-semibold mb-1">Kamar Tersedia</h3>
                    <p class="text-2xl sm:text-3xl font-bold text-blue-600">{{ $availableRooms ?? 0 }}</p>
                </div>

                <div class="bg-white rounded-lg sm:rounded-xl shadow-sm p-4 sm:p-6 border border-gray-100 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <div class="bg-gray-100 p-2.5 sm:p-3 rounded-lg">
                            <i class="fa-solid fa-user-group text-gray-700 text-lg sm:text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-gray-600 text-xs sm:text-sm font-semibold mb-1">Kamar Terisi</h3>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $occupiedRooms ?? 0 }}</p>
                </div>

                <div class="bg-white rounded-lg sm:rounded-xl shadow-sm p-4 sm:p-6 border border-gray-100 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <div class="bg-blue-100 p-2.5 sm:p-3 rounded-lg">
                            <i class="fa-solid fa-wallet text-blue-600 text-lg sm:text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-gray-600 text-xs sm:text-sm font-semibold mb-1">Pendapatan</h3>
                    <p class="text-2xl sm:text-3xl font-bold text-blue-600">{{ $totalIncome ?? 0 }}</p>
                </div>
            </div>

            {{-- Quick Actions & Recent Activity --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                {{-- Quick Actions --}}
                <div class="bg-white rounded-lg sm:rounded-xl shadow-sm p-4 sm:p-6 border border-gray-100">
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-4 sm:mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-bolt text-blue-600"></i>
                        Quick Actions
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <a href="{{ route('admin.rooms.create') }}"
                           class="flex items-center gap-3 p-3 sm:p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition group border border-blue-100">
                            <div class="bg-gradient-to-br from-blue-600 to-blue-700 p-2 rounded-lg group-hover:scale-110 transition flex-shrink-0">
                                <i class="fa-solid fa-plus text-white text-xs sm:text-sm"></i>
                            </div>
                            <span class="font-semibold text-gray-700 text-sm sm:text-base">Tambah Kamar</span>
                        </a>

                        <a href="#"
                           class="flex items-center gap-3 p-3 sm:p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition group border border-blue-100">
                            <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-2 rounded-lg group-hover:scale-110 transition flex-shrink-0">
                                <i class="fa-solid fa-calendar-check text-white text-xs sm:text-sm"></i>
                            </div>
                            <span class="font-semibold text-gray-700 text-sm sm:text-base">Kelola Booking</span>
                        </a>

                        <a href="#"
                           class="flex items-center gap-3 p-3 sm:p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition group border border-gray-100">
                            <div class="bg-gradient-to-br from-gray-700 to-gray-800 p-2 rounded-lg group-hover:scale-110 transition flex-shrink-0">
                                <i class="fa-solid fa-pen-to-square text-white text-xs sm:text-sm"></i>
                            </div>
                            <span class="font-semibold text-gray-700 text-sm sm:text-base">Edit Website</span>
                        </a>

                        <a href="#"
                           class="flex items-center gap-3 p-3 sm:p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition group border border-gray-100">
                            <div class="bg-gradient-to-br from-gray-600 to-gray-700 p-2 rounded-lg group-hover:scale-110 transition flex-shrink-0">
                                <i class="fa-solid fa-file-invoice-dollar text-white text-xs sm:text-sm"></i>
                            </div>
                            <span class="font-semibold text-gray-700 text-sm sm:text-base">Lihat Laporan</span>
                        </a>
                    </div>
                </div>

                {{-- Recent Activity --}}
                <div class="bg-white rounded-lg sm:rounded-xl shadow-sm p-4 sm:p-6 border border-gray-100">
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-4 sm:mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-chart-line text-blue-600"></i>
                        Aktivitas Terbaru
                    </h3>
                    <div class="space-y-4 h-48 sm:h-64 overflow-y-auto">
                        {{-- Example Activities --}}
                        @forelse([] as $activity)
                            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg border border-gray-100">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-circle text-blue-600 text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">{{ $activity->title }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $activity->time }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center h-full text-center py-8">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                    <i class="fa-solid fa-inbox text-gray-400 text-2xl"></i>
                                </div>
                                <p class="text-gray-500 text-sm font-medium">Tidak ada aktivitas terbaru</p>
                                <p class="text-gray-400 text-xs mt-1">Aktivitas akan muncul di sini</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Additional Info Section --}}
            {{-- <div class="mt-6 sm:mt-8 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg sm:rounded-xl p-4 sm:p-6 border border-blue-200">
                <div class="flex items-start gap-3 sm:gap-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-info text-white text-base sm:text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900 text-sm sm:text-base mb-1 sm:mb-2">Selamat Datang di Dashboard Admin</h4>
                        <p class="text-xs sm:text-sm text-gray-700 leading-relaxed">
                            Kelola properti kos Anda dengan mudah. Gunakan menu navigasi di samping untuk mengakses fitur-fitur lengkap seperti
                            manajemen kamar, penghuni, pembayaran, dan laporan keuangan.
                        </p>
                        <div class="mt-3 sm:mt-4 flex flex-wrap gap-2">
                            <a href="#" class="inline-flex items-center gap-1.5 text-xs sm:text-sm text-blue-700 font-semibold hover:text-blue-800">
                                Lihat Tutorial
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                            <span class="text-gray-400">•</span>
                            <a href="#" class="inline-flex items-center gap-1.5 text-xs sm:text-sm text-blue-700 font-semibold hover:text-blue-800">
                                Bantuan & Support
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div> --}}

        </div>
    </div>
</x-app-layout>
