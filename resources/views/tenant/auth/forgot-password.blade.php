<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - RumahKos</title>
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
                    <i class="fas fa-key text-3xl text-blue-600"></i>
                </div>

                <h1 class="text-slate-900 text-3xl font-semibold tracking-wide">
                    Lupa Password?
                </h1>

                <p class="text-slate-600 text-sm mt-2 text-center">
                    Masukkan email Anda, kami akan kirimkan link reset password
                </p>
            </div>

            <!-- Success Message -->
            @if (session('status'))
                <div class="mb-6 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('status') }}
                </div>
            @endif

            <!-- Error Message -->
            @if (session('error'))
                <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('tenant.password.email') }}" class="space-y-5">
                @csrf

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

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full py-3 bg-blue-600 text-white font-semibold rounded-xl shadow-lg hover:bg-blue-700 hover:shadow-xl transition-all duration-200">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Kirim Link Reset Password
                </button>
            </form>

            <!-- Back to Login -->
            <p class="text-center text-sm text-slate-600 mt-6">
                Sudah ingat password? 
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