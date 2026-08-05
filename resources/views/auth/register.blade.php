<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Penghuni - Cemara Living & Residence</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0B132B; }
        .font-heading { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8 selection:bg-amber-500 selection:text-slate-950">

    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[640px] border border-slate-200/80">
        
        <!-- LEFT BRAND & VISUAL BANNER (5 COLS) -->
        <div class="hidden lg:flex lg:col-span-5 bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 p-10 text-white flex-col justify-between relative overflow-hidden">
            <div class="absolute -top-20 -left-20 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 space-y-6">
                <!-- Brand Logo -->
                <a href="{{ route('landing') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-2xl bg-amber-500 text-slate-950 flex items-center justify-center font-black text-lg shadow-lg">
                        <i class="fas fa-building"></i>
                    </div>
                    <div>
                        <div class="text-xl font-extrabold text-white tracking-tight">
                            Cemara<span class="text-amber-400 font-light ml-1">Residence</span>
                        </div>
                        <div class="text-[9px] tracking-widest uppercase font-bold text-slate-400">
                            Luxury Boutique Living
                        </div>
                    </div>
                </a>

                <div class="pt-8 space-y-4">
                    <span class="px-3 py-1 rounded-full bg-amber-500/20 text-amber-400 font-bold text-[11px] uppercase tracking-wider border border-amber-400/30">
                        Pendaftaran Tamu & Penghuni
                    </span>
                    <h2 class="text-2xl font-black font-heading leading-tight text-white">
                        Mulai Pengalaman Tinggal Mewah Anda
                    </h2>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Daftar akun gratis untuk melakukan booking kamar impian, bayar sewa instan dengan invoice otomatis, dan akses portal eksklusif penghuni.
                    </p>
                </div>
            </div>

            <!-- Bottom Highlights -->
            <div class="relative z-10 pt-8 border-t border-slate-800 space-y-2 text-xs text-slate-400">
                <div class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-emerald-400"></i>
                    <span>Proses Reservasi Cepat & Tanpa Ribet</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-bolt text-amber-400"></i>
                    <span>Pembayaran Terhubung Langsung ke Midtrans</span>
                </div>
            </div>
        </div>

        <!-- RIGHT REGISTER FORM (7 COLS) -->
        <div class="lg:col-span-7 p-8 sm:p-12 flex flex-col justify-between bg-white">
            
            <div class="space-y-6 max-w-md mx-auto w-full">
                
                <!-- Top Back to Home -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('landing') }}" class="text-xs font-bold text-slate-500 hover:text-amber-600 flex items-center gap-1.5 transition-colors">
                        <i class="fas fa-arrow-left text-[10px]"></i> Kembali ke Beranda
                    </a>
                    <span class="text-xs font-bold text-slate-400 lg:hidden">Cemara Residence</span>
                </div>

                <!-- Form Header -->
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black font-heading text-slate-900 tracking-tight">
                        Buat Akun Penghuni
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        Lengkapi informasi di bawah ini untuk memulai registrasi.
                    </p>
                </div>

                <!-- Register Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-3.5">
                    @csrf

                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Nama Lengkap (Sesuai KTP)
                        </label>
                        <div class="relative">
                            <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus 
                                   placeholder="Contoh: Budi Santoso" 
                                   class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl text-sm font-medium focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all outline-none">
                        </div>
                        @error('name')
                            <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Alamat Email
                        </label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required 
                                   placeholder="nama@email.com" 
                                   class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl text-sm font-medium focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all outline-none">
                        </div>
                        @error('email')
                            <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- WhatsApp Phone Number -->
                    <div>
                        <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Nomor WhatsApp Aktif
                        </label>
                        <div class="relative">
                            <i class="fab fa-whatsapp absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required 
                                   placeholder="081234567890" 
                                   class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl text-sm font-medium focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all outline-none">
                        </div>
                        @error('phone')
                            <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Kata Sandi
                            </label>
                            <input id="password" type="password" name="password" required 
                                   placeholder="Min. 8 karakter" 
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl text-sm font-medium focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all outline-none">
                            @error('password')
                                <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Ulangi Sandi
                            </label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required 
                                   placeholder="Ulangi sandi" 
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl text-sm font-medium focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all outline-none">
                            @error('password_confirmation')
                                <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit CTA Button -->
                    <div class="pt-3">
                        <button type="submit" 
                                class="w-full py-3.5 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 hover:from-amber-500 hover:to-amber-600 hover:text-slate-950 text-white font-black text-sm rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 flex items-center justify-center gap-2">
                            <span>Daftar Sekarang</span>
                            <i class="fas fa-user-plus"></i>
                        </button>
                    </div>
                </form>

                <!-- Switch to Login -->
                <div class="text-center pt-3 border-t border-slate-100">
                    <p class="text-xs text-slate-500">
                        Sudah memiliki akun? 
                        <a href="{{ route('login') }}" class="font-bold text-amber-600 hover:text-amber-700 ml-1">
                            Masuk ke Akun
                        </a>
                    </p>
                </div>

            </div>

            <!-- Bottom Copyright -->
            <div class="text-center text-[11px] text-slate-400 pt-4">
                &copy; {{ date('Y') }} Cemara Living & Residence. All rights reserved.
            </div>

        </div>

    </div>

</body>
</html>
