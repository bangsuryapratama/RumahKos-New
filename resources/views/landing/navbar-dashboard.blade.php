@php
    $user     = Auth::guard('tenant')->user();
    $name     = $user->name ?? 'User';
    $words    = explode(' ', trim($name));
    $initials = strtoupper(substr($words[0], 0, 1));
    if (count($words) > 1) $initials .= strtoupper(substr(end($words), 0, 1));
@endphp

<header class="bg-white/90 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-4">

        {{-- Logo --}}
        <a href="/" class="text-xl sm:text-2xl font-extrabold tracking-tight text-gray-900 shrink-0">
            Rumah<span class="text-blue-600">Kos</span>
        </a>

        {{-- Desktop Nav --}}
        <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-gray-600">
            <a href="/#kamar"    class="hover:text-blue-600 transition-colors">Kamar</a>
            <a href="/#fasilitas" class="hover:text-blue-600 transition-colors">Fasilitas</a>
            <a href="/#lokasi"   class="hover:text-blue-600 transition-colors">Lokasi</a>
            <a href="/#kontak"   class="hover:text-blue-600 transition-colors">Kontak</a>
        </nav>

        {{-- Desktop Auth --}}
        <div class="hidden md:flex items-center gap-2 relative">
            @auth('tenant')
                <div class="relative">
                    <button id="profileBtn"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold text-sm transition-colors">
                        {{-- Avatar --}}
                        <div class="w-7 h-7 shrink-0 relative">
                            @if($user->avatar)
                                <img src="{{ $user->avatar }}" alt="{{ $name }}"
                                     class="w-7 h-7 rounded-full object-cover border border-blue-200"
                                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                <div style="display:none"
                                     class="w-7 h-7 rounded-full bg-blue-600 text-white absolute inset-0 flex items-center justify-center text-xs font-bold">
                                    {{ $initials }}
                                </div>
                            @else
                                <div class="w-7 h-7 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">
                                    {{ $initials }}
                                </div>
                            @endif
                        </div>
                        <span class="max-w-[110px] truncate">{{ $name }}</span>
                        <i class="fas fa-chevron-down text-[10px] text-blue-400"></i>
                    </button>

                    {{-- Dropdown --}}
                    <div id="profileMenu"
                         class="hidden absolute right-0 top-full mt-2 w-52 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden py-1">
                        <a href="{{ route('tenant.dashboard') }}"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            <i class="fas fa-th-large w-4 text-center"></i> Dashboard Saya
                        </a>
                        <a href="https://wa.me/6283841806357" target="_blank"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 transition-colors">
                            <i class="fab fa-whatsapp w-4 text-center"></i> Customer Service
                        </a>
                        <div class="h-px bg-gray-100 my-1"></div>
                        <form method="POST" action="{{ route('tenant.logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <i class="fas fa-sign-out-alt w-4 text-center"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('tenant.login') }}"
                   class="px-4 py-2 text-sm text-blue-600 font-semibold hover:text-blue-700 transition-colors">
                    Login
                </a>
                <a href="{{ route('tenant.register') }}"
                   class="px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                    Daftar
                </a>
            @endauth
        </div>

        {{-- Mobile: Auth shortcut + hamburger --}}
        <div class="flex md:hidden items-center gap-2">
            @auth('tenant')
                <div class="w-8 h-8 shrink-0">
                    @if($user->avatar)
                        <img src="{{ $user->avatar }}" alt="{{ $name }}"
                             class="w-8 h-8 rounded-full object-cover border border-gray-200"
                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                        <div style="display:none"
                             class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">
                            {{ $initials }}
                        </div>
                    @else
                        <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">
                            {{ $initials }}
                        </div>
                    @endif
                </div>
            @endauth
            <button id="menuBtn" class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-gray-100 transition-colors text-gray-700">
                <i id="menuIcon" class="fas fa-bars text-lg"></i>
            </button>
        </div>

    </div>

    {{-- Mobile Menu --}}
    <div id="mobileMenu" class="md:hidden hidden border-t border-gray-100 bg-white">
        <nav class="max-w-6xl mx-auto px-4 py-4 flex flex-col gap-1">

            <a href="/#kamar"    class="px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                <i class="fas fa-bed mr-2 text-gray-400 w-4 text-center"></i>Kamar
            </a>
            <a href="/#fasilitas" class="px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                <i class="fas fa-star mr-2 text-gray-400 w-4 text-center"></i>Fasilitas
            </a>
            <a href="/#lokasi"   class="px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                <i class="fas fa-map-pin mr-2 text-gray-400 w-4 text-center"></i>Lokasi
            </a>
            <a href="/#kontak"   class="px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                <i class="fas fa-phone mr-2 text-gray-400 w-4 text-center"></i>Kontak
            </a>

            <div class="h-px bg-gray-100 my-2"></div>

            @auth('tenant')
                {{-- Profile info --}}
                <div class="flex items-center gap-3 px-3 py-2 mb-1">
                    <div class="w-10 h-10 shrink-0">
                        @if($user->avatar)
                            <img src="{{ $user->avatar }}" alt="{{ $name }}"
                                 class="w-10 h-10 rounded-full object-cover border border-gray-200"
                                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                            <div style="display:none"
                                 class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-bold">
                                {{ $initials }}
                            </div>
                        @else
                            <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-bold">
                                {{ $initials }}
                            </div>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <div class="font-semibold text-gray-900 text-sm truncate">{{ $name }}</div>
                        <div class="text-xs text-gray-500 truncate">{{ $user->email }}</div>
                    </div>
                </div>

                <a href="{{ route('tenant.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 transition-colors">
                    <i class="fas fa-th-large w-4 text-center"></i> Dashboard Saya
                </a>
                <a href="https://wa.me/6283841806357" target="_blank"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold text-green-600 bg-green-50 hover:bg-green-100 transition-colors">
                    <i class="fab fa-whatsapp w-4 text-center"></i> Customer Service
                </a>
                <form method="POST" action="{{ route('tenant.logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold text-red-600 bg-red-50 hover:bg-red-100 transition-colors">
                        <i class="fas fa-sign-out-alt w-4 text-center"></i> Logout
                    </button>
                </form>
            @else
                <div class="flex flex-col gap-2">
                    <a href="{{ route('tenant.login') }}"
                       class="px-4 py-2.5 rounded-xl border border-blue-600 text-blue-600 font-semibold text-sm text-center hover:bg-blue-50 transition-colors">
                        Login
                    </a>
                    <a href="{{ route('tenant.register') }}"
                       class="px-4 py-2.5 rounded-xl bg-blue-600 text-white font-semibold text-sm text-center hover:bg-blue-700 transition-colors">
                        Daftar
                    </a>
                </div>
            @endauth

            <div class="pb-2"></div>
        </nav>
    </div>
</header>

<script>
    const menuBtn     = document.getElementById('menuBtn');
    const menuIcon    = document.getElementById('menuIcon');
    const mobileMenu  = document.getElementById('mobileMenu');
    const profileBtn  = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');

    // Hamburger toggle
    menuBtn?.addEventListener('click', () => {
        const open = mobileMenu.classList.toggle('hidden');
        menuIcon.className = open ? 'fas fa-bars text-lg' : 'fas fa-times text-lg';
    });

    // Close mobile menu on nav link click
    mobileMenu?.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
            menuIcon.className = 'fas fa-bars text-lg';
        });
    });

    // Profile dropdown
    profileBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        profileMenu.classList.toggle('hidden');
    });

    // Close dropdown on outside click
    document.addEventListener('click', () => {
        profileMenu?.classList.add('hidden');
    });
</script>