<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'OPDSR') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100"
         x-data="{
             showingSidebar: false,
             isMinimized: localStorage.getItem('sidebarMinimized') === 'true',
             init() {
                 this.$watch('isMinimized', value => {
                     localStorage.setItem('sidebarMinimized', value);
                     // Dispatch event to notify main content
                     window.dispatchEvent(new CustomEvent('sidebar-state-changed', { detail: { isMinimized: value } }));
                 });
             }
         }">
        <x-sidebar :showing-sidebar="false" :is-minimized="false" />

        <!-- Main Content Wrapper -->
        <div class="transition-all duration-300 ease-in-out"
             :class="{
                 'md:ml-64': !isMinimized,
                 'md:ml-20': isMinimized
             }">
            <!-- Mobile Header -->
            <div class="md:hidden">
                <div class="flex items-center justify-between h-16 px-4 bg-white border-b border-gray-200 shadow-sm">
                    <button @click="showingSidebar = !showingSidebar" class="text-gray-600 hover:text-opd-primary focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="text-xl font-semibold text-gray-800">{{ config('app.name', 'OPDSR') }}</div>
                    <div class="flex items-center space-x-2">
                        <!-- Mobile Notifications -->
                        <button class="p-2 text-gray-600 hover:text-opd-primary focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </button>
                        <!-- Mobile User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-opd-primary flex items-center justify-center text-white">
                                    <span class="text-sm font-medium">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</span>
                                </div>
                            </button>
                            <div x-show="open"
                                 @click.away="open = false"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Profile Settings
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desktop Header -->
            <div class="hidden md:block">
                <div class="flex items-center justify-between h-16 px-6 bg-white border-b border-gray-200 shadow-sm">
                    <!-- Left side - Sidebar Toggle -->
                    <div class="flex items-center">
                        <button @click="isMinimized = !isMinimized" class="p-2 text-gray-600 hover:text-opd-primary focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>

                    <!-- Center - Page Title -->
                    <div class="flex-1 text-center">
                        @if (isset($header))
                            <h1 class="text-xl font-semibold text-gray-800">{{ $header }}</h1>
                        @endif
                    </div>

                    <!-- Right side - User Menu and Notifications -->
                    <div class="flex items-center space-x-4">


                        <!-- User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-opd-primary flex items-center justify-center text-white">
                                    <span class="text-sm font-medium">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</span>
                                </div>
                                <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name ?? 'User' }}</span>
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open"
                                 @click.away="open = false"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <div class="px-4 py-2 text-sm text-gray-700 border-b border-gray-100">
                                    <p class="font-medium">{{ auth()->user()->name ?? 'User' }}</p>

                                </div>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Profile Settings
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="p-4">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
