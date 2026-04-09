<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 – Tidak Ditemukan</title>
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
            <div class="error-num">404</div>

            <div class="inline-flex items-center justify-center w-20 h-20 bg-orange-50 border-2 border-orange-100 rounded-2xl mb-5">
                <i class="fas fa-compass text-orange-400 text-3xl"></i>
            </div>

            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 mb-3">
                Halamannya <span class="gradient-text">kabur</span> nih 😢
            </h1>
            <p class="text-gray-400 text-sm sm:text-base leading-relaxed mb-6">
                Halaman yang kamu cari tidak ditemukan. Mungkin sudah dipindah, dihapus, atau URL-nya salah.
            </p>

            <div class="bg-white border border-gray-100 rounded-xl p-4 mb-6 text-left shadow-sm">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Mau ke mana?</p>
                <div class="space-y-2">
                    @php $links = [
                        ['href'=>url('/'),         'icon'=>'fa-house',   'bg'=>'bg-blue-50',   'ic'=>'text-blue-500',  'label'=>'Beranda',       'sub'=>'Lihat semua info kos'],
                        ['href'=>url('/#kamar'),   'icon'=>'fa-bed',     'bg'=>'bg-green-50',  'ic'=>'text-green-500', 'label'=>'Daftar Kamar',   'sub'=>'Cek ketersediaan kamar'],
                        ['href'=>url('/#kontak'),  'icon'=>'fa-headset', 'bg'=>'bg-purple-50', 'ic'=>'text-purple-500','label'=>'Hubungi Kami',   'sub'=>'Butuh bantuan?'],
                    ]; @endphp
                    @foreach($links as $l)
                    <a href="{{ $l['href'] }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition group">
                        <div class="w-9 h-9 {{ $l['bg'] }} rounded-xl flex items-center justify-center shrink-0">
                            <i class="fas {{ $l['icon'] }} {{ $l['ic'] }} text-sm"></i>
                        </div>
                        <div class="text-left">
                            <div class="font-semibold text-gray-800 text-sm">{{ $l['label'] }}</div>
                            <div class="text-gray-400 text-xs">{{ $l['sub'] }}</div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-300 ml-auto text-xs"></i>
                    </a>
                    @endforeach
                </div>
            </div>

            <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-3 rounded-xl shadow-md transition text-sm">
                <i class="fas fa-house"></i> Kembali ke Beranda
            </a>
        </div>
    </main>

    <footer class="relative z-10 text-center py-4 text-xs text-gray-400 border-t border-gray-100 bg-white/40">
        © {{ date('Y') }} RumahKos · All rights reserved
    </footer>
</body>
</html>