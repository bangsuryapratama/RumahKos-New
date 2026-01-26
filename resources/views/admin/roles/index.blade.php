<x-app-layout>
<div class="max-w-5xl mx-auto py-10">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manajemen Role</h1>
        <a href="{{ route('admin.roles.create') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
            + Tambah Role
        </a>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="mb-4 bg-green-100 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-100 text-red-800 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="p-3 text-left">Nama Role</th>
                    <th class="p-3 text-center">Jumlah User</th>
                    <th class="p-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3 font-medium">{{ $role->name }}</td>
                    <td class="p-3 text-center">
                        <span class="px-3 py-1 rounded-full text-xs bg-indigo-100 text-indigo-700">
                            {{ $role->users_count }} user
                        </span>
                    </td>
                    <td class="p-3 text-right space-x-2">
                        <a href="{{ route('admin.roles.edit', $role) }}"
                           class="text-blue-600 hover:underline">
                            Edit
                        </a>

                        <form action="{{ route('admin.roles.destroy', $role) }}"
                              method="POST"
                              class="inline"
                              onsubmit="return confirm('Hapus role ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $roles->links() }}
    </div>

</div>
</x-app-layout>
