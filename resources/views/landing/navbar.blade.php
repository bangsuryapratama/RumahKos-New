@php
    $user     = Auth::guard('tenant')->user();
    $name     = $user->name ?? 'User';
    $words    = explode(' ', trim($name));
    $initials = strtoupper(substr($words[0], 0, 1));
    if (count($words) > 1) $initials .= strtoupper(substr(end($words), 0, 1));
    $isLanding = request()->is('/') || request()->routeIs('landing', 'rooms.search', 'rooms.catalog', 'rooms.detail', 'rooms.show', 'tenant.dashboard');
@endphp

<header id="mainNavbar"
        data-is-landing="{{ $isLanding ? '1' : '0' }}"
        class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 {{ $isLanding ? 'bg-transparent border-b border-white/10' : 'bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-sm' }}">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 sm:h-[72px] flex items-center justify-between gap-4">

        {{-- Brand Logo with Staycation / Enjoy Kosan Vibe --}}
        <a href="/" class="inline-flex items-center gap-2.5 shrink-0 group">
            <img src="{{ asset('images/icons/logo-rumahkos.webp') }}"
                 alt="RumahKos"
                 width="38" height="38"
                 class="w-9 h-9 sm:w-[38px] sm:h-[38px] object-contain shrink-0 group-hover:-rotate-6 group-hover:scale-105 transition-transform duration-300">
            <div class="flex flex-col leading-none">
                <div class="flex items-baseline">
                    <span id="navLogoText" class="text-xl sm:text-[22px] font-extrabold tracking-tight transition-colors duration-300 {{ $isLanding ? 'text-white' : 'text-slate-900' }}">
                        rumah<span id="navLogoAccent" class="transition-colors duration-300 {{ $isLanding ? 'text-sky-300' : 'text-blue-600' }}">kos</span>
                    </span>
                    <span class="w-2 h-2 rounded-full bg-amber-400 ml-0.5 inline-block shrink-0"></span>
                </div>
                <span id="navLogoSub" class="text-[10px] font-semibold tracking-wide transition-colors duration-300 mt-0.5 {{ $isLanding ? 'text-sky-200/90' : 'text-slate-400' }}">
                    kos & co-living ruang rantau
                </span>
            </div>
        </a>

        {{-- Desktop Navigation --}}
        <nav id="navLinks" class="hidden md:flex items-center gap-1 lg:gap-2 text-sm font-semibold transition-colors duration-300 {{ $isLanding ? 'text-white/90' : 'text-slate-600' }}">
            <a href="{{ route('landing') }}"      class="nav-link px-3.5 py-2 rounded-lg transition-colors {{ $isLanding ? 'hover:text-white hover:bg-white/10' : 'hover:text-blue-600 hover:bg-slate-100/70' }}">Beranda</a>
            <a href="{{ route('rooms.search') }}" class="nav-link px-3.5 py-2 rounded-lg transition-colors {{ request()->routeIs('rooms.search') ? 'bg-white/20 text-white font-bold' : '' }} {{ $isLanding ? 'hover:text-white hover:bg-white/10' : 'hover:text-blue-600 hover:bg-slate-100/70' }}">Cari &amp; Daftar Kos</a>
            <a href="/#promo"                     class="nav-link px-3.5 py-2 rounded-lg transition-colors {{ $isLanding ? 'hover:text-white hover:bg-white/10' : 'hover:text-blue-600 hover:bg-slate-100/70' }}">Promo</a>
            <a href="/#kota"                      class="nav-link px-3.5 py-2 rounded-lg transition-colors {{ $isLanding ? 'hover:text-white hover:bg-white/10' : 'hover:text-blue-600 hover:bg-slate-100/70' }}">Kota Populer</a>
            <a href="/#fasilitas"                 class="nav-link px-3.5 py-2 rounded-lg transition-colors {{ $isLanding ? 'hover:text-white hover:bg-white/10' : 'hover:text-blue-600 hover:bg-slate-100/70' }}">Fasilitas</a>
        </nav>

        {{-- Desktop Auth (Traveloka / Tiket.com Transparent Glass Style) --}}
        <div class="hidden md:flex items-center gap-2.5">
            @auth('tenant')
                <div class="relative">
                    <button id="profileBtn" type="button"
                            class="flex items-center gap-2.5 px-3.5 py-1.5 rounded-full font-semibold text-sm transition-all duration-300 {{ $isLanding ? 'bg-white/15 hover:bg-white/25 text-white border border-white/25 backdrop-blur-md' : 'bg-slate-50 hover:bg-slate-100 text-slate-800 border border-slate-200' }}">
                        <div class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold overflow-hidden shrink-0">
                            @if($user->avatar)
                                <img src="{{ $user->avatar }}" alt="{{ $name }}" class="w-full h-full object-cover"
                                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                <span style="display:none">{{ $initials }}</span>
                            @else
                                {{ $initials }}
                            @endif
                        </div>
                        <span class="max-w-[130px] truncate">{{ $name }}</span>
                        <svg class="w-3.5 h-3.5 opacity-75 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div id="profileMenu"
                         class="hidden absolute right-0 top-full mt-2 w-52 bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden py-1.5 z-50">
                        <a href="{{ route('tenant.dashboard') }}"
                           class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-600 font-semibold">
                            Dashboard Saya
                        </a>
                        <a href="https://wa.me/6283841806357" target="_blank"
                           class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 font-medium">
                            Bantuan WhatsApp
                        </a>
                        <div class="h-px bg-slate-100 my-1"></div>
                        <form method="POST" action="{{ route('tenant.logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-semibold">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a id="navLoginBtn" href="{{ route('tenant.login') }}"
                   class="px-4 py-2 rounded-lg text-sm font-bold transition-all duration-300 {{ $isLanding ? 'border border-white/70 text-white hover:bg-white/15 backdrop-blur-sm' : 'border border-slate-200 bg-white text-slate-700 hover:text-blue-600 hover:border-slate-300' }}">
                    Masuk
                </a>
                <a href="{{ route('tenant.register') }}"
                   class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold transition-all shadow-sm">
                    Daftar
                </a>
            @endauth
        </div>

        {{-- Mobile Hamburger --}}
        <div class="flex md:hidden items-center gap-2">
            <button id="menuBtn" type="button" aria-label="Menu"
                    class="w-9 h-9 flex items-center justify-center rounded-lg transition-colors {{ $isLanding ? 'text-white hover:bg-white/15' : 'text-slate-700 hover:bg-slate-100' }}">
                <svg id="menuIconOpen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg id="menuIconClose" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

    </div>

    {{-- Mobile Menu --}}
    <div id="mobileMenu" class="md:hidden hidden border-t border-slate-200 bg-white shadow-xl">
        <div class="max-w-6xl mx-auto px-4 py-3 flex flex-col gap-1">
            <a href="{{ route('landing') }}"      class="px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('landing') ? 'text-blue-600 bg-blue-50' : 'text-slate-700 hover:bg-slate-50' }}">Beranda</a>
            <a href="{{ route('rooms.search') }}" class="px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('rooms.search') ? 'text-blue-600 bg-blue-50' : 'text-slate-700 hover:bg-slate-50' }}">Cari &amp; Daftar Kos</a>
            <a href="/#promo"                     class="px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50">Promo</a>
            <a href="/#kota"                      class="px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50">Kota Populer</a>
            <a href="/#fasilitas"                 class="px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50">Fasilitas</a>

            <div class="h-px bg-slate-100 my-1.5"></div>

            @auth('tenant')
                <a href="{{ route('tenant.dashboard') }}"
                   class="px-3 py-2 rounded-lg text-sm font-semibold text-blue-600 hover:bg-blue-50">
                    Dashboard ({{ $name }})
                </a>
                <form method="POST" action="{{ route('tenant.logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50">
                        Keluar
                    </button>
                </form>
            @else
                <div class="grid grid-cols-2 gap-2 pt-1">
                    <a href="{{ route('tenant.login') }}"
                       class="py-2 rounded-lg border border-slate-200 text-slate-700 font-semibold text-sm text-center hover:bg-slate-50">
                        Masuk
                    </a>
                    <a href="{{ route('tenant.register') }}"
                       class="py-2 rounded-lg bg-blue-600 text-white font-semibold text-sm text-center hover:bg-blue-700">
                        Daftar
                    </a>
                </div>
            @endauth
        </div>
    </div>
