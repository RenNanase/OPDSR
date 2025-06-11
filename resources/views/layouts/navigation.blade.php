<nav>
    <!-- Main Navigation Bar -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-12">
                <div class="flex space-x-8">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(auth()->user()->isStaff())
                        <x-nav-link :href="route('consultant')" :active="request()->routeIs('consultant')">
                            {{ __('Consultant') }}
                        </x-nav-link>
                        <x-nav-link :href="route('reports')" :active="request()->routeIs('reports')">
                            {{ __('Reports') }}
                        </x-nav-link>
                    @elseif(auth()->user()->isAdmin())
                        <x-nav-link :href="route('log')" :active="request()->routeIs('log')">
                            {{ __('Log') }}
                        </x-nav-link>
                        <x-nav-link :href="route('user-management.index')" :active="request()->routeIs('user-management.*')">
                            {{ __('User Management') }}
                        </x-nav-link>
                        <x-nav-link :href="route('reports')" :active="request()->routeIs('reports')">
                            {{ __('Reports') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>
        </div>
    </div>
</nav>
