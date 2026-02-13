<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - RumahKos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ── Secure document canvas ── */
        .doc-canvas-wrap {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            background: #1e2330;
            line-height: 0;
            user-select: none;
            -webkit-user-select: none;
        }
        .doc-canvas-wrap canvas {
            width: 100%;
            height: auto;
            display: block;
            pointer-events: none;
        }
        .doc-canvas-wrap::after {
            content: '';
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(
                45deg,
                rgba(255,255,255,0.018) 0px,
                rgba(255,255,255,0.018) 1px,
                transparent 1px,
                transparent 8px
            );
            pointer-events: none;
        }
        .doc-secure-badge {
            position: absolute;
            bottom: 8px;
            right: 8px;
            background: rgba(0,0,0,0.55);
            backdrop-filter: blur(4px);
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            gap: 4px;
            pointer-events: none;
            letter-spacing: .04em;
        }
        .doc-canvas-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 28px 16px;
            color: rgba(255,255,255,0.35);
            font-size: 12px;
        }

        /* ── Upload drop zone ── */
        .upload-dropzone {
            border: 2px dashed #d1d5db;
            border-radius: 10px;
            padding: 18px 12px;
            text-align: center;
            cursor: pointer;
            transition: border-color .2s, background .2s;
            background: #f9fafb;
        }
        .upload-dropzone:hover,
        .upload-dropzone.dragover {
            border-color: #3b82f6;
            background: #eff6ff;
        }
        .upload-dropzone input[type="file"] { display: none; }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Header -->
    @include('landing.navbar-dashboard')

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
                    @php $unpaidPayments = $resident->payments()->where('status', 'pending')->count(); @endphp
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

                <!-- ══════════════ PROFILE TAB ══════════════ -->
                <div id="content-profile" class="tab-content">
                    <form method="POST" action="{{ route('tenant.profile.update') }}"
                          enctype="multipart/form-data" class="space-y-6" id="profileForm">
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
                                    <input type="tel" name="phone"
                                           value="{{ old('phone', $user->profile->phone ?? '') }}"
                                           placeholder="08xxxxxxxxxx"
                                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">No. KTP</label>
                                    <input type="text" name="identity_number"
                                           value="{{ old('identity_number', $user->profile->identity_number ?? '') }}"
                                           placeholder="16 digit" maxlength="16"
                                           oninput="this.value=this.value.replace(/\D/g,'')"
                                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono tracking-widest">
                                    @error('identity_number')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>

                                {{--
                                    Tanggal Lahir:
                                    - max di-set PHP dari controller via $maxDob (17 tahun ke belakang)
                                    - Validasi utama ada di PHP (DashboardController::updateProfile)
                                    - max attribute di bawah hanya untuk UX (browser date picker)
                                --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Tanggal Lahir
                                        <span class="text-xs font-normal text-gray-400">(min. 17 tahun)</span>
                                    </label>
                                    <input type="date" name="date_of_birth" id="dobInput"
                                           value="{{ old('date_of_birth', $user->profile->date_of_birth?->format('Y-m-d') ?? '') }}"
                                           max="{{ \Carbon\Carbon::now()->subYears(17)->format('Y-m-d') }}"
                                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_of_birth') border-red-400 @enderror">
                                    @error('date_of_birth')
                                        <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Kelamin</label>
                                    <select name="gender"
                                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Pilih</option>
                                        <option value="male"   {{ old('gender', $user->profile->gender ?? '') == 'male'   ? 'selected' : '' }}>Laki-laki</option>
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
                                    <input type="text" name="occupation"
                                           value="{{ old('occupation', $user->profile->occupation ?? '') }}"
                                           placeholder="Mahasiswa / Karyawan"
                                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Kontak Darurat</label>
                                    <input type="text" name="emergency_contact_name"
                                           value="{{ old('emergency_contact_name', $user->profile->emergency_contact_name ?? '') }}"
                                           placeholder="Nama keluarga"
                                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">No. Kontak Darurat</label>
                                    <input type="tel" name="emergency_contact"
                                           value="{{ old('emergency_contact', $user->profile->emergency_contact ?? '') }}"
                                           placeholder="08xxx"
                                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>

                            </div>
                        </div>

                        <!-- ── Dokumen Identitas ── -->
                        <div class="pt-6 border-t">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Dokumen Identitas</h3>
                            <p class="text-sm text-gray-500 mb-4 flex items-center gap-1.5">
                                <i class="fas fa-shield-alt text-blue-500 text-xs"></i>
                                Dokumen hanya bisa diakses oleh Anda dan admin — tidak dapat dibuka atau disalin.
                                Format: JPG, PNG (Maks. 2MB)
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                {{-- ════ KTP ════ --}}
                                <div class="p-4 border rounded-lg">
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <h4 class="font-medium text-gray-900">
                                                KTP <span class="text-xs text-red-600">(Wajib)</span>
                                            </h4>
                                            <p class="text-xs text-gray-500">Kartu Tanda Penduduk</p>
                                        </div>
                                        @if($user->profile && $user->profile->ktp_photo)
                                            <span class="inline-flex items-center gap-1 text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full">
                                                <i class="fas fa-check text-xs"></i> Terupload
                                            </span>
                                        @endif
                                    </div>

                                    @if($user->profile && $user->profile->ktp_photo)
                                        {{--
                                            Preview aman:
                                            - Gambar di-fetch dari route controller (auth:tenant),
                                              bukan dari /storage/... langsung
                                            - Di browser: gambar dikonversi ke blob URL sementara,
                                              lalu digambar ke canvas, blob langsung di-revoke
                                            - Tidak ada <img src>, tidak ada href, tidak ada URL yang
                                              tersisa di DOM setelah render selesai
                                        --}}
                                        <div class="doc-canvas-wrap mb-3" id="ktp-existing-wrap">
                                            <canvas id="ktp-existing-canvas"></canvas>
                                            <div class="doc-secure-badge">
                                                <i class="fas fa-lock" style="font-size:9px;"></i> Dokumen Aman
                                            </div>
                                        </div>
                                        {{-- Route disimpan di JS variable, tidak di atribut HTML --}}
                                        <script>
                                            (function () {
                                                var _r = @json(route('tenant.document.ktp'));
                                                document.addEventListener('DOMContentLoaded', function () {
                                                    fetchAndRenderSecure(_r, 'ktp-existing-canvas', 'ktp-existing-wrap');
                                                });
                                            })();
                                        </script>
                                        <label class="flex items-center gap-2 text-xs cursor-pointer mt-2">
                                            <input type="checkbox" name="delete_ktp" value="1" class="rounded accent-red-500">
                                            <span class="text-red-600">Hapus &amp; ganti dokumen</span>
                                        </label>
                                    @endif

                                    <div class="upload-dropzone mt-3"
                                         onclick="document.getElementById('ktp_photo').click()"
                                         ondragover="event.preventDefault();this.classList.add('dragover')"
                                         ondragleave="this.classList.remove('dragover')"
                                         ondrop="handleDrop(event,'ktp_photo','ktp-new-wrap')">
                                        <input type="file" id="ktp_photo" name="ktp_photo"
                                               accept="image/jpeg,image/png,image/jpg"
                                               onchange="handleFileChange(this,'ktp-new-wrap')">
                                        <i class="fas fa-cloud-upload-alt text-2xl text-gray-300 mb-1"></i>
                                        <p class="text-sm text-gray-600 font-medium">Klik atau seret foto KTP</p>
                                        <p class="text-xs text-gray-400 mt-0.5">JPG / PNG · maks. 2 MB</p>
                                    </div>
                                    <div id="ktp-new-wrap" class="mt-3"></div>
                                    @error('ktp_photo')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror

                                    <div class="mt-3 p-3 bg-blue-50 rounded-lg text-xs">
                                        <p class="font-medium text-gray-700 mb-1.5">
                                            <i class="fas fa-info-circle text-blue-500 mr-1"></i>Panduan Foto KTP:
                                        </p>
                                        <ul class="text-gray-600 space-y-0.5">
                                            <li>• Foto harus jelas &amp; tidak blur</li>
                                            <li>• Semua teks &amp; angka terlihat</li>
                                            <li>• Tidak ada pantulan cahaya</li>
                                            <li>• Seluruh kartu terlihat (tidak terpotong)</li>
                                        </ul>
                                    </div>
                                </div>

                                {{-- ════ SIM ════ --}}
                                <div class="p-4 border rounded-lg">
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <h4 class="font-medium text-gray-900">SIM</h4>
                                            <p class="text-xs text-gray-500">
                                                Surat Izin Mengemudi
                                                <span class="text-gray-400">(Opsional)</span>
                                            </p>
                                        </div>
                                        @if($user->profile && $user->profile->sim_photo)
                                            <span class="inline-flex items-center gap-1 text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full">
                                                <i class="fas fa-check text-xs"></i> Terupload
                                            </span>
                                        @endif
                                    </div>

                                    @if($user->profile && $user->profile->sim_photo)
                                        <div class="doc-canvas-wrap mb-3" id="sim-existing-wrap">
                                            <canvas id="sim-existing-canvas"></canvas>
                                            <div class="doc-secure-badge">
                                                <i class="fas fa-lock" style="font-size:9px;"></i> Dokumen Aman
                                            </div>
                                        </div>
                                        <script>
                                            (function () {
                                                var _r = @json(route('tenant.document.sim'));
                                                document.addEventListener('DOMContentLoaded', function () {
                                                    fetchAndRenderSecure(_r, 'sim-existing-canvas', 'sim-existing-wrap');
                                                });
                                            })();
                                        </script>
                                        <label class="flex items-center gap-2 text-xs cursor-pointer mt-2">
                                            <input type="checkbox" name="delete_sim" value="1" class="rounded accent-red-500">
                                            <span class="text-red-600">Hapus &amp; ganti dokumen</span>
                                        </label>
                                    @endif

                                    <div class="upload-dropzone mt-3"
                                         onclick="document.getElementById('sim_photo').click()"
                                         ondragover="event.preventDefault();this.classList.add('dragover')"
                                         ondragleave="this.classList.remove('dragover')"
                                         ondrop="handleDrop(event,'sim_photo','sim-new-wrap')">
                                        <input type="file" id="sim_photo" name="sim_photo"
                                               accept="image/jpeg,image/png,image/jpg"
                                               onchange="handleFileChange(this,'sim-new-wrap')">
                                        <i class="fas fa-id-card text-2xl text-gray-300 mb-1"></i>
                                        <p class="text-sm text-gray-600 font-medium">Klik atau seret foto SIM</p>
                                        <p class="text-xs text-gray-400 mt-0.5">JPG / PNG · maks. 2 MB</p>
                                    </div>
                                    <div id="sim-new-wrap" class="mt-3"></div>
                                    @error('sim_photo')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror

                                    <div class="mt-3 p-3 bg-blue-50 rounded-lg text-xs">
                                        <p class="font-medium text-gray-700 mb-1.5">
                                            <i class="fas fa-info-circle text-blue-500 mr-1"></i>Panduan Foto SIM:
                                        </p>
                                        <ul class="text-gray-600 space-y-0.5">
                                            <li>• Foto harus jelas &amp; tidak blur</li>
                                            <li>• Semua teks &amp; angka terlihat</li>
                                            <li>• Seluruh kartu terlihat (tidak terpotong)</li>
                                        </ul>
                                    </div>
                                </div>

                            </div>{{-- /grid dokumen --}}
                        </div>{{-- /dokumen --}}

                        <!-- Submit -->
                        <div class="pt-4 border-t">
                            <button type="submit"
                                    class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors">
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>{{-- /profile tab --}}

                <!-- ══════════════ KOS TAB ══════════════ -->
                <div id="content-kos" class="tab-content hidden">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kos Saya</h3>

                    @if($resident)
                        <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-lg p-5 mb-4">
                            <div class="flex gap-4">
                                <div class="w-20 h-20 bg-white rounded-lg overflow-hidden flex-shrink-0">
                                    @if($resident->room->image)
                                        <img src="{{ asset('storage/' . $resident->room->image) }}"
                                             alt="{{ $resident->room->name }}" class="w-full h-full object-cover">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=80&h=80&fit=crop"
                                             alt="Kamar" class="w-full h-full object-cover">
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
                                <p class="text-sm font-medium">
                                    {{ $resident->start_date->format('d M Y') }} - {{ $resident->end_date->format('d M Y') }}
                                </p>
                            </div>
                            <div class="border rounded-lg p-3">
                                <p class="text-xs text-gray-600 mb-1">Harga per Bulan</p>
                                <p class="text-sm font-medium">Rp {{ number_format($resident->room->price, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <a href="{{ route('tenant.bookings.index') }}"
                           class="inline-block px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                            Detail Pembayaran
                        </a>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-home text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-600 mb-4">Anda belum terdaftar sebagai penghuni kos</p>
                            <a href="/#kamar"
                               class="inline-block px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                Cari Kamar
                            </a>
                        </div>
                    @endif
                </div>

                <!-- ══════════════ PAYMENT TAB ══════════════ -->
                <div id="content-payment" class="tab-content hidden">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Riwayat Pembayaran</h3>
                        @if($resident)
                            <a href="{{ route('tenant.bookings.index') }}" class="text-sm text-blue-600 hover:underline">
                                Lihat Semua
                            </a>
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
                                            <p class="font-semibold text-gray-900">
                                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                            </p>
                                            @if($payment->status === 'paid')
                                                <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded">Lunas</span>
                                            @elseif($payment->status === 'pending')
                                                <a href="{{ route('tenant.payment.midtrans', $payment->id) }}"
                                                   class="text-xs px-2 py-1 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200">
                                                    Bayar
                                                </a>
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

            </div>{{-- /tab content --}}
        </div>{{-- /tabs --}}

    </div>{{-- /main --}}

    <script>
    /* ═══════════════════════════════════════════════
       TAB NAVIGATION
    ═══════════════════════════════════════════════ */
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

    /* ═══════════════════════════════════════════════
       SECURE DOCUMENT FETCH + CANVAS RENDER
       ───────────────────────────────────────────────
       Alur:
       1. fetch() ke route controller (bukan /storage/...)
          → hanya berhasil jika user sudah login (middleware auth:tenant)
       2. Response binary diubah ke Blob
       3. createObjectURL() dipakai sementara untuk load Image
       4. Image di-draw ke canvas
       5. URL blob langsung di-revoke → tidak ada URL yang tersisa di memori
       6. Canvas punya pointer-events:none + contextmenu diblokir
       Hasilnya: gambar tampil, tapi URL-nya tidak bisa diambil dari mana pun.
    ═══════════════════════════════════════════════ */
    function fetchAndRenderSecure(route, canvasId, wrapId) {
        const canvas = document.getElementById(canvasId);
        const wrap   = document.getElementById(wrapId);
        if (!canvas || !wrap) return;

        fetch(route, {
            method: 'GET',
            credentials: 'same-origin', // kirim session cookie agar auth:tenant bekerja
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
        .then(function (res) {
            if (!res.ok) throw new Error('Akses ditolak atau file tidak ditemukan.');
            return res.blob();
        })
        .then(function (blob) {
            // Blob URL bersifat sementara di tab ini saja, tidak bisa dibagikan
            var blobUrl = URL.createObjectURL(blob);
            var img = new Image();

            img.onload = function () {
                canvas.width  = img.naturalWidth;
                canvas.height = img.naturalHeight;
                canvas.getContext('2d').drawImage(img, 0, 0);

                // Revoke segera setelah draw — URL blob tidak lagi valid
                URL.revokeObjectURL(blobUrl);
                img.src = '';
            };

            img.onerror = function () {
                URL.revokeObjectURL(blobUrl);
                showDocPlaceholder(wrap);
            };

            img.src = blobUrl;
        })
        .catch(function () {
            showDocPlaceholder(wrap);
        });

        // Blokir klik kanan & drag pada canvas
        canvas.addEventListener('contextmenu', function (e) { e.preventDefault(); });
        canvas.addEventListener('dragstart',   function (e) { e.preventDefault(); });
    }

    function showDocPlaceholder(wrap) {
        wrap.innerHTML =
            '<div class="doc-canvas-placeholder">' +
                '<i class="fas fa-file-image" style="font-size:28px;"></i>' +
                '<span>Dokumen tersimpan di server</span>' +
            '</div>';
    }

    /* ═══════════════════════════════════════════════
       FILE UPLOAD — validasi + preview aman via canvas
       File yang baru dipilih juga tidak pernah jadi <img src>
    ═══════════════════════════════════════════════ */
    var MAX_BYTES = 2 * 1024 * 1024;
    var OK_TYPES  = ['image/jpeg', 'image/jpg', 'image/png'];

    function handleDrop(event, inputId, wrapId) {
        event.preventDefault();
        event.currentTarget.classList.remove('dragover');
        var file = event.dataTransfer && event.dataTransfer.files && event.dataTransfer.files[0];
        if (!file) return;
        try {
            var dt = new DataTransfer();
            dt.items.add(file);
            document.getElementById(inputId).files = dt.files;
        } catch (e) {}
        processFile(file, wrapId, null);
    }

    function handleFileChange(input, wrapId) {
        var file = input.files && input.files[0];
        if (!file) return;
        processFile(file, wrapId, input);
    }

    function processFile(file, wrapId, inputEl) {
        var wrap = document.getElementById(wrapId);
        if (!wrap) return;
        wrap.innerHTML = '';

        if (OK_TYPES.indexOf(file.type) === -1) {
            wrap.innerHTML = fileErr('Format tidak valid. Gunakan JPG atau PNG.');
            if (inputEl) inputEl.value = '';
            return;
        }
        if (file.size > MAX_BYTES) {
            wrap.innerHTML = fileErr('Ukuran ' + (file.size / 1024 / 1024).toFixed(2) + ' MB melebihi batas 2 MB.');
            if (inputEl) inputEl.value = '';
            return;
        }

        var reader = new FileReader();
        reader.onload = function (ev) {
            var img = new Image();
            img.onload = function () {
                var outer  = document.createElement('div');
                outer.className = 'doc-canvas-wrap';

                var canvas = document.createElement('canvas');
                canvas.width  = img.naturalWidth;
                canvas.height = img.naturalHeight;
                canvas.getContext('2d').drawImage(img, 0, 0);
                img.src = '';

                var badge = document.createElement('div');
                badge.className = 'doc-secure-badge';
                badge.innerHTML = '<i class="fas fa-lock" style="font-size:9px;"></i> Pratinjau Aman';

                outer.appendChild(canvas);
                outer.appendChild(badge);

                var info = document.createElement('p');
                info.className = 'text-xs text-green-600 mt-2 flex items-center gap-1';
                info.innerHTML = '<i class="fas fa-check-circle"></i> ' + file.name + ' &nbsp;&middot;&nbsp; ' + (file.size / 1024).toFixed(1) + ' KB';

                wrap.appendChild(outer);
                wrap.appendChild(info);

                canvas.addEventListener('contextmenu', function (e) { e.preventDefault(); });
                canvas.addEventListener('dragstart',   function (e) { e.preventDefault(); });
            };
            img.src = ev.target.result;
        };
        reader.readAsDataURL(file);
    }

    function fileErr(msg) {
        return '<p class="text-xs text-red-600 mt-1 flex items-center gap-1"><i class="fas fa-times-circle"></i> ' + msg + '</p>';
    }
    </script>

</body>
</html>
