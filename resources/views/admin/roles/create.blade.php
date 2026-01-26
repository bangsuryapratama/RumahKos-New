<x-app-layout>
<div class="max-w-md mx-auto py-10">

    <h1 class="text-xl font-bold mb-6">Tambah Role</h1>

    <form action="{{ route('admin.roles.store') }}" method="POST"
          class="bg-white p-6 rounded-xl shadow space-y-4">
        @csrf

        <input type="text"
               name="name"
               placeholder="Nama Role (admin, owner, penghuni)"
               class="w-full border rounded-lg p-2"
               required>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.roles.index') }}"
               class="px-4 py-2 rounded-lg border">
                Batal
            </a>
            <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white">
                Simpan
            </button>
        </div>
    </form>

</div>
</x-app-layout>
