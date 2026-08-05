@php
    $user = $currentUser ?? Auth::user();
    $name = $user->name ?? 'Tamu';
    $words = explode(' ', trim($name));
    $initials = strtoupper(substr($words[0], 0, 1));
    if (count($words) > 1) {
        $initials .= strtoupper(substr(end($words), 0, 1));
    }
    $isAdmin = $user && $user->isAdmin();
    $isTenant = $user && $user->isPenghuni();
    $whatsappNum = preg_replace('/[^0-9]/', '', $contact->whatsapp ?? '6281234567890');
    if (str_starts_with($whatsappNum, '0')) {
        $whatsappNum = '62' . substr($whatsappNum, 1);
    }
@endphp

<!-- Top Utility Bar (Luxury Hotel Standard) -->
<div class="bg-slate-900 text-slate-300 text-xs py-2 px-4 border-b border-slate-800 hidden sm:block">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div class="flex items-center gap-6">
            <span class="flex items-center gap-1.5 text-amber-400 font-medium">
                <i class="fas fa-crown text-[10px]"></i> Boutique Living & Luxury Residence
            </span>
            <span class="text-slate-500">|</span>
            <span class="flex items-center gap-1.5 hover:text-white transition-colors">
                <i class="fas fa-map-marker-alt text-amber-400"></i> Setiabudi, Bandung, Jawa Barat
            </span>
            <span class="text-slate-500">|</span>
            <a href="https://wa.me/{{ $whatsappNum }}?text=Halo%20Concierge%20Cemara%20Residence,%20saya%20ingin%20menanyakan%20ketersediaan%20kamar" 
               target="_blank" rel="noopener noreferrer" 
               class="flex items-center gap-1.5 text-emerald-400 hover:text-emerald-300 font-semibold transition-colors">
                <i class="fab fa-whatsapp"></i> 24/7 Concierge: {{ $contact->whatsapp ?? '0812-3456-7890' }}
            </a>
        </div>
        <div class="flex items-center gap-4 text-slate-400">
            <span class="flex items-center gap-1">
                <i class="fas fa-shield-alt text-amber-400 text-xs"></i> Garansi 100% Terverifikasi
            </span>
            <span class="text-slate-600">•</span>
            <span class="bg-slate-800 text-amber-400 px-2 py-0.5 rounded text-[11px] font-semibold border border-slate-700">
                IDR (Rp)
            </span>
        </div>
    </div>
</div>