</header>

<script>
    (function() {
        const navbar        = document.getElementById('mainNavbar');
        const navLogoText   = document.getElementById('navLogoText');
        const navLogoAccent = document.getElementById('navLogoAccent');
        const navLogoSub    = document.getElementById('navLogoSub');
        const navLinks      = document.getElementById('navLinks');
        const navLoginBtn   = document.getElementById('navLoginBtn');
        const profileBtn    = document.getElementById('profileBtn');
        const profileMenu   = document.getElementById('profileMenu');
        const menuBtn       = document.getElementById('menuBtn');
        const menuIconOpen  = document.getElementById('menuIconOpen');
        const menuIconClose = document.getElementById('menuIconClose');
        const mobileMenu    = document.getElementById('mobileMenu');

        const isLandingPage = navbar?.dataset.isLanding === '1';

        function updateNavbarState() {
            const isMobileOpen = mobileMenu && !mobileMenu.classList.contains('hidden');
            const scrolled = !isLandingPage || window.scrollY > 30 || isMobileOpen;

            if (scrolled) {
                navbar?.classList.remove('bg-transparent', 'border-white/10');
                navbar?.classList.add('bg-white/95', 'backdrop-blur-md', 'border-slate-200/80', 'shadow-sm');

                navLogoText?.classList.replace('text-white', 'text-slate-900');
                navLogoAccent?.classList.replace('text-sky-300', 'text-blue-600');
                navLogoSub?.classList.replace('text-sky-200/90', 'text-slate-400');

                navLinks?.classList.replace('text-white/90', 'text-slate-600');
                document.querySelectorAll('#navLinks .nav-link').forEach(el => {
                    el.classList.remove('hover:text-white', 'hover:bg-white/10');
                    el.classList.add('hover:text-blue-600', 'hover:bg-slate-100/70');
                });

                if (navLoginBtn) {
                    navLoginBtn.className = 'px-4 py-2 rounded-lg border border-slate-200 bg-white text-slate-700 hover:text-blue-600 hover:border-slate-300 text-sm font-bold transition-all duration-300';
                }
                if (profileBtn) {
                    profileBtn.className = 'flex items-center gap-2.5 px-3.5 py-1.5 rounded-full font-semibold text-sm transition-all duration-300 bg-slate-50 hover:bg-slate-100 text-slate-800 border border-slate-200';
                }
                if (menuBtn) {
                    menuBtn.className = 'w-9 h-9 flex items-center justify-center rounded-lg text-slate-700 hover:bg-slate-100 transition-colors';
                }
            } else {
                navbar?.classList.add('bg-transparent', 'border-white/10');
                navbar?.classList.remove('bg-white/95', 'backdrop-blur-md', 'border-slate-200/80', 'shadow-sm');

                navLogoText?.classList.replace('text-slate-900', 'text-white');
                navLogoAccent?.classList.replace('text-blue-600', 'text-sky-300');
                navLogoSub?.classList.replace('text-slate-400', 'text-sky-200/90');

                navLinks?.classList.replace('text-slate-600', 'text-white/90');
                document.querySelectorAll('#navLinks .nav-link').forEach(el => {
                    el.classList.add('hover:text-white', 'hover:bg-white/10');
                    el.classList.remove('hover:text-blue-600', 'hover:bg-slate-100/70');
                });

                if (navLoginBtn) {
                    navLoginBtn.className = 'px-4 py-2 rounded-lg border border-white/70 text-white hover:bg-white/15 backdrop-blur-sm text-sm font-bold transition-all duration-300';
                }
                if (profileBtn) {
                    profileBtn.className = 'flex items-center gap-2.5 px-3.5 py-1.5 rounded-full font-semibold text-sm transition-all duration-300 bg-white/15 hover:bg-white/25 text-white border border-white/25 backdrop-blur-md';
                }
                if (menuBtn) {
                    menuBtn.className = 'w-9 h-9 flex items-center justify-center rounded-lg text-white hover:bg-white/15 transition-colors';
                }
            }
        }

        window.addEventListener('scroll', updateNavbarState, { passive: true });
        document.addEventListener('DOMContentLoaded', updateNavbarState);
        updateNavbarState();

        menuBtn?.addEventListener('click', () => {
            const isOpen = !mobileMenu.classList.contains('hidden');
            mobileMenu.classList.toggle('hidden', isOpen);
            menuIconOpen?.classList.toggle('hidden', !isOpen);
            menuIconClose?.classList.toggle('hidden', isOpen);
            updateNavbarState();
        });

        mobileMenu?.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
                menuIconOpen?.classList.remove('hidden');
                menuIconClose?.classList.add('hidden');
                updateNavbarState();
            });
        });

        profileBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            profileMenu?.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (profileMenu && !profileMenu.classList.contains('hidden')) {
                if (!profileMenu.contains(e.target) && !profileBtn?.contains(e.target)) {
                    profileMenu.classList.add('hidden');
                }
            }
        });
    })();
</script>
