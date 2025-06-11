<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Resident Consultant Log Details') }}
            </h2>
            <a href="{{ route('daily.resident-consultant.index') }}" class="inline-flex items-center px-4 py-2 bg-[#87cefa] border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-[#ffd3dd] focus:bg-[#ffd3dd] active:bg-[#ffd3dd] focus:outline-none focus:ring-2 focus:ring-[#87cefa] focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Back to List') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Basic Information -->
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                {{ __('Basic Information') }}
                            </h3>
                        </div>
                        <div class="border-t border-gray-200">
                            <dl>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Date') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ \Carbon\Carbon::parse($entry->date)->format('d/m/Y') }}
                                    </dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Consultant Name') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $entry->consultant_name }}
                                    </dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Suite Number') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $entry->no_suite }}
                                    </dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Doctor\'s Time In/Out') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $entry->time_in ? $entry->time_in->format('H:i') : '-' }} /
                                        {{ $entry->time_out ? $entry->time_out->format('H:i') : '-' }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Patient Statistics -->
                    <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                {{ __('Patient Statistics') }}
                            </h3>
                        </div>
                        <div class="border-t border-gray-200">
                            <dl>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Total Patients') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $entry->total_patients_count }}
                                    </dd>
                                </div>
                                @if($entry->patients->isNotEmpty())
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">{{ __('Race Distribution') }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            <ul class="list-disc list-inside">
                                                <li>Malay: {{ $entry->patients->first()->malay_count }}</li>
                                                <li>Chinese: {{ $entry->patients->first()->chinese_count }}</li>
                                                <li>Indian: {{ $entry->patients->first()->india_count }}</li>
                                                <li>Kadazan: {{ $entry->patients->first()->kdms_count }}</li>
                                                <li>Others: {{ $entry->patients->first()->others_count }}</li>
                                            </ul>
                                        </dd>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">{{ __('Gender Distribution') }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            <ul class="list-disc list-inside">
                                                <li>Male: {{ $entry->patients->first()->male_count }}</li>
                                                <li>Female: {{ $entry->patients->first()->female_count }}</li>
                                            </ul>
                                        </dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">{{ __('New Patients Gender Distribution') }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            <ul class="list-disc list-inside">
                                                <li>Male: {{ $entry->patients->first()->new_male_count ?? 0 }}</li>
                                                <li>Female: {{ $entry->patients->first()->new_female_count ?? 0 }}</li>
                                            </ul>
                                        </dd>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">{{ __('Foreigner Gender Distribution') }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            <ul class="list-disc list-inside">
                                                <li>Male: {{ $entry->foreignerPatients->where('gender', 'Male')->count() }}</li>
                                                <li>Female: {{ $entry->foreignerPatients->where('gender', 'Female')->count() }}</li>
                                            </ul>
                                        </dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Foreigner Patients -->
                    @if($entry->foreignerPatients->isNotEmpty())
                        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    {{ __('Foreigner Patients') }}
                                </h3>
                            </div>
                            <div class="border-t border-gray-200">
                                <div class="px-4 py-5 sm:px-6">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Country') }}
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Gender') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($entry->foreignerPatients as $foreigner)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $foreigner->country }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $foreigner->gender }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Additional Information -->
                    <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                {{ __('Additional Information') }}
                            </h3>
                        </div>
                        <div class="border-t border-gray-200">
                            <dl>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Referral Details') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $entry->ref_details ?: '-' }}
                                    </dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Remarks') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $entry->remarks ?: '-' }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-4">
                        <a href="{{ route('daily.resident-consultant.index') }}" class="inline-flex items-center px-4 py-2 bg-[#87cefa] border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-[#ffd3dd] focus:bg-[#ffd3dd] active:bg-[#ffd3dd] focus:outline-none focus:ring-2 focus:ring-[#87cefa] focus:ring-offset-2 transition ease-in-out duration-150">
                            Back to List
                        </a>
                        <a href="{{ route('daily.resident-consultant.edit', $entry) }}" class="inline-flex items-center px-4 py-2 bg-[#87cefa] border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-[#ffd3dd] focus:bg-[#ffd3dd] active:bg-[#ffd3dd] focus:outline-none focus:ring-2 focus:ring-[#87cefa] focus:ring-offset-2 transition ease-in-out duration-150">
                            Edit Record
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
