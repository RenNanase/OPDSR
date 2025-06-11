<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('CTG Records') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Action Buttons -->
                    <div class="flex justify-end mb-6">
                        <div class="flex space-x-4">
                            <a href="{{ route('annual.ctg.daily') }}"
                               class="inline-flex items-center px-4 py-2 bg-[#87cefa] border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-[#ffd3dd] focus:bg-[#ffd3dd] active:bg-[#ffd3dd] focus:outline-none focus:ring-2 focus:ring-[#87cefa] focus:ring-offset-2 transition ease-in-out duration-150">
                                Daily Records
                            </a>
                            <a href="{{ route('annual.ctg.monthly') }}"
                               class="inline-flex items-center px-4 py-2 bg-[#87cefa] border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-[#ffd3dd] focus:bg-[#ffd3dd] active:bg-[#ffd3dd] focus:outline-none focus:ring-2 focus:ring-[#87cefa] focus:ring-offset-2 transition ease-in-out duration-150">
                                Monthly Records
                            </a>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('annual.ctg.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="date" :value="__('Date')" />
                            <div class="flex gap-4 items-end">
                                <div class="flex-1">
                                    <x-text-input id="date" name="date" type="date" class="mt-1 block w-full"
                                        :value="old('date', $record?->date?->format('Y-m-d') ?? now()->format('Y-m-d'))" required />
                                    <x-input-error :messages="$errors->get('date')" class="mt-2" />
                                </div>
                                <button type="button" id="viewButton"
                                    class="inline-flex items-center px-4 py-2 bg-[#87cefa] border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-[#ffd3dd] focus:bg-[#ffd3dd] active:bg-[#ffd3dd] focus:outline-none focus:ring-2 focus:ring-[#87cefa] focus:ring-offset-2 transition ease-in-out duration-150">
                                    View
                                </button>
                            </div>

                            <!-- Last Update Information -->
                            <div id="lastUpdateInfo" class="mt-2 text-sm text-gray-600" style="display: none;">
                                Last updated by: <span id="lastUpdateUser"></span> on <span id="lastUpdateTime"></span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="dr_geetha_count" :value="__('Dr. Geetha CTG Count')" />
                                <x-text-input id="dr_geetha_count" name="dr_geetha_count" type="number" class="mt-1 block w-full"
                                    :value="old('dr_geetha_count', $record?->dr_geetha_count)" required min="0" />
                                <x-input-error :messages="$errors->get('dr_geetha_count')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="dr_joseph_count" :value="__('Dr. Joseph CTG Count')" />
                                <x-text-input id="dr_joseph_count" name="dr_joseph_count" type="number" class="mt-1 block w-full"
                                    :value="old('dr_joseph_count', $record?->dr_joseph_count)" required min="0" />
                                <x-input-error :messages="$errors->get('dr_joseph_count')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="dr_sutha_count" :value="__('Dr. Sutha CTG Count')" />
                                <x-text-input id="dr_sutha_count" name="dr_sutha_count" type="number" class="mt-1 block w-full"
                                    :value="old('dr_sutha_count', $record?->dr_sutha_count)" required min="0" />
                                <x-input-error :messages="$errors->get('dr_sutha_count')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="dr_ramesh_count" :value="__('Dr. Ramesh CTG Count')" />
                                <x-text-input id="dr_ramesh_count" name="dr_ramesh_count" type="number" class="mt-1 block w-full"
                                    :value="old('dr_ramesh_count', $record?->dr_ramesh_count)" required min="0" />
                                <x-input-error :messages="$errors->get('dr_ramesh_count')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Save') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('date');
            const viewButton = document.getElementById('viewButton');
            const lastUpdateInfo = document.getElementById('lastUpdateInfo');
            const lastUpdateUser = document.getElementById('lastUpdateUser');
            const lastUpdateTime = document.getElementById('lastUpdateTime');

            function checkDate() {
                const selectedDate = dateInput.value;
                if (!selectedDate) return;

                // Fetch CTG record for the selected date
                fetch(`/OPDinsight/public/annual/ctg/check-date/${selectedDate}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            lastUpdateInfo.style.display = 'block';
                            lastUpdateUser.textContent = data.user_name;
                            lastUpdateTime.textContent = new Date(data.updated_at).toLocaleString('en-GB', {
                                day: '2-digit',
                                month: 'short',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: true
                            });
                        } else {
                            lastUpdateInfo.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        lastUpdateInfo.style.display = 'none';
                    });
            }

            // Check date when view button is clicked
            viewButton.addEventListener('click', checkDate);

            // Check date when date input changes
            dateInput.addEventListener('change', checkDate);

            // Check date on page load if date is pre-selected
            if (dateInput.value) {
                checkDate();
            }
        });
    </script>
    @endpush
</x-app-layout>
