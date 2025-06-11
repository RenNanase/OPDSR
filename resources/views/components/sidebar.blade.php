@props(['showingSidebar' => false, 'isMinimized' => false])

<aside class="fixed h-full shadow-lg z-40 flex flex-col transition-all duration-300 ease-in-out
       bg-opd-primary text-white"
       x-data="{
           showingSidebar: {{ $showingSidebar ? 'true' : 'false' }},
           isMinimized: {{ $isMinimized ? 'true' : 'false' }},
           init() {
               // Initialize from localStorage if available
               const storedMinimized = localStorage.getItem('sidebarMinimized');
               if (storedMinimized !== null) {
                   this.isMinimized = storedMinimized === 'true';
               }

               // Listen for sidebar state changes
               window.addEventListener('sidebar-state-changed', (e) => {
                   this.isMinimized = e.detail.isMinimized;
               });
           }
       }"
       :class="{
           'w-64': !isMinimized || showingSidebar,
           'w-20': isMinimized && !showingSidebar,
           'translate-x-0': showingSidebar || window.innerWidth >= 768,
           '-translate-x-full': !showingSidebar && window.innerWidth < 768
       }">
    <div class="flex items-center justify-between h-16 bg-opd-primary-dark text-white text-2xl font-bold border-b border-opd-primary-dark shadow-md px-4">
        <div class="flex items-center space-x-3" :class="{ 'justify-center': isMinimized }">

            <span class="text-lg text-center font-semibold" :class="{ 'hidden': isMinimized }">{{ config('app.name', 'OPDSR') }}</span>
        </div>
        {{-- <button @click="isMinimized = !isMinimized"
                class="hidden md:block text-white hover:text-opd-secondary focus:outline-none ml-auto"
                :class="{ 'hidden': isMinimized }">
            <svg x-show="!isMinimized" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
            <svg x-show="isMinimized" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
            </svg>
        </button> --}}
    </div>

    <nav class="flex-1 px-2 py-4 space-y-2 overflow-y-auto pb-4">
        <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-2': !isMinimized}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0l7 7 7 7M19 10v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span x-show="!isMinimized">{{ __('Dashboard') }}</span>
        </x-sidebar-link>

        @if(auth()->user()->isStaff())
            {{-- Daily Dropdown --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                        class="flex items-center w-full px-4 py-2 text-white hover:bg-opd-primary-dark rounded-md transition duration-150 ease-in-out focus:outline-none">
                    <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-2': !isMinimized}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h.01M17 11h.01M5 15h.01M17 15h.01M5 19h.01M17 19h.01M3 21h18a2 2 0 002-2V7a2 2 0 00-2-2H3a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span x-show="!isMinimized" class="flex-1 text-left">{{ __('Daily') }}</span>
                    <svg x-show="!isMinimized" class="w-4 h-4 transform transition-transform" :class="{'rotate-90': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
                <div x-show="open && !isMinimized"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="ml-4 mt-1 space-y-1">
                    <x-sidebar-link :href="route('daily.resident-consultant.index')" :active="request()->routeIs('daily.resident-consultant.*')">
                        <span x-show="!isMinimized">{{ __('Resident Consultant') }}</span>
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('daily.visiting-consultant.index')" :active="request()->routeIs('daily.visiting-consultant.*')">
                        <span x-show="!isMinimized">{{ __('Visiting Consultant') }}</span>
                    </x-sidebar-link>
                </div>
            </div>

            {{-- Annual Dropdown --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                        class="flex items-center w-full px-4 py-2 text-white hover:bg-opd-primary-dark rounded-md transition duration-150 ease-in-out focus:outline-none">
                    <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-2': !isMinimized}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span x-show="!isMinimized" class="flex-1 text-left">{{ __('Annual') }}</span>
                    <svg x-show="!isMinimized" class="w-4 h-4 transform transition-transform" :class="{'rotate-90': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
                <div x-show="open && !isMinimized"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="ml-4 mt-1 space-y-1">
                    <x-sidebar-link :href="route('annual.old-wing.index')" :active="request()->routeIs('annual.old-wing.*')">
                        <span x-show="!isMinimized">{{ __('Old Wing') }}</span>
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('annual.new-wing.index')" :active="request()->routeIs('annual.new-wing.*')">
                        <span x-show="!isMinimized">{{ __('New Wing') }}</span>
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('annual.ctg.index')" :active="request()->routeIs('annual.ctg.*')">
                        <span x-show="!isMinimized">{{ __('CTG') }}</span>
                    </x-sidebar-link>
                </div>
            </div>
        @elseif(auth()->user()->isAdmin())
            <x-sidebar-link :href="route('log')" :active="request()->routeIs('log')">
                <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-2': !isMinimized}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <span x-show="!isMinimized">{{ __('Log') }}</span>
            </x-sidebar-link>
            <x-sidebar-link :href="route('user-management.index')" :active="request()->routeIs('user-management.*')">
                <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-2': !isMinimized}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h2a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2m3-2h1m1-16h-1m16 16l-3-3m-4-3V4m0 0H7m4 0l-3 3"></path></svg>
                <span x-show="!isMinimized">{{ __('User Management') }}</span>
            </x-sidebar-link>
            <x-sidebar-link :href="route('reports')" :active="request()->routeIs('reports')">
                <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-2': !isMinimized}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-4m3 4v-6m3 6V7m0 10h2a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h2L9 17z"></path></svg>
                <span x-show="!isMinimized">{{ __('Reports') }}</span>
            </x-sidebar-link>
        @endif

        <div class="border-t border-opd-primary-dark pt-2 mt-auto">
            <x-sidebar-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-2': !isMinimized}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0H9m7 0a2 2 0 100 4m-4 4h4m-4 4h4m-12 0H8m-4 0H5"></path></svg>
                <span x-show="!isMinimized">{{ __('Profile') }}</span>
            </x-sidebar-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-sidebar-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                    <svg class="w-5 h-5 flex-shrink-0" :class="{'mr-2': !isMinimized}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span x-show="!isMinimized">{{ __('Log Out') }}</span>
                </x-sidebar-link>
            </form>

        </div>
    </nav>
</aside>
