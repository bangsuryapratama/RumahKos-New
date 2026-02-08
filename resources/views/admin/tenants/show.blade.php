<x-app-layout>
<div class="py-6 sm:py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <a href="{{ route('admin.tenants.index') }}" 
                           class="text-gray-600 hover:text-gray-900">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Detail Penghuni</h1>
                    </div>
                    <p class="text-sm sm:text-base text-gray-600">Informasi lengkap data penghuni</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.tenants.edit', $tenant) }}"
                       class="inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2 sm:py-2.5 bg-yellow-500 text-white rounded-lg sm:rounded-xl hover:bg-yellow-600 transition-all font-semibold text-sm sm:text-base shadow-md">
                        <i class="fas fa-edit"></i>
                        <span>Edit Data</span>
                    </a>
                </div>
            </div>
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

        {{-- Profile Completion Progress --}}
        @php
            $profileComplete = $tenant->profile && $tenant->profile->phone && $tenant->profile->identity_number && $tenant->profile->ktp_photo;
            $completionPercent = 0;
            if ($tenant->profile) {
                $fields = ['phone', 'identity_number', 'address', 'date_of_birth', 'gender', 'occupation', 'emergency_contact', 'ktp_photo'];
                $filled = 0;
                foreach ($fields as $field) {
                    if ($tenant->profile->$field) $filled++;
                }
                $completionPercent = round(($filled / count($fields)) * 100);
            }
        @endphp

        <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-4 sm:p-6 mb-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold text-gray-900">Kelengkapan Profil</h3>
                <span class="text-2xl font-bold {{ $completionPercent == 100 ? 'text-green-600' : 'text-yellow-600' }}">
                    {{ $completionPercent }}%
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-gradient-to-r {{ $completionPercent == 100 ? 'from-green-500 to-green-600' : 'from-yellow-500 to-yellow-600' }} h-3 rounded-full transition-all duration-500" 
                     style="width: {{ $completionPercent }}%"></div>
            </div>
            @if($completionPercent < 100)
                <p class="text-xs text-gray-600 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    Penghuni belum melengkapi semua data profil dan dokumen
                </p>
            @else
                <p class="text-xs text-green-600 mt-2">
                    <i class="fas fa-check-circle mr-1"></i>
                    Profil lengkap
                </p>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Left Column: Profile Summary --}}
            <div class="lg:col-span-1 space-y-6">
                
                {{-- Profile Card --}}
                <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-6">
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-full flex items-center justify-center text-white text-3xl font-bold mx-auto mb-4">
                            {{ substr($tenant->name, 0, 1) }}
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 mb-1">{{ $tenant->name }}</h2>
                        <p class="text-sm text-gray-600">{{ $tenant->email }}</p>
                        
                        {{-- Status Badge --}}
                        <div class="mt-4">
                            @if($tenant->resident && $tenant->resident->status === 'active')
                                <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                                    <i class="fas fa-check-circle"></i> Status Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-semibold">
                                    <i class="fas fa-times-circle"></i> Tidak Aktif
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Quick Info --}}
                    <div class="space-y-3 pt-4 border-t">
                        <div class="flex items-center gap-3 text-sm">
                            <i class="fas fa-phone w-5 text-gray-400"></i>
                            <span class="text-gray-700">{{ $tenant->profile->phone ?? '-' }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <i class="fas fa-id-card w-5 text-gray-400"></i>
                            <span class="text-gray-700">{{ $tenant->profile->identity_number ?? '-' }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <i class="fas fa-venus-mars w-5 text-gray-400"></i>
                            <span class="text-gray-700">
                                {{ $tenant->profile && $tenant->profile->gender ? ($tenant->profile->gender == 'male' ? 'Laki-laki' : 'Perempuan') : '-' }}
                            </span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <i class="fas fa-calendar w-5 text-gray-400"></i>
                            <span class="text-gray-700">
                                {{ $tenant->profile && $tenant->profile->date_of_birth ? $tenant->profile->date_of_birth->format('d M Y') : '-' }}
                            </span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <i class="fas fa-briefcase w-5 text-gray-400"></i>
                            <span class="text-gray-700">{{ $tenant->profile->occupation ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Room Info (if active) --}}
                @if($tenant->resident)
                    <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-lg sm:rounded-xl p-5 shadow-md">
                        <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-home text-blue-600"></i>
                            Informasi Kamar
                        </h3>
                        
                        <div class="bg-white rounded-lg p-4 mb-3">
                            <div class="flex gap-3">
                                <div class="w-16 h-16 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                    @if($tenant->resident->room->image)
                                        <img src="{{ asset('storage/' . $tenant->resident->room->image) }}" 
                                             alt="{{ $tenant->resident->room->name }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=80&h=80&fit=crop" 
                                             alt="Kamar" 
                                             class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">{{ $tenant->resident->room->name }}</h4>
                                    <p class="text-xs text-gray-600">{{ $tenant->resident->room->property->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $tenant->resident->room->property->address }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Harga/Bulan:</span>
                                <span class="font-semibold text-gray-900">Rp {{ number_format($tenant->resident->room->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Mulai:</span>
                                <span class="font-semibold text-gray-900">{{ $tenant->resident->start_date->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Berakhir:</span>
                                <span class="font-semibold text-gray-900">{{ $tenant->resident->end_date->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between text-sm pt-2 border-t">
                                <span class="text-gray-600">Durasi:</span>
                                <span class="font-semibold text-gray-900">{{ $tenant->resident->getDurationInMonths() }} Bulan</span>
                            </div>
                        </div>

                        {{-- Resident Actions --}}
                        <div class="mt-4 pt-4 border-t flex gap-2">
                            @if($tenant->resident->status === 'active')
                                <form action="{{ route('admin.tenants.deactivate', $tenant->resident) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" 
                                            onclick="return confirm('Nonaktifkan penghuni ini?')"
                                            class="w-full px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all text-sm font-semibold">
                                        <i class="fas fa-times-circle mr-1"></i>Nonaktifkan
                                    </button>
                                </form>
                            @elseif($tenant->resident->status === 'inactive')
                                <form action="{{ route('admin.tenants.activate', $tenant->resident) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" 
                                            onclick="return confirm('Aktifkan penghuni ini?')"
                                            class="w-full px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all text-sm font-semibold">
                                        <i class="fas fa-check-circle mr-1"></i>Aktifkan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg sm:rounded-xl p-5 shadow-md">
                        <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <i class="fas fa-home text-gray-400"></i>
                            Informasi Kamar
                        </h3>
                        <div class="text-center py-6">
                            <i class="fas fa-door-open text-4xl text-gray-300 mb-2"></i>
                            <p class="text-sm text-gray-600 mb-3">Belum assign kamar</p>
                            <a href="{{ route('admin.tenants.edit', $tenant) }}" 
                               class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-xs font-semibold">
                                <i class="fas fa-plus mr-1"></i>Assign Kamar
                            </a>
                        </div>
                    </div>
                @endif

            </div>

            {{-- Right Column: Detailed Information --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Personal Information --}}
                <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-user text-blue-600"></i>
                        Data Pribadi
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Nama Lengkap</label>
                            <p class="text-sm font-semibold text-gray-900">{{ $tenant->name }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Email</label>
                            <p class="text-sm font-semibold text-gray-900">{{ $tenant->email }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">No. Telepon</label>
                            <p class="text-sm font-semibold text-gray-900">{{ $tenant->profile->phone ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">No. KTP</label>
                            <p class="text-sm font-semibold text-gray-900">{{ $tenant->profile->identity_number ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tanggal Lahir</label>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $tenant->profile && $tenant->profile->date_of_birth ? $tenant->profile->date_of_birth->format('d F Y') : '-' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Jenis Kelamin</label>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $tenant->profile && $tenant->profile->gender ? ($tenant->profile->gender == 'male' ? 'Laki-laki' : 'Perempuan') : '-' }}
                            </p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Alamat Asal</label>
                            <p class="text-sm font-semibold text-gray-900">{{ $tenant->profile->address ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Pekerjaan</label>
                            <p class="text-sm font-semibold text-gray-900">{{ $tenant->profile->occupation ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Terdaftar Sejak</label>
                            <p class="text-sm font-semibold text-gray-900">{{ $tenant->created_at->format('d F Y') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Emergency Contact --}}
                <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-phone-alt text-red-600"></i>
                        Kontak Darurat
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Nama Kontak</label>
                            <p class="text-sm font-semibold text-gray-900">{{ $tenant->profile->emergency_contact_name ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">No. Telepon</label>
                            <p class="text-sm font-semibold text-gray-900">{{ $tenant->profile->emergency_contact ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Documents --}}
                <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-file-alt text-green-600"></i>
                        Dokumen Identitas
                    </h3>

                    @php
                        $hasDocuments = ($tenant->profile && ($tenant->profile->ktp_photo || $tenant->profile->sim_photo || $tenant->profile->passport_photo));
                    @endphp

                    @if($hasDocuments)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            
                            {{-- KTP --}}
                            @if($tenant->profile->ktp_photo)
                                <div class="border-2 border-blue-200 rounded-lg p-4 bg-blue-50">
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <h4 class="font-semibold text-gray-900 flex items-center gap-2">
                                                <i class="fas fa-id-card text-blue-600"></i>
                                                KTP
                                            </h4>
                                            <p class="text-xs text-gray-600">Kartu Tanda Penduduk</p>
                                        </div>
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">
                                            <i class="fas fa-check-circle"></i> Tersedia
                                        </span>
                                    </div>
                                    
                                    @php
                                        $ktpPath = 'storage/' . $tenant->profile->ktp_photo;
                                        $ktpExtension = strtolower(pathinfo($tenant->profile->ktp_photo, PATHINFO_EXTENSION));
                                    @endphp

                                    @if(in_array($ktpExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                        <div class="mb-3 bg-white rounded border overflow-hidden">
                                            <img src="{{ asset($ktpPath) }}" 
                                                 alt="KTP {{ $tenant->name }}" 
                                                 class="w-full h-32 object-contain cursor-pointer hover:opacity-90 transition-opacity"
                                                 onclick="openImageModal('{{ asset($ktpPath) }}', 'KTP - {{ $tenant->name }}')">
                                        </div>
                                    @else
                                        <div class="mb-3 bg-white rounded border p-4 text-center">
                                            <i class="fas fa-file-pdf text-4xl text-red-600 mb-2"></i>
                                            <p class="text-xs text-gray-600">Dokumen PDF</p>
                                        </div>
                                    @endif

                                    <div class="flex gap-2">
                                        <a href="{{ asset($ktpPath) }}" 
                                           target="_blank"
                                           class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all text-xs font-semibold text-center">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </a>
                                        <a href="{{ asset($ktpPath) }}" 
                                           download
                                           class="flex-1 px-3 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-all text-xs font-semibold text-center">
                                            <i class="fas fa-download mr-1"></i>Unduh
                                        </a>
                                    </div>
                                </div>
                            @endif

                            {{-- SIM --}}
                            @if($tenant->profile->sim_photo)
                                <div class="border-2 border-green-200 rounded-lg p-4 bg-green-50">
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <h4 class="font-semibold text-gray-900 flex items-center gap-2">
                                                <i class="fas fa-id-card-alt text-green-600"></i>
                                                SIM
                                            </h4>
                                            <p class="text-xs text-gray-600">Surat Izin Mengemudi</p>
                                        </div>
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">
                                            <i class="fas fa-check-circle"></i> Tersedia
                                        </span>
                                    </div>
                                    
                                    @php
                                        $simPath = 'storage/' . $tenant->profile->sim_photo;
                                        $simExtension = strtolower(pathinfo($tenant->profile->sim_photo, PATHINFO_EXTENSION));
                                    @endphp

                                    @if(in_array($simExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                        <div class="mb-3 bg-white rounded border overflow-hidden">
                                            <img src="{{ asset($simPath) }}" 
                                                 alt="SIM {{ $tenant->name }}" 
                                                 class="w-full h-32 object-contain cursor-pointer hover:opacity-90 transition-opacity"
                                                 onclick="openImageModal('{{ asset($simPath) }}', 'SIM - {{ $tenant->name }}')">
                                        </div>
                                    @else
                                        <div class="mb-3 bg-white rounded border p-4 text-center">
                                            <i class="fas fa-file-pdf text-4xl text-red-600 mb-2"></i>
                                            <p class="text-xs text-gray-600">Dokumen PDF</p>
                                        </div>
                                    @endif

                                    <div class="flex gap-2">
                                        <a href="{{ asset($simPath) }}" 
                                           target="_blank"
                                           class="flex-1 px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all text-xs font-semibold text-center">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </a>
                                        <a href="{{ asset($simPath) }}" 
                                           download
                                           class="flex-1 px-3 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-all text-xs font-semibold text-center">
                                            <i class="fas fa-download mr-1"></i>Unduh
                                        </a>
                                    </div>
                                </div>
                            @endif

                            {{-- Passport --}}
                            @if($tenant->profile->passport_photo)
                                <div class="border-2 border-purple-200 rounded-lg p-4 bg-purple-50">
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <h4 class="font-semibold text-gray-900 flex items-center gap-2">
                                                <i class="fas fa-passport text-purple-600"></i>
                                                Passport
                                            </h4>
                                            <p class="text-xs text-gray-600">Paspor</p>
                                        </div>
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">
                                            <i class="fas fa-check-circle"></i> Tersedia
                                        </span>
                                    </div>
                                    
                                    @php
                                        $passportPath = 'storage/' . $tenant->profile->passport_photo;
                                        $passportExtension = strtolower(pathinfo($tenant->profile->passport_photo, PATHINFO_EXTENSION));
                                    @endphp

                                    @if(in_array($passportExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                        <div class="mb-3 bg-white rounded border overflow-hidden">
                                            <img src="{{ asset($passportPath) }}" 
                                                 alt="Passport {{ $tenant->name }}" 
                                                 class="w-full h-32 object-contain cursor-pointer hover:opacity-90 transition-opacity"
                                                 onclick="openImageModal('{{ asset($passportPath) }}', 'Passport - {{ $tenant->name }}')">
                                        </div>
                                    @else
                                        <div class="mb-3 bg-white rounded border p-4 text-center">
                                            <i class="fas fa-file-pdf text-4xl text-red-600 mb-2"></i>
                                            <p class="text-xs text-gray-600">Dokumen PDF</p>
                                        </div>
                                    @endif

                                    <div class="flex gap-2">
                                        <a href="{{ asset($passportPath) }}" 
                                           target="_blank"
                                           class="flex-1 px-3 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all text-xs font-semibold text-center">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </a>
                                        <a href="{{ asset($passportPath) }}" 
                                           download
                                           class="flex-1 px-3 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-all text-xs font-semibold text-center">
                                            <i class="fas fa-download mr-1"></i>Unduh
                                        </a>
                                    </div>
                                </div>
                            @endif

                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-folder-open text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-600">Belum ada dokumen yang diupload</p>
                            <p class="text-sm text-gray-500 mt-1">Penghuni belum mengupload dokumen identitas</p>
                        </div>
                    @endif
                </div>

                {{-- Payment History --}}
                @if($tenant->residents->count() > 0)
                    <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-receipt text-purple-600"></i>
                            Riwayat Pembayaran
                        </h3>

                        @php
                            $allPayments = collect();
                            foreach($tenant->residents as $resident) {
                                $allPayments = $allPayments->merge($resident->payments);
                            }
                            $allPayments = $allPayments->sortByDesc('billing_month')->take(10);
                        @endphp

                        @if($allPayments->count() > 0)
                            <div class="space-y-3">
                                @foreach($allPayments as $payment)
                                    <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <p class="font-semibold text-gray-900">{{ $payment->billing_month->format('F Y') }}</p>
                                                <p class="text-xs text-gray-600 mt-1">Jatuh tempo: {{ $payment->due_date->format('d M Y') }}</p>
                                                @if($payment->status === 'paid' && $payment->paid_at)
                                                    <p class="text-xs text-green-600 mt-1">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        Dibayar: {{ $payment->paid_at->format('d M Y H:i') }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="text-right">
                                                <p class="font-bold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                                @if($payment->status === 'paid')
                                                    <span class="inline-block mt-1 px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                                        <i class="fas fa-check-circle"></i> Lunas
                                                    </span>
                                                @elseif($payment->status === 'pending')
                                                    <span class="inline-block mt-1 px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
                                                        <i class="fas fa-clock"></i> Pending
                                                    </span>
                                                @elseif($payment->status === 'failed')
                                                    <span class="inline-block mt-1 px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                                        <i class="fas fa-times-circle"></i> Gagal
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-wallet text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-600">Belum ada riwayat pembayaran</p>
                            </div>
                        @endif
                    </div>
                @endif

            </div>

        </div>

    </div>
</div>

{{-- Image Modal --}}
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 hidden items-center justify-center z-50 p-4">
    <div class="relative max-w-4xl w-full">
        <button onclick="closeImageModal()" 
                class="absolute -top-12 right-0 text-white hover:text-gray-300 text-2xl">
            <i class="fas fa-times"></i>
        </button>
        <img id="modalImage" src="" alt="" class="w-full h-auto rounded-lg">
        <p id="modalCaption" class="text-white text-center mt-4 text-sm"></p>
    </div>
</div>

<script>
function openImageModal(imageSrc, caption) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const modalCaption = document.getElementById('modalCaption');
    
    modalImage.src = imageSrc;
    modalCaption.textContent = caption;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = 'auto';
}

// Close modal on ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});

// Close modal on click outside
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});
</script>

</x-app-layout>