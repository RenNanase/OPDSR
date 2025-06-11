<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit CTG Record') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('annual.ctg.update', $record) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div>
                                <x-input-label for="dr_geetha_count" :value="__('Dr. Geetha Count')" />
                                <x-text-input id="dr_geetha_count" name="dr_geetha_count" type="number" class="mt-1 block w-full"
                                    :value="old('dr_geetha_count', $record->dr_geetha_count)" required min="0" />
                                <x-input-error class="mt-2" :messages="$errors->get('dr_geetha_count')" />
                            </div>

                            <div>
                                <x-input-label for="dr_joseph_count" :value="__('Dr. Joseph Count')" />
                                <x-text-input id="dr_joseph_count" name="dr_joseph_count" type="number" class="mt-1 block w-full"
                                    :value="old('dr_joseph_count', $record->dr_joseph_count)" required min="0" />
                                <x-input-error class="mt-2" :messages="$errors->get('dr_joseph_count')" />
                            </div>

                            <div>
                                <x-input-label for="dr_sutha_count" :value="__('Dr. Sutha Count')" />
                                <x-text-input id="dr_sutha_count" name="dr_sutha_count" type="number" class="mt-1 block w-full"
                                    :value="old('dr_sutha_count', $record->dr_sutha_count)" required min="0" />
                                <x-input-error class="mt-2" :messages="$errors->get('dr_sutha_count')" />
                            </div>

                            <div>
                                <x-input-label for="dr_ramesh_count" :value="__('Dr. Ramesh Count')" />
                                <x-text-input id="dr_ramesh_count" name="dr_ramesh_count" type="number" class="mt-1 block w-full"
                                    :value="old('dr_ramesh_count', $record->dr_ramesh_count)" required min="0" />
                                <x-input-error class="mt-2" :messages="$errors->get('dr_ramesh_count')" />
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Update') }}</x-primary-button>
                            <a href="{{ route('annual.ctg.daily', ['date' => $record->date]) }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
