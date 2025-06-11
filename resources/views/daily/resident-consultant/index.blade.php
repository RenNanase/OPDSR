<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Resident Consultant Daily Logs') }}
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

                    <!-- Date Selection and Action Buttons -->
                    <div class="mb-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <!-- Date Selection -->
                            <form action="{{ route('daily.resident-consultant.index') }}" method="GET" class="flex items-center space-x-4">
                                <div>
                                    <label for="date" class="block text-sm font-medium text-gray-700">Select Date</label>
                                    <input type="date" name="date" id="date" value="{{ request('date', now()->format('Y-m-d')) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div class="mt-6">
                                    <x-monthly-button>
                                        View Records
                                    </x-monthly-button>
                                </div>
                            </form>

                            <!-- Action Buttons -->
                            <div class="flex space-x-4">
                                <a href="{{ route('daily.resident-consultant.monthly') }}"
                                   class="inline-flex items-center px-4 py-2 bg-[#87cefa] border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-[#ffd3dd] focus:bg-[#ffd3dd] active:bg-[#ffd3dd] focus:outline-none focus:ring-2 focus:ring-[#87cefa] focus:ring-offset-2 transition ease-in-out duration-150">
                                    Monthly Records
                                </a>
                                <a href="{{ route('daily.resident-consultant.create') }}"
                                   class="inline-flex items-center px-4 py-2 bg-[#87cefa] border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-[#ffd3dd] focus:bg-[#ffd3dd] active:bg-[#ffd3dd] focus:outline-none focus:ring-2 focus:ring-[#87cefa] focus:ring-offset-2 transition ease-in-out duration-150">
                                    Add New Log
                                </a>
                            </div>
                        </div>
                    </div>



                    <!-- Daily Statistics -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Daily Total Statistics</h3>
                        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Total Patients for {{ \Carbon\Carbon::parse(request('date', now()))->format('d F Y') }}
                                </h3>
                            </div>
                            <div class="border-t border-gray-200">
                                <dl>
                                    <div class="bg-purple-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-purple-700">Total Existing Patients</dt>
                                        <dd class="mt-1 text-sm text-purple-900 sm:mt-0 sm:col-span-2 font-semibold">
                                            {{ ($dailyStats['male_count'] ?? 0) + ($dailyStats['female_count'] ?? 0) }}
                                        </dd>
                                    </div>
                                    <div class="bg-green-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-green-700">Total New Patients</dt>
                                        <dd class="mt-1 text-sm text-green-900 sm:mt-0 sm:col-span-2 font-semibold">
                                            {{ ($dailyStats['new_male_count'] ?? 0) + ($dailyStats['new_female_count'] ?? 0) }}
                                        </dd>
                                    </div>
                                    <div class="bg-blue-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-blue-700">Total Foreigners</dt>
                                        <dd class="mt-1 text-sm text-blue-900 sm:mt-0 sm:col-span-2 font-semibold">
                                            {{ ($dailyStats['foreigner_male_count'] ?? 0) + ($dailyStats['foreigner_female_count'] ?? 0) }}
                                        </dd>
                                    </div>
                                    <div class="bg-pink-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-pink-700">Total All Patients</dt>
                                        <dd class="mt-1 text-sm text-pink-900 sm:mt-0 sm:col-span-2 font-semibold">
                                            {{ $dailyStats['total_patients'] ?? 0 }}
                                        </dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">By Race</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            <div class="space-y-1">
                                                <div>Chinese: {{ $dailyStats['chinese_count'] ?? 0 }}</div>
                                                <div>Malay: {{ $dailyStats['malay_count'] ?? 0 }}</div>
                                                <div>Indian: {{ $dailyStats['india_count'] ?? 0 }}</div>
                                                <div>KDMS: {{ $dailyStats['kdms_count'] ?? 0 }}</div>
                                                <div>Others: {{ $dailyStats['others_count'] ?? 0 }}</div>
                                            </div>
                                        </dd>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">By Gender</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            <div class="space-y-1">
                                                <div>Male: {{ $dailyStats['male_count'] ?? 0 }}</div>
                                                <div>Female: {{ $dailyStats['female_count'] ?? 0 }}</div>
                                                <div class="mt-2 pt-2 border-t border-gray-200">
                                                    <div class="font-medium text-gray-700">New Patients:</div>
                                                    <div>New Male: {{ $dailyStats['new_male_count'] ?? 0 }}</div>
                                                    <div>New Female: {{ $dailyStats['new_female_count'] ?? 0 }}</div>
                                                </div>
                                                <div class="mt-2 pt-2 border-t border-gray-200">
                                                    <div class="font-medium text-gray-700">Foreigner Patients:</div>
                                                    <div>Foreigner Male: {{ $dailyStats['foreigner_male_count'] ?? 0 }}</div>
                                                    <div>Foreigner Female: {{ $dailyStats['foreigner_female_count'] ?? 0 }}</div>
                                                </div>
                                            </div>
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <!-- Missing Consultants Section -->
                    @if($missingConsultants->isNotEmpty())
                        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <h3 class="text-lg font-medium text-yellow-800 mb-2">{{ __('Missing Logs for Today') }}</h3>
                            <p class="text-sm text-yellow-700 mb-3">{{ __('The following consultants have not submitted their logs for today:') }}</p>
                            <ul class="list-disc list-inside text-sm text-yellow-700">
                                @foreach($missingConsultants as $consultant)
                                    <li>{{ $consultant->name }} (Suite {{ $consultant->suite_number }})</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Logs Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Date') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Consultant') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Suite') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Total Patients') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Doctor\'s Time In/Out') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($loggedEntries as $log)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-900">
                                            {{ \Carbon\Carbon::parse($log->date)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-900">
                                            {{ $log->consultant_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-900">
                                            {{ $log->no_suite }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-900">
                                            {{ $log->total_patients_count }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-900">
                                            {{ $log->time_in ? $log->time_in->format('H:i') : '-' }} /
                                            {{ $log->time_out ? $log->time_out->format('H:i') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium">
                                            <a href="{{ route('daily.resident-consultant.show', $log) }}" class="text-[#87cefa] hover:text-[#ffd3dd]">
                                                {{ __('View Details') }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            {{ __('No logs found for today.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
