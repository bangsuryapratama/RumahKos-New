<x-app-layout>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            
            {{-- Header --}}
            <div class="mb-6">
                <a href="{{ route('admin.rooms.index') }}" 
                   class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Daftar Kamar
                </a>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                    {{ isset($room) ? 'Edit Kamar' : 'Tambah Kamar Baru' }}
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    {{ isset($room) ? 'Perbarui informasi kamar' : 'Isi form di bawah untuk menambah kamar baru' }}
                </p>
            </div>

            {{-- Form Card --}}
            <form method="POST" 
                  enctype="multipart/form-data"
                  action="{{ isset($room) ? route('admin.rooms.update',$room) : route('admin.rooms.store') }}"
                  class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                
                @csrf
                @isset($room) @method('PUT') @endisset

                <div class="p-6 sm:p-8 space-y-6">
                    
                    {{-- Property Selection --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Property <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="property_id" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all appearance-none bg-white">
                                <option value="">Pilih Property</option>
                                @foreach($properties as $property)
                                    <option value="{{ $property->id }}"
                                        @selected(old('property_id', $room->property_id ?? '') == $property->id)>
                                        {{ $property->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                        @error('property_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Room Name --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Kamar <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               required
                               placeholder="Contoh: Kamar A1"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                               value="{{ old('name', $room->name ?? '') }}">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Floor & Size Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Lantai <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="floor" 
                                   required
                                   placeholder="Contoh: 2"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                   value="{{ old('floor', $room->floor ?? '') }}">
                            @error('floor')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Ukuran <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="size" 
                                   required
                                   placeholder="Contoh: 3x4m"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                   value="{{ old('size', $room->size ?? '') }}">
                            @error('size')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Price --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Harga <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                            <input type="text" 
                                   id="price_display"
                                   placeholder="1.500.000"
                                   class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                   value="{{ old('price', isset($room->price) ? number_format($room->price, 0, ',', '.') : '') }}"
                                   oninput="formatPrice(this)">
                            <input type="hidden" 
                                   name="price" 
                                   id="price_value"
                                   required
                                   value="{{ old('price', $room->price ?? '') }}">
                        </div>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status & Billing Cycle Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="status" 
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all appearance-none bg-white">
                                    <option value="available" @selected(old('status', $room->status ?? '') == 'available')>Tersedia</option>
                                    <option value="occupied" @selected(old('status', $room->status ?? '') == 'occupied')>Terisi</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Periode Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="billing_cycle" 
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all appearance-none bg-white">
                                    <option value="monthly" @selected(old('billing_cycle', $room->billing_cycle ?? '') == 'monthly')>Bulanan</option>
                                    <option value="yearly" @selected(old('billing_cycle', $room->billing_cycle ?? '') == 'yearly')>Tahunan</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                            @error('billing_cycle')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Image Upload --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Foto Kamar
                        </label>
                        
                        @isset($room->image)
                        <div class="mb-4">
                            <img src="{{ asset('storage/'.$room->image) }}" 
                                 class="w-full sm:w-64 h-48 object-cover rounded-lg border border-gray-200"
                                 alt="Current room image">
                            <p class="mt-2 text-sm text-gray-500">Foto saat ini (upload foto baru untuk menggantinya)</p>
                        </div>
                        @endisset
                        
                        <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-blue-400 transition-colors">
                            <input type="file" 
                                   name="image" 
                                   accept="image/*"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                   onchange="previewImage(event)">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="mt-2 text-sm text-gray-600">
                                    <span class="font-semibold text-blue-600">Klik untuk upload</span> atau drag & drop
                                </p>
                                <p class="text-xs text-gray-500 mt-1">PNG, JPG, JPEG maksimal 2MB</p>
                            </div>
                        </div>
                        <div id="imagePreview" class="mt-4 hidden">
                            <img id="previewImg" class="w-full sm:w-64 h-48 object-cover rounded-lg border border-gray-200" alt="Preview">
                        </div>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Form Actions --}}
                <div class="px-6 sm:px-8 py-4 bg-gray-50 border-t border-gray-200 flex flex-col-reverse sm:flex-row sm:justify-between gap-3">
                    <a href="{{ route('admin.rooms.index') }}"
                       class="inline-flex justify-center items-center px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex justify-center items-center px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ isset($room) ? 'Perbarui Kamar' : 'Simpan Kamar' }}
                    </button>
                </div>

            </form>

        </div>
    </div>

    <script>
        function formatPrice(input) {
            // Ambil nilai dan hapus semua karakter non-digit
            let value = input.value.replace(/\D/g, '');
            
            // Format dengan titik sebagai pemisah ribuan
            let formatted = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            
            // Update display input
            input.value = formatted;
            
            // Update hidden input dengan nilai asli (tanpa format)
            document.getElementById('price_value').value = value;
        }

        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').classList.remove('hidden');
                    document.getElementById('previewImg').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>