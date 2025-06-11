<x-auth-layout>
    <div class="w-full max-w-md">
        <!-- Title -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-extrabold text-opd-primary tracking-wide mb-2">OPD STATISTIC RECORD</h1>
            <div class="w-24 h-1 bg-opd-primary mx-auto rounded-full"></div>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Name')" class="text-opd-text-dark" />
                    <x-text-input id="name" 
                        class="block mt-1 w-full border-opd-primary focus:border-opd-primary focus:ring-opd-primary rounded-md shadow-sm" 
                        type="text" 
                        name="name" 
                        :value="old('name')" 
                        required 
                        autofocus 
                        autocomplete="username" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" class="text-opd-text-dark" />
                    <x-text-input id="password" 
                        class="block mt-1 w-full border-opd-primary focus:border-opd-primary focus:ring-opd-primary rounded-md shadow-sm"
                        type="password"
                        name="password"
                        required 
                        autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center justify-center mt-6">
                    <button type="submit" 
                            class="inline-flex items-center px-8 py-2 bg-opd-primary border border-transparent rounded-md font-semibold text-black
                             hover:bg-opd-secondary hover:text-opd-primary-dark active:bg-opd-primary-dark focus:outline-none focus:ring-2 focus:ring-opd-primary focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Log in') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                &copy; {{ date('Y') }} OPD Statistic Record. All rights reserved. RN
            </p>
        </div>
    </div>
</x-auth-layout>
