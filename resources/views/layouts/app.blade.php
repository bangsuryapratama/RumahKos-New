<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>RumahKos - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js Cloak Style -->
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen">

        {{-- Mobile Overlay --}}
        <div x-show="sidebarOpen"
             @click="sidebarOpen = false"
             x-transition.opacity
             x-cloak
             class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 lg:hidden"></div>

        {{-- Sidebar --}}
        @include('layouts.sidebar')

        {{-- Main Content Area --}}
        <div class="lg:ml-64 min-h-screen flex flex-col">

            {{-- Topbar --}}
            <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">

                        {{-- Left: Hamburger + Logo/Title --}}
                        <div class="flex items-center gap-4">
                            {{-- Hamburger Menu (Mobile) --}}
                            <button @click="sidebarOpen = !sidebarOpen"
                                    class="lg:hidden p-2 hover:bg-gray-100 rounded-lg transition">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>

                            {{-- Logo (Mobile Only) --}}
                            <div class="flex items-center gap-2 lg:hidden">
                                <img src="{{ asset('favicon.ico') }}" alt="RumahKos logo" class="w-6 h-6 object-contain">
                                <span class="text-lg font-bold text-gray-900">
                                    Rumah<span class="text-blue-600">Kos</span>
                                </span>
                            </div>

                            {{-- Page Title (Desktop) --}}
                            <div class="hidden lg:block">
                                @isset($header)
                                    {{ $header }}
                                @else
                                    <h2 class="text-lg font-semibold text-gray-900">Dashboard</h2>
                                @endisset
                            </div>
                        </div>

                        {{-- Right: Notifications + User Menu --}}
                        <div class="flex items-center gap-2 sm:gap-4">

                            {{-- Notifications --}}
                            <button class="p-2 hover:bg-gray-100 rounded-lg transition relative">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>

                            {{-- User Dropdown --}}
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open"
                                        class="flex items-center gap-2 p-2 hover:bg-gray-100 rounded-lg transition">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-600 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                {{-- Dropdown Menu --}}
                                <div x-show="open"
                                     @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     x-cloak
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                                    <div class="px-4 py-2 border-b border-gray-100">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                    </div>
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-user mr-2"></i>Profile
                                    </a>
                                    <div class="border-t border-gray-100 mt-2 pt-2">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1">
                {{ $slot }}
            </main>

            {{-- Footer --}}
            <footer class="bg-white border-t border-gray-200 py-4 mt-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <p class="text-center text-sm text-gray-500">
                        © {{ date('Y') }} RumahKos. All rights reserved.
                    </p>
                </div>
            </footer>

        </div>
    </div>
</body>
</html>
