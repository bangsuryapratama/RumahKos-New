<header class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50 border-b border-gray-100">
    <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">

        <!-- Logo -->
        <a href="/" class="text-2xl font-extrabold tracking-tight text-gray-900">
            Rumah<span class="text-blue-600">Kos</span>
        </a>

        <!-- Navigation (Desktop) -->
        <nav class="hidden md:flex items-center gap-8 text-gray-700 font-medium">
            <a href="#kamar" class="hover:text-blue-600 transition">Kamar</a>
            <a href="#fasilitas" class="hover:text-blue-600 transition">Fasilitas</a>
            <a href="#lokasi" class="hover:text-blue-600 transition">Lokasi</a>
            <a href="#kontak" class="hover:text-blue-600 transition">Kontak</a>
        </nav>

        <!-- Auth Section (Desktop) -->
        <div class="hidden md:flex items-center gap-3 relative">
            @auth('tenant')
                <!-- Profile Button -->
                <button id="profileBtn"
                    class="flex items-center gap-3 px-4 py-2 rounded-xl bg-blue-50 text-blue-600 font-semibold hover:bg-blue-100 transition">

                    <!-- Avatar -->
                    <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                        {{ strtoupper(substr(Auth::guard('tenant')->user()->name, 0, 1)) }}
                    </div>

                    <span class="max-w-[120px] truncate">
                        {{ Auth::guard('tenant')->user()->name }}
                    </span>

                    <i class="fas fa-chevron-down text-xs"></i>
                </button>

                <!-- Dropdown -->
                <div id="profileMenu"
                    class="hidden absolute right-0 top-full mt-3 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

                    <a href="{{ route('tenant.dashboard') }}"
                       class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                        <i class="fas fa-th-large"></i>
                        Dashboard Saya
                    </a>

                    <a href="https://wa.me/6283841806357" target="_blank"
                       class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-600 transition">
                        <i class="fab fa-whatsapp"></i>
                        Customer Service
                    </a>

                    <div class="border-t border-gray-100"></div>

                    <form method="POST" action="{{ route('tenant.logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 transition">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('tenant.login') }}"
                   class="px-5 py-2 text-blue-600 font-semibold hover:text-blue-700 transition">
                    Login
                </a>
                <a href="{{ route('tenant.register') }}"
                   class="px-5 py-2 rounded-xl bg-blue-600 text-white font-semibold shadow-sm hover:bg-blue-700 transition">
                    Daftar
                </a>
            @endauth
        </div>

        <!-- Mobile Menu Button -->
        <button id="menuBtn" class="md:hidden text-2xl text-gray-700">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="md:hidden hidden px-6 pb-6">
        <nav class="flex flex-col gap-4 text-gray-700 font-medium">

            <a href="#kamar">Kamar</a>
            <a href="#fasilitas">Fasilitas</a>
            <a href="#lokasi">Lokasi</a>
            <a href="#kontak">Kontak</a>

            @auth('tenant')
                <div class="mt-4 border-t pt-4 flex flex-col gap-3">

                    <a href="{{ route('tenant.dashboard') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-50 text-blue-600 font-semibold">
                        <i class="fas fa-th-large"></i>
                        Dashboard Saya
                    </a>

                    <a href="https://wa.me/6283841806357" target="_blank"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl bg-green-50 text-green-600 font-semibold">
                        <i class="fab fa-whatsapp"></i>
                        Customer Service
                    </a>

                    <form method="POST" action="{{ route('tenant.logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl bg-red-50 text-red-600 font-semibold w-full">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </button>
                    </form>
                </div>
            @else
                <div class="mt-4 flex flex-col gap-3">
                    <a href="{{ route('tenant.login') }}"
                       class="px-5 py-3 rounded-xl border border-blue-600 text-blue-600 font-semibold text-center">
                        Login
                    </a>
                    <a href="{{ route('tenant.register') }}"
                       class="px-5 py-3 rounded-xl bg-blue-600 text-white font-semibold text-center">
                        Daftar
                    </a>
                </div>
            @endauth
        </nav>
    </div>
</header>

<!-- JS -->
<script>
    // Mobile Menu
    const menuBtn = document.getElementById('menuBtn');
    const mobileMenu = document.getElementById('mobileMenu');

    menuBtn?.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // Profile Dropdown
    const profileBtn = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');

    profileBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        profileMenu.classList.toggle('hidden');
    });

    document.addEventListener('click', (e) => {
        if (!profileMenu?.contains(e.target)) {
            profileMenu?.classList.add('hidden');
        }
    });
</script>
