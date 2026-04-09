<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 – Akses Ditolak</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-text { background: linear-gradient(135deg,#1d4ed8,#06b6d4); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
        .error-num { font-size:clamp(6rem,20vw,12rem); font-weight:900; line-height:1; letter-spacing:-0.05em; background:linear-gradient(135deg,#dbeafe,#a5f3fc); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-cyan-50 min-h-screen flex flex-col overflow-x-hidden">
    <div class="fixed -top-20 -right-20 w-80 h-80 bg-blue-100 rounded-full opacity-40 blur-3xl pointer-events-none"></div>
    <div class="fixed -bottom-10 -left-10 w-64 h-64 bg-cyan-100 rounded-full opacity-30 blur-3xl pointer-events-none"></div>

    @include('landing.navbar')

    <main class="flex-1 flex items-center justify-center px-4 py-16">
        <div class="text-center max-w-md w-full">
            <div class="error-num">403</div>

            <div class="inline-flex items-center justify-center w-20 h-20 bg-red-50 border-2 border-red-100 rounded-2xl mb-5">
                <i class="fas fa-ban text-red-400 text-3xl"></i>
            </div>

            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 mb-3">
                Akses <span class="gradient-text">ditolak</span> bro 😅
            </h1>
            <p class="text-gray-400 text-sm sm:text-base leading-relaxed mb-6">
                Kamu tidak punya izin untuk mengakses halaman ini. Pastikan kamu sudah login atau hubungi admin.
            </p>

            <div class="bg-white border border-red-100 rounded-xl p-4 mb-6 text-left shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 bg-red-50 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fas fa-lock text-red-400 text-sm"></i>
                    </div>
                    <div>
                        <div class="font-bold text-gray-800 text-sm mb-0.5">Error 403 – Forbidden</div>
                        <div class="text-gray-400 text-xs leading-relaxed">Halaman ini memerlukan izin khusus. Coba login terlebih dahulu atau minta akses ke admin.</div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 justify-center mb-5">
                <a href="{{ url('/login') }}" class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-xl shadow-md transition text-sm">
                    <i class="fas fa-right-to-bracket"></i> Login Dulu
                </a>
                <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 border-2 border-blue-200 text-blue-600 hover:bg-blue-50 font-bold px-6 py-3 rounded-xl transition text-sm">
                    <i class="fas fa-house"></i> Ke Beranda
                </a>
            </div>

            <p class="text-gray-400 text-xs">Perlu bantuan?
                <a href="https://wa.me/6287824660303?text=Saya%20tidak%20bisa%20akses%20halaman" class="text-green-600 font-semibold hover:text-green-700 transition">
                    <i class="fab fa-whatsapp mr-1"></i>Hubungi admin
                </a>
            </p>
        </div>
    </main>

    <footer class="relative z-10 text-center py-4 text-xs text-gray-400 border-t border-gray-100 bg-white/40">
        © {{ date('Y') }} RumahKos · All rights reserved
    </footer>
</body>
</html>