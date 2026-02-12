<x-app-layout>
    <div class="py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-6 sm:mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">Manajemen User</h1>
                        <p class="text-sm sm:text-base text-gray-600">Daftar seluruh pengguna yang terdaftar di sistem</p>
                    </div>
                    <a href="{{ route('admin.users.create') }}"
                       class="inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2 sm:py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg sm:rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all font-semibold text-sm sm:text-base shadow-md hover:shadow-lg active:scale-[0.98]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span>Tambah User</span>
                    </a>
                </div>
            </div>

            {{-- Alert Messages --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms
                     class="mb-6 p-4 bg-blue-50 text-blue-700 rounded-lg sm:rounded-xl border border-blue-200 text-sm sm:text-base">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="hover:bg-blue-100 rounded-full p-1 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6">
                @php
                    $totalUsers = \App\Models\User::count();
                    $adminUsers = \App\Models\User::where('role_id', 1)->count();
                    $tenantUsers = \App\Models\User::where('role_id', 2)->count();
                    $activeUsers = \App\Models\User::whereHas('resident', function($q) {
                        $q->where('status', 'active');
                    })->count();
                @endphp

                <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg sm:rounded-xl p-4 sm:p-5 text-white shadow-md">
                    <div class="flex items-center justify-between mb-2">
                        <i class="fas fa-users text-2xl sm:text-3xl opacity-20"></i>
                        <span class="text-xs sm:text-sm opacity-90">Total</span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-bold">{{ $totalUsers }}</div>
                    <p class="text-xs sm:text-sm opacity-90 mt-1">Semua User</p>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg sm:rounded-xl p-4 sm:p-5 text-white shadow-md">
                    <div class="flex items-center justify-between mb-2">
                        <i class="fas fa-user-shield text-2xl sm:text-3xl opacity-20"></i>
                        <span class="text-xs sm:text-sm opacity-90">Role</span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-bold">{{ $adminUsers }}</div>
                    <p class="text-xs sm:text-sm opacity-90 mt-1">Admin</p>
                </div>

                <div class="bg-gradient-to-br from-gray-700 to-gray-800 rounded-lg sm:rounded-xl p-4 sm:p-5 text-white shadow-md">
                    <div class="flex items-center justify-between mb-2">
                        <i class="fas fa-user text-2xl sm:text-3xl opacity-20"></i>
                        <span class="text-xs sm:text-sm opacity-90">Role</span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-bold">{{ $tenantUsers }}</div>
                    <p class="text-xs sm:text-sm opacity-90 mt-1">Tenant</p>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg sm:rounded-xl p-4 sm:p-5 text-white shadow-md">
                    <div class="flex items-center justify-between mb-2">
                        <i class="fas fa-user-check text-2xl sm:text-3xl opacity-20"></i>
                        <span class="text-xs sm:text-sm opacity-90">Status</span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-bold">{{ $activeUsers }}</div>
                    <p class="text-xs sm:text-sm opacity-90 mt-1">User Aktif</p>
                </div>
            </div>

            {{-- Users Table --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-md overflow-hidden">
                @if($users->count() > 0)
                    {{-- Mobile View --}}
                    <div class="block lg:hidden divide-y divide-gray-200">
                        @foreach($users as $user)
                            <div class="p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold flex-shrink-0">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h3 class="font-semibold text-gray-900 truncate">{{ $user->name }}</h3>
                                            <p class="text-xs text-gray-600 truncate">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <span class="px-2.5 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold whitespace-nowrap ml-2">
                                        {{ $user->role->name ?? 'Guest' }}
                                    </span>
                                </div>

                                <div class="flex gap-2 pt-3 border-t border-gray-100">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="flex-1 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-all text-xs font-semibold text-center">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus user ini?')" class="flex-1">
                                        @csrf @method('DELETE')
                                        <button class="w-full px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-all text-xs font-semibold">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Desktop View --}}
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Informasi User</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($users as $user)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-4">
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold shadow-sm flex-shrink-0">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="text-gray-900 font-semibold">{{ $user->name }}</div>
                                                    <div class="text-gray-600 text-sm">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                                {{ $user->role->name ?? 'Guest' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-2">
                                               <a href="{{ route('admin.users.edit', $user) }}"
                                                   class="p-2 text-gray-600 hover:bg-gray-50 rounded-lg transition-all"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                                                    @csrf @method('DELETE')
                                                    <button class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Hapus User">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
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
                    <div class="px-4 py-3 sm:px-6 border-t border-gray-200">
                        {{ $users->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-users text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-600 mb-4">Belum ada data user</p>
                        <a href="{{ route('admin.users.create') }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-semibold text-sm">
                            <i class="fas fa-plus"></i>
                            <span>Tambah User</span>
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
