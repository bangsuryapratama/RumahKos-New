<x-app-layout>
    <div class="py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-6 sm:mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">Manajemen Role</h1>
                        <p class="text-sm sm:text-base text-gray-600">Kelola peran dan hak akses pengguna</p>
                    </div>
                    <a href="{{ route('admin.roles.create') }}"
                       class="inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2 sm:py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg sm:rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all font-semibold text-sm sm:text-base shadow-md hover:shadow-lg active:scale-[0.98]">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Role</span>
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

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms
                     class="mb-6 p-4 bg-red-50 text-red-700 rounded-lg sm:rounded-xl border border-red-200 text-sm sm:text-base">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="hover:bg-red-100 rounded-full p-1 transition">
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
                    $totalRoles = \App\Models\Role::count();
                    $adminRole = \App\Models\User::where('role_id', 1)->count();
                    $tenantRole = \App\Models\User::where('role_id', 2)->count();
                    $activeRoles = \App\Models\Role::whereHas('users')->count();
                @endphp

                <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg sm:rounded-xl p-4 sm:p-5 text-white shadow-md">
                    <div class="flex items-center justify-between mb-2">
                        <i class="fas fa-user-shield text-2xl sm:text-3xl opacity-20"></i>
                        <span class="text-xs sm:text-sm opacity-90">Total</span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-bold">{{ $totalRoles }}</div>
                    <p class="text-xs sm:text-sm opacity-90 mt-1">Role</p>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg sm:rounded-xl p-4 sm:p-5 text-white shadow-md">
                    <div class="flex items-center justify-between mb-2">
                        <i class="fas fa-user-tie text-2xl sm:text-3xl opacity-20"></i>
                        <span class="text-xs sm:text-sm opacity-90">Admin</span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-bold">{{ $adminRole }}</div>
                    <p class="text-xs sm:text-sm opacity-90 mt-1">User</p>
                </div>

                <div class="bg-gradient-to-br from-gray-700 to-gray-800 rounded-lg sm:rounded-xl p-4 sm:p-5 text-white shadow-md">
                    <div class="flex items-center justify-between mb-2">
                        <i class="fas fa-users text-2xl sm:text-3xl opacity-20"></i>
                        <span class="text-xs sm:text-sm opacity-90">Tenant</span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-bold">{{ $tenantRole }}</div>
                    <p class="text-xs sm:text-sm opacity-90 mt-1">User</p>
                </div>

                <div class="bg-gradient-to-br from-blue-400 to-blue-500 rounded-lg sm:rounded-xl p-4 sm:p-5 text-white shadow-md">
                    <div class="flex items-center justify-between mb-2">
                        <i class="fas fa-check-circle text-2xl sm:text-3xl opacity-20"></i>
                        <span class="text-xs sm:text-sm opacity-90">Status</span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-bold">{{ $activeRoles }}</div>
                    <p class="text-xs sm:text-sm opacity-90 mt-1">Role Aktif</p>
                </div>
            </div>

            {{-- Roles Table --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-md overflow-hidden">
                @if($roles->count() > 0)
                    {{-- Mobile View --}}
                    <div class="block lg:hidden divide-y divide-gray-200">
                        @foreach($roles as $role)
                            <div class="p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white flex-shrink-0">
                                            <i class="fas fa-user-shield text-sm"></i>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h3 class="font-semibold text-gray-900 truncate">{{ $role->name }}</h3>
                                        </div>
                                    </div>
                                    <span class="px-2.5 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold whitespace-nowrap ml-2">
                                        {{ $role->users->count() }} User
                                    </span>
                                </div>

                                @if($role->permissions && $role->permissions->count() > 0)
                                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-3 mb-3 text-xs border border-blue-200">
                                        <div class="flex items-start gap-2">
                                            <i class="fas fa-key text-blue-600 mt-0.5"></i>
                                            <div class="flex-1">
                                                <p class="font-medium text-gray-900 mb-1">Permissions:</p>
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($role->permissions->take(3) as $permission)
                                                        <span class="px-2 py-0.5 bg-white text-blue-700 rounded text-[10px] font-medium border border-blue-200">
                                                            {{ $permission->name }}
                                                        </span>
                                                    @endforeach
                                                    @if($role->permissions->count() > 3)
                                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-[10px] font-medium">
                                                            +{{ $role->permissions->count() - 3 }} lainnya
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-gray-50 rounded-lg p-3 mb-3 text-xs text-center border border-gray-200">
                                        <i class="fas fa-key text-gray-400 mr-1"></i>
                                        <span class="text-gray-600">Belum ada permission</span>
                                    </div>
                                @endif

                                <div class="flex gap-2 pt-3 border-t border-gray-100">
                                    <a href="{{ route('admin.roles.show', $role) }}"
                                       class="flex-1 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-all text-xs font-semibold text-center border border-blue-200">
                                        <i class="fas fa-eye mr-1"></i>Detail
                                    </a>
                                    <a href="{{ route('admin.roles.edit', $role) }}"
                                       class="flex-1 px-3 py-1.5 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition-all text-xs font-semibold text-center border border-gray-200">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>
                                    @if($role->users->count() === 0)
                                        <button onclick="confirmDelete({{ $role->id }})"
                                                class="flex-1 px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-all text-xs font-semibold text-center border border-red-200">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    @else
                                        <button disabled
                                                class="flex-1 px-3 py-1.5 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed text-xs font-semibold text-center border border-gray-200"
                                                title="Tidak dapat dihapus, masih memiliki user">
                                            <i class="fas fa-lock mr-1"></i>Terkunci
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Desktop View --}}
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Total User</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($roles as $role)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white flex-shrink-0">
                                                    <i class="fas fa-user-shield"></i>
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-gray-900">{{ $role->name }}</div>
                                                    <div class="text-xs text-gray-500">ID: {{ $role->id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                                {{ $role->users->count() }} User
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-2">
                                                {{-- <a href="{{ route('admin.roles.show', $role) }}"
                                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                                   title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a> --}}
                                                <a href="{{ route('admin.roles.edit', $role) }}"
                                                   class="p-2 text-gray-600 hover:bg-gray-50 rounded-lg transition-all"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($role->users->count() === 0)
                                                    <button onclick="confirmDelete({{ $role->id }})"
                                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                                            title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @else
                                                    <button disabled
                                                            class="p-2 text-gray-400 cursor-not-allowed rounded-lg"
                                                            title="Tidak dapat dihapus, masih memiliki {{ $role->users->count() }} user">
                                                        <i class="fas fa-lock"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="px-4 py-3 sm:px-6 border-t border-gray-200">
                        {{ $roles->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-user-shield text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-600 mb-4">Belum ada data role</p>
                        <a href="{{ route('admin.roles.create') }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-semibold text-sm">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Role</span>
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl sm:rounded-2xl p-6 sm:p-8 max-w-md mx-4 shadow-2xl">
            <div class="text-center">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-3xl sm:text-4xl text-red-600"></i>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">Hapus Role?</h3>
                <p class="text-sm sm:text-base text-gray-600 mb-6">Tindakan ini akan menghapus role dan tidak dapat dikembalikan. Apakah Anda yakin?</p>
                <form id="deleteForm" method="POST" class="flex flex-col sm:flex-row gap-3">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                            onclick="closeDeleteModal()"
                            class="flex-1 px-4 sm:px-6 py-2.5 sm:py-3 border-2 border-gray-300 text-gray-700 rounded-lg sm:rounded-xl font-semibold hover:bg-gray-50 transition-all text-sm sm:text-base">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 sm:px-6 py-2.5 sm:py-3 bg-red-600 text-white rounded-lg sm:rounded-xl font-semibold hover:bg-red-700 transition-all shadow-md text-sm sm:text-base">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
    function confirmDelete(roleId) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        form.action = `/admin/roles/${roleId}`;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Close modal on ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });
    </script>
</x-app-layout>
