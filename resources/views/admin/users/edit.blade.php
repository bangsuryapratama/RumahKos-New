<x-app-layout>
<div class="max-w-xl mx-auto py-10">

    <h1 class="text-xl font-bold mb-6">Edit User</h1>

    <form action="{{ route('admin.users.update', $user) }}" method="POST"
          class="bg-white p-6 rounded-xl shadow space-y-4">
        @csrf
        @method('PUT')

        <input type="text" name="name"
               value="{{ $user->name }}"
               class="w-full border rounded-lg p-2">

        <input type="email" name="email"
               value="{{ $user->email }}"
               class="w-full border rounded-lg p-2">

        <select name="role_id" class="w-full border rounded-lg p-2">
            @foreach($roles as $role)
                <option value="{{ $role->id }}"
                    @selected($user->role_id == $role->id)>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>

        <input type="password" name="password"
               placeholder="Password baru (opsional)"
               class="w-full border rounded-lg p-2">

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.users.index') }}"
               class="px-4 py-2 rounded-lg border">Batal</a>
            <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white">
                Update
            </button>
        </div>
    </form>

</div>
</x-app-layout>
