{{-- Sidebar --}}
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
       class="bg-white border-r border-gray-200 fixed h-full z-50 shadow-lg w-64 transition-transform duration-300 ease-in-out">

    {{-- Logo & Brand --}}
    <div class="flex items-center justify-between p-5 border-b border-gray-200">
        <div class="flex items-center gap-3">
            <div class="p-1 rounded-lg flex-shrink-0">
                <img src="{{ asset('favicon.ico') }}" alt="RumahKos logo" class="w-7 h-7 object-contain">
            </div>
            <span class="text-xl font-extrabold tracking-tight text-gray-900 whitespace-nowrap">
                Rumah<span class="text-blue-600">Kos</span>
            </span>
        </div>
        {{-- Close button for mobile --}}
        <button @click="sidebarOpen = false" class="lg:hidden p-2 hover:bg-gray-100 rounded-lg transition">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Navigation Menu --}}
    <nav class="mt-4 px-3 overflow-y-auto" style="height: calc(100vh - 180px);">
        <div class="space-y-1">

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
               @click="sidebarOpen = false"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="font-medium">Dashboard</span>
            </a>

            {{-- User Management Dropdown --}}
            @php
                $userManagementOpen = request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*');
            @endphp
            <div>
                <button @click="$refs.userManagementMenu.classList.toggle('hidden')"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition
                    {{ $userManagementOpen ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="flex-1 text-left">User Management</span>
                    <svg class="w-4 h-4 flex-shrink-0 transition-transform"
                         :class="$refs.userManagementMenu.classList.contains('hidden') ? '' : 'rotate-90'"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <div x-ref="userManagementMenu" class="ml-9 mt-1 space-y-1 {{ $userManagementOpen ? '' : 'hidden' }}">
                    <a href="{{ route('admin.users.index') }}"
                        @click="sidebarOpen = false"
                        class="block px-4 py-2 rounded-lg text-sm transition
                        {{ request()->routeIs('admin.users.*')
                            ? 'bg-blue-100 text-blue-700 font-medium'
                            : 'text-gray-600 hover:bg-gray-100' }}">
                        Users
                    </a>
                    <a href="{{ route('admin.roles.index') }}"
                        @click="sidebarOpen = false"
                        class="block px-4 py-2 rounded-lg text-sm transition
                        {{ request()->routeIs('admin.roles.*')
                            ? 'bg-blue-100 text-blue-700 font-medium'
                            : 'text-gray-600 hover:bg-gray-100' }}">
                        Roles
                    </a>
                </div>
            </div>

            {{-- Kelola Penghuni --}}
            <a href="{{ route('admin.tenants.index') }}"
               @click="sidebarOpen = false"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.tenants.*') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">Kelola Penghuni</span>
            </a>

            {{-- Fasilitas Dropdown --}}
            @php
                $fasilitasOpen = request()->routeIs('admin.facilities.*') || request()->routeIs('admin.facility_rooms.*');
            @endphp
            <div>
                <button @click="$refs.fasilitasMenu.classList.toggle('hidden')"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition
                    {{ $fasilitasOpen ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span class="flex-1 text-left">Fasilitas</span>
                    <svg class="w-4 h-4 flex-shrink-0 transition-transform"
                         :class="$refs.fasilitasMenu.classList.contains('hidden') ? '' : 'rotate-90'"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <div x-ref="fasilitasMenu" class="ml-9 mt-1 space-y-1 {{ $fasilitasOpen ? '' : 'hidden' }}">
                    <a href="{{ route('admin.facilities.index') }}"
                        @click="sidebarOpen = false"
                        class="block px-4 py-2 rounded-lg text-sm transition
                        {{ request()->routeIs('admin.facilities.*')
                            ? 'bg-blue-100 text-blue-700 font-medium'
                            : 'text-gray-600 hover:bg-gray-100' }}">
                        Fasilitas
                    </a>
                    <a href="{{ route('admin.facility_rooms.index') }}"
                        @click="sidebarOpen = false"
                        class="block px-4 py-2 rounded-lg text-sm transition
                        {{ request()->routeIs('admin.facility_rooms.*')
                            ? 'bg-blue-100 text-blue-700 font-medium'
                            : 'text-gray-600 hover:bg-gray-100' }}">
                        Fasilitas Kamar
                    </a>
                </div>
            </div>

            {{-- Properti --}}
            <a href="{{ route('admin.properties.index') }}"
                @click="sidebarOpen = false"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.properties.*') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                 <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7M16 3h-1a2 2 0 00-2 2v1H9V5a2 2 0 00-2-2H6a2 2 0 00-2 2v1h16V5a2 2 0 00-2-2z" />
                 </svg>
                 <span class="font-medium">Kelola Properti</span>
            </a>

            {{-- Kamar --}}
            <a href="{{ route('admin.rooms.index') }}"
               @click="sidebarOpen = false"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.rooms.*') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span class="font-medium">Kelola Kamar</span>
            </a>

        </div>
    </nav>

    {{-- User Profile at Bottom --}}
    <div class="absolute bottom-0 w-full p-4 border-t border-gray-200 bg-white">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-white font-semibold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500">Admin</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2 hover:bg-gray-100 rounded-lg transition flex-shrink-0" title="Logout">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
    </div>

</aside>
