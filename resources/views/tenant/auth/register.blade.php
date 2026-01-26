<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Penghuni - RumahKos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-10 bg-gradient-to-br from-blue-50 via-white to-purple-50">

    <div class="w-full max-w-md">
        <!-- Back to Home -->
        <a href="/" class="inline-flex items-center gap-2 text-gray-600 hover:text-blue-600 transition mb-6">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Beranda</span>
        </a>

        <div class="bg-white shadow-2xl rounded-2xl p-8 md:p-10 transition-all duration-300 hover:shadow-blue-500/20">

            <!-- Logo Section -->
            <div class="flex flex-col items-center mb-8">
                <img src="{{ asset('favicon.ico') }}" alt="RumahKos Logo" class="w-16 h-16 mb-4">

                <h1 class="text-slate-900 text-3xl font-semibold tracking-wide">
                    Daftar Penghuni
                </h1>

                <p class="text-slate-600 text-sm mt-2">
                    Buat akun untuk mulai mencari kos
                </p>
            </div>

            <!-- Social Register Buttons -->
            <div class="space-y-3 mb-6">
                <a href="{{ route('tenant.social.redirect', 'google') }}" 
                   class="w-full flex items-center justify-center gap-3 px-4 py-3 border-2 border-gray-200 rounded-xl font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    <span>Daftar dengan Google</span>
                </a>

                {{-- <a href="{{ route('tenant.social.redirect', 'facebook') }}" 
                   class="w-full flex items-center justify-center gap-3 px-4 py-3 bg-[#1877F2] text-white rounded-xl font-semibold hover:bg-[#166FE5] transition">
                    <i class="fab fa-facebook-f text-lg"></i>
                    <span>Daftar dengan Facebook</span>
                </a> --}}
            </div>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white text-gray-500">atau daftar dengan email</span>
                </div>
            </div>

            <!-- Register Form -->
            <form method="POST" action="{{ route('tenant.register') }}" class="space-y-5">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                        Nama Lengkap
                    </label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        required
                        autofocus
                        value="{{ old('name') }}"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-300 text-slate-900 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        placeholder="John Doe"
                    />
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                        Email
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        required
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-300 text-slate-900 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        placeholder="nama@email.com"
                    />
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700 mb-2">
                        Nomor Telepon
                    </label>
                    <input
                        id="phone"
                        type="tel"
                        name="phone"
                        required
                        value="{{ old('phone') }}"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-300 text-slate-900 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        placeholder="08123456789"
                    />
                    @error('phone')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                        Password
                    </label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-300 text-slate-900 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        placeholder="••••••••"
                    />
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-2">
                        Konfirmasi Password
                    </label>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-300 text-slate-900 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        placeholder="••••••••"
                    />
                </div>

                <!-- Terms -->
                <div class="flex items-start gap-2">
                    <input 
                        type="checkbox" 
                        name="terms"
                        required
                        class="w-4 h-4 mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    />
                    <label class="text-sm text-slate-600">
                        Saya setuju dengan 
                        <a href="#" class="text-blue-600 hover:underline">Syarat & Ketentuan</a> 
                        dan 
                        <a href="#" class="text-blue-600 hover:underline">Kebijakan Privasi</a>
                    </label>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full py-3 bg-blue-600 text-white font-semibold rounded-xl shadow-lg hover:bg-blue-700 hover:shadow-xl transition-all duration-200">
                    Daftar Sekarang
                </button>
            </form>

            <!-- Login Link -->
            <p class="text-center text-sm text-slate-600 mt-6">
                Sudah punya akun? 
                <a href="{{ route('tenant.login') }}" class="text-blue-600 font-semibold hover:text-blue-700 hover:underline">
                    Login di sini
                </a>
            </p>

        </div>

        <!-- Footer -->
        <p class="text-center text-sm text-slate-500 mt-6">
            © {{ date('Y') }} RumahKos. All rights reserved.
        </p>
    </div>

</body>
</html>