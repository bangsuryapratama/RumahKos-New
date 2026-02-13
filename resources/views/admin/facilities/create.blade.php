<x-app-layout>
<div class="py-6 sm:py-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6 sm:mb-8">
            <a href="{{ route('admin.facilities.index') }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-900 transition-colors mb-4">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Master Fasilitas
            </a>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">Tambah Fasilitas Baru</h1>
            <p class="text-sm sm:text-base text-gray-600">Ketik nama fasilitas lalu gunakan AI Search untuk icon terbaik!</p>
        </div>

        {{-- Alert --}}
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-lg sm:rounded-xl border border-red-200 text-sm sm:text-base">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.facilities.store') }}" class="space-y-5">
            @csrf

            {{-- Nama Fasilitas --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-5 sm:p-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Fasilitas <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="name"
                       id="facilityName"
                       required
                       placeholder="Contoh: WiFi, AC, TV, Lemari, Kamar Mandi Dalam..."
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                       value="{{ old('name') }}">
                @error('name')
                    <p class="mt-1.5 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>

            {{-- Icon Picker --}}
            @php $selectedIcon = old('icon', ''); $pickerOpen = true; @endphp
            @include('admin.facilities._icon_picker')

            {{-- Form Actions --}}
            <div class="flex flex-col-reverse sm:flex-row sm:justify-between gap-3 pt-2">
                <a href="{{ route('admin.facilities.index') }}"
                   class="inline-flex justify-center items-center gap-2 px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border-2 border-gray-300 rounded-lg sm:rounded-xl hover:bg-gray-50 transition-all active:scale-[0.98]">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit"
                        class="inline-flex justify-center items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg sm:rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all shadow-md hover:shadow-lg active:scale-[0.98]">
                    <i class="fas fa-check"></i> Simpan Fasilitas
                </button>
            </div>

        </form>
    </div>
</div>
</x-app-layout>