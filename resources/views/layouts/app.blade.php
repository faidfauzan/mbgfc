<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MBG FC') }}</title>

    <!-- Fonts & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Script Init Dark Mode (Mencetak class 'dark' di <html>) -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="font-sans antialiased bg-slate-100 dark:bg-slate-900 text-slate-900 dark:text-slate-100 min-h-screen flex flex-col" 
      x-data="{ 
          sidebarCollapsed: false, 
          openMobileSidebar: false,
          darkMode: localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
          toggleTheme() {
              this.darkMode = !this.darkMode;
              if (this.darkMode) {
                  document.documentElement.classList.add('dark');
                  localStorage.theme = 'dark';
              } else {
                  document.documentElement.classList.remove('dark');
                  localStorage.theme = 'light';
              }
          }
      }">

    <!-- Sidebar komponen -->
    @include('layouts.navigation')

    <!-- Container Utama -->
    <div :class="sidebarCollapsed ? 'lg:pl-[80px]' : 'lg:pl-[260px]'" class="flex-1 flex flex-col transition-all duration-300 min-h-screen w-full">
        
        <!-- Topbar Floating Blur Area -->
<div class="sticky top-0 z-40 p-4 md:p-6 pb-2 bg-slate-100/60 dark:bg-slate-900/60 backdrop-blur-md transition-all">
    <header class="bg-white/80 dark:bg-slate-800/80 border border-gray-200/80 dark:border-slate-700/60 rounded-2xl px-6 py-4 flex items-center justify-between shadow-lg">
        <div class="flex items-center gap-4">
            <!-- Toggle Mobile -->
            <button @click="openMobileSidebar = !openMobileSidebar" class="lg:hidden p-2 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white rounded-lg">
                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="flex items-center gap-3">
                <span class="w-1.5 h-6 bg-emerald-500 rounded-full"></span>
                <h1 class="text-xl font-bold text-slate-900 dark:text-white">Welcome, {{ auth()->user()->name ?? 'User' }}!</h1>
            </div>
        </div>

        <!-- Tombol Dark Mode & Profile Dropdown -->
        <div class="flex items-center gap-4" x-data="{ profileOpen: false }">
            <!-- Tombol Toggle Dark/Light Mode -->
            <button @click="toggleTheme()" type="button" class="p-2.5 rounded-xl bg-gray-100/80 dark:bg-slate-700/50 text-slate-700 dark:text-yellow-400 hover:bg-gray-200 dark:hover:bg-slate-700 transition focus:outline-none border border-gray-200/60 dark:border-slate-600/60">
                <!-- Icon Bulan (Dark Mode Active) -->
                <svg x-show="darkMode" class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
                <!-- Icon Matahari (Light Mode Active) -->
                <svg x-show="!darkMode" class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </button>

            <!-- Dropdown Profile Menu -->
            <div class="relative">
                <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false" type="button" class="size-10 rounded-full bg-emerald-600 text-white font-bold flex items-center justify-center border-2 border-emerald-400 hover:ring-4 hover:ring-emerald-500/20 transition focus:outline-none">
                    {{ strtoupper(substr(auth()->user()->name ?? 'FA', 0, 2)) }}
                </button>

                <!-- Popup Menu Profile -->
                <div x-show="profileOpen" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 rounded-xl bg-white dark:bg-slate-800 shadow-xl py-1 border border-gray-100 dark:border-slate-700 z-50" 
                     style="display: none;">
                    
                    <div class="px-4 py-2 border-b border-gray-100 dark:border-slate-700">
                        <p class="text-sm font-semibold text-slate-800 dark:text-white truncate">{{ auth()->user()->name ?? 'User' }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ auth()->user()->email ?? '' }}</p>
                    </div>

                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-slate-700/50 hover:text-emerald-600 dark:hover:text-emerald-400 transition">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Edit Profile
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 text-left px-4 py-2 text-sm text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>
</div>

        <!-- Main Content Area -->
        <main class="flex-1 p-6 md:p-8 overflow-y-auto max-w-full">
            {{ $slot }}
        </main>
    </div>

</body>
</html>