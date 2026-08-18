<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }" x-init="if(darkMode) document.documentElement.classList.add('dark')">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            /* Custom Scrollbar for Sidebar */
            .custom-scrollbar::-webkit-scrollbar { width: 5px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #475569; }
        </style>
    </head>
<body class="font-sans antialiased bg-gray-100 dark:bg-[#121629] text-gray-800 dark:text-gray-100 min-h-screen transition-colors duration-300" x-data="{ sidebarCollapsed: false, openMobileSidebar: false }">
    @include('layouts.navigation')

    <!-- Content Wrapper -->
    <div :class="sidebarCollapsed ? 'lg:ps-[80px]' : 'lg:ps-[260px]'" class="w-full min-h-screen flex flex-col transition-all duration-300">
        
        <!-- ========== FLOATING HEADER ========== -->
        <div class="p-4 sm:p-6 lg:p-8 pb-0 z-20 sticky top-0">
            <header class="bg-white dark:bg-[#1a1f37] backdrop-blur-md rounded-2xl shadow-sm border border-gray-200 dark:border-slate-800 px-4 py-3 flex items-center justify-between">
                <!-- Kiri: Mobile Toggle / Info -->
                <div class="flex items-center gap-3">
                    <button @click="openMobileSidebar = true" class="lg:hidden text-gray-500 hover:text-emerald-600 focus:outline-none">
                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <!-- Aksen warna / Greeting -->
                    <div class="hidden lg:block w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                    <span class="font-semibold text-gray-700 dark:text-gray-200 hidden sm:block">Welcome, {{ explode(' ', Auth::user()->name)[0] }}!</span>
                </div>

                <!-- Kanan: Dark Mode, Profile -->
                <div class="flex items-center gap-4">
                    <!-- Dark mode toggle -->
                    <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light'); document.documentElement.classList.toggle('dark')" class="p-2 text-gray-400 hover:text-emerald-500 rounded-full bg-gray-50 dark:bg-slate-800 hover:bg-emerald-50 dark:hover:bg-slate-700 transition-colors">
                        <svg x-show="!darkMode" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <svg x-show="darkMode" style="display: none;" class="size-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>

                    <!-- Avatar Dropdown -->
                    <x-dropdown align="right" width="56" contentClasses="py-0 bg-slate-900 border border-slate-700 rounded-xl shadow-xl overflow-hidden">
                        <x-slot name="trigger">
                            @php
                                $initials = collect(explode(' ', Auth::user()->name))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
                            @endphp
                            <button class="relative flex items-center focus:outline-none transition-transform hover:scale-105">
                                <div class="size-10 rounded-full bg-emerald-100 text-emerald-600 font-bold flex items-center justify-center border-2 border-white shadow-sm">
                                    {{ strtoupper($initials) }}
                                </div>
                                <span class="absolute bottom-0 right-0 size-3 bg-emerald-500 border-2 border-white rounded-full"></span>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Header Dropdown -->
                            <div class="px-4 py-3 border-b border-slate-700 bg-slate-800/50 text-left">
                                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-emerald-400 capitalize mt-0.5">{{ Auth::user()->role ?? 'Member' }}</p>
                            </div>
                            
                            <!-- Links -->
                            <div class="p-1.5">
                                <x-dropdown-link :href="route('profile.edit')" class="text-sm text-slate-300 hover:bg-slate-800 hover:text-emerald-400 rounded-lg flex items-center gap-2 transition-colors px-3 py-2">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    My Profile
                                </x-dropdown-link>
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-sm text-red-400 hover:bg-red-500/10 hover:text-red-300 rounded-lg flex items-center gap-2 transition-colors px-3 py-2 mt-1">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                        Log Out
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>
            </header>
        </div>

        <main class="w-full max-w-6xl mx-auto flex-1 overflow-x-auto px-4 sm:px-6 lg:px-8 pt-4 pb-8 space-y-6">
            {{ $slot }}
        </main>
    </div>
</body>
</html>