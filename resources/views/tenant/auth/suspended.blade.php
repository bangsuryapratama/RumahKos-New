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

    <div class="w-full max-w-md sm:max-w-lg">

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-lg sm:shadow-xl overflow-hidden">

            {{-- Banner --}}
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-10 text-center">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-ban text-2xl sm:text-4xl text-white"></i>
                </div>

                <h1 class="text-xl sm:text-2xl font-bold text-white">
                    Akun Dinonaktifkan
                </h1>

                <p class="text-red-100 text-xs sm:text-sm mt-2">
                    Akses Anda telah dibatasi oleh admin
                </p>
            </div>

            {{-- Content --}}
            <div class="px-5 py-6 sm:px-8 sm:py-8">

                <p class="text-gray-600 text-sm sm:text-base text-center leading-relaxed mb-6">
                    Akun Anda telah dinonaktifkan oleh admin. Hal ini dapat terjadi karena keterlambatan pembayaran atau pelanggaran peraturan kos.
                </p>

                {{-- Info Box --}}
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                    <p class="text-xs sm:text-sm text-red-700 text-center">
                        <i class="fas fa-info-circle mr-1"></i>
                        Hubungi admin untuk informasi lebih lanjut dan reaktivasi akun.
                    </p>
                </div>

                {{-- PHP --}}
                @php
                    $property = \App\Models\Property::select('whatsapp', 'name')->first();
                    $waNumber = $property?->whatsapp ?? '6281234567890';
                    $waNumber = preg_replace('/[^0-9]/', '', $waNumber);

                    if (str_starts_with($waNumber, '0')) {
                        $waNumber = '62' . substr($waNumber, 1);
                    }

                    $email = Auth::guard('tenant')->user()->email ?? '-';

                    $waMessage = urlencode(
                        "Halo Admin, akun saya dinonaktifkan. Mohon bantuan reaktivasi.\nEmail: $email"
                    );
                @endphp

                {{-- WA Button --}}
                <a href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}"
                   target="_blank"
                   class="flex items-center justify-center gap-2 sm:gap-3 w-full px-4 py-3.5 bg-green-500 text-white rounded-xl hover:bg-green-600 transition-all font-semibold text-sm sm:text-base shadow-md hover:shadow-lg active:scale-[0.97]">

                    <i class="fab fa-whatsapp text-lg sm:text-xl"></i>
                    <span>Hubungi Admin</span>
                </a>

                {{-- Logout --}}
                <form method="POST" action="{{ route('tenant.logout') }}" class="mt-3">
                    @csrf
                    <button type="submit"
                        class="w-full px-4 py-3 border border-gray-300 text-gray-600 rounded-xl hover:bg-gray-100 transition-all font-semibold text-sm sm:text-base active:scale-[0.97]">

                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Keluar
                    </button>
                </form>

            </div>
        </div>

        {{-- Footer --}}
        <p class="text-center text-xs text-gray-400 mt-6">
            &copy; {{ date('Y') }} {{ $property?->name ?? 'RumahKos' }}
        </p>

    </div>

</body>
</html>