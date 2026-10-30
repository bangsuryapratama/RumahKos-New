<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - RumahKos</title>
    <link rel="icon" type="image/webp" href="{{ asset('favicon.webp') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.webp') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-10 bg-slate-900 relative overflow-hidden antialiased">

    <img src="{{ asset('images/auth-admin.webp') }}"
         alt="RumahKos Admin"
         class="absolute inset-0 w-full h-full object-cover object-center opacity-25 pointer-events-none">
    <div class="absolute inset-0 bg-gradient-to-b from-slate-950/85 via-slate-900/75 to-slate-950/90 pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
        <div class="flex items-center justify-between mb-5">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-xs font-bold text-white/80 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Kembali ke Beranda</span>
            </a>
        </div>

        <div class="bg-white shadow-2xl rounded-2xl border border-slate-200/80 p-7 sm:p-9">
            <div class="flex flex-col items-center text-center mb-7">
                <img src="{{ asset('images/icons/logo-rumahkos.webp') }}" alt="RumahKos Logo" class="w-14 h-14 mb-3 rounded-2xl shadow-md">
                <h1 class="text-slate-900 text-2xl font-extrabold tracking-tight">
                    Portal Admin Rumah<span class="text-blue-600">Kos</span>
                </h1>
                <p class="text-slate-500 text-xs sm:text-sm mt-1">
                    Masuk untuk mengelola properti, kamar, penghuni &amp; transaksi.
                </p>
            </div>

            @if (session('status'))
                <div class="mb-5 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs sm:text-sm font-medium">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Email Admin
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        required
                        autofocus
                        value="{{ old('email') }}"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm font-medium rounded-xl focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 focus:bg-white focus:outline-none transition"
                        placeholder="admin@rumahkos.com"
                    />
                    @error('email')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Password
                    </label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm font-medium rounded-xl focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 focus:bg-white focus:outline-none transition"
                        placeholder="••••••••"
                    />
                    @error('password')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-xs pt-0.5">
                    <label class="flex items-center gap-2 text-slate-600 font-semibold cursor-pointer">
                        <input
                            type="checkbox"
                            name="remember"
                            class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                        />
                        <span>Ingat saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-blue-600 font-bold hover:text-blue-700 hover:underline">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <button
                    type="submit"
                    class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-sm transition-colors">
                    Masuk ke Dashboard Admin
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-white/60 mt-6">
            &copy; {{ date('Y') }} RumahKos. All rights reserved.
        </p>
    </div>

</body>
</html>
