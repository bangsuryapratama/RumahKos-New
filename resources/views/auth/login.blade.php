<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Akun - Cemara Living & Residence</title>
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

    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[600px] border border-slate-200/80">
        
        <!-- LEFT BRAND & VISUAL BANNER (5 COLS ON DESKTOP) -->
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
                        Guest & Resident Portal
                    </span>
                    <h2 class="text-2xl font-black font-heading leading-tight text-white">
                        Satu Pintu Masuk Menuju Kenyamanan Eksklusif Anda
                    </h2>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Kelola reservasi sewa, lakukan pembayaran instan Midtrans QRIS/VA, unduh kwitansi resmi, dan nikmati layanan concierge kami.
                    </p>
                </div>
            </div>

            <!-- Bottom Highlights -->
            <div class="relative z-10 pt-8 border-t border-slate-800 space-y-2 text-xs text-slate-400">
                <div class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-emerald-400"></i>
                    <span>Sistem Otomatis Deteksi Akun Admin & Penghuni</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-shield-alt text-amber-400"></i>
                    <span>Enkripsi Data Sandi Standar 256-Bit</span>
                </div>
            </div>
        </div>

        <!-- RIGHT LOGIN FORM (7 COLS) -->
        <div class="lg:col-span-7 p-8 sm:p-12 flex flex-col justify-between bg-white">
            
            <div class="space-y-6 max-w-md mx-auto w-full">
                
                <!-- Top Back to Home & Mobile Logo -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('landing') }}" class="text-xs font-bold text-slate-500 hover:text-amber-600 flex items-center gap-1.5 transition-colors">
                        <i class="fas fa-arrow-left text-[10px]"></i> Kembali ke Beranda
                    </a>
                    <span class="text-xs font-bold text-slate-400 lg:hidden">Cemara Residence</span>
                </div>

                <!-- Form Header -->
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black font-heading text-slate-900 tracking-tight">
                        Selamat Datang Kembali
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        Masukkan email dan kata sandi Anda untuk mengakses akun.
                    </p>
                </div>

                <!-- Session Status / Errors -->
                @if (session('status'))
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold">
                        {{ session('status') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Alamat Email
                        </label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                                   placeholder="nama@email.com" 
                                   class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl text-sm font-medium focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all outline-none">
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Kata Sandi
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-bold text-amber-600 hover:text-amber-700">
                                    Lupa sandi?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input id="password" type="password" name="password" required 
                                   placeholder="••••••••" 
                                   class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl text-sm font-medium focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all outline-none">
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="flex items-center pt-1">
                        <label class="flex items-center gap-2 text-xs font-semibold text-slate-600 cursor-pointer select-none">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded text-amber-600 focus:ring-amber-500 border-slate-300">
                            <span>Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    <!-- Submit CTA Button -->
                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full py-3.5 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 hover:from-amber-500 hover:to-amber-600 hover:text-slate-950 text-white font-black text-sm rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 flex items-center justify-center gap-2">
                            <span>Masuk Sekarang</span>
                            <i class="fas fa-sign-in-alt"></i>
                        </button>
                    </div>
                </form>

                <!-- Switch to Register -->
                <div class="text-center pt-4 border-t border-slate-100">
                    <p class="text-xs text-slate-500">
                        Belum memiliki akun penghuni? 
                        <a href="{{ route('register') }}" class="font-bold text-amber-600 hover:text-amber-700 ml-1">
                            Daftar Akun Baru
                        </a>
                    </p>
                </div>

            </div>

            <!-- Bottom Copyright -->
            <div class="text-center text-[11px] text-slate-400 pt-6">
                &copy; {{ date('Y') }} Cemara Living & Residence. All rights reserved.
            </div>

        </div>

    </div>

</body>
</html>