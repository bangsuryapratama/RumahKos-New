<x-app-layout>
<div class="max-w-5xl mx-auto py-6 sm:py-10 px-4">

    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Tambah Sosmed</h1>
        <p class="text-sm sm:text-base text-gray-600">Tambahkan Sosmed baru ke sistem</p>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-lg sm:rounded-xl border border-green-200 text-sm sm:text-base">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-lg sm:rounded-xl border border-red-200 text-sm sm:text-base">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <strong>Terdapat kesalahan:</strong>
            <ul class="mt-2 ml-4 list-disc text-xs sm:text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.socialmedia.store') }}"
          class="bg-white p-5 sm:p-6 md:p-8 rounded-xl sm:rounded-2xl shadow-md sm:shadow-lg space-y-5 sm:space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
            <div>
                <label class="block mb-2 text-sm font-semibold text-gray-700">
                    Instagram <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="instagram"
                       value="{{ old('instagram') }}"
                       class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2 sm:py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm sm:text-base @error('instagram') border-red-500 @enderror"
                       placeholder="@rumahkosofficial"
                       required>
                @error('instagram')
                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
            <div>
                <label class="block mb-2 text-sm font-semibold text-gray-700">
                    Facebook <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="facebook"
                       value="{{ old('facebook') }}"
                       class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2 sm:py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm sm:text-base @error('facebook') border-red-500 @enderror"
                       placeholder="@rumahkosofficial"
                       required>
                @error('facebook')
                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
            <div>
                <label class="block mb-2 text-sm font-semibold text-gray-700">
                    Tiktok <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="tiktok"
                       value="{{ old('tiktok') }}"
                       class="w-full border border-gray-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2 sm:py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm sm:text-base @error('tiktok') border-red-500 @enderror"
                       placeholder="@rumahkosofficial"
                       required>
                @error('tiktok')
                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

          
        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 sm:pt-6 border-t border-gray-200">
            <a href="{{ route('admin.socialmedia.index') }}"
               class="w-full sm:w-auto px-5 sm:px-6 py-2 sm:py-2.5 border-2 border-gray-300 text-gray-700 text-center rounded-lg sm:rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all font-semibold text-sm sm:text-base active:scale-[0.98]">
                <i class="fas fa-times mr-2"></i>Batal
            </a>

            <button type="submit"
                class="w-full sm:w-auto px-5 sm:px-6 py-2 sm:py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg sm:rounded-xl font-semibold text-sm sm:text-base transition-all shadow-md hover:shadow-lg active:scale-[0.98]">
                <i class="fas fa-save mr-2"></i>Simpan Sosmed
            </button>
        </div>

    </form>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</x-app-layout>
