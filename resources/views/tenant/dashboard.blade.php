<x-app-layout>
<div class="py-6 sm:py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <a href="{{ route('admin.tenants.index') }}"
                           class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-800 transition-colors">
                            <i class="fas fa-arrow-left text-xs"></i> Kembali
                        </a>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Detail Penghuni</h1>
                    <p class="text-sm text-gray-500 mt-1">Informasi lengkap data penghuni</p>
                </div>
                <a href="{{ route('admin.tenants.edit', $tenant) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-xl shadow transition-colors">
                    <i class="fas fa-edit"></i> Edit Data
                </a>
            </div>
        </div>

        {{-- Alert Messages --}}
        @if(session('success'))
            <div class="mb-5 flex items-center gap-3 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
                <i class="fas fa-check-circle"></i>{{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-5 flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                <i class="fas fa-exclamation-circle"></i>{{ session('error') }}
            </div>
        @endif

        {{-- ═══════════════════════════════════════════ --}}
        {{-- ALERT NUNGGAK                               --}}
        {{-- ═══════════════════════════════════════════ --}}
        @php
            $overduePayments = collect();
            foreach($tenant->residents as $res) {
                $overdue = $res->payments->filter(
                    fn($p) => $p->status === 'pending' && $p->due_date->isPast()
                );
                $overduePayments = $overduePayments->merge($overdue);
            }
        @endphp

        @if($overduePayments->count() > 0)
            <div class="mb-5 p-4 bg-red-50 border border-red-300 rounded-xl">
                <div class="flex items-center gap-2 mb-3">
                    <i class="fas fa-exclamation-triangle text-red-600 text-base"></i>
                    <span class="font-bold text-red-700 text-sm">
                        Penghuni ini nunggak {{ $overduePayments->count() }} tagihan!
                    </span>
                </div>
                <div class="space-y-1.5">
                    @foreach($overduePayments as $op)
                        <div class="flex items-center justify-between text-xs bg-white border border-red-200 rounded-lg px-3 py-2">
                            <span class="font-medium text-gray-800">{{ $op->billing_month->format('F Y') }}</span>
                            <span class="text-gray-500">Jatuh tempo {{ $op->due_date->format('d M Y') }}</span>
                            <span class="text-red-600 font-semibold">{{ $op->due_date->diffForHumans() }}</span>
                            <span class="font-bold text-gray-900">Rp {{ number_format($op->amount, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
                <p class="text-xs text-red-600 font-semibold mt-3">
                    Total tunggakan: Rp {{ number_format($overduePayments->sum('amount'), 0, ',', '.') }}
                </p>
            </div>
        @endif
        {{-- ═══════════════════════════════════════════ --}}

        {{-- Profile Completion Progress --}}
        @php
            $completionPercent = 0;
            if ($tenant->profile) {
                $fields = ['phone', 'identity_number', 'address', 'date_of_birth', 'gender', 'occupation', 'emergency_contact', 'ktp_photo'];
                $filled = collect($fields)->filter(fn($f) => !empty($tenant->profile->$f))->count();
                $completionPercent = round(($filled / count($fields)) * 100);
            }
            $isComplete = $completionPercent === 100;
        @endphp

        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 mb-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-base font-semibold text-gray-800">Kelengkapan Profil</h3>
                <span class="text-2xl font-bold {{ $isComplete ? 'text-green-600' : 'text-amber-500' }}">
                    {{ $completionPercent }}%
                </span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2">
                <div class="h-2 rounded-full transition-all duration-500 {{ $isComplete ? 'bg-green-500' : 'bg-amber-400' }}"
                     style="width: {{ $completionPercent }}%"></div>
            </div>
            <p class="text-xs mt-2 {{ $isComplete ? 'text-green-600' : 'text-gray-400' }}">
                <i class="fas {{ $isComplete ? 'fa-check-circle' : 'fa-info-circle' }} mr-1"></i>
                {{ $isComplete ? 'Profil lengkap' : 'Penghuni belum melengkapi semua data profil dan dokumen' }}
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT COLUMN --}}
            <div class="lg:col-span-1 space-y-5">

                {{-- Profile Summary Card --}}
                <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5">
                    {{-- Avatar --}}
                    <div class="flex flex-col items-center text-center mb-4">
                        <div class="w-16 h-16 rounded-full bg-violet-100 flex items-center justify-center text-violet-700 font-bold text-xl mb-3">
                            {{ strtoupper(substr($tenant->name, 0, 1)) }}{{ strtoupper(substr(strstr($tenant->name, ' '), 1, 1)) }}
                        </div>
                        <h2 class="text-base font-bold text-gray-900">{{ $tenant->name }}</h2>
                        <p class="text-xs text-gray-400 break-all">{{ $tenant->email }}</p>

                        {{-- Status Badge --}}
                        <div class="mt-3">
                            @if($tenant->resident && $tenant->resident->status === 'active')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-700 border border-green-200 rounded-full text-xs font-semibold">
                                    <i class="fas fa-check-circle"></i> Aktif
                                </span>
                            @elseif($tenant->resident && $tenant->resident->status === 'suspended')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 text-red-700 border border-red-200 rounded-full text-xs font-semibold">
                                    <i class="fas fa-ban"></i> Disuspend
                                </span>
                            @elseif($tenant->resident && $tenant->resident->status === 'inactive')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-full text-xs font-semibold">
                                    <i class="fas fa-clock"></i> Menunggu Aktivasi
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-100 text-gray-600 border border-gray-200 rounded-full text-xs font-semibold">
                                    <i class="fas fa-times-circle"></i> Tidak Aktif
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4 space-y-2.5">
                        <div class="flex items-center gap-3 text-sm text-gray-700">
                            <i class="fas fa-phone w-4 text-gray-400 text-xs"></i>
                            {{ $tenant->profile->phone ?? '-' }}
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-700">
                            <i class="fas fa-id-card w-4 text-gray-400 text-xs"></i>
                            {{ $tenant->profile->identity_number ?? '-' }}
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-700">
                            <i class="fas fa-venus-mars w-4 text-gray-400 text-xs"></i>
                            {{ $tenant->profile && $tenant->profile->gender ? ($tenant->profile->gender == 'male' ? 'Laki-laki' : 'Perempuan') : '-' }}
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-700">
                            <i class="fas fa-calendar w-4 text-gray-400 text-xs"></i>
                            {{ $tenant->profile?->date_of_birth?->format('d M Y') ?? '-' }}
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-700">
                            <i class="fas fa-briefcase w-4 text-gray-400 text-xs"></i>
                            {{ $tenant->profile->occupation ?? '-' }}
                        </div>
                    </div>
                </div>

                {{-- Room Info Card --}}
                @if($tenant->resident)
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 rounded-2xl shadow-sm p-5">
                        <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2 mb-4">
                            <i class="fas fa-home text-blue-500"></i> Informasi Kamar
                        </h3>

                        {{-- Room preview --}}
                        <div class="bg-white border border-blue-100 rounded-xl p-3 mb-4 flex gap-3 items-center">
                            <div class="w-14 h-14 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                @if($tenant->resident->room->image)
                                    <img src="{{ asset('storage/' . $tenant->resident->room->image) }}"
                                         class="w-full h-full object-cover" alt="Kamar">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300 text-xl">
                                        <i class="fas fa-door-open"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="font-semibold text-gray-900 text-sm truncate">{{ $tenant->resident->room->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $tenant->resident->room->property->name }}</p>
                                <p class="text-xs text-gray-400 truncate mt-0.5">{{ $tenant->resident->room->property->address }}</p>
                            </div>
                        </div>

                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Harga/bulan</span>
                                <span class="font-semibold text-gray-900">Rp {{ number_format($tenant->resident->room->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Mulai</span>
                                <span class="font-semibold text-gray-900">{{ $tenant->resident->start_date->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Berakhir</span>
                                <span class="font-semibold text-gray-900">{{ $tenant->resident->end_date->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between pt-2 border-t border-blue-100">
                                <span class="text-gray-500">Durasi</span>
                                <span class="font-semibold text-gray-900">{{ $tenant->resident->getDurationInMonths() }} Bulan</span>
                            </div>
                        </div>
                     {{-- Action buttons --}}
                        <div class="mt-4 pt-4 border-t border-blue-100 flex flex-col gap-2">
                            @if($tenant->resident->status === 'active')
                                <button type="button"
                                        onclick="openDeactivateModal('{{ $tenant->resident->id }}')"
                                        class="w-full py-2 bg-red-50 hover:bg-red-100 border border-red-200 text-red-700 text-sm font-semibold rounded-xl transition-colors">
                                    <i class="fas fa-ban mr-1"></i>Nonaktifkan
                                </button>

                            @elseif($tenant->resident->status === 'suspended')
                                <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-600">
                                    <i class="fas fa-ban mr-1"></i> <strong>Penghuni sedang disuspend.</strong>
                                </div>
                                <form action="{{ route('admin.tenants.activate', $tenant->resident) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            onclick="return confirm('Aktifkan kembali penghuni ini?')"
                                            class="w-full py-2 bg-green-50 hover:bg-green-100 border border-green-200 text-green-700 text-sm font-semibold rounded-xl transition-colors">
                                        <i class="fas fa-check-circle mr-1"></i>Aktifkan Kembali
                                    </button>
                                </form>

                            @elseif($tenant->resident->status === 'inactive')
                                <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-700">
                                    <i class="fas fa-clock mr-1"></i> Menunggu pembayaran pertama dari penghuni.
                                </div>
                                <form action="{{ route('admin.tenants.activate', $tenant->resident) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            onclick="return confirm('Aktifkan penghuni ini tanpa menunggu pembayaran?')"
                                            class="w-full py-2 bg-blue-50 hover:bg-blue-100 border border-blue-200 text-blue-700 text-sm font-semibold rounded-xl transition-colors">
                                        <i class="fas fa-check-circle mr-1"></i>Aktifkan Manual
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5">
                        <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2 mb-3">
                            <i class="fas fa-home text-gray-400"></i> Informasi Kamar
                        </h3>
                        <div class="text-center py-8">
                            <i class="fas fa-door-open text-4xl text-gray-200 mb-3"></i>
                            <p class="text-sm text-gray-500">Belum assign kamar</p>
                            <p class="text-xs text-gray-400 mt-1">atau pembayaran belum terverifikasi</p>
                        </div>
                    </div>
                @endif

            </div>

            {{-- RIGHT COLUMN --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Personal Info --}}
                <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">
                    <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2 mb-5">
                        <i class="fas fa-user text-blue-500"></i> Data Pribadi
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Nama Lengkap</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $tenant->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Email</p>
                            <p class="text-sm font-semibold text-gray-900 break-all">{{ $tenant->email }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">No. Telepon</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $tenant->profile->phone ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">No. KTP</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $tenant->profile->identity_number ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Tanggal Lahir</p>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $tenant->profile?->date_of_birth?->format('d F Y') ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Jenis Kelamin</p>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $tenant->profile && $tenant->profile->gender ? ($tenant->profile->gender == 'male' ? 'Laki-laki' : 'Perempuan') : '-' }}
                            </p>
                        </div>
                        <div class="sm:col-span-2">
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Alamat Asal</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $tenant->profile->address ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Pekerjaan</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $tenant->profile->occupation ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Terdaftar Sejak</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $tenant->created_at->format('d F Y') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Emergency Contact --}}
                <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">
                    <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2 mb-5">
                        <i class="fas fa-phone-alt text-red-500"></i> Kontak Darurat
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Nama Kontak</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $tenant->profile->emergency_contact_name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">No. Telepon</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $tenant->profile->emergency_contact ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Documents --}}
                <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">
                    <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2 mb-5">
                        <i class="fas fa-file-alt text-green-500"></i> Dokumen Identitas
                    </h3>

                    @php
                        $hasDocuments = $tenant->profile && ($tenant->profile->ktp_photo || $tenant->profile->sim_photo || $tenant->profile->passport_photo);
                    @endphp

                    @if($hasDocuments)
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">

                            {{-- KTP --}}
                            @if($tenant->profile->ktp_photo)
                                @php
                                    $ktpPath = 'storage/' . $tenant->profile->ktp_photo;
                                    $ktpExt = strtolower(pathinfo($tenant->profile->ktp_photo, PATHINFO_EXTENSION));
                                    $ktpIsImage = in_array($ktpExt, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                @endphp
                                <div class="border border-blue-100 bg-blue-50 rounded-xl p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 flex items-center gap-1.5">
                                                <i class="fas fa-id-card text-blue-500 text-xs"></i> KTP
                                            </p>
                                            <p class="text-xs text-gray-400">Kartu Tanda Penduduk</p>
                                        </div>
                                        <span class="text-xs font-semibold px-2 py-0.5 bg-green-100 text-green-700 rounded-full">✓ Ada</span>
                                    </div>
                                    <div class="bg-white rounded-lg border border-blue-100 overflow-hidden mb-3 h-28 flex items-center justify-center">
                                        @if($ktpIsImage)
                                            <img src="{{ asset($ktpPath) }}" alt="KTP"
                                                 class="w-full h-full object-contain cursor-pointer hover:opacity-90 transition-opacity"
                                                 onclick="openImageModal('{{ asset($ktpPath) }}', 'KTP - {{ $tenant->name }}')">
                                        @else
                                            <i class="fas fa-file-pdf text-3xl text-red-400"></i>
                                        @endif
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ asset($ktpPath) }}" target="_blank"
                                           class="flex-1 py-1.5 text-xs font-semibold text-center bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </a>
                                        <a href="{{ asset($ktpPath) }}" download
                                           class="flex-1 py-1.5 text-xs font-semibold text-center bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                                            <i class="fas fa-download mr-1"></i>Unduh
                                        </a>
                                    </div>
                                </div>
                            @endif

                            {{-- SIM --}}
                            @if($tenant->profile->sim_photo)
                                @php
                                    $simPath = 'storage/' . $tenant->profile->sim_photo;
                                    $simExt = strtolower(pathinfo($tenant->profile->sim_photo, PATHINFO_EXTENSION));
                                    $simIsImage = in_array($simExt, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                @endphp
                                <div class="border border-green-100 bg-green-50 rounded-xl p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 flex items-center gap-1.5">
                                                <i class="fas fa-id-card-alt text-green-500 text-xs"></i> SIM
                                            </p>
                                            <p class="text-xs text-gray-400">Surat Izin Mengemudi</p>
                                        </div>
                                        <span class="text-xs font-semibold px-2 py-0.5 bg-green-100 text-green-700 rounded-full">✓ Ada</span>
                                    </div>
                                    <div class="bg-white rounded-lg border border-green-100 overflow-hidden mb-3 h-28 flex items-center justify-center">
                                        @if($simIsImage)
                                            <img src="{{ asset($simPath) }}" alt="SIM"
                                                 class="w-full h-full object-contain cursor-pointer hover:opacity-90 transition-opacity"
                                                 onclick="openImageModal('{{ asset($simPath) }}', 'SIM - {{ $tenant->name }}')">
                                        @else
                                            <i class="fas fa-file-pdf text-3xl text-red-400"></i>
                                        @endif
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ asset($simPath) }}" target="_blank"
                                           class="flex-1 py-1.5 text-xs font-semibold text-center bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </a>
                                        <a href="{{ asset($simPath) }}" download
                                           class="flex-1 py-1.5 text-xs font-semibold text-center bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                                            <i class="fas fa-download mr-1"></i>Unduh
                                        </a>
                                    </div>
                                </div>
                            @endif

                            {{-- Passport --}}
                            @if($tenant->profile->passport_photo)
                                @php
                                    $passportPath = 'storage/' . $tenant->profile->passport_photo;
                                    $passportExt = strtolower(pathinfo($tenant->profile->passport_photo, PATHINFO_EXTENSION));
                                    $passportIsImage = in_array($passportExt, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                @endphp
                                <div class="border border-purple-100 bg-purple-50 rounded-xl p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 flex items-center gap-1.5">
                                                <i class="fas fa-passport text-purple-500 text-xs"></i> Passport
                                            </p>
                                            <p class="text-xs text-gray-400">Paspor</p>
                                        </div>
                                        <span class="text-xs font-semibold px-2 py-0.5 bg-green-100 text-green-700 rounded-full">✓ Ada</span>
                                    </div>
                                    <div class="bg-white rounded-lg border border-purple-100 overflow-hidden mb-3 h-28 flex items-center justify-center">
                                        @if($passportIsImage)
                                            <img src="{{ asset($passportPath) }}" alt="Passport"
                                                 class="w-full h-full object-contain cursor-pointer hover:opacity-90 transition-opacity"
                                                 onclick="openImageModal('{{ asset($passportPath) }}', 'Passport - {{ $tenant->name }}')">
                                        @else
                                            <i class="fas fa-file-pdf text-3xl text-red-400"></i>
                                        @endif
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ asset($passportPath) }}" target="_blank"
                                           class="flex-1 py-1.5 text-xs font-semibold text-center bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </a>
                                        <a href="{{ asset($passportPath) }}" download
                                           class="flex-1 py-1.5 text-xs font-semibold text-center bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                                            <i class="fas fa-download mr-1"></i>Unduh
                                        </a>
                                    </div>
                                </div>
                            @endif

                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-folder-open text-4xl text-gray-200 mb-3"></i>
                            <p class="text-sm text-gray-500">Belum ada dokumen yang diupload</p>
                            <p class="text-xs text-gray-400 mt-1">Penghuni belum mengupload dokumen identitas</p>
                        </div>
                    @endif
                </div>

                {{-- Payment History --}}
                @if($tenant->residents->count() > 0)
                    @php
                        $allPayments = collect();
                        foreach($tenant->residents as $resident) {
                            $allPayments = $allPayments->merge($resident->payments);
                        }
                        $allPayments = $allPayments->sortByDesc('billing_month')->take(10);
                    @endphp

                    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">
                        <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2 mb-5">
                            <i class="fas fa-receipt text-violet-500"></i> Riwayat Pembayaran
                        </h3>

                        @if($allPayments->count() > 0)
                            <div class="space-y-3">
                                @foreach($allPayments as $payment)
                                    @php $isOverdue = $payment->status === 'pending' && $payment->due_date->isPast(); @endphp
                                    <div class="flex items-center justify-between p-4 border rounded-xl transition-colors
                                        {{ $isOverdue ? 'border-red-200 bg-red-50' : 'border-gray-100 hover:bg-gray-50' }}">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $payment->billing_month->format('F Y') }}</p>
                                            <p class="text-xs text-gray-400 mt-0.5">Jatuh tempo: {{ $payment->due_date->format('d M Y') }}</p>
                                            @if($isOverdue)
                                                <p class="text-xs text-red-500 mt-0.5 font-medium">
                                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $payment->due_date->diffForHumans() }}
                                                </p>
                                            @endif
                                            @if($payment->status === 'paid' && $payment->paid_at)
                                                <p class="text-xs text-green-600 mt-0.5">
                                                    <i class="fas fa-check-circle mr-1"></i>Dibayar: {{ $payment->paid_at->format('d M Y H:i') }}
                                                </p>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-bold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                            <div class="mt-1">
                                                @if($payment->status === 'paid')
                                                    <span class="text-xs font-semibold px-2.5 py-0.5 bg-green-50 text-green-700 border border-green-200 rounded-full">
                                                        <i class="fas fa-check-circle mr-0.5"></i>Lunas
                                                    </span>
                                                @elseif($isOverdue)
                                                    <span class="text-xs font-semibold px-2.5 py-0.5 bg-red-100 text-red-700 border border-red-300 rounded-full animate-pulse">
                                                        <i class="fas fa-exclamation-circle mr-0.5"></i>Terlambat
                                                    </span>
                                                @elseif($payment->status === 'pending')
                                                    <span class="text-xs font-semibold px-2.5 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-full">
                                                        <i class="fas fa-clock mr-0.5"></i>Pending
                                                    </span>
                                                @elseif($payment->status === 'failed')
                                                    <span class="text-xs font-semibold px-2.5 py-0.5 bg-red-50 text-red-700 border border-red-200 rounded-full">
                                                        <i class="fas fa-times-circle mr-0.5"></i>Gagal
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="fas fa-wallet text-4xl text-gray-200 mb-3"></i>
                                <p class="text-sm text-gray-500">Belum ada riwayat pembayaran</p>
                            </div>
                        @endif
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>

{{-- Image Modal --}}
<div id="imageModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden items-center justify-center z-50 p-4" onclick="if(event.target===this) closeImageModal()">
    <div class="relative max-w-3xl w-full">
        <button onclick="closeImageModal()"
                class="absolute -top-10 right-0 text-white/70 hover:text-white text-2xl transition-colors">
            <i class="fas fa-times"></i>
        </button>
        <img id="modalImage" src="" alt="" class="w-full h-auto rounded-2xl shadow-2xl">
        <p id="modalCaption" class="text-white/70 text-center mt-3 text-sm"></p>
    </div>
</div>

{{-- Deactivate Modal --}}
<div id="deactivateModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-ban text-red-600"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-gray-900">Nonaktifkan Penghuni</h3>
                <p class="text-xs text-gray-400">Pilih alasan penonaktifan</p>
            </div>
        </div>

        <form id="deactivateForm" method="POST">
            @csrf

            <div class="space-y-3 mb-5">
                <label class="flex items-start gap-3 p-3 border-2 border-gray-100 rounded-xl cursor-pointer hover:border-red-200 hover:bg-red-50 transition-all has-[:checked]:border-red-400 has-[:checked]:bg-red-50">
                    <input type="radio" name="reason" value="late_payment" class="mt-0.5 accent-red-500" required>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Telat Bayar</p>
                        <p class="text-xs text-gray-400 mt-0.5">Penghuni belum membayar tagihan yang jatuh tempo</p>
                    </div>
                </label>

                <label class="flex items-start gap-3 p-3 border-2 border-gray-100 rounded-xl cursor-pointer hover:border-amber-200 hover:bg-amber-50 transition-all has-[:checked]:border-amber-400 has-[:checked]:bg-amber-50">
                    <input type="radio" name="reason" value="violation" class="mt-0.5 accent-amber-500" required>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Pelanggaran Peraturan</p>
                        <p class="text-xs text-gray-400 mt-0.5">Penghuni melanggar peraturan kos</p>
                    </div>
                </label>

                <label class="flex items-start gap-3 p-3 border-2 border-gray-100 rounded-xl cursor-pointer hover:border-gray-300 hover:bg-gray-50 transition-all has-[:checked]:border-gray-400 has-[:checked]:bg-gray-50">
                    <input type="radio" name="reason" value="checkout" class="mt-0.5 accent-gray-500" required>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Penghuni Keluar</p>
                        <p class="text-xs text-gray-400 mt-0.5">Penghuni sudah tidak tinggal di kos ini</p>
                    </div>
                </label>
            </div>

            <div class="flex gap-3">
                <button type="button"
                        onclick="closeDeactivateModal()"
                        class="flex-1 py-2.5 text-sm font-semibold border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 py-2.5 text-sm font-semibold bg-red-600 hover:bg-red-700 text-white rounded-xl transition-colors">
                    <i class="fas fa-ban mr-1"></i> Nonaktifkan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openDeactivateModal(residentId) {
    document.getElementById('deactivateForm').action = `/admin/tenants/${residentId}/deactivate`;
    const modal = document.getElementById('deactivateModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}
function closeDeactivateModal() {
    const modal = document.getElementById('deactivateModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
    document.querySelectorAll('#deactivateForm input[type=radio]').forEach(r => r.checked = false);
}
document.getElementById('deactivateModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeactivateModal();
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeDeactivateModal();
});
</script>

<script>
function openImageModal(src, caption) {
    const modal = document.getElementById('imageModal');
    document.getElementById('modalImage').src = src;
    document.getElementById('modalCaption').textContent = caption;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}
function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeImageModal(); });
</script>

</x-app-layout>