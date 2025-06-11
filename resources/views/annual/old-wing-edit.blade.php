<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Old Wing Medical Procedures - ') }} {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('annual.old-wing.update', ['date' => $date]) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="injection_vaccine" value="INJECTION VACCINE" />
                                <x-text-input id="injection_vaccine" name="injection_vaccine" type="number" class="mt-1 block w-full"
                                    :value="old('injection_vaccine', $procedures->injection_vaccine)" required min="0" />
                                <x-input-error :messages="$errors->get('injection_vaccine')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="iv_medication" value="IV MEDICATION" />
                                <x-text-input id="iv_medication" name="iv_medication" type="number" class="mt-1 block w-full"
                                    :value="old('iv_medication', $procedures->iv_medication)" required min="0" />
                                <x-input-error :messages="$errors->get('iv_medication')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="urea_blood_test" value="UREA BLOOD TEST (UBT)" />
                                <x-text-input id="urea_blood_test" name="urea_blood_test" type="number" class="mt-1 block w-full"
                                    :value="old('urea_blood_test', $procedures->urea_blood_test)" required min="0" />
                                <x-input-error :messages="$errors->get('urea_blood_test')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="venepuncture" value="VENEPUNCTURE" />
                                <x-text-input id="venepuncture" name="venepuncture" type="number" class="mt-1 block w-full"
                                    :value="old('venepuncture', $procedures->venepuncture)" required min="0" />
                                <x-input-error :messages="$errors->get('venepuncture')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="iv_cannulation" value="IV CANNULATION" />
                                <x-text-input id="iv_cannulation" name="iv_cannulation" type="number" class="mt-1 block w-full"
                                    :value="old('iv_cannulation', $procedures->iv_cannulation)" required min="0" />
                                <x-input-error :messages="$errors->get('iv_cannulation')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="swab_cs_nose_oral" value="SWAB C&S / NOSE / ORAL" />
                                <x-text-input id="swab_cs_nose_oral" name="swab_cs_nose_oral" type="number" class="mt-1 block w-full"
                                    :value="old('swab_cs_nose_oral', $procedures->swab_cs_nose_oral)" required min="0" />
                                <x-input-error :messages="$errors->get('swab_cs_nose_oral')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="dressing" value="DRESSING" />
                                <x-text-input id="dressing" name="dressing" type="number" class="mt-1 block w-full"
                                    :value="old('dressing', $procedures->dressing)" required min="0" />
                                <x-input-error :messages="$errors->get('dressing')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="ecg_12_led" value="12 LED ECG" />
                                <x-text-input id="ecg_12_led" name="ecg_12_led" type="number" class="mt-1 block w-full"
                                    :value="old('ecg_12_led', $procedures->ecg_12_led)" required min="0" />
                                <x-input-error :messages="$errors->get('ecg_12_led')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="urinary_catheterization" value="URINARY CATHETERIZATION" />
                                <x-text-input id="urinary_catheterization" name="urinary_catheterization" type="number" class="mt-1 block w-full"
                                    :value="old('urinary_catheterization', $procedures->urinary_catheterization)" required min="0" />
                                <x-input-error :messages="$errors->get('urinary_catheterization')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="ng_tube_insertion" value="NG TUBE INSERTION" />
                                <x-text-input id="ng_tube_insertion" name="ng_tube_insertion" type="number" class="mt-1 block w-full"
                                    :value="old('ng_tube_insertion', $procedures->ng_tube_insertion)" required min="0" />
                                <x-input-error :messages="$errors->get('ng_tube_insertion')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="nebulization" value="NEBULIZATION" />
                                <x-text-input id="nebulization" name="nebulization" type="number" class="mt-1 block w-full"
                                    :value="old('nebulization', $procedures->nebulization)" required min="0" />
                                <x-input-error :messages="$errors->get('nebulization')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-4">
                            <a href="{{ route('annual.old-wing.daily', ['date' => $date]) }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Update Procedures') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>