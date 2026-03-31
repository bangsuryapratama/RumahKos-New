{{-- Sidebar --}}
<aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed z-50 h-full w-64 border-r border-gray-200 bg-white shadow-lg transition-transform duration-300 ease-in-out"
>

    {{-- Logo & Brand --}}
    <div class="flex items-center justify-between border-b border-gray-200 p-5">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0 rounded-lg p-1">
                <img src="{{ asset('favicon.ico') }}" alt="RumahKos logo" class="h-7 w-7 object-contain">
            </div>
            <span class="whitespace-nowrap text-xl font-extrabold tracking-tight text-gray-900">
                Rumah<span class="text-blue-600">Kos</span>
            </span>
        </div>

        {{-- Close button (mobile only) --}}
        <button @click="sidebarOpen = false" class="rounded-lg p-2 transition hover:bg-gray-100 lg:hidden">
            <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="mt-4 overflow-y-auto px-3" style="height: calc(100vh - 180px);">
        <div class="space-y-1">

            {{-- Dashboard --}}
            <a
                href="{{ route('dashboard') }}"
                @click="sidebarOpen = false"
                class="flex items-center gap-3 rounded-lg px-4 py-3 transition
                    {{ request()->routeIs('dashboard') ? 'bg-blue-50 font-medium text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}"
            >
                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="font-medium">Dashboard</span>
            </a>

            {{-- User Management --}}
            @php
                $userManagementOpen = request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*');
            @endphp
            <div x-data="{ open: {{ $userManagementOpen ? 'true' : 'false' }} }">
                <button
                    @click="open = !open"
                    class="flex w-full items-center gap-3 rounded-lg px-4 py-3 transition
                        {{ $userManagementOpen ? 'bg-blue-50 font-medium text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}"
                >
                    <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="flex-1 text-left">User Management</span>
                    <svg class="h-4 w-4 flex-shrink-0 transition-transform" :class="open ? 'rotate-90' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                <div x-show="open" x-cloak class="ml-9 mt-1 space-y-1">
                    <a
                        href="{{ route('admin.users.index') }}"
                        @click="sidebarOpen = false"
                        class="block rounded-lg px-4 py-2 text-sm transition
                            {{ request()->routeIs('admin.users.*') ? 'bg-blue-100 font-medium text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}"
                    >Users</a>
                    <a
                        href="{{ route('admin.roles.index') }}"
                        @click="sidebarOpen = false"
                        class="block rounded-lg px-4 py-2 text-sm transition
                            {{ request()->routeIs('admin.roles.*') ? 'bg-blue-100 font-medium text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}"
                    >Roles</a>
                </div>
            </div>

            {{-- Kelola Penghuni --}}
            <a
                href="{{ route('admin.tenants.index') }}"
                @click="sidebarOpen = false"
                class="flex items-center gap-3 rounded-lg px-4 py-3 transition
                    {{ request()->routeIs('admin.tenants.*') ? 'bg-blue-50 font-medium text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}"
            >
                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-medium">Kelola Penghuni</span>
            </a>

            {{-- Fasilitas --}}
            @php
                $fasilitasOpen = request()->routeIs('admin.facilities.*') || request()->routeIs('admin.facility_rooms.*');
            @endphp
            <div x-data="{ open: {{ $fasilitasOpen ? 'true' : 'false' }} }">
                <button
                    @click="open = !open"
                    class="flex w-full items-center gap-3 rounded-lg px-4 py-3 transition
                        {{ $fasilitasOpen ? 'bg-blue-50 font-medium text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}"
                >
                    <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="flex-1 text-left">Fasilitas</span>
                    <svg class="h-4 w-4 flex-shrink-0 transition-transform" :class="open ? 'rotate-90' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                <div x-show="open" x-cloak class="ml-9 mt-1 space-y-1">
                    <a
                        href="{{ route('admin.facilities.index') }}"
                        @click="sidebarOpen = false"
                        class="block rounded-lg px-4 py-2 text-sm transition
                            {{ request()->routeIs('admin.facilities.*') ? 'bg-blue-100 font-medium text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}"
                    >Fasilitas</a>
                    <a
                        href="{{ route('admin.facility_rooms.index') }}"
                        @click="sidebarOpen = false"
                        class="block rounded-lg px-4 py-2 text-sm transition
                            {{ request()->routeIs('admin.facility_rooms.*') ? 'bg-blue-100 font-medium text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}"
                    >Fasilitas Kamar</a>
                </div>
            </div>

            {{-- Kelola Properti --}}
            <a
                href="{{ route('admin.properties.index') }}"
                @click="sidebarOpen = false"
                class="flex items-center gap-3 rounded-lg px-4 py-3 transition
                    {{ request()->routeIs('admin.properties.*') ? 'bg-blue-50 font-medium text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}"
            >
                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7M16 3h-1a2 2 0 00-2 2v1H9V5a2 2 0 00-2-2H6a2 2 0 00-2 2v1h16V5a2 2 0 00-2-2z"/>
                </svg>
                <span class="font-medium">Kelola Properti</span>
            </a>

            {{-- Kelola Kamar --}}
            <a
                href="{{ route('admin.rooms.index') }}"
                @click="sidebarOpen = false"
                class="flex items-center gap-3 rounded-lg px-4 py-3 transition
                    {{ request()->routeIs('admin.rooms.*') ? 'bg-blue-50 font-medium text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}"
            >
                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span class="font-medium">Kelola Kamar</span>
            </a>

            {{-- Laporan --}}
            {{-- TODO: ganti $laporanOpen dengan route laporan yang sesuai --}}
            @php
                $laporanOpen = request()->routeIs('admin.reports.*');
            @endphp
            <div x-data="{ open: {{ $laporanOpen ? 'true' : 'false' }} }">
                <button
                    @click="open = !open"
                    class="flex w-full items-center gap-3 rounded-lg px-4 py-3 transition
                        {{ $laporanOpen ? 'bg-blue-50 font-medium text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}"
                >
                    <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25M9 16.5v.75m3-3v3M15 12v5.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                    </svg>
                    <span class="flex-1 text-left">Laporan</span>
                    <svg class="h-4 w-4 flex-shrink-0 transition-transform" :class="open ? 'rotate-90' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                <div x-show="open" x-cloak class="ml-9 mt-1 space-y-1">
                    {{-- TODO: ganti route di bawah dengan route laporan penghuni & keuangan --}}
                    <a
                        href="{{ route('admin.reports.tenants') }}"
                        @click="sidebarOpen = false"
                        class="block rounded-lg px-4 py-2 text-sm transition
                            {{ request()->routeIs('admin.reports.tenants') ? 'bg-blue-100 font-medium text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}"
                    >Penghuni</a>
                    <a
                        href="{{ route('admin.reports.finance') }}"
                        @click="sidebarOpen = false"
                        class="block rounded-lg px-4 py-2 text-sm transition
                            {{ request()->routeIs('admin.reports.finance') ? 'bg-blue-100 font-medium text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}"
                    >Keuangan</a>
                </div>
            </div>

        </div>
    </nav>

    {{-- User Profile --}}
    <div class="absolute bottom-0 w-full border-t border-gray-200 bg-white p-4">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-600 to-blue-700">
                <span class="text-sm font-semibold text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
            </div>
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500">Admin</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Logout" class="flex-shrink-0 rounded-lg p-2 transition hover:bg-gray-100">
                    <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>

</aside>