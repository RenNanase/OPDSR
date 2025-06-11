<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Old Wing Medical Procedures') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="mb-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <form method="GET" action="{{ route('annual.old-wing.index') }}" class="flex items-center space-x-4">
                            <x-input-label for="date" value="Select Date" />
                            <x-text-input id="date" name="date" type="date" class="mt-1"
                                :value="old('date', $date)" />
                            <x-primary-button>
                                {{ __('View') }}
                            </x-primary-button>
                        </form>
                                               <!-- Action Buttons -->
                                               <div class="flex space-x-4">
                                                <a href="{{  route('annual.old-wing.daily') }}"
                                                   class="inline-flex items-center px-4 py-2 bg-[#87cefa] border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-[#ffd3dd] focus:bg-[#ffd3dd] active:bg-[#ffd3dd] focus:outline-none focus:ring-2 focus:ring-[#87cefa] focus:ring-offset-2 transition ease-in-out duration-150">
                                                    Daily Records
                                                </a>
                                                <a href="{{  route('annual.old-wing.monthly') }}"
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
                        </div>
                    @endif

                    @if($record)
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            <p class="text-sm">This date already has records. Please use the edit button on the Daily Records page to modify the data.</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('annual.old-wing.store') }}" class="space-y-6">
                        @csrf
                        <input type="hidden" name="date" value="{{ request('date', $date) }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="injection_vaccine" value="INJECTION VACCINE" />
                                <x-text-input id="injection_vaccine" name="injection_vaccine" type="number" class="mt-1 block w-full"
                                    :value="old('injection_vaccine', $record?->injection_vaccine ?? 0)" required min="0" :disabled="$record ? true : false" />
                                <x-input-error :messages="$errors->get('injection_vaccine')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="iv_medication" value="IV MEDICATION" />
                                <x-text-input id="iv_medication" name="iv_medication" type="number" class="mt-1 block w-full"
                                    :value="old('iv_medication', $record?->iv_medication ?? 0)" required min="0" :disabled="$record ? true : false" />
                                <x-input-error :messages="$errors->get('iv_medication')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="urea_blood_test" value="UREA BLOOD TEST (UBT)" />
                                <x-text-input id="urea_blood_test" name="urea_blood_test" type="number" class="mt-1 block w-full"
                                    :value="old('urea_blood_test', $record?->urea_blood_test ?? 0)" required min="0" :disabled="$record ? true : false" />
                                <x-input-error :messages="$errors->get('urea_blood_test')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="venepuncture" value="VENEPUNCTURE" />
                                <x-text-input id="venepuncture" name="venepuncture" type="number" class="mt-1 block w-full"
                                    :value="old('venepuncture', $record?->venepuncture ?? 0)" required min="0" :disabled="$record ? true : false" />
                                <x-input-error :messages="$errors->get('venepuncture')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="iv_cannulation" value="IV CANNULATION" />
                                <x-text-input id="iv_cannulation" name="iv_cannulation" type="number" class="mt-1 block w-full"
                                    :value="old('iv_cannulation', $record?->iv_cannulation ?? 0)" required min="0" :disabled="$record ? true : false" />
                                <x-input-error :messages="$errors->get('iv_cannulation')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="swab_cs_nose_oral" value="SWAB C&S / NOSE / ORAL" />
                                <x-text-input id="swab_cs_nose_oral" name="swab_cs_nose_oral" type="number" class="mt-1 block w-full"
                                    :value="old('swab_cs_nose_oral', $record?->swab_cs_nose_oral ?? 0)" required min="0" :disabled="$record ? true : false" />
                                <x-input-error :messages="$errors->get('swab_cs_nose_oral')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="dressing" value="DRESSING" />
                                <x-text-input id="dressing" name="dressing" type="number" class="mt-1 block w-full"
                                    :value="old('dressing', $record?->dressing ?? 0)" required min="0" :disabled="$record ? true : false" />
                                <x-input-error :messages="$errors->get('dressing')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="ecg_12_led" value="12 LED ECG" />
                                <x-text-input id="ecg_12_led" name="ecg_12_led" type="number" class="mt-1 block w-full"
                                    :value="old('ecg_12_led', $record?->ecg_12_led ?? 0)" required min="0" :disabled="$record ? true : false" />
                                <x-input-error :messages="$errors->get('ecg_12_led')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="urinary_catheterization" value="URINARY CATHETERIZATION" />
                                <x-text-input id="urinary_catheterization" name="urinary_catheterization" type="number" class="mt-1 block w-full"
                                    :value="old('urinary_catheterization', $record?->urinary_catheterization ?? 0)" required min="0" :disabled="$record ? true : false" />
                                <x-input-error :messages="$errors->get('urinary_catheterization')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="ng_tube_insertion" value="NG TUBE INSERTION" />
                                <x-text-input id="ng_tube_insertion" name="ng_tube_insertion" type="number" class="mt-1 block w-full"
                                    :value="old('ng_tube_insertion', $record?->ng_tube_insertion ?? 0)" required min="0" :disabled="$record ? true : false" />
                                <x-input-error :messages="$errors->get('ng_tube_insertion')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="nebulization" value="NEBULIZATION" />
                                <x-text-input id="nebulization" name="nebulization" type="number" class="mt-1 block w-full"
                                    :value="old('nebulization', $record?->nebulization ?? 0)" required min="0" :disabled="$record ? true : false" />
                                <x-input-error :messages="$errors->get('nebulization')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button :disabled="$record ? true : false">
                                {{ __('Save') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
