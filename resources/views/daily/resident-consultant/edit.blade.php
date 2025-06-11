<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Resident Consultant Log') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('daily.resident-consultant.update', $entry) }}" class="space-y-6" x-data="{
                        maleCount: {{ old('male_count', optional($entry->patients->first())->male_count ?? 0) }},
                        femaleCount: {{ old('female_count', optional($entry->patients->first())->female_count ?? 0) }},
                        newMaleCount: {{ old('new_male_count', optional($entry->patients->first())->new_male_count ?? 0) }},
                        newFemaleCount: {{ old('new_female_count', optional($entry->patients->first())->new_female_count ?? 0) }},
                        foreignerMaleCount: {{ old('foreigner_male_count', $entry->foreignerPatients->where('gender', 'Male')->count()) }},
                        foreignerFemaleCount: {{ old('foreigner_female_count', $entry->foreignerPatients->where('gender', 'Female')->count()) }},
                        get totalPatients() {
                            const regularTotal = (parseInt(this.maleCount) || 0) + (parseInt(this.femaleCount) || 0);
                            const newTotal = (parseInt(this.newMaleCount) || 0) + (parseInt(this.newFemaleCount) || 0);
                            const foreignerTotal = (parseInt(this.foreignerMaleCount) || 0) + (parseInt(this.foreignerFemaleCount) || 0);
                            return regularTotal + newTotal + foreignerTotal;
                        }
                    }">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="consultant_name" :value="__('Consultant Name')" />
                                <x-text-input id="consultant_name" name="consultant_name" type="text" class="mt-1 block w-full" :value="old('consultant_name', $entry->consultant_name)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('consultant_name')" />
                            </div>

                            <div>
                                <x-input-label for="no_suite" :value="__('Suite Number')" />
                                <x-text-input id="no_suite" name="no_suite" type="text" class="mt-1 block w-full" :value="old('no_suite', $entry->no_suite)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('no_suite')" />
                            </div>

                            <div>
                                <x-input-label for="date" :value="__('Date')" />
                                <x-text-input id="date" name="date" type="date" class="mt-1 block w-full" :value="old('date', \Carbon\Carbon::parse($entry->date)->format('Y-m-d'))" required />
                                <x-input-error class="mt-2" :messages="$errors->get('date')" />
                            </div>

                            <div>
                                <x-input-label for="total_patients_count" :value="__('Total Patients')" />
                                <x-text-input id="total_patients_count" name="total_patients_count" type="number" class="mt-1 block w-full bg-gray-100" x-model="totalPatients" readonly />
                                <div class="mt-2 text-sm text-gray-600">
                                    <div>Regular patients: <span x-text="maleCount"></span> male + <span x-text="femaleCount"></span> female = <span x-text="(parseInt(maleCount) || 0) + (parseInt(femaleCount) || 0)"></span></div>
                                    <div>New patients: <span x-text="newMaleCount"></span> male + <span x-text="newFemaleCount"></span> female = <span x-text="(parseInt(newMaleCount) || 0) + (parseInt(newFemaleCount) || 0)"></span></div>
                                    <div>Foreigner patients: <span x-text="foreignerMaleCount"></span> male + <span x-text="foreignerFemaleCount"></span> female = <span x-text="(parseInt(foreignerMaleCount) || 0) + (parseInt(foreignerFemaleCount) || 0)"></span></div>
                                    <div class="font-semibold mt-1">Total: <span x-text="totalPatients"></span> patients</div>
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('total_patients_count')" />
                            </div>

                            <div>
                                <x-input-label for="time_in" :value="__('Doctor\'s Time In')" />
                                <x-text-input id="time_in" name="time_in" type="time" class="mt-1 block w-full" :value="old('time_in', $entry->time_in ? \Carbon\Carbon::parse($entry->time_in)->format('H:i') : '')" />
                                <x-input-error class="mt-2" :messages="$errors->get('time_in')" />
                            </div>

                            <div>
                                <x-input-label for="time_out" :value="__('Doctor\'s Time Out')" />
                                <x-text-input id="time_out" name="time_out" type="time" class="mt-1 block w-full" :value="old('time_out', $entry->time_out ? \Carbon\Carbon::parse($entry->time_out)->format('H:i') : '')" />
                                <x-input-error class="mt-2" :messages="$errors->get('time_out')" />
                            </div>
                        </div>

                        <!-- Patient Statistics -->
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Patient Statistics') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Gender Statistics -->
                                <div class="bg-white p-4 rounded-lg border border-gray-200">
                                    <h5 class="font-medium text-gray-700 mb-4">{{ __('Gender Distribution') }}</h5>

                                    <!-- Total Gender -->
                                    <div class="mb-4">
                                        <h6 class="text-sm font-medium text-gray-600 mb-2">{{ __('Total Patients') }}</h6>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <x-input-label for="male_count" :value="__('Male')" />
                                                <x-text-input id="male_count" name="male_count" type="number" class="mt-1 block w-full" x-model="maleCount" min="0" required />
                                                <x-input-error class="mt-2" :messages="$errors->get('male_count')" />
                                            </div>
                                            <div>
                                                <x-input-label for="female_count" :value="__('Female')" />
                                                <x-text-input id="female_count" name="female_count" type="number" class="mt-1 block w-full" x-model="femaleCount" min="0" required />
                                                <x-input-error class="mt-2" :messages="$errors->get('female_count')" />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- New Patients -->
                                    <div class="pt-4 border-t border-gray-200">
                                        <h6 class="text-sm font-medium text-gray-600 mb-2">{{ __('New Patients') }}</h6>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <x-input-label for="new_male_count" :value="__('New Male')" />
                                                <x-text-input id="new_male_count" name="new_male_count" type="number" class="mt-1 block w-full" x-model="newMaleCount" min="0" required />
                                                <x-input-error class="mt-2" :messages="$errors->get('new_male_count')" />
                                            </div>
                                            <div>
                                                <x-input-label for="new_female_count" :value="__('New Female')" />
                                                <x-text-input id="new_female_count" name="new_female_count" type="number" class="mt-1 block w-full" x-model="newFemaleCount" min="0" required />
                                                <x-input-error class="mt-2" :messages="$errors->get('new_female_count')" />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Foreigner Patients -->
                                    <div class="pt-4 border-t border-gray-200">
                                        <h6 class="text-sm font-medium text-gray-600 mb-2">{{ __('Foreigner Patients') }}</h6>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <x-input-label for="foreigner_male_count" :value="__('Foreigner Male')" />
                                                <x-text-input id="foreigner_male_count" name="foreigner_male_count" type="number" class="mt-1 block w-full" x-model="foreignerMaleCount" min="0" required />
                                                <x-input-error class="mt-2" :messages="$errors->get('foreigner_male_count')" />
                                            </div>
                                            <div>
                                                <x-input-label for="foreigner_female_count" :value="__('Foreigner Female')" />
                                                <x-text-input id="foreigner_female_count" name="foreigner_female_count" type="number" class="mt-1 block w-full" x-model="foreignerFemaleCount" min="0" required />
                                                <x-input-error class="mt-2" :messages="$errors->get('foreigner_female_count')" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Race Statistics -->
                                <div class="bg-white p-4 rounded-lg border border-gray-200">
                                    <h5 class="font-medium text-gray-700 mb-4">{{ __('Race Distribution') }}</h5>
                                    <div class="grid grid-cols-1 gap-4">
                                        <div>
                                            <x-input-label for="chinese_count" :value="__('Chinese')" />
                                            <x-text-input id="chinese_count" name="chinese_count" type="number" class="mt-1 block w-full" :value="old('chinese_count', optional($entry->patients->first())->chinese_count ?? 0)" min="0" required />
                                            <x-input-error class="mt-2" :messages="$errors->get('chinese_count')" />
                                        </div>

                                        <div>
                                            <x-input-label for="malay_count" :value="__('Malay')" />
                                            <x-text-input id="malay_count" name="malay_count" type="number" class="mt-1 block w-full" :value="old('malay_count', optional($entry->patients->first())->malay_count ?? 0)" min="0" required />
                                            <x-input-error class="mt-2" :messages="$errors->get('malay_count')" />
                                        </div>

                                        <div>
                                            <x-input-label for="india_count" :value="__('Indian')" />
                                            <x-text-input id="india_count" name="india_count" type="number" class="mt-1 block w-full" :value="old('india_count', optional($entry->patients->first())->india_count ?? 0)" min="0" required />
                                            <x-input-error class="mt-2" :messages="$errors->get('india_count')" />
                                        </div>

                                        <div>
                                            <x-input-label for="kdms_count" :value="__('KDMS')" />
                                            <x-text-input id="kdms_count" name="kdms_count" type="number" class="mt-1 block w-full" :value="old('kdms_count', optional($entry->patients->first())->kdms_count ?? 0)" min="0" required />
                                            <x-input-error class="mt-2" :messages="$errors->get('kdms_count')" />
                                        </div>

                                        <div>
                                            <x-input-label for="others_count" :value="__('Others')" />
                                            <x-text-input id="others_count" name="others_count" type="number" class="mt-1 block w-full" :value="old('others_count', optional($entry->patients->first())->others_count ?? 0)" min="0" required />
                                            <x-input-error class="mt-2" :messages="$errors->get('others_count')" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Foreigner Patients -->
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Foreigner Patients') }}</h3>
                            <div id="foreigner-container">
                                @foreach($entry->foreignerPatients as $index => $foreigner)
                                    <div class="foreigner-entry grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                        <div>
                                            <x-input-label :for="'foreigner_countries['.$index.']'" :value="__('Country')" />
                                            <x-text-input :id="'foreigner_countries['.$index.']'" :name="'foreigner_countries['.$index.']'" type="text" class="mt-1 block w-full" :value="old('foreigner_countries.'.$index, $foreigner->country)" />
                                        </div>
                                        <div>
                                            <x-input-label :for="'foreigner_genders['.$index.']'" :value="__('Gender')" />
                                            <select :id="'foreigner_genders['.$index.']'" :name="'foreigner_genders['.$index.']'" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                                <option value="Male" {{ old('foreigner_genders.'.$index, $foreigner->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                                <option value="Female" {{ old('foreigner_genders.'.$index, $foreigner->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" onclick="addForeignerEntry()" class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Add Foreigner
                            </button>
                        </div>

                        <!-- Additional Information -->
                        <div class="mt-6">
                            <div>
                                <x-input-label for="ref_details" :value="__('Reference Details')" />
                                <textarea id="ref_details" name="ref_details" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('ref_details', $entry->ref_details) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('ref_details')" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="remarks" :value="__('Remarks')" />
                                <textarea id="remarks" name="remarks" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('remarks', $entry->remarks) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('remarks')" />
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-4">
                            <a href="{{ route('daily.resident-consultant.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Update Log') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function addForeignerEntry() {
            const container = document.getElementById('foreigner-container');
            const index = container.children.length;

            const entry = document.createElement('div');
            entry.className = 'foreigner-entry grid grid-cols-1 md:grid-cols-2 gap-6 mb-4';
            entry.innerHTML = `
                <div>
                    <label class="block font-medium text-sm text-gray-700">Country</label>
                    <input type="text" name="foreigner_countries[${index}]" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block font-medium text-sm text-gray-700">Gender</label>
                    <select name="foreigner_genders[${index}]" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
            `;

            container.appendChild(entry);
        }
    </script>
    @endpush
</x-app-layout>
