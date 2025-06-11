<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('CTG Records') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <form method="GET" action="{{ route('annual.ctg.index') }}" class="flex items-center space-x-4">
                                <x-input-label for="date" value="Select Date" />
                                <x-text-input id="date" name="date" type="date" class="mt-1"
                                    :value="old('date', request('date', now()->format('Y-m-d')))" />
                                <x-primary-button>
                                    {{ __('View') }}
                                </x-primary-button>
                            </form>

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
                    </div>

                    @if($record)
                        <div class="mb-6 p-4 bg-[#f0e130] rounded-lg">
                            <p class="text-sm text-gray-600">
                                Last updated by: {{ $record->user_name }} on {{ $record->updated_at->format('d M Y, h:i A') }}
                            </p>
                            <p class="text-sm text-red-600 mt-2">
                                Note: To modify these records, please use the edit button in the Daily Records page.
                            </p>
                        </div>
                    @elseif(request('date'))
                        <div class="mb-6 p-4 bg-blue-100 rounded-lg">
                            <p class="text-sm text-gray-600">
                                No CTG records found for {{ Carbon\Carbon::parse(request('date'))->format('d M Y') }}. You can add new records below.
                            </p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('annual.ctg.store') }}" class="space-y-6">
                        @csrf
                        <input type="hidden" name="date" value="{{ request('date', now()->format('Y-m-d')) }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="dr_geetha_count" :value="__('Dr. Geetha CTG Count')" />
                                <x-text-input id="dr_geetha_count" name="dr_geetha_count" type="number" class="mt-1 block w-full"
                                    :value="old('dr_geetha_count', $record?->dr_geetha_count)"
                                    :disabled="$record ? true : false"
                                    required min="0" />
                                <x-input-error :messages="$errors->get('dr_geetha_count')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="dr_joseph_count" :value="__('Dr. Joseph CTG Count')" />
                                <x-text-input id="dr_joseph_count" name="dr_joseph_count" type="number" class="mt-1 block w-full"
                                    :value="old('dr_joseph_count', $record?->dr_joseph_count)"
                                    :disabled="$record ? true : false"
                                    required min="0" />
                                <x-input-error :messages="$errors->get('dr_joseph_count')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="dr_sutha_count" :value="__('Dr. Sutha CTG Count')" />
                                <x-text-input id="dr_sutha_count" name="dr_sutha_count" type="number" class="mt-1 block w-full"
                                    :value="old('dr_sutha_count', $record?->dr_sutha_count)"
                                    :disabled="$record ? true : false"
                                    required min="0" />
                                <x-input-error :messages="$errors->get('dr_sutha_count')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="dr_ramesh_count" :value="__('Dr. Ramesh CTG Count')" />
                                <x-text-input id="dr_ramesh_count" name="dr_ramesh_count" type="number" class="mt-1 block w-full"
                                    :value="old('dr_ramesh_count', $record?->dr_ramesh_count)"
                                    :disabled="$record ? true : false"
                                    required min="0" />
                                <x-input-error :messages="$errors->get('dr_ramesh_count')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button :disabled="$record ? true : false">
                                {{ __('Save CTG') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
