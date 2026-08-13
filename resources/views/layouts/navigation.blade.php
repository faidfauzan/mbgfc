<div x-data="{ openMobileSidebar: false }">

  <!-- ========== HEADER ATAS ========== -->
  <!-- HEADER UTAMA -->
<header class="sticky top-0 inset-x-0 z-[30] w-full bg-blue-900 border-b border-amber-400/30 text-sm py-2.5 lg:ps-[260px] shadow-sm">
  <nav class="flex basis-full items-center w-full mx-auto px-4 sm:px-6" aria-label="Global">
    
    <!-- Toggle Mobile (Kiri) -->
    <div class="lg:hidden flex items-center gap-x-2">
      <button @click="openMobileSidebar = true" type="button" class="size-9 flex justify-center items-center gap-x-2 border border-emerald-500/50 text-white hover:bg-emerald-600 rounded-lg">
        <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
      
      <a class="flex items-center gap-2 text-lg font-bold text-white focus:outline-none whitespace-nowrap" href="{{ route('dashboard') }}">
        <img src="{{ asset('images/mbglogo.png') }}" alt="MBG FC Logo" class="h-8 w-auto">
        <span class="text-emerald-400">MBG <span class="text-emerald-300">FC</span></span>
      </a>
    </div>

    <!-- User Dropdown (Kanan Atas) -->
    <div class="w-full flex items-center justify-end ms-auto gap-x-3">
      <x-dropdown align="right" width="48">
          <x-slot name="trigger">
              <button class="inline-flex items-center px-3 py-2 border border-amber-400/40 text-sm leading-4 font-semibold rounded-lg text-white bg-blue-800 hover:bg-emerald-600 focus:outline-none transition duration-150 shadow-sm">
                  <div>{{ Auth::user()->name }}</div>
                  <div class="ms-1">
                      <svg class="fill-current h-4 w-4 text-amber-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                      </svg>
                  </div>
              </button>
          </x-slot>

          <x-slot name="content">
              <x-dropdown-link :href="route('profile.edit')" class="text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium">
                  {{ __('Profile') }}
              </x-dropdown-link>

              <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-600 hover:bg-red-50 font-medium">
                      {{ __('Log Out') }}
                  </x-dropdown-link>
              </form>
          </x-slot>
      </x-dropdown>
    </div>

  </nav>
</header>

  <!-- ========== BACKDROP MOBILE (GELAP) ========== -->
  <div x-show="openMobileSidebar" @click="openMobileSidebar = false"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-slate-900/80 z-[60] lg:hidden" style="display: none;"></div>

  <!-- ========== SIDEBAR UTAMA ========== -->
  <aside :class="openMobileSidebar ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 start-0 z-[70] w-[260px] bg-slate-900 border-e border-blue-900/50 transition-transform duration-300 transform lg:translate-x-0">

    <div class="relative flex flex-col h-full max-h-full">

      <!-- Tombol Close Mobile (X) -->
      <div class="lg:hidden absolute top-3 end-3 z-10">
        <button @click="openMobileSidebar = false" type="button" class="p-1 text-slate-400 hover:text-white rounded-lg">
          <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Logo Sidebar -->
      <div class="px-5 pt-5 pb-4 border-b border-slate-800 flex items-center gap-3">
        <a class="flex items-center gap-3 rounded-xl focus:outline-none" href="{{ route('dashboard') }}">
          <img src="{{ asset('images/mbglogo.png') }}" alt="MBG FC Logo" class="h-10 w-auto object-contain">
          <span class="text-xl font-bold tracking-wider text-emerald-400">MBG <span
              class="text-emerald-300">FC</span></span>
        </a>
      </div>

      <!-- Links Navigasi -->
      <div class="h-full overflow-y-auto p-3">
        <nav class="w-full flex flex-col">
          <ul class="space-y-1.5">

            {{-- Menu Dashboard --}}
            <li>
              <a class="flex items-center gap-x-3 py-2.5 px-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-emerald-600 to-emerald-500 text-white shadow-md shadow-emerald-900/30 ring-1 ring-emerald-400/50' : 'text-slate-300 hover:bg-slate-800 hover:text-amber-400' }}"
                href="{{ route('dashboard') }}">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2">
                  <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                  <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
                {{ __('Dashboard') }}
              </a>
            </li>

            {{-- Menu Member --}}
            @if (auth()->user()->role === 'member')
              <li>
                <a class="flex items-center gap-x-3 py-2.5 px-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('matchday.member.*') ? 'bg-gradient-to-r from-emerald-600 to-emerald-500 text-white shadow-md shadow-emerald-900/30 ring-1 ring-emerald-400/50' : 'text-slate-300 hover:bg-slate-800 hover:text-amber-400' }}"
                  href="{{ route('matchday.member.index') }}">
                  <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                    <line x1="16" x2="16" y1="2" y2="6" />
                    <line x1="8" x2="8" y1="2" y2="6" />
                    <line x1="3" x2="21" y1="10" y2="10" />
                  </svg>
                  Jadwal Matchday
                </a>
              </li>
            @endif

            {{-- Menu Captain / Admin --}}
            @if (auth()->user()->role === 'captain')
              <li>
                <a class="flex items-center gap-x-3 py-2.5 px-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('members.*') ? 'bg-gradient-to-r from-emerald-600 to-emerald-500 text-white shadow-md shadow-emerald-900/30 ring-1 ring-emerald-400/50' : 'text-slate-300 hover:bg-slate-800 hover:text-amber-400' }}"
                  href="{{ route('members.index') }}">
                  <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                  </svg>
                  Kelola Members
                </a>
              </li>

              <li>
                <a class="flex items-center gap-x-3 py-2.5 px-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('matchdays.*') ? 'bg-gradient-to-r from-emerald-600 to-emerald-500 text-white shadow-md shadow-emerald-900/30 ring-1 ring-emerald-400/50' : 'text-slate-300 hover:bg-slate-800 hover:text-amber-400' }}"
                  href="{{ route('matchdays.index') }}">
                  <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                    <circle cx="12" cy="10" r="3" />
                  </svg>
                  Kelola Matchdays
                </a>
              </li>
            @endif

          </ul>
        </nav>
      </div>

    </div>
  </aside>

</div>