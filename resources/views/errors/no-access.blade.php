<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Terbatas - Cemara Living & Residence</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0B132B; color: #fff; }
        .font-heading { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6 text-center">

    <div class="max-w-md w-full bg-slate-900/90 border border-slate-800 rounded-3xl p-8 backdrop-blur-xl shadow-2xl space-y-6">
        <div class="w-16 h-16 rounded-2xl bg-amber-500/20 border border-amber-400/30 text-amber-400 flex items-center justify-center text-2xl mx-auto">
            <i class="fas fa-lock"></i>
        </div>

        <div>
            <h1 class="text-2xl font-black font-heading tracking-tight text-white">Akses Terbatas</h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-2 leading-relaxed">
                Halaman ini memerlukan hak akses khusus atau akun yang sesuai. Silakan kembali ke beranda atau masuk menggunakan akun terdaftar Anda.
            </p>
        </div>

        <div class="pt-2 flex flex-col sm:flex-row gap-3">
            <a href="{{ route('landing') }}" class="flex-1 py-3 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-bold text-slate-200 transition-colors">
                <i class="fas fa-arrow-left mr-1.5"></i> Beranda
            </a>
            <a href="{{ route('login') }}" class="flex-1 py-3 px-4 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-bold transition-colors">
                <i class="fas fa-sign-in-alt mr-1.5"></i> Masuk Akun
            </a>
        </div>
    </div>

</body>
</html>
