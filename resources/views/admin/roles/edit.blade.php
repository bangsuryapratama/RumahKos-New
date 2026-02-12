<x-app-layout>
    <div class="py-6 sm:py-10">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-6 sm:mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('admin.roles.index') }}"
                       class="p-2 hover:bg-gray-100 rounded-lg transition-all">
                        <i class="fas fa-arrow-left text-gray-600"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Role</h1>
                        <p class="text-sm sm:text-base text-gray-600 mt-1">Perbarui informasi role</p>
                    </div>
                </div>
            </div>

            {{-- Alert Messages --}}
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-lg sm:rounded-xl border border-red-200">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5 mr-3"></i>
                        <div class="flex-1">
                            <p class="font-semibold mb-1">Terdapat kesalahan:</p>
                            <ul class="list-disc list-inside space-y-1 text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Form --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-md overflow-hidden">
                <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="p-6 sm:p-8">
                        {{-- Role Name --}}
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Role <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user-shield text-gray-400"></i>
                                </div>
                                <input type="text"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', $role->name) }}"
                                       placeholder="Contoh: Admin, Manager, Staff"
                                       class="w-full pl-10 pr-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base @error('name') border-red-500 @enderror"
                                       required>
                            </div>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Footer Actions --}}
                    <div class="bg-gray-50 px-6 sm:px-8 py-4 sm:py-5 border-t border-gray-200 flex flex-col sm:flex-row gap-3 justify-end">
                        <a href="{{ route('admin.roles.index') }}"
                           class="w-full sm:w-auto px-5 py-2.5 sm:py-3 border-2 border-gray-300 text-gray-700 rounded-lg sm:rounded-xl font-semibold hover:bg-gray-50 transition-all text-sm sm:text-base text-center">
                            <i class="fas fa-times mr-2"></i>Batal
                        </a>
                        <button type="submit"
                                class="w-full sm:w-auto px-6 py-2.5 sm:py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg sm:rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 transition-all shadow-md hover:shadow-lg text-sm sm:text-base active:scale-[0.98]">
                            <i class="fas fa-save mr-2"></i>Update Role
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
