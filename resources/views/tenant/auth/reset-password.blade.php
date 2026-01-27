<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - RumahKos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-10 bg-gradient-to-br from-blue-50 via-white to-purple-50">

    <div class="w-full max-w-md">
        <!-- Back to Login -->
        <a href="{{ route('tenant.login') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-blue-600 transition mb-6">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Login</span>
        </a>

        <div class="bg-white shadow-2xl rounded-2xl p-8 md:p-10">

            <!-- Header -->
            <div class="flex flex-col items-center mb-8">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-lock text-3xl text-blue-600"></i>
                </div>

                <h1 class="text-slate-900 text-3xl font-semibold tracking-wide">
                    Reset Password
                </h1>

                <p class="text-slate-600 text-sm mt-2 text-center">
                    Masukkan password baru Anda
                </p>
            </div>

            <!-- Error Message -->
            @if (session('error'))
                <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('tenant.password.update') }}" class="space-y-5">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <!-- Password Baru -->
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                        Password Baru
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

                <!-- Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-2">
                        Konfirmasi Password Baru
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

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full py-3 bg-blue-600 text-white font-semibold rounded-xl shadow-lg hover:bg-blue-700 hover:shadow-xl transition-all duration-200">
                    <i class="fas fa-check mr-2"></i>
                    Reset Password
                </button>
            </form>

        </div>

        <!-- Footer -->
        <p class="text-center text-sm text-slate-500 mt-6">
            © {{ date('Y') }} RumahKos. All rights reserved.
        </p>
    </div>

</body>
</html>