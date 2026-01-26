<header class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50 border-b border-gray-100">
    <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">

        <!-- Logo -->
        <a href="/" class="text-2xl font-extrabold tracking-tight text-gray-900">
            Rumah<span class="text-blue-600">Kos</span>
        </a>

        <!-- Navigation -->
        <nav class="hidden md:flex items-center gap-8 text-gray-700 font-medium">
            <a href="#kamar" class="hover:text-blue-600 transition">Kamar</a>
            <a href="#fasilitas" class="hover:text-blue-600 transition">Fasilitas</a>
            <a href="#lokasi" class="hover:text-blue-600 transition">Lokasi</a>
            <a href="#kontak" class="hover:text-blue-600 transition">Kontak</a>
        </nav>

        <!-- Auth Buttons -->
        <div class="hidden md:flex items-center gap-3">
            @auth('tenant')
                <!-- Dashboard Dropdown -->
                <div class="relative group">
                    <button class="flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-50 text-blue-600 font-semibold hover:bg-blue-100 transition">
                        <i class="fas fa-user-circle text-lg"></i>
                        <span>{{ Auth::guard('tenant')->user()->name }}</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                        <a href="{{ route('tenant.dashboard') }}" class="block px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition rounded-t-xl">
                            <i class="fas fa-th-large mr-2"></i>Dashboard Saya
                        </a>
                        <form method="POST" action="{{ route('tenant.logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-3 text-red-600 hover:bg-red-50 transition rounded-b-xl">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                    </div>
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
        <button id="menuBtn" class="md:hidden text-2xl text-gray-700">☰</button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="md:hidden hidden px-6 pb-4">
        <nav class="flex flex-col gap-4 text-gray-700 font-medium">
            <a href="#kamar">Kamar</a>
            <a href="#fasilitas">Fasilitas</a>
            <a href="#lokasi">Lokasi</a>
            <a href="#kontak">Kontak</a>

            @auth('tenant')
                <a href="{{ route('tenant.dashboard') }}" 
                   class="flex items-center gap-2 px-5 py-2 rounded-xl bg-blue-50 text-blue-600 font-semibold">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard Saya</span>
                </a>
                <form method="POST" action="{{ route('tenant.logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-5 py-2 rounded-xl bg-red-50 text-red-600 font-semibold">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            @else
                <a href="{{ route('tenant.login') }}" 
                   class="px-5 py-2 rounded-xl border border-blue-600 text-blue-600 font-semibold text-center">
                    Login
                </a>
                <a href="{{ route('tenant.register') }}" 
                   class="px-5 py-2 rounded-xl bg-blue-600 text-white font-semibold text-center">
                    Daftar
                </a>
            @endauth
        </nav>
    </div>

    <script>
        const btn = document.getElementById('menuBtn');
        const menu = document.getElementById('mobileMenu');
        btn.addEventListener('click', () => menu.classList.toggle('hidden'));
    </script>
</header>