<!-- Main Sticky Glassmorphism Header -->
<header class="sticky top-0 z-50 bg-white/90 backdrop-blur-xl border-b border-slate-100/80 shadow-sm transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">

        <!-- Luxury Brand Logo -->
        <a href="/" class="flex items-center gap-3 group shrink-0">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-950 flex items-center justify-center text-amber-400 shadow-md group-hover:scale-105 transition-transform duration-300 border border-amber-400/30">
                <i class="fas fa-building text-lg"></i>
            </div>
            <div>
                <div class="text-xl sm:text-2xl font-black tracking-tight text-slate-900 leading-tight">
                    Cemara<span class="text-amber-500 font-light ml-1">Residence</span>
                </div>
                <div class="text-[10px] tracking-widest uppercase font-bold text-slate-400">
                    Luxury Boutique Living
                </div>
            </div>
        </a>

        <!-- Desktop Navigation Links -->
        <nav class="hidden lg:flex items-center gap-8 text-sm font-semibold text-slate-600">
            <a href="{{ route('landing') }}#kamar" class="hover:text-amber-600 transition-colors flex items-center gap-1.5">
                <i class="fas fa-bed text-xs text-slate-400"></i> Kamar & Suite
            </a>
            <a href="{{ route('landing') }}#fasilitas" class="hover:text-amber-600 transition-colors flex items-center gap-1.5">
                <i class="fas fa-concierge-bell text-xs text-slate-400"></i> Fasilitas Bintang 5
            </a>
            <a href="{{ route('landing') }}#lokasi" class="hover:text-amber-600 transition-colors flex items-center gap-1.5">
                <i class="fas fa-compass text-xs text-slate-400"></i> Lokasi
            </a>
            <a href="{{ route('landing') }}#ulasan" class="hover:text-amber-600 transition-colors flex items-center gap-1.5">
                <i class="fas fa-star text-xs text-amber-400"></i> Ulasan Tamu
            </a>
            <a href="{{ route('landing') }}#faq" class="hover:text-amber-600 transition-colors flex items-center gap-1.5">
                <i class="fas fa-question-circle text-xs text-slate-400"></i> FAQ
            </a>
        </nav>

        <!-- Desktop Right Actions -->
        <div class="hidden sm:flex items-center gap-3">
            @if($user)
                <!-- Authenticated User Dropdown -->
                <div class="relative">
                    <button id="profileDropdownBtn" 
                            class="flex items-center gap-3 px-3.5 py-2 rounded-2xl bg-slate-50 hover:bg-slate-100 border border-slate-200/80 text-slate-800 font-semibold text-sm transition-all shadow-sm">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-amber-500 to-amber-300 text-slate-950 flex items-center justify-center font-bold text-xs shadow-inner overflow-hidden">
                            @if($user->avatar)
                                <img src="{{ str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar) }}" 
                                     alt="{{ $name }}" class="w-full h-full object-cover">
                            @else
                                {{ $initials }}
                            @endif
                        </div>
                        <div class="text-left hidden md:block">
                            <div class="text-xs text-slate-400 font-normal leading-none mb-0.5">
                                {{ $isAdmin ? 'Administrator' : 'Guest / Resident' }}
                            </div>
                            <div class="text-sm font-bold text-slate-900 max-w-[120px] truncate leading-none">
                                {{ $name }}
                            </div>
                        </div>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400 ml-1"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="profileDropdownMenu" 
                         class="hidden absolute right-0 top-full mt-2 w-64 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden py-2 z-50 animate-in fade-in slide-in-from-top-2 duration-200">
                        <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50">
                            <div class="font-bold text-slate-900 text-sm truncate">{{ $name }}</div>
                            <div class="text-xs text-slate-500 truncate">{{ $user->email }}</div>
                        </div>

                        @if($isAdmin)
                            <a href="{{ route('admin.dashboard') }}" 
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-amber-50 hover:text-amber-700 font-medium transition-colors">
                                <i class="fas fa-chart-pie w-4 text-center text-amber-500"></i> Admin CMS Portal
                            </a>
                            <a href="{{ route('admin.rooms.index') }}" 
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-amber-50 hover:text-amber-700 font-medium transition-colors">
                                <i class="fas fa-door-open w-4 text-center text-amber-500"></i> Kelola Kamar
                            </a>
                        @else
                            <a href="{{ route('tenant.dashboard') }}" 
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-700 font-medium transition-colors">
                                <i class="fas fa-th-large w-4 text-center text-blue-500"></i> Guest Dashboard
                            </a>
                            <a href="{{ route('tenant.bookings.index') }}" 
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-700 font-medium transition-colors">
                                <i class="fas fa-calendar-check w-4 text-center text-blue-500"></i> Riwayat Booking
                            </a>
                        @endif

                        <a href="https://wa.me/{{ $whatsappNum }}" target="_blank" 
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium transition-colors">
                            <i class="fab fa-whatsapp w-4 text-center text-emerald-500"></i> Hubungi Concierge
                        </a>

                        <div class="h-px bg-slate-100 my-1"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 font-medium transition-colors text-left">
                                <i class="fas fa-sign-out-alt w-4 text-center"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <!-- Guest Actions -->
                <a href="{{ route('login') }}" 
                   class="px-4 py-2.5 rounded-xl text-sm font-bold text-slate-700 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                    Masuk
                </a>
                <a href="{{ route('landing') }}#kamar" 
                   class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white text-sm font-bold shadow-md hover:shadow-lg hover:scale-[1.02] active:scale-95 transition-all duration-200 border border-slate-700 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-amber-400"></i>
                    <span>Pesan Kamar</span>
                </a>
            @endif
        </div>

        <!-- Mobile Hamburger Button -->
        <div class="flex sm:hidden items-center gap-2">
            @if($user)
                <a href="{{ $isAdmin ? route('admin.dashboard') : route('tenant.dashboard') }}" 
                   class="w-9 h-9 rounded-xl bg-slate-900 text-amber-400 flex items-center justify-center font-bold text-xs border border-amber-400/30">
                    {{ $initials }}
                </a>
            @endif
            <button id="mobileMenuToggleBtn" 
                    class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700 hover:bg-slate-200 transition-colors"
                    aria-label="Buka Menu">
                <i id="mobileMenuIcon" class="fas fa-bars text-lg"></i>
            </button>
        </div>

    </div>

    <!-- Mobile Flyout Drawer -->
    <div id="mobileDrawer" class="hidden sm:hidden border-t border-slate-100 bg-white/95 backdrop-blur-xl px-4 py-5 shadow-2xl">
        <div class="flex flex-col gap-2">
            <a href="{{ route('landing') }}#kamar" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-700 hover:bg-slate-50 font-semibold text-sm">
                <i class="fas fa-bed text-amber-500 w-5"></i> Kamar & Suite
            </a>
            <a href="{{ route('landing') }}#fasilitas" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-700 hover:bg-slate-50 font-semibold text-sm">
                <i class="fas fa-concierge-bell text-amber-500 w-5"></i> Fasilitas Bintang 5
            </a>
            <a href="{{ route('landing') }}#lokasi" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-700 hover:bg-slate-50 font-semibold text-sm">
                <i class="fas fa-compass text-amber-500 w-5"></i> Lokasi Strategis
            </a>
            <a href="{{ route('landing') }}#ulasan" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-700 hover:bg-slate-50 font-semibold text-sm">
                <i class="fas fa-star text-amber-500 w-5"></i> Ulasan Tamu (4.9/5)
            </a>
            <a href="{{ route('landing') }}#faq" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-700 hover:bg-slate-50 font-semibold text-sm">
                <i class="fas fa-question-circle text-amber-500 w-5"></i> FAQ
            </a>

            <div class="h-px bg-slate-100 my-2"></div>

            @if($user)
                <a href="{{ $isAdmin ? route('admin.dashboard') : route('tenant.dashboard') }}" 
                   class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-slate-900 text-amber-400 font-bold text-sm shadow-md">
                    <i class="fas fa-th-large"></i> Dashboard Saya
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                    @csrf
                    <button type="submit" 
                            class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-rose-50 text-rose-600 font-bold text-sm">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                    </button>
                </form>
            @else
                <div class="grid grid-cols-2 gap-2 mt-2">
                    <a href="{{ route('login') }}" 
                       class="py-3 text-center rounded-xl border border-slate-200 text-slate-700 font-bold text-sm hover:bg-slate-50">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" 
                       class="py-3 text-center rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-sm shadow-sm">
                        Daftar
                    </a>
                </div>
            @endif
        </div>
    </div>
