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
                    <span class="text-sm opacity-90">Pembayaran</span>
                </div>
                @if($resident)
                    <div class="text-3xl font-bold mb-1">Lunas</div>
                    <div class="text-sm opacity-90">Semua tagihan lunas</div>
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
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Riwayat Pembayaran</h3>
                    
                    @if($resident)
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