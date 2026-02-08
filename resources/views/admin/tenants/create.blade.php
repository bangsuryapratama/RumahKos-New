<x-app-layout>
<div class="py-6 sm:py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6 sm:mb-8">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.tenants.index') }}" 
                   class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Tambah Penghuni Baru</h1>
            </div>
            <p class="text-sm sm:text-base text-gray-600">Daftarkan penghuni baru dan assign kamar</p>
        </div>

        {{-- Alert Messages --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-lg sm:rounded-xl border border-green-200 text-sm sm:text-base">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-lg sm:rounded-xl border border-red-200 text-sm sm:text-base">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('admin.tenants.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Account Information --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-user-lock text-blue-600"></i>
                    Informasi Akun
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Nama Lengkap <span class="text-red-600">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Email <span class="text-red-600">*</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Password <span class="text-red-600">*</span>
                        </label>
                        <input type="password" 
                               name="password" 
                               required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Konfirmasi Password <span class="text-red-600">*</span>
                        </label>
                        <input type="password" 
                               name="password_confirmation" 
                               required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            {{-- Personal Information --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-id-card text-green-600"></i>
                    Data Pribadi
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">No. Telepon</label>
                        <input type="tel" 
                               name="phone" 
                               value="{{ old('phone') }}"
                               placeholder="08xxxxxxxxxx"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('phone')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">No. KTP</label>
                        <input type="text" 
                               name="identity_number" 
                               value="{{ old('identity_number') }}"
                               placeholder="16 digit" 
                               maxlength="16"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('identity_number')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Lahir</label>
                        <input type="date" 
                               name="date_of_birth" 
                               value="{{ old('date_of_birth') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('date_of_birth')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Kelamin</label>
                        <select name="gender" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Asal</label>
                        <textarea name="address" 
                                  rows="3"
                                  placeholder="Masukkan alamat lengkap"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Pekerjaan</label>
                        <input type="text" 
                               name="occupation" 
                               value="{{ old('occupation') }}"
                               placeholder="Mahasiswa / Karyawan"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('occupation')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Emergency Contact --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-phone-alt text-red-600"></i>
                    Kontak Darurat
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Kontak</label>
                        <input type="text" 
                               name="emergency_contact_name" 
                               value="{{ old('emergency_contact_name') }}"
                               placeholder="Nama keluarga"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('emergency_contact_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">No. Telepon Darurat</label>
                        <input type="tel" 
                               name="emergency_contact" 
                               value="{{ old('emergency_contact') }}"
                               placeholder="08xxx"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('emergency_contact')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Documents --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2 flex items-center gap-2">
                    <i class="fas fa-file-alt text-purple-600"></i>
                    Dokumen Identitas
                </h3>
                <p class="text-sm text-gray-600 mb-4">Upload foto/scan dokumen dengan jelas. Format: JPG, PNG, PDF (Max 2MB)</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    {{-- KTP --}}
                    <div class="border-2 border-blue-200 rounded-lg p-4 bg-blue-50">
                        <div class="mb-3">
                            <h4 class="font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-id-card text-blue-600"></i>
                                KTP <span class="text-xs text-gray-600">(Recommended)</span>
                            </h4>
                            <p class="text-xs text-gray-600">Kartu Tanda Penduduk</p>
                        </div>

                        <input type="file" 
                               name="ktp_photo" 
                               accept="image/*,application/pdf"
                               onchange="validateFile(this, 'ktp-preview')"
                               class="w-full text-sm border rounded file:mr-3 file:py-2 file:px-3 file:rounded file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                        <div id="ktp-preview" class="mt-2"></div>
                        @error('ktp_photo')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror

                        <div class="mt-3 p-2 bg-white rounded text-xs">
                            <p class="font-medium text-gray-700 mb-1">Tips:</p>
                            <ul class="text-gray-600 space-y-0.5">
                                <li>• Foto jelas & tidak blur</li>
                                <li>• Semua teks terlihat</li>
                                <li>• Tidak ada pantulan cahaya</li>
                            </ul>
                        </div>
                    </div>

                    {{-- SIM or Passport --}}
                    <div class="border-2 border-green-200 rounded-lg p-4 bg-green-50">
                        <div class="mb-3">
                            <h4 class="font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-id-card-alt text-green-600"></i>
                                SIM atau Passport <span class="text-xs text-gray-600">(Opsional)</span>
                            </h4>
                            <p class="text-xs text-gray-600">Pilih salah satu</p>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">SIM</label>
                                <input type="file" 
                                       name="sim_photo" 
                                       accept="image/*,application/pdf"
                                       onchange="validateFile(this, 'sim-preview')"
                                       class="w-full text-xs border rounded file:mr-2 file:py-1.5 file:px-2 file:rounded file:border-0 file:bg-gray-100 file:text-gray-700">
                                <div id="sim-preview" class="mt-2"></div>
                            </div>

                            <div class="text-center text-xs text-gray-500">ATAU</div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Passport</label>
                                <input type="file" 
                                       name="passport_photo" 
                                       accept="image/*,application/pdf"
                                       onchange="validateFile(this, 'passport-preview')"
                                       class="w-full text-xs border rounded file:mr-2 file:py-1.5 file:px-2 file:rounded file:border-0 file:bg-gray-100 file:text-gray-700">
                                <div id="passport-preview" class="mt-2"></div>
                            </div>
                        </div>

                        @error('sim_photo')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        @error('passport_photo')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Room Assignment (Optional) --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2 flex items-center gap-2">
                    <i class="fas fa-home text-orange-600"></i>
                    Assign Kamar <span class="text-xs text-gray-600 font-normal">(Opsional)</span>
                </h3>
                <p class="text-sm text-gray-600 mb-4">Langsung assign kamar untuk penghuni ini, atau skip untuk assign nanti</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Kamar</label>
                        <select name="room_id" 
                                id="room_select"
                                onchange="toggleRoomDetails()"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Tidak Assign Kamar Dulu --</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" 
                                        data-price="{{ $room->price }}"
                                        data-property="{{ $room->property->name }}"
                                        {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                    {{ $room->name }} - {{ $room->property->name }} (Rp {{ number_format($room->price, 0, ',', '.') }}/bulan)
                                </option>
                            @endforeach
                        </select>
                        @error('room_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="room_details" class="md:col-span-3 hidden">
                        <div class="bg-gradient-to-br from-orange-50 to-yellow-50 rounded-lg p-4 border-2 border-orange-200">
                            <h4 class="font-semibold text-gray-900 mb-3">Detail Booking</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Tanggal Mulai <span class="text-red-600">*</span>
                                    </label>
                                    <input type="date" 
                                           name="start_date" 
                                           value="{{ old('start_date') }}"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @error('start_date')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Durasi (Bulan) <span class="text-red-600">*</span>
                                    </label>
                                    <select name="duration_months" 
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Pilih Durasi</option>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ old('duration_months') == $i ? 'selected' : '' }}>
                                                {{ $i }} Bulan
                                            </option>
                                        @endfor
                                    </select>
                                    @error('duration_months')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-3 p-3 bg-blue-50 rounded border border-blue-200">
                                <p class="text-xs text-blue-800">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    <strong>Info:</strong> Sistem akan otomatis membuat tagihan pembayaran bulanan sesuai durasi yang dipilih. 
                                    Status booking akan "Inactive" sampai pembayaran pertama dikonfirmasi.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-6">
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('admin.tenants.index') }}"
                       class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all font-semibold text-center">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit"
                            class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-semibold shadow-md">
                        <i class="fas fa-save mr-2"></i>Simpan Penghuni
                    </button>
                </div>
            </div>

        </form>

    </div>
</div>

<script>
function validateFile(input, previewId) {
    const preview = document.getElementById(previewId);
    preview.innerHTML = '';

    if (!input.files || !input.files[0]) return;

    const file = input.files[0];
    const maxSize = 2 * 1024 * 1024; // 2MB
    const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];

    // Validate type
    if (!validTypes.includes(file.type)) {
        preview.innerHTML = '<p class="text-xs text-red-600 mt-1">❌ Format tidak valid. Gunakan: JPG, PNG, atau PDF</p>';
        input.value = '';
        return;
    }

    // Validate size
    if (file.size > maxSize) {
        const sizeMB = (file.size / 1024 / 1024).toFixed(2);
        preview.innerHTML = `<p class="text-xs text-red-600 mt-1">❌ Ukuran ${sizeMB}MB terlalu besar. Max: 2MB</p>`;
        input.value = '';
        return;
    }

    // Show preview
    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <div class="mt-2 p-2 bg-green-50 border border-green-200 rounded">
                    <p class="text-xs text-green-700 mb-1">✓ File valid</p>
                    <img src="${e.target.result}" class="max-w-full h-24 object-contain rounded border">
                    <p class="text-xs text-gray-600 mt-1">${file.name} (${(file.size / 1024).toFixed(1)} KB)</p>
                </div>
            `;
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = `
            <div class="mt-2 p-2 bg-green-50 border border-green-200 rounded">
                <p class="text-xs text-green-700">✓ PDF valid: ${file.name} (${(file.size / 1024).toFixed(1)} KB)</p>
            </div>
        `;
    }
}

function toggleRoomDetails() {
    const roomSelect = document.getElementById('room_select');
    const roomDetails = document.getElementById('room_details');
    
    if (roomSelect.value) {
        roomDetails.classList.remove('hidden');
    } else {
        roomDetails.classList.add('hidden');
    }
}

// Check on page load if room is selected (for old input)
document.addEventListener('DOMContentLoaded', function() {
    toggleRoomDetails();
});
</script>

</x-app-layout>