<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Penghuni - RumahKos</title>
    <link rel="icon" type="image/webp" href="{{ asset('favicon.webp') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.webp') }}">
    <link rel="preload" as="image" href="{{ asset('images/auth-login.webp') }}" type="image/webp" fetchpriority="high">
    <link rel="preload" as="image" href="{{ asset('images/auth-register.webp') }}" type="image/webp">
    <link rel="preload" as="image" href="{{ asset('images/icons/logo-rumahkos.webp') }}" type="image/webp">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes shimmerMove {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .shimmer-dark {
            background: linear-gradient(90deg, #0f172a 25%, #1e293b 50%, #0f172a 75%);
            background-size: 200% 100%;
            animation: shimmerMove 1.3s infinite linear;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-100 lg:bg-white text-slate-900 antialiased flex flex-col lg:flex-row">

    {{-- ══════════════════════════════════════════════════════════════════
         PANEL KIRI (DESKTOP lg+): BANNER EKSTERIOR SENJA CO-LIVING
    ══════════════════════════════════════════════════════════════════ --}}
    <div class="hidden lg:flex lg:w-1/2 xl:w-[52%] relative overflow-hidden bg-slate-900 shimmer-dark p-10 xl:p-12 flex-col justify-between">
        <img src="{{ asset('images/auth-login.webp') }}"
             alt="RumahKos Login"
             fetchpriority="high"
             decoding="sync"
             onload="this.parentElement.classList.remove('shimmer-dark')"
             class="absolute inset-0 w-full h-full object-cover object-center pointer-events-none">

        <div class="absolute inset-0 bg-gradient-to-b from-slate-950/80 via-slate-900/40 to-slate-950/90 pointer-events-none"></div>

        {{-- Top Brand & Back Link --}}
        <div class="relative z-10 flex items-center justify-between">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-3 group">
                <img src="{{ asset('images/icons/logo-rumahkos.webp') }}" alt="RumahKos" width="40" height="40"
                     decoding="sync"
                     class="w-10 h-10 rounded-xl shadow-lg group-hover:scale-105 transition-transform">
                <span class="text-xl font-extrabold tracking-tight text-white">
                    Rumah<span class="text-sky-400">Kos</span>
                </span>
            </a>

            <a href="{{ url('/') }}"
               class="inline-flex items-center gap-2 text-xs font-bold text-white/90 hover:text-white bg-white/10 hover:bg-white/20 backdrop-blur-md px-3.5 py-2 rounded-xl border border-white/15 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Kembali ke Beranda</span>
            </a>
        </div>

        {{-- Bottom Copywriting & Perks --}}
        <div class="relative z-10 max-w-lg">
            <h2 class="text-3xl xl:text-4xl font-extrabold text-white leading-tight tracking-tight mb-3 drop-shadow">
                Satu akun untuk semua urusan ngekos kamu.
            </h2>
            <p class="text-white/85 text-sm xl:text-base leading-relaxed mb-7">
                Booking kamar, pantau tagihan bulanan, hingga bayar kos via QRIS &amp; Virtual Account langsung dari dashboard penghuni.
            </p>

            <div class="grid grid-cols-3 gap-3 pt-6 border-t border-white/15">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-white/10 backdrop-blur-sm border border-white/15 flex items-center justify-center shrink-0">
                        <img src="{{ asset('images/icons/ic-location.webp') }}" alt="" decoding="sync" class="w-5 h-5 object-contain">
                    </div>
                    <div>
                        <div class="text-xs font-extrabold text-white">Multi-Kota</div>
                        <div class="text-[11px] text-white/70">Dekat kampus &amp; kantor</div>
                    </div>
                </div>
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-white/10 backdrop-blur-sm border border-white/15 flex items-center justify-center shrink-0">
                        <img src="{{ asset('images/icons/ic-price.webp') }}" alt="" decoding="sync" class="w-5 h-5 object-contain">
                    </div>
                    <div>
                        <div class="text-xs font-extrabold text-white">Transparan</div>
                        <div class="text-[11px] text-white/70">Tanpa biaya tersembunyi</div>
                    </div>
                </div>
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-white/10 backdrop-blur-sm border border-white/15 flex items-center justify-center shrink-0">
                        <img src="{{ asset('images/icons/fac-wifi.webp') }}" alt="" decoding="sync" class="w-5 h-5 object-contain">
                    </div>
                    <div>
                        <div class="text-xs font-extrabold text-white">Siap Huni</div>
                        <div class="text-[11px] text-white/70">Fasilitas lengkap</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════
         PANEL KANAN: RESPONSIVE MOBILE/TABLET CARD & DESKTOP FORM
    ══════════════════════════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col justify-center px-3.5 py-5 sm:px-8 sm:py-10 lg:px-12 xl:px-16 bg-slate-100 lg:bg-white">
        <div class="w-full max-w-md mx-auto bg-white rounded-2xl shadow-lg lg:shadow-none border border-slate-200/80 lg:border-0 overflow-hidden">

            {{-- Visual Header Banner Khusus Mobile & Tablet (< lg) --}}
            <div class="lg:hidden relative h-32 sm:h-36 bg-slate-900 shimmer-dark overflow-hidden p-4 flex flex-col justify-between">
                <img src="{{ asset('images/auth-login.webp') }}"
                     alt="RumahKos Login"
                     fetchpriority="high"
                     decoding="sync"
                     onload="this.parentElement.classList.remove('shimmer-dark')"
                     class="absolute inset-0 w-full h-full object-cover object-center pointer-events-none">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-900/45 to-slate-950/65"></div>

                <div class="relative z-10 flex items-center justify-between">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-white/95 bg-black/30 backdrop-blur-xs px-2.5 py-1.5 rounded-lg border border-white/15">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Beranda</span>
                    </a>
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2">
                        <img src="{{ asset('images/icons/logo-rumahkos.webp') }}" alt="RumahKos" decoding="sync" class="w-7 h-7 rounded-lg shadow">
                        <span class="text-sm font-extrabold text-white">Rumah<span class="text-sky-400">Kos</span></span>
                    </a>
                </div>

                <div class="relative z-10">
                    <h1 class="text-lg sm:text-xl font-extrabold text-white leading-tight">Selamat datang kembali</h1>
                    <p class="text-[11px] sm:text-xs text-white/80">Masuk untuk cek kamar &amp; tagihan kos kamu.</p>
                </div>
            </div>

            <div class="p-5 sm:p-7 lg:p-0">
                {{-- Segmented Auth Switcher (Masuk / Daftar) --}}
                <div class="grid grid-cols-2 p-1 bg-slate-100 rounded-xl mb-5 sm:mb-6 border border-slate-200/80">
                    <a href="{{ route('tenant.login') }}"
                       class="text-center py-2 rounded-lg text-xs sm:text-sm font-extrabold bg-white text-blue-600 shadow-xs">
                        Masuk Akun
                    </a>
                    <a href="{{ route('tenant.register') }}"
                       class="text-center py-2 rounded-lg text-xs sm:text-sm font-bold text-slate-600 hover:text-slate-900 transition-colors">
                        Daftar Baru
                    </a>
                </div>

                {{-- Desktop Header --}}
                <div class="hidden lg:block mb-6">
                    <h1 class="text-2xl xl:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Selamat datang kembali
                    </h1>
                    <p class="text-slate-500 text-sm mt-1">
                        Masuk ke akun RumahKos kamu untuk melanjutkan sewa atau cek tagihan kamar.
                    </p>
                </div>

                {{-- Session Messages --}}
                @if (session('status'))
                    <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs sm:text-sm font-medium">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs sm:text-sm font-medium">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Google OAuth Button --}}
                <a href="{{ route('tenant.social.redirect', 'google') }}"
                   class="w-full flex items-center justify-center gap-3 px-4 py-2.5 bg-white border border-slate-200 hover:border-slate-300 hover:bg-slate-50 rounded-xl text-xs sm:text-sm font-bold text-slate-700 shadow-2xs transition-colors">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    <span>Lanjutkan dengan Google</span>
                </a>

                {{-- Divider --}}
                <div class="relative my-4 sm:my-5">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-200"></div>
                    </div>
                    <div class="relative flex justify-center text-xs">
                        <span class="px-3 bg-white text-slate-400 font-medium">atau masuk dengan email</span>
                    </div>
                </div>

                {{-- Login Form --}}
                <form method="POST" action="{{ route('tenant.login') }}" class="space-y-4">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Alamat Email
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                required
                                autofocus
                                value="{{ old('email') }}"
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm font-medium rounded-xl focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 focus:bg-white focus:outline-none transition"
                                placeholder="nama@email.com"
                            />
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-bold text-slate-700">
                                Password
                            </label>
                            <a href="{{ route('tenant.password.request') }}" class="text-xs text-blue-600 font-bold hover:text-blue-700 hover:underline">
                                Lupa password?
                            </a>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </span>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm font-medium rounded-xl focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 focus:bg-white focus:outline-none transition"
                                placeholder="Masukkan password"
                            />
                            <button type="button" onclick="togglePasswordVisibility('password', this)"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600"
                                    aria-label="Tampilkan Password">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center justify-between pt-0.5">
                        <label class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 cursor-pointer select-none">
                            <input
                                type="checkbox"
                                name="remember"
                                class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                            />
                            <span>Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <button
                        type="submit"
                        class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-xs transition-colors">
                        Masuk ke RumahKos
                    </button>
                </form>

                {{-- Register Link --}}
                <p class="text-center text-xs sm:text-sm text-slate-500 mt-5">
                    Belum punya akun penghuni?
                    <a href="{{ route('tenant.register') }}" class="text-blue-600 font-bold hover:text-blue-700 hover:underline">
                        Daftar gratis di sini
                    </a>
                </p>

                <p class="text-center text-[11px] text-slate-400 mt-6">
                    &copy; {{ date('Y') }} RumahKos. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('img').forEach(img => {
            if (img.complete && img.parentElement) {
                img.parentElement.classList.remove('shimmer-dark');
            }
        });
        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            input.type = input.type === 'password' ? 'text' : 'password';
            btn.classList.toggle('text-blue-600', input.type === 'text');
        }
    </script>
</body>
</html>
