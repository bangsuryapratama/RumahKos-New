<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Dinonaktifkan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4">

    <div class="max-w-md w-full">

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">

            {{-- Top Banner --}}
            <div class="bg-gradient-to-r from-red-500 to-red-600 p-8 text-center">
                <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-ban text-4xl text-white"></i>
                </div>
                <h1 class="text-2xl font-bold text-white">Akun Dinonaktifkan</h1>
                <p class="text-red-100 text-sm mt-2">Akses Anda telah dibatasi oleh admin</p>
            </div>

            {{-- Content --}}
            <div class="p-6 sm:p-8">
                <p class="text-gray-600 text-sm sm:text-base text-center mb-6 leading-relaxed">
                    Akun Anda telah dinonaktifkan oleh admin. Hal ini dapat terjadi karena beberapa alasan seperti keterlambatan pembayaran atau pelanggaran peraturan kos.
                </p>

                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                    <p class="text-xs sm:text-sm text-red-700 text-center">
                        <i class="fas fa-info-circle mr-1"></i>
                        Silakan hubungi admin untuk informasi lebih lanjut dan proses reaktivasi akun Anda.
                    </p>
                </div>

                {{-- WA Button --}}
                @php
                    $property = \App\Models\Property::select('whatsapp', 'name')->first();
                    $waNumber = $property?->whatsapp ?? '6281234567890';
                    $waNumber = preg_replace('/[^0-9]/', '', $waNumber);
                    if (str_starts_with($waNumber, '0')) {
                        $waNumber = '62' . substr($waNumber, 1);
                    }
                    $waMessage = urlencode('Halo Admin, akun saya telah dinonaktifkan. Mohon bantuan untuk reaktivasi akun. Email: ' . (Auth::guard('tenant')->user()->email ?? '-'));
                @endphp

                <a href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}"
                   target="_blank"
                   class="flex items-center justify-center gap-3 w-full px-6 py-3.5 bg-green-500 text-white rounded-xl hover:bg-green-600 transition-all font-semibold text-sm sm:text-base shadow-md hover:shadow-lg active:scale-[0.98] mb-3">
                    <i class="fab fa-whatsapp text-xl"></i>
                    Hubungi Admin via WhatsApp
                </a>

                <form method="POST" action="{{ route('tenant.logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full px-6 py-3 border-2 border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 transition-all font-semibold text-sm sm:text-base active:scale-[0.98]">
                        <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                    </button>
                </form>
            </div>

        </div>

        {{-- Footer --}}
        <p class="text-center text-xs text-gray-400 mt-6">
            &copy; {{ date('Y') }} {{ $property?->name ?? 'RumahKos' }}. All rights reserved.
        </p>

    </div>

</body>
</html>