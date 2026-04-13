<x-app-layout>
<div class="py-6 sm:py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6 sm:mb-8">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.tenants.show', $tenant) }}" 
                   class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Penghuni</h1>
            </div>
            <p class="text-sm sm:text-base text-gray-600">Update data penghuni</p>
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
        <form method="POST" action="{{ route('admin.tenants.update', $tenant) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

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
                               value="{{ old('name', $tenant->name) }}" 
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
                               value="{{ old('email', $tenant->email) }}" 
                               required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Password Baru <span class="text-xs text-gray-500">(Kosongkan jika tidak ingin mengubah)</span>
                        </label>
                        <input type="password" 
                               name="password" 
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Konfirmasi Password
                        </label>
                        <input type="password" 
                               name="password_confirmation" 
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
                               value="{{ old('phone', $tenant->profile->phone ?? '') }}"
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
                               value="{{ old('identity_number', $tenant->profile->identity_number ?? '') }}"
                               placeholder="16 digit" 
                               maxlength="16"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('identity_number')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Tanggal Lahir
                                <span class="text-xs font-normal text-gray-400">(min. 17 tahun)</span>
                            </label>
                            <input type="date" name="date_of_birth"
                                    value="{{ old('date_of_birth', $tenant->profile?->date_of_birth?->format('Y-m-d') ?? '') }}"
                                    max="{{ \Carbon\Carbon::now()->subYears(17)->format('Y-m-d') }}"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm @error('date_of_birth') border-red-400 @enderror">
                            @error('date_of_birth')
                                <p class="mt-1 text-xs text-red-600"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Kelamin</label>
                        <select name="gender" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih</option>
                            <option value="male" {{ old('gender', $tenant->profile->gender ?? '') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="female" {{ old('gender', $tenant->profile->gender ?? '') == 'female' ? 'selected' : '' }}>Perempuan</option>
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
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('address', $tenant->profile->address ?? '') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Pekerjaan</label>
                        <input type="text" 
                               name="occupation" 
                               value="{{ old('occupation', $tenant->profile->occupation ?? '') }}"
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
                               value="{{ old('emergency_contact_name', $tenant->profile->emergency_contact_name ?? '') }}"
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
                               value="{{ old('emergency_contact', $tenant->profile->emergency_contact ?? '') }}"
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
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h4 class="font-semibold text-gray-900 flex items-center gap-2">
                                    <i class="fas fa-id-card text-blue-600"></i>
                                    KTP
                                </h4>
                                <p class="text-xs text-gray-600">Kartu Tanda Penduduk</p>
                            </div>
                            @if($tenant->profile && $tenant->profile->ktp_photo)
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">
                                    <i class="fas fa-check-circle"></i> Ada
                                </span>
                            @endif
                        </div>

                        @if($tenant->profile && $tenant->profile->ktp_photo)
                            <div class="mb-3 p-3 bg-white rounded border">
                                @php
                                    $ktpExtension = strtolower(pathinfo($tenant->profile->ktp_photo, PATHINFO_EXTENSION));
                                @endphp
                                
                                @if(in_array($ktpExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                    <img src="{{ asset('storage/' . $tenant->profile->ktp_photo) }}" 
                                         alt="KTP" 
                                         class="w-full h-32 object-contain mb-2">
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-file-pdf text-4xl text-red-600 mb-2"></i>
                                        <p class="text-xs text-gray-600">Dokumen PDF</p>
                                    </div>
                                @endif
                                
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-700 truncate">{{ basename($tenant->profile->ktp_photo) }}</span>
                                    <a href="{{ asset('storage/' . $tenant->profile->ktp_photo) }}" 
                                       target="_blank"
                                       class="px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 ml-2">
                                        Lihat
                                    </a>
                                </div>
                                
                                <label class="flex items-center gap-2 mt-3 cursor-pointer">
                                    <input type="checkbox" name="delete_ktp" value="1" class="rounded">
                                    <span class="text-red-600 text-xs">Hapus & ganti dengan file baru</span>
                                </label>
                            </div>
                        @endif

                        <input type="file" 
                               name="ktp_photo" 
                               accept="image/*,application/pdf"
                               onchange="validateFile(this, 'ktp-preview')"
                               class="w-full text-sm border rounded file:mr-3 file:py-2 file:px-3 file:rounded file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                        <div id="ktp-preview" class="mt-2"></div>
                        @error('ktp_photo')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- SIM or Passport --}}
                    <div class="border-2 border-green-200 rounded-lg p-4 bg-green-50">
                        <div class="mb-3">
                            <h4 class="font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-id-card-alt text-green-600"></i>
                                SIM atau Passport
                            </h4>
                            <p class="text-xs text-gray-600">Pilih salah satu</p>
                        </div>

                        @php
                            $simUploaded = $tenant->profile && $tenant->profile->sim_photo;
                            $passportUploaded = $tenant->profile && $tenant->profile->passport_photo;
                        @endphp

                        @if($simUploaded || $passportUploaded)
                            <div class="mb-3 p-3 bg-white rounded border">
                                @php
                                    $docPath = $simUploaded ? $tenant->profile->sim_photo : $tenant->profile->passport_photo;
                                    $docExtension = strtolower(pathinfo($docPath, PATHINFO_EXTENSION));
                                @endphp
                                
                                @if(in_array($docExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                    <img src="{{ asset('storage/' . $docPath) }}" 
                                         alt="{{ $simUploaded ? 'SIM' : 'Passport' }}" 
                                         class="w-full h-32 object-contain mb-2">
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-file-pdf text-4xl text-red-600 mb-2"></i>
                                        <p class="text-xs text-gray-600">Dokumen PDF</p>
                                    </div>
                                @endif
                                
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-700 truncate">{{ $simUploaded ? 'SIM' : 'Passport' }}: {{ basename($docPath) }}</span>
                                    <a href="{{ asset('storage/' . $docPath) }}" 
                                       target="_blank"
                                       class="px-2 py-1 bg-green-600 text-white rounded hover:bg-green-700 ml-2">
                                        Lihat
                                    </a>
                                </div>
                                
                                <label class="flex items-center gap-2 mt-3 cursor-pointer">
                                    <input type="checkbox" 
                                           name="{{ $simUploaded ? 'delete_sim' : 'delete_passport' }}" 
                                           value="1" 
                                           class="rounded">
                                    <span class="text-red-600 text-xs">Hapus & ganti dengan file baru</span>
                                </label>
                            </div>
                        @endif

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

            {{-- Action Buttons --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-6">
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('admin.tenants.show', $tenant) }}"
                       class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all font-semibold text-center">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit"
                            class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-semibold shadow-md">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
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
</script>

</x-app-layout>