<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Visiting Consultant Monthly Logs')}}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Month Selection -->
                    <div class="mb-6">
                     <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <form action="{{ route('daily.visiting-consultant.monthly') }}" method="GET" class="flex items-center space-x-4">
                            <div>
                                <label for="month" class="block text-sm font-medium text-gray-700">Select Month</label>
                                <select name="month" id="month" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="year" class="block text-sm font-medium text-gray-700">Select Year</label>
                                <select name="year" id="year" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach(range(date('Y')-1, date('Y')+1) as $y)
                                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-6">
                                <x-monthly-button>
                                    View Records
                                </x-monthly-button>
                            </div>
                            <div class="mt-6">
                                <a href="{{ route('daily.visiting-consultant.export', ['month' => $selectedMonth, 'year' => $selectedYear]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Export to Excel') }}
                                </a>
                            </div>
                        </form>

                        <!-- Action Buttons -->
                        <div class="flex space-x-4">
                            <a href="{{ route('daily.visiting-consultant.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-[#87cefa] border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-[#ffd3dd] focus:bg-[#ffd3dd] active:bg-[#ffd3dd] focus:outline-none focus:ring-2 focus:ring-[#87cefa] focus:ring-offset-2 transition ease-in-out duration-150">
                               Daily Records
                            </a>

                        </div>
                    </div>
                    </div>

                    <!-- Monthly Records Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Consultant</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Suite</th>
                                    @for($day = 1; $day <= $daysInMonth; $day++)
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $day }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($consultants as $consultant)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $consultant->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $consultant->suite_number }}
                                        </td>
                                        @for($day = 1; $day <= $daysInMonth; $day++)
                                            @php
                                                $log = $monthlyData[$consultant->name][$day] ?? null;
                                            @endphp
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center {{ !$log ? 'bg-red-100' : '' }}">
                                                @if($log)
                                                    {{ $log->total_patients_count ?? 0 }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        @endfor
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Monthly Statistics -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Monthly Total Statistics</h3>
                        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6 bg-pink-100">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Total Patients for {{ date('F Y', mktime(0, 0, 0, $selectedMonth, 1, $selectedYear)) }}
                                </h3>
                            </div>
                            <div class="border-t border-gray-200">
                                <dl>
                                    <!-- Total Patients Section -->
                                    <div class="bg-pink-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-900">Total Patients</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-semibold">{{ $monthlyStats['total_patients'] }}</dd>
                                    </div>

                                    <!-- Existing vs New Patients Section -->
                                    <div class="bg-purple-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-900">Total Existing Patients</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-semibold">{{ $monthlyStats['existing_patients_count'] }}</dd>
                                    </div>
                                    <!-- Foreigners -->
                                    <div class="bg-yellow-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-900">Total Foreigners</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-semibold">{{ $monthlyStats['foreigner_count'] }}</dd>
                                    </div>
                                    <div class="bg-green-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-900">Total New Patients</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-semibold">{{ $monthlyStats['new_patients_count'] }}</dd>
                                    </div>

                                    <!-- Regular Patients by Gender -->
                                    <div style="background-color: #afeeee" class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-900">Regular Patients by Gender</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            <div class="grid grid-cols-2 gap-4">
                                                <div class="flex items-center gap-2">
                                                    <span>Male:</span>
                                                    <span class="font-semibold">{{ $monthlyStats['male_count'] }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span>Female:</span>
                                                    <span class="font-semibold">{{ $monthlyStats['female_count'] }}</span>
                                                </div>
                                            </div>
                                        </dd>
                                    </div>

                                    <!-- New Patients by Gender -->
                                    <div style="background-color: #afeeee" class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-900">New Patients by Gender</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            <div class="grid grid-cols-2 gap-4">
                                                <div class="flex items-center gap-2">
                                                    <span>Male:</span>
                                                    <span class="font-semibold">{{ $monthlyStats['new_male_count_vs'] ?? 0 }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span>Female:</span>
                                                    <span class="font-semibold">{{ $monthlyStats['new_female_count_vs'] ?? 0 }}</span>
                                                </div>
                                            </div>
                                        </dd>
                                    </div>

                                    <!-- Foreigner Patients by Gender -->
                                    <div style="background-color: #afeeee" class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-900">Foreigner Patients by Gender</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            <div class="grid grid-cols-2 gap-4">
                                                <div class="flex items-center gap-2">
                                                    <span>Male:</span>
                                                    <span class="font-semibold">{{ $monthlyStats['foreigner_male_count'] ?? 0 }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span>Female:</span>
                                                    <span class="font-semibold">{{ $monthlyStats['foreigner_female_count'] ?? 0 }}</span>
                                                </div>
                                            </div>
                                        </dd>
                                    </div>

                                    <!-- Race Statistics -->
                                    <div class="bg-red-100 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-900">By Race</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            <div class="grid grid-cols-2 gap-4">
                                                <div class="flex items-center gap-2">
                                                    <span>Chinese:</span>
                                                    <span class="font-semibold">{{ $monthlyStats['chinese_count'] }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span>Malay:</span>
                                                    <span class="font-semibold">{{ $monthlyStats['malay_count'] }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span>Indian:</span>
                                                    <span class="font-semibold">{{ $monthlyStats['india_count'] }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span>KDMS:</span>
                                                    <span class="font-semibold">{{ $monthlyStats['kdms_count'] }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span>Others:</span>
                                                    <span class="font-semibold">{{ $monthlyStats['others_count'] }}</span>
                                                </div>
                                            </div>
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
