<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - RumahKos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-10 bg-gradient-to-br from-blue-50 via-white to-purple-50">

    <div class="w-full max-w-md">
        
        <div class="bg-white shadow-2xl rounded-2xl p-8 md:p-10 transition-all duration-300 hover:shadow-blue-500/20">

            <!-- Logo Section -->
            <div class="flex flex-col items-center mb-8">
                <img src="{{ asset('favicon.ico') }}" alt="RumahKos Logo" class="w-16 h-16 mb-4">

                <h1 class="text-slate-900 text-3xl font-semibold tracking-wide">
                    Admin RumahKos
                </h1>

                <p class="text-slate-600 text-sm mt-2">
                    Sistem Manajemen Admin RumahKos
                </p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-6 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
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
                        autofocus
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-300 text-slate-900 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        placeholder="admin@rumahkos.com"
                    />
                    @error('email')
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

                <!-- Remember & Forgot Password -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-slate-700 cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="remember"
                            class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        />
                        <span>Ingat saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-blue-600 font-medium hover:text-blue-700 hover:underline">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full py-3 bg-blue-600 text-white font-semibold rounded-xl shadow-lg hover:bg-blue-700 hover:shadow-xl transition-all duration-200">
                    Login
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