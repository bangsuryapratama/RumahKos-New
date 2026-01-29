<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - RumahKos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">

    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <a href="/" class="text-2xl font-extrabold text-gray-900">
                        Rumah<span class="text-blue-600">Kos</span>
                    </a>
                    <span class="hidden sm:block text-gray-400">|</span>
                    <span class="hidden sm:block text-gray-600 font-medium">Dashboard Penghuni</span>
                </div>

                <div class="flex items-center gap-4">
                    <a href="/" class="text-gray-600 hover:text-blue-600 transition">
                        <i class="fas fa-home mr-2"></i>
                        <span class="hidden sm:inline">Beranda</span>
                    </a>

                    <div class="relative group">
                        <button class="flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition">
                            @if($user->avatar)
                                <img src="{{ $user->avatar }}" class="w-8 h-8 rounded-full" alt="{{ $user->name }}">
                            @else
                                <i class="fas fa-user-circle text-xl text-blue-600"></i>
                            @endif
                            <span class="hidden sm:inline font-medium">{{ $user->name }}</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>

                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                            <form method="POST" action="{{ route('tenant.logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-3 text-red-600 hover:bg-red-50 transition rounded-xl">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                Selamat Datang, {{ $user->name }}!
            </h1>
            <p class="text-gray-600">Kelola data kos dan keuangan Anda di sini</p>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl border border-green-200">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-xl border border-red-200">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        <!-- Profile Completion Alert -->
        @php
            $profileComplete = $user->profile &&
                              $user->profile->phone &&
                              $user->profile->identity_number &&
                              $user->profile->ktp_photo;
            $completionPercent = 0;
            if ($user->profile) {
                $fields = ['phone', 'identity_number', 'address', 'date_of_birth', 'gender', 'occupation', 'emergency_contact', 'ktp_photo'];
                $filled = 0;
                foreach ($fields as $field) {
                    if ($user->profile->$field) $filled++;
                }
                $completionPercent = round(($filled / count($fields)) * 100);
            }
        @endphp

        @if(!$profileComplete)
            <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-sm font-medium text-yellow-800">
                            Lengkapi Profile Anda ({{ $completionPercent }}%)
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Mohon lengkapi data pribadi dan dokumen identitas Anda untuk pengalaman yang lebih baik.</p>
                            <div class="w-full bg-yellow-200 rounded-full h-2 mt-2">
                                <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $completionPercent }}%"></div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button onclick="showTab('data-pribadi')" class="text-sm font-medium text-yellow-800 hover:text-yellow-900 underline">
                                Lengkapi Sekarang →
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-home text-2xl"></i>
                    </div>
                    <span class="text-sm opacity-90">Status Kos</span>
                </div>
                @if($resident)
                    <div class="text-3xl font-bold mb-1">
                        {{ $resident->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                    </div>
                    <div class="text-sm opacity-90">{{ $resident->room->name }} - Lantai {{ $resident->room->floor }}</div>
                @else
                    <div class="text-3xl font-bold mb-1">Belum Ngekos</div>
                    <div class="text-sm opacity-90">
                        <a href="/#kamar" class="underline hover:text-blue-100">Cari kamar tersedia</a>
                    </div>
                @endif
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar-check text-2xl"></i>
                    </div>
                    <span class="text-sm opacity-90">Status Pembayaran</span>
                </div>
                @if($resident)
                    @php
                        $unpaidPayments = $resident->payments()->where('status', 'pending')->count();
                        $paidPayments = $resident->payments()->where('status', 'paid')->count();
                    @endphp
                    @if($unpaidPayments > 0)
                        <div class="text-3xl font-bold mb-1">{{ $unpaidPayments }} Tagihan</div>
                        <div class="text-sm opacity-90">
                            <a href="{{ route('tenant.bookings.index') }}" class="underline hover:text-green-100">Lihat & bayar</a>
                        </div>
                    @else
                        <div class="text-3xl font-bold mb-1">Lunas</div>
                        <div class="text-sm opacity-90">{{ $paidPayments }} pembayaran selesai</div>
                    @endif
                @else
                    <div class="text-3xl font-bold mb-1">-</div>
                    <div class="text-sm opacity-90">Belum ada tagihan</div>
                @endif
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clock text-2xl"></i>
                    </div>
                    <span class="text-sm opacity-90">Durasi Tinggal</span>
                </div>
                @if($resident)
                    <div class="text-3xl font-bold mb-1">{{ $resident->getDurationInMonths() }} Bulan</div>
                    <div class="text-sm opacity-90">Sejak {{ $resident->start_date->format('M Y') }}</div>
                @else
                    <div class="text-3xl font-bold mb-1">-</div>
                    <div class="text-sm opacity-90">Belum mulai</div>
                @endif
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white rounded-2xl shadow-sm mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px overflow-x-auto">
                    <button onclick="showTab('data-pribadi')" id="tab-data-pribadi"
                            class="tab-button active px-6 py-4 text-sm font-semibold border-b-2 border-blue-600 text-blue-600 whitespace-nowrap">
                        <i class="fas fa-user mr-2"></i>Data Pribadi
                    </button>
                    <button onclick="showTab('dokumen')" id="tab-dokumen"
                            class="tab-button px-6 py-4 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap relative">
                        <i class="fas fa-id-card mr-2"></i>Dokumen
                        @if(!$user->profile || !$user->profile->ktp_photo)
                            <span class="absolute top-3 right-3 flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                            </span>
                        @endif
                    </button>
                    <button onclick="showTab('data-kos')" id="tab-data-kos"
                            class="tab-button px-6 py-4 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap">
                        <i class="fas fa-building mr-2"></i>Data Kos
                    </button>
                    <button onclick="showTab('keuangan')" id="tab-keuangan"
                            class="tab-button px-6 py-4 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap">
                        <i class="fas fa-wallet mr-2"></i>Keuangan
                    </button>
                </nav>
            </div>

            <!-- Tab Contents -->
            <div class="p-6">

                <!-- Data Pribadi Tab -->
                <div id="content-data-pribadi" class="tab-content">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Informasi Pribadi</h3>

                    <form method="POST" action="{{ route('tenant.profile.update') }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" value="{{ $user->email }}" disabled
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100 cursor-not-allowed">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                                <input type="tel" name="phone" value="{{ old('phone', $user->profile->phone ?? '') }}"
                                       placeholder="08xxxxxxxxxx"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->profile->date_of_birth?->format('Y-m-d') ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                                <select name="gender" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Pilih</option>
                                    <option value="male" {{ old('gender', $user->profile->gender ?? '') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="female" {{ old('gender', $user->profile->gender ?? '') == 'female' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">No. Identitas (KTP)</label>
                                <input type="text" name="identity_number" value="{{ old('identity_number', $user->profile->identity_number ?? '') }}"
                                       placeholder="16 digit"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Asal</label>
                                <textarea name="address" rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Masukkan alamat lengkap...">{{ old('address', $user->profile->address ?? '') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan</label>
                                <input type="text" name="occupation" value="{{ old('occupation', $user->profile->occupation ?? '') }}"
                                       placeholder="Mahasiswa / Karyawan"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kontak Darurat</label>
                                <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $user->profile->emergency_contact_name ?? '') }}"
                                       placeholder="Nama (keluarga)"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">No. Kontak Darurat</label>
                                <input type="tel" name="emergency_contact" value="{{ old('emergency_contact', $user->profile->emergency_contact ?? '') }}"
                                       placeholder="08xxx (keluarga)"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button type="submit"
                                    class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition">
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Dokumen Tab -->
                <div id="content-dokumen" class="tab-content hidden">
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Dokumen Identitas</h3>
                        <p class="text-gray-600 text-sm">Upload KTP (wajib) dan salah satu antara SIM atau Passport. Format: JPG, PNG, PDF (Max: 2MB)</p>
                    </div>

                    <form method="POST" action="{{ route('tenant.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- KTP -->
                            <div class="border-2 border-blue-200 bg-blue-50/30 rounded-xl p-6 hover:border-blue-400 transition">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-id-card text-white text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 flex items-center gap-2">
                                                Foto KTP
                                                <span class="px-2 py-0.5 bg-red-500 text-white text-xs rounded">Wajib</span>
                                            </h4>
                                            <p class="text-sm text-gray-600">Kartu Tanda Penduduk</p>
                                        </div>
                                    </div>
                                    @if($user->profile && $user->profile->ktp_photo)
                                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                            <i class="fas fa-check-circle mr-1"></i>Uploaded
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                            <i class="fas fa-exclamation-circle mr-1"></i>Belum
                                        </span>
                                    @endif
                                </div>

                                @if($user->profile && $user->profile->ktp_photo)
                                    <div class="mb-4 p-3 bg-green-50 rounded-lg border border-green-200">
                                        <div class="flex items-center gap-3">
                                            <i class="fas fa-file-image text-green-600 text-xl"></i>
                                            <div class="flex-1">
                                                <div class="text-sm font-medium text-green-900">Dokumen tersimpan</div>
                                                <div class="text-xs text-green-700">{{ basename($user->profile->ktp_photo) }}</div>
                                            </div>
                                            <a href="{{ asset('storage/' . $user->profile->ktp_photo) }}" target="_blank"
                                               class="px-3 py-1.5 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700 transition">
                                                <i class="fas fa-eye mr-1"></i>Lihat
                                            </a>
                                        </div>
                                        <label class="flex items-center gap-2 mt-3 pt-3 border-t border-green-200 cursor-pointer">
                                            <input type="checkbox" name="delete_ktp" value="1" class="rounded text-red-600">
                                            <span class="text-sm text-red-600 font-medium">Hapus & ganti dokumen</span>
                                        </label>
                                    </div>
                                @endif

                                <label class="block">
                                    <span class="text-sm font-medium text-gray-700 mb-2 block">
                                        {{ $user->profile && $user->profile->ktp_photo ? 'Upload ulang KTP' : 'Upload KTP' }}
                                    </span>
                                    <input type="file" name="ktp_photo" accept="image/*,application/pdf"
                                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition">
                                    @error('ktp_photo')
                                        <p class="mt-2 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                    @enderror
                                </label>
                            </div>

                            <!-- SIM atau Passport (Pilih salah satu) -->
                            <div class="border-2 border-gray-200 rounded-xl p-6 hover:border-blue-300 transition">
                                <div class="mb-4">
                                    <h4 class="font-semibold text-gray-900 flex items-center gap-2 mb-1">
                                        SIM atau Passport
                                        <span class="px-2 py-0.5 bg-blue-500 text-white text-xs rounded">Pilih 1</span>
                                    </h4>
                                    <p class="text-sm text-gray-600">Upload salah satu sebagai identitas tambahan</p>
                                </div>

                                @php
                                    $hasSimOrPassport = ($user->profile && ($user->profile->sim_photo || $user->profile->passport_photo));
                                    $simUploaded = $user->profile && $user->profile->sim_photo;
                                    $passportUploaded = $user->profile && $user->profile->passport_photo;
                                @endphp

                                @if($hasSimOrPassport)
                                    <div class="mb-4 p-3 bg-green-50 rounded-lg border border-green-200">
                                        <div class="flex items-center gap-3">
                                            <i class="fas {{ $simUploaded ? 'fa-car' : 'fa-passport' }} text-green-600 text-xl"></i>
                                            <div class="flex-1">
                                                <div class="text-sm font-medium text-green-900">
                                                    {{ $simUploaded ? 'SIM' : 'Passport' }} tersimpan
                                                </div>
                                                <div class="text-xs text-green-700">
                                                    {{ basename($simUploaded ? $user->profile->sim_photo : $user->profile->passport_photo) }}
                                                </div>
                                            </div>
                                            <a href="{{ asset('storage/' . ($simUploaded ? $user->profile->sim_photo : $user->profile->passport_photo)) }}" target="_blank"
                                               class="px-3 py-1.5 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700 transition">
                                                <i class="fas fa-eye mr-1"></i>Lihat
                                            </a>
                                        </div>
                                        <label class="flex items-center gap-2 mt-3 pt-3 border-t border-green-200 cursor-pointer">
                                            <input type="checkbox" name="{{ $simUploaded ? 'delete_sim' : 'delete_passport' }}" value="1" class="rounded text-red-600">
                                            <span class="text-sm text-red-600 font-medium">Hapus & ganti dokumen</span>
                                        </label>
                                    </div>
                                @endif

                                <!-- Pilihan SIM atau Passport -->
                                <div class="space-y-4">
                                    <label class="block p-4 border-2 border-gray-300 rounded-xl hover:border-orange-400 hover:bg-orange-50/30 transition cursor-pointer">
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-car text-orange-600"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-900">SIM (Surat Izin Mengemudi)</div>
                                                <div class="text-xs text-gray-600">Upload foto SIM Anda</div>
                                            </div>
                                        </div>
                                        <input type="file" name="sim_photo" accept="image/*,application/pdf"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                                    </label>

                                    <div class="text-center text-sm text-gray-500 font-medium">ATAU</div>

                                    <label class="block p-4 border-2 border-gray-300 rounded-xl hover:border-purple-400 hover:bg-purple-50/30 transition cursor-pointer">
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-passport text-purple-600"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-900">Passport</div>
                                                <div class="text-xs text-gray-600">Upload foto Passport Anda</div>
                                            </div>
                                        </div>
                                        <input type="file" name="passport_photo" accept="image/*,application/pdf"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Info Box -->
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                            <div class="flex gap-3">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                                </div>
                                <div class="text-sm text-blue-800">
                                    <p class="font-semibold mb-1">Catatan Penting:</p>
                                    <ul class="list-disc list-inside space-y-1">
                                        <li><strong>KTP wajib</strong> diupload untuk verifikasi identitas</li>
                                        <li>Upload <strong>salah satu</strong> antara SIM atau Passport sebagai identitas tambahan</li>
                                        <li>Format file: JPG, PNG, atau PDF</li>
                                        <li>Ukuran maksimal: 2MB per file</li>
                                        <li>Pastikan foto jelas dan tidak blur</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3 pt-4 border-t border-gray-200">
                            <button type="submit"
                                    class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition shadow-lg hover:shadow-xl">
                                <i class="fas fa-save mr-2"></i>Simpan Dokumen
                            </button>
                            <button type="button" onclick="showTab('data-pribadi')"
                                    class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-300 transition">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Data Kos Tab -->
                <div id="content-data-kos" class="tab-content hidden">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Informasi Kos Saya</h3>

                    @if($resident)
                        <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl p-6 mb-6">
                            <div class="flex items-start gap-4">
                                <div class="w-24 h-24 bg-white rounded-xl overflow-hidden flex-shrink-0">
                                    @if($resident->room->image)
                                        <img src="{{ asset('storage/' . $resident->room->image) }}"
                                             alt="{{ $resident->room->name }}" class="w-full h-full object-cover">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=200&h=200&fit=crop"
                                             alt="Kamar" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-xl font-bold text-gray-900 mb-2">{{ $resident->room->name }}</h4>
                                    <div class="space-y-2 text-sm text-gray-600">
                                        <p><i class="fas fa-building w-5 text-blue-600"></i> {{ $resident->room->property->name }}</p>
                                        <p><i class="fas fa-map-marker-alt w-5 text-blue-600"></i> {{ $resident->room->property->address }}</p>
                                        <p><i class="fas fa-ruler-combined w-5 text-blue-600"></i> {{ $resident->room->size ?? '-' }}</p>
                                        <p><i class="fas fa-layer-group w-5 text-blue-600"></i> Lantai {{ $resident->room->floor }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contract Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div class="bg-white border-2 border-gray-200 rounded-xl p-4">
                                <div class="text-sm text-gray-600 mb-1">Periode Kontrak</div>
                                <div class="font-semibold text-gray-900">
                                    {{ $resident->start_date->format('d M Y') }} - {{ $resident->end_date->format('d M Y') }}
                                </div>
                            </div>
                            <div class="bg-white border-2 border-gray-200 rounded-xl p-4">
                                <div class="text-sm text-gray-600 mb-1">Harga Sewa per Bulan</div>
                                <div class="font-semibold text-gray-900">
                                    Rp {{ number_format($resident->room->price, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('tenant.bookings.index') }}"
                           class="inline-block px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-semibold">
                            <i class="fas fa-receipt mr-2"></i>Lihat Detail Pembayaran
                        </a>
                    @else
                        <div class="text-center py-12">
                            <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-home text-4xl text-gray-400"></i>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Data Kos</h4>
                            <p class="text-gray-600 mb-6">Anda belum terdaftar sebagai penghuni kos.</p>
                            <a href="/#kamar" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">
                                <i class="fas fa-search mr-2"></i>Cari Kamar Tersedia
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Keuangan Tab -->
                <div id="content-keuangan" class="tab-content hidden">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Riwayat Pembayaran</h3>
                        @if($resident)
                            <a href="{{ route('tenant.bookings.index') }}"
                               class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                                <i class="fas fa-external-link-alt mr-1"></i>Lihat Semua
                            </a>
                        @endif
                    </div>

                    @if($resident && $resident->payments->count() > 0)
                        <div class="space-y-4">
                            @foreach($resident->payments->take(5) as $payment)
                                <div class="bg-white border-2 border-gray-200 rounded-xl p-4 hover:border-blue-300 transition">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="font-semibold text-gray-900 mb-1">
                                                {{ $payment->billing_month->format('F Y') }}
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                Jatuh tempo: {{ $payment->due_date->format('d M Y') }}
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-bold text-gray-900 mb-1">
                                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                            </div>
                                            @if($payment->status === 'paid')
                                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                                    <i class="fas fa-check-circle mr-1"></i>Lunas
                                                </span>
                                            @elseif($payment->status === 'pending')
                                                <a href="{{ route('tenant.payment.midtrans', $payment->id) }}"
                                                   class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold hover:bg-yellow-200 transition">
                                                    <i class="fas fa-clock mr-1"></i>Bayar
                                                </a>
                                            @else
                                                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @elseif($resident)
                        <div class="text-center py-12">
                            <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-wallet text-4xl text-gray-400"></i>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Pembayaran</h4>
                            <p class="text-gray-600">Riwayat pembayaran akan muncul di sini.</p>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-wallet text-4xl text-gray-400"></i>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Data Keuangan</h4>
                            <p class="text-gray-600">Anda belum memiliki riwayat pembayaran.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>

    </div>

    <script>
        function showTab(tabName) {
            // Hide all contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active class from all buttons
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active', 'border-blue-600', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });

            // Show selected content
            document.getElementById('content-' + tabName).classList.remove('hidden');

            // Add active class to selected button
            const activeBtn = document.getElementById('tab-' + tabName);
            activeBtn.classList.add('active', 'border-blue-600', 'text-blue-600');
            activeBtn.classList.remove('border-transparent', 'text-gray-500');
        }
    </script>

</body>
</html>
