<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak | RumahKos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-white flex items-center justify-center px-4">

    <div class="text-center max-w-md">

        <!-- Icon -->
        <div class="flex justify-center mb-6">
            <svg class="w-16 h-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>

        <!-- Error Badge -->
        <div class="mb-4">
            <span class="inline-block px-3 py-1 bg-slate-100 text-slate-700 rounded text-sm font-medium">
                Error 403
            </span>
        </div>

        <!-- Heading -->
        <h1 class="text-3xl font-bold text-slate-900 mb-3">
            Akses Ditolak
        </h1>

        <!-- Description -->
        <p class="text-slate-600 mb-8">
            Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.
        </p>

        <!-- Button -->
        <a href="{{ route('login') }}"
           class="inline-block px-8 py-3 bg-slate-900 text-white rounded-lg font-semibold 
                  hover:bg-black transition-colors duration-200">
            Kembali ke Login
        </a>

        <!-- Support -->
        <p class="text-xs text-slate-400 mt-12">
            Jika masalah berlanjut, hubungi support di 
            <a href="mailto:support@rumahkos.com" class="text-blue-600 hover:underline">
                support@rumahkos.com
            </a>
        </p>

    </div>

</body>
</html>