<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New Resident Consultant Log Entry') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 font-medium text-sm text-red-600">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex justify-end mb-6">
                        <a href="{{ route('daily.resident-consultant.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-[#87cefa] border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-[#ffd3dd] focus:bg-[#ffd3dd] active:bg-[#ffd3dd] focus:outline-none focus:ring-2 focus:ring-[#87cefa] focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Back to List') }}
                        </a>
                    </div>

                    <form method="POST" action="{{ route('daily.resident-consultant.store') }}" x-data="{
                        selectedConsultant: '{{ old('consultant_name', '') }}',
                        consultants: {{ json_encode($consultantsWithStatus) }},
                        foreignerCount: {{ old('foreigner_countries') ? count(old('foreigner_countries')) : 0 }},
                        maleCount: {{ old('male_count', 0) }},
                        femaleCount: {{ old('female_count', 0) }},
                        newMaleCount: {{ old('new_male_count', 0) }},
                        newFemaleCount: {{ old('new_female_count', 0) }},
                        foreignerMaleCount: {{ old('foreigner_male_count', 0) }},
                        foreignerFemaleCount: {{ old('foreigner_female_count', 0) }},
                        get totalPatients() {
                            const regularTotal = (parseInt(this.maleCount) || 0) + (parseInt(this.femaleCount) || 0);
                            const newTotal = (parseInt(this.newMaleCount) || 0) + (parseInt(this.newFemaleCount) || 0);
                            const foreignerTotal = (parseInt(this.foreignerMaleCount) || 0) + (parseInt(this.foreignerFemaleCount) || 0);
                            return regularTotal + newTotal + foreignerTotal;
                        },
                        init() {
                            if (this.selectedConsultant) {
                                const consultant = this.consultants.find(c => c.name === this.selectedConsultant);
                                if (consultant) {
                                    document.getElementById('no_suite').value = consultant.suite_number || '';
                                }
                            }
                        },
                        updateSuite() {
                            const consultant = this.consultants.find(c => c.name === this.selectedConsultant);
                            if (consultant) {
                                document.getElementById('no_suite').value = consultant.suite_number || '';
                            }
                        },
                        addForeigner() {
                            this.foreignerCount++;
                        },
                        removeForeigner(index) {
                            if (this.foreignerCount > 0) {
                                this.foreignerCount--;
                            }
                        }
                    }">
                        @csrf

                        <div>
                            <x-input-label for="date" :value="__('Date')" />
                            <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" :value="old('date', $selectedDate)" required @change="window.location.href = '{{ route('daily.resident-consultant.create') }}?date=' + $event.target.value" />
                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="consultant_name" :value="__('Consultant Name')" />
                            <select id="consultant_name" name="consultant_name" x-model="selectedConsultant" @change="updateSuite()" class="border-gray-300 focus:border-opd-primary focus:ring-opd-primary rounded-md shadow-sm block mt-1 w-full">
                                <option value="">{{ __('Select Consultant') }}</option>
                                @foreach ($consultantsWithStatus as $consultant)
                                    <option value="{{ $consultant['name'] }}"
                                        {{ old('consultant_name') == $consultant['name'] ? 'selected' : '' }}
                                        {{ $consultant['is_logged'] ? 'disabled' : '' }}
                                        style="{{ $consultant['is_logged'] ? 'background-color: #e5e7eb; color: #6b7280; font-style: italic;' : '' }}">
                                        {{ $consultant['name'] }}
                                        @if($consultant['is_logged'])
                                            (Already logged for {{ $selectedDate }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <div class="mt-2 text-sm text-gray-600">
                                <span class="text-red-500">*</span> Consultants marked as "Already logged" cannot be selected for this date
                            </div>
                            <x-input-error :messages="$errors->get('consultant_name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="no_suite" :value="__('No. Suite')" />
                            <x-text-input id="no_suite" class="block mt-1 w-full bg-gray-100" type="text" name="no_suite" :value="old('no_suite')" readonly />
                            <x-input-error :messages="$errors->get('no_suite')" class="mt-2" />
                        </div>

                        <div class="mt-6 border-t border-gray-200 pt-6">
                            <h4 class="text-md font-semibold mb-3">{{ __('Patient Statistics') }}</h4>

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
                                                <x-text-input id="male_count" class="block mt-1 w-full" type="number" name="male_count" x-model="maleCount" min="0" />
                                                <x-input-error :messages="$errors->get('male_count')" class="mt-2" />
                                            </div>
                                            <div>
                                                <x-input-label for="female_count" :value="__('Female')" />
                                                <x-text-input id="female_count" class="block mt-1 w-full" type="number" name="female_count" x-model="femaleCount" min="0" />
                                                <x-input-error :messages="$errors->get('female_count')" class="mt-2" />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Foreigner Patients -->
                                    <div class="mb-4">
                                        <h6 class="text-sm font-medium text-gray-600 mb-2">{{ __('Foreigner Patients') }}</h6>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <x-input-label for="foreigner_male_count" :value="__('Foreigner Male')" />
                                                <x-text-input id="foreigner_male_count" class="block mt-1 w-full" type="number" name="foreigner_male_count" x-model="foreignerMaleCount" min="0" />
                                                <x-input-error :messages="$errors->get('foreigner_male_count')" class="mt-2" />
                                            </div>
                                            <div>
                                                <x-input-label for="foreigner_female_count" :value="__('Foreigner Female')" />
                                                <x-text-input id="foreigner_female_count" class="block mt-1 w-full" type="number" name="foreigner_female_count" x-model="foreignerFemaleCount" min="0" />
                                                <x-input-error :messages="$errors->get('foreigner_female_count')" class="mt-2" />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- New Patients -->
                                    <div class="pt-4 border-t border-gray-200">
                                        <h6 class="text-sm font-medium text-gray-600 mb-2">{{ __('New Patients') }}</h6>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <x-input-label for="new_male_count" :value="__('New Male')" />
                                                <x-text-input id="new_male_count" class="block mt-1 w-full" type="number" name="new_male_count" x-model="newMaleCount" min="0" />
                                                <x-input-error :messages="$errors->get('new_male_count')" class="mt-2" />
                                            </div>
                                            <div>
                                                <x-input-label for="new_female_count" :value="__('New Female')" />
                                                <x-text-input id="new_female_count" class="block mt-1 w-full" type="number" name="new_female_count" x-model="newFemaleCount" min="0" />
                                                <x-input-error :messages="$errors->get('new_female_count')" class="mt-2" />
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
                                            <x-text-input id="chinese_count" class="block mt-1 w-full" type="number" name="chinese_count" :value="old('chinese_count', 0)" min="0" />
                                            <x-input-error :messages="$errors->get('chinese_count')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="malay_count" :value="__('Malay')" />
                                            <x-text-input id="malay_count" class="block mt-1 w-full" type="number" name="malay_count" :value="old('malay_count', 0)" min="0" />
                                            <x-input-error :messages="$errors->get('malay_count')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="india_count" :value="__('India')" />
                                            <x-text-input id="india_count" class="block mt-1 w-full" type="number" name="india_count" :value="old('india_count', 0)" min="0" />
                                            <x-input-error :messages="$errors->get('india_count')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="kdms_count" :value="__('Kadazan/Dusun/Murut/Sino')" />
                                            <x-text-input id="kdms_count" class="block mt-1 w-full" type="number" name="kdms_count" :value="old('kdms_count', 0)" min="0" />
                                            <x-input-error :messages="$errors->get('kdms_count')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="others_count" :value="__('Others')" />
                                            <x-text-input id="others_count" class="block mt-1 w-full" type="number" name="others_count" :value="old('others_count', 0)" min="0" />
                                            <x-input-error :messages="$errors->get('others_count')" class="mt-2" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 border-t border-gray-200 pt-6">
                            <h4 class="text-md font-semibold mb-3">{{ __('Foreigner Patients') }}</h4>
                            <div x-data="{
                                foreignerCount: {{ old('foreigner_countries') ? count(old('foreigner_countries')) : 0 }},
                                oldForeigners: {{ json_encode(old('foreigner_countries', [])) }},
                                oldGenders: {{ json_encode(old('foreigner_genders', [])) }},
                                addForeigner() {
                                    this.foreignerCount++;
                                },
                                removeForeigner(index) {
                                    if (this.foreignerCount > 0) {
                                        this.foreignerCount--;
                                    }
                                }
                            }">
                                <template x-for="(_, idx) in Array.from({length: foreignerCount})" :key="idx">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 p-4 border border-gray-200 rounded-md bg-gray-50 relative">
                                        <div class="absolute top-2 right-2">
                                            <button type="button" @click="removeForeigner(idx)" class="text-red-500 hover:text-red-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                        <div>
                                            <label :for="'foreigner_countries_' + idx" class="block font-medium text-sm text-gray-700">
                                                {{ __('Foreigner Country') }}
                                            </label>
                                            <input :id="'foreigner_countries_' + idx"
                                                   :name="'foreigner_countries[' + idx + ']'"
                                                   type="text"
                                                   class="border-gray-300 focus:border-opd-primary focus:ring-opd-primary rounded-md shadow-sm block mt-1 w-full"
                                                   :value="oldForeigners[idx] || ''">
                                            <div x-show="$errors.has('foreigner_countries.' + idx)" class="text-red-500 text-sm mt-1">
                                                <span x-text="$errors.get('foreigner_countries.' + idx)"></span>
                                            </div>
                                        </div>
                                        <div>
                                            <label :for="'foreigner_genders_' + idx" class="block font-medium text-sm text-gray-700">
                                                {{ __('Foreigner Gender') }}
                                            </label>
                                            <select :id="'foreigner_genders_' + idx"
                                                    :name="'foreigner_genders[' + idx + ']'"
                                                    class="border-gray-300 focus:border-opd-primary focus:ring-opd-primary rounded-md shadow-sm block mt-1 w-full">
                                                <option value="">{{ __('Select Gender') }}</option>
                                                <option value="Male" :selected="oldGenders[idx] === 'Male'">{{ __('Male') }}</option>
                                                <option value="Female" :selected="oldGenders[idx] === 'Female'">{{ __('Female') }}</option>
                                            </select>
                                            <div x-show="$errors.has('foreigner_genders.' + idx)" class="text-red-500 text-sm mt-1">
                                                <span x-text="$errors.get('foreigner_genders.' + idx)"></span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <x-secondary-button type="button" @click="addForeigner()">
                                    {{ __('Add Foreigner Patient') }}
                                </x-secondary-button>
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="time_in" :value="__('Doctor\'s Time In')" />
                            <x-text-input id="time_in" class="block mt-1 w-full" type="time" name="time_in" :value="old('time_in')" />
                            <x-input-error :messages="$errors->get('time_in')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="time_out" :value="__('Doctor\'s Time Out')" />
                            <x-text-input id="time_out" class="block mt-1 w-full" type="time" name="time_out" :value="old('time_out')" />
                            <x-input-error :messages="$errors->get('time_out')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="ref_details" :value="__('Referral Details')" />
                            <textarea id="ref_details" name="ref_details" class="border-gray-300 focus:border-opd-primary focus:ring-opd-primary rounded-md shadow-sm block mt-1 w-full" rows="3">{{ old('ref_details') }}</textarea>
                            <x-input-error :messages="$errors->get('ref_details')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="remarks" :value="__('Remarks')" />
                            <textarea id="remarks" name="remarks" class="border-gray-300 focus:border-opd-primary focus:ring-opd-primary rounded-md shadow-sm block mt-1 w-full" rows="3">{{ old('remarks') }}</textarea>
                            <x-input-error :messages="$errors->get('remarks')" class="mt-2" />
                        </div>

                        <div class="mt-6 border-t border-gray-200 pt-6">
                            <h4 class="text-md font-semibold mb-3">{{ __('Summary') }}</h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="mt-4">
                                    <x-input-label for="total_patients_count" :value="__('Total Patients Seen')" />
                                    <x-text-input id="total_patients_count" class="block mt-1 w-full bg-gray-100" type="number" name="total_patients_count" x-model="totalPatients" readonly />
                                    <div class="mt-2 text-sm text-gray-600">
                                        <div>Regular patients: <span x-text="maleCount"></span> male + <span x-text="femaleCount"></span> female = <span x-text="(parseInt(maleCount) || 0) + (parseInt(femaleCount) || 0)"></span></div>
                                        <div>New patients: <span x-text="newMaleCount"></span> male + <span x-text="newFemaleCount"></span> female = <span x-text="(parseInt(newMaleCount) || 0) + (parseInt(newFemaleCount) || 0)"></span></div>
                                        <div>Foreigner patients: <span x-text="foreignerMaleCount"></span> male + <span x-text="foreignerFemaleCount"></span> female = <span x-text="(parseInt(foreignerMaleCount) || 0) + (parseInt(foreignerFemaleCount) || 0)"></span></div>
                                        <div class="font-semibold mt-1">Total: <span x-text="totalPatients"></span> patients</div>
                                    </div>
                                    <x-input-error :messages="$errors->get('total_patients_count')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Save Log Entry') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        select option:disabled {
            background-color: #e5e7eb !important;
            color: #6b7280 !important;
            font-style: italic;
        }
        select option:not(:disabled) {
            background-color: white;
            color: black;
        }
    </style>
</x-app-layout>
