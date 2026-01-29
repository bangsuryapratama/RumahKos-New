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
    <header class="bg-white border-b sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <a href="/" class="text-xl font-bold text-gray-900">
                        Rumah<span class="text-blue-600">Kos</span>
                    </a>
                    <span class="hidden sm:block text-gray-300">|</span>
                    <span class="hidden sm:block text-sm text-gray-600">Dashboard Penghuni</span>
                </div>

                <div class="flex items-center gap-3">
                    <a href="/" class="text-gray-600 hover:text-gray-900 text-sm">
                        <i class="fas fa-home mr-1"></i>
                        <span class="hidden sm:inline">Beranda</span>
                    </a>

                    <div class="relative group">
                        <button class="flex items-center gap-2 px-3 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">
                            <i class="fas fa-user-circle text-gray-600"></i>
                            <span class="hidden sm:inline text-sm font-medium">{{ $user->name }}</span>
                            <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                        </button>

                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                            <form method="POST" action="{{ route('tenant.logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-lg">
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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <!-- Welcome -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Selamat Datang, {{ $user->name }}</h1>
            <p class="text-sm text-gray-600">Kelola data kos dan keuangan Anda di sini</p>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-50 text-green-800 rounded-lg border border-green-200 text-sm">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-3 bg-red-50 text-red-800 rounded-lg border border-red-200 text-sm">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        <!-- Profile Completion -->
        @php
            $profileComplete = $user->profile && $user->profile->phone && $user->profile->identity_number && $user->profile->ktp_photo;
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
            <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5"></i>
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-gray-900">Lengkapi Profil Anda ({{ $completionPercent }}%)</h3>
                        <p class="text-sm text-gray-600 mt-1">Mohon lengkapi data pribadi dan dokumen identitas</p>
                        <div class="w-full bg-yellow-200 rounded-full h-1.5 mt-2">
                            <div class="bg-yellow-600 h-1.5 rounded-full" style="width: {{ $completionPercent }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-5 text-white">
                <div class="flex items-center justify-between mb-3">
                    <i class="fas fa-home text-3xl opacity-20"></i>
                    <span class="text-xs opacity-90">Status Kos</span>
                </div>
                @if($resident)
                    <div class="text-2xl font-bold">Aktif</div>
                    <p class="text-sm opacity-90">{{ $resident->room->name }}</p>
                @else
                    <div class="text-2xl font-bold">Belum Ngekos</div>
                    <a href="/#kamar" class="text-sm underline opacity-90">Cari kamar</a>
                @endif
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-5 text-white">
                <div class="flex items-center justify-between mb-3">
                    <i class="fas fa-wallet text-3xl opacity-20"></i>
                    <span class="text-xs opacity-90">Pembayaran</span>
                </div>
                @if($resident)
                    @php
                        $unpaidPayments = $resident->payments()->where('status', 'pending')->count();
                    @endphp
                    @if($unpaidPayments > 0)
                        <div class="text-2xl font-bold">{{ $unpaidPayments }} Tagihan</div>
                        <a href="{{ route('tenant.bookings.index') }}" class="text-sm underline opacity-90">Bayar sekarang</a>
                    @else
                        <div class="text-2xl font-bold">Lunas</div>
                        <p class="text-sm opacity-90">Semua tagihan lunas</p>
                    @endif
                @else
                    <div class="text-2xl font-bold">-</div>
                    <p class="text-sm opacity-90">Belum ada tagihan</p>
                @endif
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-5 text-white">
                <div class="flex items-center justify-between mb-3">
                    <i class="fas fa-clock text-3xl opacity-20"></i>
                    <span class="text-xs opacity-90">Durasi</span>
                </div>
                @if($resident)
                    <div class="text-2xl font-bold">{{ $resident->getDurationInMonths() }} Bulan</div>
                    <p class="text-sm opacity-90">Sejak {{ $resident->start_date->format('M Y') }}</p>
                @else
                    <div class="text-2xl font-bold">-</div>
                    <p class="text-sm opacity-90">Belum mulai</p>
                @endif
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-xl border">
            <div class="border-b">
                <nav class="flex overflow-x-auto">
                    <button onclick="showTab('profile')" id="tab-profile"
                            class="tab-btn px-5 py-3 text-sm font-medium border-b-2 border-blue-600 text-blue-600 whitespace-nowrap">
                        <i class="fas fa-user mr-2"></i>Profil & Dokumen
                    </button>
                    <button onclick="showTab('kos')" id="tab-kos"
                            class="tab-btn px-5 py-3 text-sm font-medium border-b-2 border-transparent text-gray-600 hover:text-gray-900 whitespace-nowrap">
                        <i class="fas fa-building mr-2"></i>Data Kos
                    </button>
                    <button onclick="showTab('payment')" id="tab-payment"
                            class="tab-btn px-5 py-3 text-sm font-medium border-b-2 border-transparent text-gray-600 hover:text-gray-900 whitespace-nowrap">
                        <i class="fas fa-receipt mr-2"></i>Keuangan
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">

                <!-- PROFILE TAB -->
                <div id="content-profile" class="tab-content">
                    <form method="POST" action="{{ route('tenant.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Personal Info -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Data Pribadi</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap *</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                                    <input type="email" value="{{ $user->email }}" disabled
                                           class="w-full px-3 py-2 border rounded-lg bg-gray-50 text-gray-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">No. Telepon</label>
                                    <input type="tel" name="phone" value="{{ old('phone', $user->profile->phone ?? '') }}"
                                           placeholder="08xxxxxxxxxx"
                                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">No. KTP</label>
                                    <input type="text" name="identity_number" value="{{ old('identity_number', $user->profile->identity_number ?? '') }}"
                                           placeholder="16 digit" maxlength="16"
                                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('identity_number')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Lahir</label>
                                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->profile->date_of_birth?->format('Y-m-d') ?? '') }}"
                                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Kelamin</label>
                                    <select name="gender" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Pilih</option>
                                        <option value="male" {{ old('gender', $user->profile->gender ?? '') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="female" {{ old('gender', $user->profile->gender ?? '') == 'female' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Asal</label>
                                    <textarea name="address" rows="2"
                                              class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                              placeholder="Masukkan alamat lengkap">{{ old('address', $user->profile->address ?? '') }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Pekerjaan</label>
                                    <input type="text" name="occupation" value="{{ old('occupation', $user->profile->occupation ?? '') }}"
                                           placeholder="Mahasiswa / Karyawan"
                                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Kontak Darurat</label>
                                    <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $user->profile->emergency_contact_name ?? '') }}"
                                           placeholder="Nama keluarga"
                                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">No. Kontak Darurat</label>
                                    <input type="tel" name="emergency_contact" value="{{ old('emergency_contact', $user->profile->emergency_contact ?? '') }}"
                                           placeholder="08xxx"
                                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>

                        <!-- Documents -->
                        <div class="pt-6 border-t">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Dokumen Identitas</h3>
                            <p class="text-sm text-gray-600 mb-4">Upload foto/scan dokumen dengan jelas. Format: JPG, PNG, PDF (Max 2MB)</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- KTP -->
                                <div class="p-4 border rounded-lg">
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <h4 class="font-medium text-gray-900">KTP <span class="text-xs text-red-600">(Wajib)</span></h4>
                                            <p class="text-xs text-gray-500">Kartu Tanda Penduduk</p>
                                        </div>
                                        @if($user->profile && $user->profile->ktp_photo)
                                            <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded">✓</span>
                                        @endif
                                    </div>

                                    @if($user->profile && $user->profile->ktp_photo)
                                        <div class="mb-3 p-2 bg-gray-50 rounded text-xs">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-gray-700 truncate">{{ basename($user->profile->ktp_photo) }}</span>
                                                <a href="{{ asset('storage/' . $user->profile->ktp_photo) }}" target="_blank"
                                                   class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300">Lihat</a>
                                            </div>
                                            <label class="flex items-center gap-2">
                                                <input type="checkbox" name="delete_ktp" value="1" class="rounded">
                                                <span class="text-red-600">Hapus & ganti</span>
                                            </label>
                                        </div>
                                    @endif

                                    <input type="file" name="ktp_photo" id="ktp_photo" accept="image/*,application/pdf"
                                           onchange="validateFile(this, 'ktp-preview')"
                                           class="w-full text-sm border rounded file:mr-3 file:py-2 file:px-3 file:rounded file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                                    <div id="ktp-preview" class="mt-2"></div>
                                    @error('ktp_photo')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror

                                    <!-- Panduan -->
                                    <div class="mt-3 p-2 bg-blue-50 rounded text-xs">
                                        <p class="font-medium text-gray-700 mb-1">Panduan Foto KTP:</p>
                                        <ul class="text-gray-600 space-y-0.5">
                                            <li>• Foto harus jelas & tidak blur</li>
                                            <li>• Semua teks & angka terlihat</li>
                                            <li>• Tidak ada pantulan cahaya</li>
                                            <li>• Seluruh kartu terlihat (tidak terpotong)</li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- SIM / Passport -->
                                <div class="p-4 border rounded-lg">
                                    <div class="mb-3">
                                        <h4 class="font-medium text-gray-900">SIM atau Passport <span class="text-xs text-gray-600">(Pilih salah satu)</span></h4>
                                        <p class="text-xs text-gray-500">Identitas tambahan</p>
                                    </div>

                                    @php
                                        $simUploaded = $user->profile && $user->profile->sim_photo;
                                        $passportUploaded = $user->profile && $user->profile->passport_photo;
                                    @endphp

                                    @if($simUploaded || $passportUploaded)
                                        <div class="mb-3 p-2 bg-gray-50 rounded text-xs">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-gray-700 truncate">{{ $simUploaded ? 'SIM' : 'Passport' }}: {{ basename($simUploaded ? $user->profile->sim_photo : $user->profile->passport_photo) }}</span>
                                                <a href="{{ asset('storage/' . ($simUploaded ? $user->profile->sim_photo : $user->profile->passport_photo)) }}" target="_blank"
                                                   class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300">Lihat</a>
                                            </div>
                                            <label class="flex items-center gap-2">
                                                <input type="checkbox" name="{{ $simUploaded ? 'delete_sim' : 'delete_passport' }}" value="1" class="rounded">
                                                <span class="text-red-600">Hapus & ganti</span>
                                            </label>
                                        </div>
                                    @endif

                                    <div class="space-y-2">
                                        <div class="border rounded p-2">
                                            <label class="block text-xs font-medium text-gray-700 mb-1">SIM</label>
                                            <input type="file" name="sim_photo" id="sim_photo" accept="image/*,application/pdf"
                                                   onchange="validateFile(this, 'sim-preview')"
                                                   class="w-full text-xs border rounded file:mr-2 file:py-1.5 file:px-2 file:rounded file:border-0 file:bg-gray-100 file:text-gray-700">
                                            <div id="sim-preview" class="mt-2"></div>
                                        </div>

                                        <div class="text-center text-xs text-gray-500">ATAU</div>

                                        <div class="border rounded p-2">
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Passport</label>
                                            <input type="file" name="passport_photo" id="passport_photo" accept="image/*,application/pdf"
                                                   onchange="validateFile(this, 'passport-preview')"
                                                   class="w-full text-xs border rounded file:mr-2 file:py-1.5 file:px-2 file:rounded file:border-0 file:bg-gray-100 file:text-gray-700">
                                            <div id="passport-preview" class="mt-2"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="pt-4 border-t">
                            <button type="submit"
                                    class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- KOS TAB -->
                <div id="content-kos" class="tab-content hidden">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kos Saya</h3>

                    @if($resident)
                        <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-lg p-5 mb-4">
                            <div class="flex gap-4">
                                <div class="w-20 h-20 bg-white rounded-lg overflow-hidden flex-shrink-0">
                                    @if($resident->room->image)
                                        <img src="{{ asset('storage/' . $resident->room->image) }}" alt="{{ $resident->room->name }}" class="w-full h-full object-cover">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=80&h=80&fit=crop" alt="Kamar" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">{{ $resident->room->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $resident->room->property->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $resident->room->property->address }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="border rounded-lg p-3">
                                <p class="text-xs text-gray-600 mb-1">Periode Kontrak</p>
                                <p class="text-sm font-medium">{{ $resident->start_date->format('d M Y') }} - {{ $resident->end_date->format('d M Y') }}</p>
                            </div>
                            <div class="border rounded-lg p-3">
                                <p class="text-xs text-gray-600 mb-1">Harga per Bulan</p>
                                <p class="text-sm font-medium">Rp {{ number_format($resident->room->price, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <a href="{{ route('tenant.bookings.index') }}" class="inline-block px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                            Detail Pembayaran
                        </a>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-home text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-600 mb-4">Anda belum terdaftar sebagai penghuni kos</p>
                            <a href="/#kamar" class="inline-block px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">Cari Kamar</a>
                        </div>
                    @endif
                </div>

                <!-- PAYMENT TAB -->
                <div id="content-payment" class="tab-content hidden">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Riwayat Pembayaran</h3>
                        @if($resident)
                            <a href="{{ route('tenant.bookings.index') }}" class="text-sm text-blue-600 hover:underline">Lihat Semua</a>
                        @endif
                    </div>

                    @if($resident && $resident->payments->count() > 0)
                        <div class="space-y-3">
                            @foreach($resident->payments->take(5) as $payment)
                                <div class="border rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $payment->billing_month->format('F Y') }}</p>
                                            <p class="text-sm text-gray-600">Jatuh tempo: {{ $payment->due_date->format('d M Y') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                            @if($payment->status === 'paid')
                                                <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded">Lunas</span>
                                            @elseif($payment->status === 'pending')
                                                <a href="{{ route('tenant.payment.midtrans', $payment->id) }}"
                                                   class="text-xs px-2 py-1 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200">Bayar</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-wallet text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-600">Belum ada riwayat pembayaran</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>

    </div>

    <script>
        // Tab Navigation
        function showTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('border-blue-600', 'text-blue-600');
                el.classList.add('border-transparent', 'text-gray-600');
            });

            document.getElementById('content-' + tabName).classList.remove('hidden');
            const activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.add('border-blue-600', 'text-blue-600');
            activeTab.classList.remove('border-transparent');
        }

        // File Validation & Preview
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

</body>
</html>
