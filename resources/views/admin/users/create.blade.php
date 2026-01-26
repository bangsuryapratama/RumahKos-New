<x-app-layout>
<div class="max-w-xl mx-auto py-10">

    <h1 class="text-xl font-bold mb-6">Tambah User</h1>

    <form action="{{ route('admin.users.store') }}" method="POST"
          class="bg-white p-6 rounded-xl shadow space-y-4">
        @csrf

        <input type="text" name="name" placeholder="Nama"
               class="w-full border rounded-lg p-2" required>

        <input type="email" name="email" placeholder="Email"
               class="w-full border rounded-lg p-2" required>

        <select name="role_id" class="w-full border rounded-lg p-2">
            @foreach($roles as $role)
                <option value="{{ $role->id }}">{{ $role->name }}</option>
            @endforeach
        </select>

        <input type="password" name="password" placeholder="Password"
               class="w-full border rounded-lg p-2" required>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.users.index') }}"
               class="px-4 py-2 rounded-lg border">Batal</a>
            <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white">
                Simpan
            </button>
        </div>
    </form>

</div>
</x-app-layout>