</header>

<!-- Mobile 1-Thumb Floating Bottom Navigation (Luxury App Standard) -->
<nav class="fixed bottom-0 inset-x-0 z-40 bg-white/90 backdrop-blur-xl border-t border-slate-200/80 sm:hidden py-2 px-4 shadow-[0_-8px_30px_rgb(0,0,0,0.06)]">
    <div class="grid grid-cols-4 gap-1 text-center">
        <a href="{{ route('landing') }}" class="flex flex-col items-center gap-1 text-slate-600 hover:text-amber-600">
            <i class="fas fa-home text-base"></i>
            <span class="text-[10px] font-bold">Home</span>
        </a>
        <a href="{{ route('landing') }}#kamar" class="flex flex-col items-center gap-1 text-slate-600 hover:text-amber-600">
            <i class="fas fa-bed text-base"></i>
            <span class="text-[10px] font-bold">Kamar</span>
        </a>
        <a href="https://wa.me/{{ $whatsappNum }}?text=Halo%20Concierge%20Cemara%20Residence,%20saya%20ingin%20booking%20kamar" 
           target="_blank" rel="noopener noreferrer" 
           class="flex flex-col items-center gap-1 text-emerald-600">
            <i class="fab fa-whatsapp text-base"></i>
            <span class="text-[10px] font-bold">Concierge</span>
        </a>
        <a href="{{ $user ? ($isAdmin ? route('admin.dashboard') : route('tenant.dashboard')) : route('login') }}" 
           class="flex flex-col items-center gap-1 {{ $user ? 'text-amber-600' : 'text-slate-600' }}">
            <i class="fas fa-user-circle text-base"></i>
            <span class="text-[10px] font-bold">{{ $user ? 'Akun' : 'Masuk' }}</span>
        </a>
    </div>
</nav>

<script>
    // Profile Dropdown Toggle
    const profileDropdownBtn = document.getElementById('profileDropdownBtn');
    const profileDropdownMenu = document.getElementById('profileDropdownMenu');

    if (profileDropdownBtn && profileDropdownMenu) {
        profileDropdownBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            profileDropdownMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', () => {
            profileDropdownMenu.classList.add('hidden');
        });
    }

    // Mobile Hamburger Menu Toggle
    const mobileMenuToggleBtn = document.getElementById('mobileMenuToggleBtn');
    const mobileDrawer = document.getElementById('mobileDrawer');
    const mobileMenuIcon = document.getElementById('mobileMenuIcon');

    if (mobileMenuToggleBtn && mobileDrawer) {
        mobileMenuToggleBtn.addEventListener('click', () => {
            const isOpen = mobileDrawer.classList.toggle('hidden');
            if (mobileMenuIcon) {
                mobileMenuIcon.className = isOpen ? 'fas fa-bars text-lg' : 'fas fa-times text-lg';
            }
        });

        mobileDrawer.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                mobileDrawer.classList.add('hidden');
                if (mobileMenuIcon) mobileMenuIcon.className = 'fas fa-bars text-lg';
            });
        });
    }
</script>