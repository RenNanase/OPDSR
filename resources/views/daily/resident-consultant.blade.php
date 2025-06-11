<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Resident Consultant Daily Log') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">{{ __('New Log Entry') }}</h3>

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

                    <form method="POST" action="{{ route('daily.resident-consultant.store') }}" x-data="{
                        selectedConsultant: '{{ old('consultant_name', '') }}',
                        consultants: {{ json_encode($consultants->pluck('suite_number', 'name')) }},
                        foreignerCount: {{ old('foreigner_countries') ? count(old('foreigner_countries')) : 0 }},
                        maleCount: {{ old('male_count', 0) }},
                        femaleCount: {{ old('female_count', 0) }},
                        get totalPatients() {
                            return parseInt(this.maleCount) + parseInt(this.femaleCount);
                        },
                        init() {
                            if (this.selectedConsultant) {
                                document.getElementById('no_suite').value = this.consultants[this.selectedConsultant] || '';
                            }
                        },
                        updateSuite() {
                            document.getElementById('no_suite').value = this.consultants[this.selectedConsultant] || '';
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
                            <x-text-input id="date" class="block mt-1 w-full bg-gray-100" type="date" name="date" :value="old('date', $today->format('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="consultant_name" :value="__('Consultant Name')" />
                            <select id="consultant_name" name="consultant_name" x-model="selectedConsultant" @change="updateSuite()" class="border-gray-300 focus:border-opd-primary focus:ring-opd-primary rounded-md shadow-sm block mt-1 w-full">
                                <option value="">{{ __('Select Consultant') }}</option>
                                @foreach ($consultants as $consultant)
                                    <option value="{{ $consultant->name }}" {{ old('consultant_name') == $consultant->name ? 'selected' : '' }}>
                                        {{ $consultant->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('consultant_name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="no_suite" :value="__('No. Suite')" />
                            <x-text-input id="no_suite" class="block mt-1 w-full bg-gray-100" type="text" name="no_suite" :value="old('no_suite')" readonly />
                            <x-input-error :messages="$errors->get('no_suite')" class="mt-2" />
                        </div>

                        <div class="mt-6 border-t border-gray-200 pt-6">
                            <h4 class="text-md font-semibold mb-3">{{ __('Patient Statistics') }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="chinese_count" :value="__('Chinese')" />
                                    <x-text-input id="chinese_count" class="block mt-1 w-full" type="number" name="chinese_count" :value="old('chinese_count', 0)" min="0" required />
                                    <x-input-error :messages="$errors->get('chinese_count')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="malay_count" :value="__('Malay')" />
                                    <x-text-input id="malay_count" class="block mt-1 w-full" type="number" name="malay_count" :value="old('malay_count', 0)" min="0" required />
                                    <x-input-error :messages="$errors->get('malay_count')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="india_count" :value="__('Indian')" />
                                    <x-text-input id="india_count" class="block mt-1 w-full" type="number" name="india_count" :value="old('india_count', 0)" min="0" required />
                                    <x-input-error :messages="$errors->get('india_count')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="kdms_count" :value="__('KDMS')" />
                                    <x-text-input id="kdms_count" class="block mt-1 w-full" type="number" name="kdms_count" :value="old('kdms_count', 0)" min="0" required />
                                    <x-input-error :messages="$errors->get('kdms_count')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="others_count" :value="__('Others')" />
                                    <x-text-input id="others_count" class="block mt-1 w-full" type="number" name="others_count" :value="old('others_count', 0)" min="0" required />
                                    <x-input-error :messages="$errors->get('others_count')" class="mt-2" />
                                </div>
                            </div>

                            <div class="mt-6">
                                <h4 class="text-md font-semibold mb-3">{{ __('Gender Statistics') }}</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="male_count" :value="__('Male')" />
                                        <x-text-input id="male_count" class="block mt-1 w-full" type="number" name="male_count" x-model="maleCount" :value="old('male_count', 0)" min="0" required />
                                        <x-input-error :messages="$errors->get('male_count')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="female_count" :value="__('Female')" />
                                        <x-text-input id="female_count" class="block mt-1 w-full" type="number" name="female_count" x-model="femaleCount" :value="old('female_count', 0)" min="0" required />
                                        <x-input-error :messages="$errors->get('female_count')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="new_male_count" :value="__('New Male Patients')" />
                                        <x-text-input id="new_male_count" class="block mt-1 w-full" type="number" name="new_male_count" :value="old('new_male_count', 0)" min="0" required />
                                        <x-input-error :messages="$errors->get('new_male_count')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="new_female_count" :value="__('New Female Patients')" />
                                        <x-text-input id="new_female_count" class="block mt-1 w-full" type="number" name="new_female_count" :value="old('new_female_count', 0)" min="0" required />
                                        <x-input-error :messages="$errors->get('new_female_count')" class="mt-2" />
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

                    {{-- Daily Logged Entries Table --}}
                    <div class="mt-10 border-t border-gray-200 pt-8">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Today\'s Logged Entries (') . $today->format('d M Y') . ')' }}</h3>

                        @if($missingConsultants->isNotEmpty())
                            <div class="mb-4 p-3 bg-yellow-100 border border-yellow-300 text-yellow-800 rounded-md">
                                <p class="font-semibold">{{ __('Missing Entries:') }}</p>
                                <ul class="list-disc list-inside mt-1">
                                    @foreach($missingConsultants as $mConsultant)
                                        <li>{{ $mConsultant->name }} (Suite: {{ $mConsultant->suite_number }})</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if ($loggedEntries->isEmpty())
                            <p class="text-gray-600">{{ __('No entries logged for today yet.') }}</p>
                        @else
                            <div class="overflow-x-auto shadow-sm sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Consultant') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Suite') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Patients') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Foreigners') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Doctor\'s Time In/Out') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Remarks') }}
                                            </th>
                                            <th scope="col" class="relative px-6 py-3">
                                                <span class="sr-only">{{ __('Actions') }}</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($loggedEntries as $entry)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $entry->consultant_name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $entry->no_suite }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $entry->total_patients_count }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    @if($entry->foreignerPatients->isNotEmpty())
                                                        <ul class="list-disc list-inside">
                                                            @foreach($entry->foreignerPatients as $fp)
                                                                <li>{{ $fp->country }} ({{ $fp->gender }})</li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $entry->time_in ? $entry->time_in->format('H:i') : '-' }} /
                                                    {{ $entry->time_out ? $entry->time_out->format('H:i') : '-' }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    {{ Str::limit($entry->remarks, 50) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="#" class="text-opd-primary hover:text-opd-secondary">View</a>
                                                    {{-- Edit/Delete actions can be added here later --}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>


