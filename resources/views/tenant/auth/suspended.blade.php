<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Dinonaktifkan</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .gradient-text {
            background: linear-gradient(135deg, #1d4ed8 0%, #06b6d4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-50 via-white to-cyan-50 min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md sm:max-w-lg">

        <div class="bg-white border border-gray-100 rounded-3xl shadow-xl overflow-hidden">

            {{-- HEADER --}}
            <div class="px-6 sm:px-8 pt-10 pb-6 text-center">

                <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-5 
                            bg-blue-100 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-user-lock text-2xl sm:text-3xl text-blue-600"></i>
                </div>

                <h1 class="text-xl sm:text-2xl font-extrabold text-gray-900">
                    Akun Dinonaktifkan
                </h1>

                <p class="text-gray-400 text-sm mt-2">
                    Akses akun sementara dibatasi oleh admin
                </p>
            </div>

            {{-- CONTENT --}}
            <div class="px-6 sm:px-8 pb-8">

                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 text-center mb-6">
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Akun Anda dinonaktifkan karena alasan tertentu seperti
                        <span class="font-semibold text-blue-600">keterlambatan pembayaran</span>
                        atau pelanggaran aturan kos.
                    </p>
                </div>

                @php
                    $property = \App\Models\Property::select('whatsapp', 'name')->first();
                    $waNumber = $property?->whatsapp ?? '6281234567890';
                    $waNumber = preg_replace('/[^0-9]/', '', $waNumber);

                    if (str_starts_with($waNumber, '0')) {
                        $waNumber = '62' . substr($waNumber, 1);
                    }

                    $email = Auth::guard('tenant')->user()->email ?? '-';

                    $waMessage = urlencode(
                        "Halo Admin, akun saya dinonaktifkan.\nMohon bantuan reaktivasi.\nEmail: $email"
                    );
                @endphp

                {{-- BUTTONS --}}
                <div class="space-y-3">

                    <a href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}"
                       class="w-full flex items-center justify-center gap-2 
                              bg-blue-600 hover:bg-blue-700 
                              text-white font-bold 
                              py-3.5 rounded-2xl 
                              transition-all active:scale-95 shadow-md">

                        <i class="fab fa-whatsapp text-lg"></i>
                        Hubungi Admin
                    </a>

                    <form method="POST" action="{{ route('tenant.logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full py-3 border border-gray-200 
                                   text-gray-600 font-semibold 
                                   rounded-2xl hover:bg-gray-50 
                                   transition-all active:scale-95">

                            Keluar
                        </button>
                    </form>

                </div>
            </div>
        </div>

        {{-- FOOTER --}}
        <p class="text-center text-xs text-gray-400 mt-6">
            &copy; {{ date('Y') }} {{ $property?->name ?? 'RumahKos' }}
        </p>

    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</body>
</html>