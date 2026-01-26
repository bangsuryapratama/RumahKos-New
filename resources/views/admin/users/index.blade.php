<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        
        {{-- HEADER SECTION --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Manajemen User</h1>
                <p class="text-sm text-gray-500 mt-1">Daftar seluruh pengguna yang terdaftar di sistem.</p>
            </div>

            <a href="{{ route('admin.users.create') }}"
               class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition-all active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Tambah User
            </a>
        </div>

        {{-- ALERT DENGAN ANIMASI --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms 
                 class="mb-6 flex items-center justify-between bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="hover:bg-emerald-100 rounded-full p-1 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        {{-- DATA CONTAINER --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            {{-- TABLE UNTUK DESKTOP (MD+) --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50/50 text-gray-500 uppercase text-[11px] font-bold tracking-widest border-b">
                        <tr>
                            <th class="px-6 py-4">Informasi User</th>
                            <th class="px-6 py-4 text-center">Role</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
                        <tr class="hover:bg-indigo-50/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold shadow-sm">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-gray-900 font-semibold">{{ $user->name }}</div>
                                        <div class="text-gray-500 text-xs">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-indigo-100 text-indigo-700">
                                    {{ $user->role->name ?? 'Guest' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-3">
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-all title="Edit User">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                                        @csrf @method('DELETE')
                                        <button class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Hapus User">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-6 py-10 text-center text-gray-400">Belum ada data user.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- MOBILE VIEW (CARDS) --}}
            <div class="md:hidden divide-y divide-gray-100">
                @foreach($users as $user)
                <div class="p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-indigo-100 text-indigo-700">
                            {{ $user->role->name ?? 'Guest' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-end gap-4 pt-2 border-t border-gray-50">
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-sm font-semibold text-blue-600">Edit</a>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="text-sm font-semibold text-red-500">Hapus</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-8">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>