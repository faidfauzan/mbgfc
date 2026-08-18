<div>
  <!-- ========== BACKDROP MOBILE (GELAP) ========== -->
  <div x-show="openMobileSidebar" @click="openMobileSidebar = false"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-slate-900/80 z-[60] lg:hidden" style="display: none;"></div>

  <!-- ========== SIDEBAR UTAMA ========== -->
  <aside :class="[
      openMobileSidebar ? 'translate-x-0' : '-translate-x-full',
      sidebarCollapsed ? 'lg:w-[80px]' : 'lg:w-[260px]',
      'lg:translate-x-0'
    ]"
    class="fixed inset-y-0 start-0 z-[70] bg-slate-900 border-e border-blue-900/50 transition-all duration-300 transform flex flex-col h-full max-h-full">

    <!-- Tombol Toggle Collapse Sidebar (Desktop) -->
    <button @click="sidebarCollapsed = !sidebarCollapsed" 
            class="hidden lg:flex absolute -right-3.5 top-8 z-[80] items-center justify-center size-7 rounded-full bg-slate-800 border-2 border-slate-900 text-slate-300 hover:text-white hover:bg-slate-700 hover:border-emerald-500 transition-colors focus:outline-none">
      <svg :class="sidebarCollapsed ? 'rotate-180' : ''" class="size-4 transition-transform duration-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
      </svg>
    </button>

    <!-- Tombol Close Mobile (X) -->
    <div class="lg:hidden absolute top-3 end-3 z-10">
      <button @click="openMobileSidebar = false" type="button" class="p-1.5 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg transition-colors">
        <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Logo Sidebar -->
    <div class="px-5 pt-6 pb-5 border-b border-slate-800/60 flex items-center gap-3 h-[76px] overflow-hidden whitespace-nowrap" :class="sidebarCollapsed ? 'justify-center px-0' : ''">
      <a class="flex items-center gap-3 rounded-xl focus:outline-none" href="{{ route('dashboard') }}">
        <img src="{{ asset('images/mbglogo.png') }}" alt="MBG FC Logo" class="h-10 w-auto object-contain shrink-0">
        <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="text-2xl font-extrabold tracking-wider text-emerald-400 dark:text-emerald-400">MBG <span class="text-emerald-300">FC</span></span>
      </a>
    </div>

    <!-- Links Navigasi -->
    <div class="flex-1 overflow-y-auto p-3 custom-scrollbar">
      <nav class="w-full flex flex-col space-y-1">
        
        <!-- Label Kategori Menu -->
        <div x-show="!sidebarCollapsed" x-transition.opacity class="px-3 pt-4 pb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
            Menu Utama
        </div>

        <ul class="space-y-1.5">
          {{-- Menu Dashboard --}}
          <li>
            <a class="flex items-center gap-x-3 py-2.5 px-3 text-sm font-medium rounded-lg transition-all duration-200 group relative {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-emerald-600 to-emerald-500 text-white shadow-md shadow-emerald-900/30 ring-1 ring-emerald-400/50' : 'text-slate-300 hover:bg-slate-800 hover:text-emerald-400' }}"
              :class="sidebarCollapsed ? 'justify-center px-0' : ''"
              href="{{ route('dashboard') }}" title="Dashboard">
              <svg class="shrink-0 size-5" :class="sidebarCollapsed ? '' : 'text-emerald-400/70 group-hover:text-emerald-400'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                <polyline points="9 22 9 12 15 12 15 22" />
              </svg>
              <span x-show="!sidebarCollapsed" class="whitespace-nowrap transition-opacity duration-300">{{ __('Dashboard') }}</span>
              
              <!-- Tooltip saat collapsed -->
              <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-2 py-1 bg-slate-800 text-white text-xs rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap">
                  Dashboard
              </div>
            </a>
          </li>

          {{-- Menu Member --}}
          @if (auth()->user()->role === 'member')
            <li>
              <a class="flex items-center gap-x-3 py-2.5 px-3 text-sm font-medium rounded-lg transition-all duration-200 group relative {{ request()->routeIs('matchday.member.*') ? 'bg-gradient-to-r from-emerald-600 to-emerald-500 text-white shadow-md shadow-emerald-900/30 ring-1 ring-emerald-400/50' : 'text-slate-300 hover:bg-slate-800 hover:text-emerald-400' }}"
                :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                href="{{ route('matchday.member.index') }}">
                <svg class="shrink-0 size-5" :class="sidebarCollapsed ? '' : 'text-emerald-400/70 group-hover:text-emerald-400'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                  <line x1="16" x2="16" y1="2" y2="6" />
                  <line x1="8" x2="8" y1="2" y2="6" />
                  <line x1="3" x2="21" y1="10" y2="10" />
                </svg>
                <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Jadwal Matchday</span>
                <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-2 py-1 bg-slate-800 text-white text-xs rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap">
                    Jadwal Matchday
                </div>
              </a>
            </li>
          @endif
        </ul>

        {{-- Label Kategori Admin --}}
        @if (auth()->user()->role === 'captain')
        <div x-show="!sidebarCollapsed" x-transition.opacity class="px-3 pt-6 pb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
            Manajemen
        </div>
        @else
        <div class="pt-2 border-t border-slate-800/60 mt-4 mx-3"></div>
        @endif

        <ul class="space-y-1.5">
          {{-- Menu Captain / Admin --}}
          @if (auth()->user()->role === 'captain')
            <li>
              <a class="flex items-center gap-x-3 py-2.5 px-3 text-sm font-medium rounded-lg transition-all duration-200 group relative {{ request()->routeIs('members.*') ? 'bg-gradient-to-r from-emerald-600 to-emerald-500 text-white shadow-md shadow-emerald-900/30 ring-1 ring-emerald-400/50' : 'text-slate-300 hover:bg-slate-800 hover:text-emerald-400' }}"
                :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                href="{{ route('members.index') }}">
                <svg class="shrink-0 size-5" :class="sidebarCollapsed ? '' : 'text-emerald-400/70 group-hover:text-emerald-400'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                  <circle cx="9" cy="7" r="4" />
                  <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                  <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
                <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Kelola Members</span>
                <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-2 py-1 bg-slate-800 text-white text-xs rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap">
                    Kelola Members
                </div>
              </a>
            </li>

            <li>
              <a class="flex items-center gap-x-3 py-2.5 px-3 text-sm font-medium rounded-lg transition-all duration-200 group relative {{ request()->routeIs('matchdays.*') ? 'bg-gradient-to-r from-emerald-600 to-emerald-500 text-white shadow-md shadow-emerald-900/30 ring-1 ring-emerald-400/50' : 'text-slate-300 hover:bg-slate-800 hover:text-emerald-400' }}"
                :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                href="{{ route('matchdays.index') }}">
                <svg class="shrink-0 size-5" :class="sidebarCollapsed ? '' : 'text-emerald-400/70 group-hover:text-emerald-400'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                  <circle cx="12" cy="10" r="3" />
                </svg>
                <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Kelola Matchdays</span>
                <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-2 py-1 bg-slate-800 text-white text-xs rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap">
                    Kelola Matchdays
                </div>
              </a>
            </li>
          @endif
        </ul>
      </nav>
    </div>

  </aside>
</div>