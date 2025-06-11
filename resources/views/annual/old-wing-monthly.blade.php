<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Old Wing Monthly Medical Procedures') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Month Selection -->
                    <div class="mb-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <form action="{{ route('annual.old-wing.monthly') }}" method="GET" class="flex items-center space-x-4">
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
                                    <a href="{{ route('annual.old-wing.export', ['month' => $selectedMonth, 'year' => $selectedYear]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500">
                                        {{ __('Export to Excel') }}
                                    </a>
                                </div>
                            </form>
                            <div class="mt-6">
                                <a href="{{route('annual.old-wing.index') }}"
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
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Procedure</th>
                                    @for($day = 1; $day <= $daysInMonth; $day++)
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $day }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">INJECTION VACCINE</td>
                                    @for($day = 1; $day <= $daysInMonth; $day++)
                                        @php
                                            $dateStr = sprintf('%04d-%02d-%02d', $selectedYear, $selectedMonth, $day);
                                            $record = $daysInMonthArray[$dateStr] ?? null;
                                        @endphp
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center {{ !$record ? 'bg-red-100' : '' }}">
                                            <span class="inline-block w-full text-center">{{ $record?->injection_vaccine ?? '-' }}</span>
                                        </td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">IV MEDICATION</td>
                                    @for($day = 1; $day <= $daysInMonth; $day++)
                                        @php
                                            $dateStr = sprintf('%04d-%02d-%02d', $selectedYear, $selectedMonth, $day);
                                            $record = $daysInMonthArray[$dateStr] ?? null;
                                        @endphp
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center {{ !$record ? 'bg-red-100' : '' }}">
                                            {{ $record?->iv_medication ?? '-' }}
                                        </td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">UREA BLOOD TEST</td>
                                    @for($day = 1; $day <= $daysInMonth; $day++)
                                        @php
                                            $dateStr = sprintf('%04d-%02d-%02d', $selectedYear, $selectedMonth, $day);
                                            $record = $daysInMonthArray[$dateStr] ?? null;
                                        @endphp
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center {{ !$record ? 'bg-red-100' : '' }}">
                                            {{ $record?->urea_blood_test ?? '-' }}
                                        </td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">VENEPUNCTURE</td>
                                    @for($day = 1; $day <= $daysInMonth; $day++)
                                        @php
                                            $dateStr = sprintf('%04d-%02d-%02d', $selectedYear, $selectedMonth, $day);
                                            $record = $daysInMonthArray[$dateStr] ?? null;
                                        @endphp
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center {{ !$record ? 'bg-red-100' : '' }}">
                                            {{ $record?->venepuncture ?? '-' }}
                                        </td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">IV CANNULATION</td>
                                    @for($day = 1; $day <= $daysInMonth; $day++)
                                        @php
                                            $dateStr = sprintf('%04d-%02d-%02d', $selectedYear, $selectedMonth, $day);
                                            $record = $daysInMonthArray[$dateStr] ?? null;
                                        @endphp
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center {{ !$record ? 'bg-red-100' : '' }}">
                                            {{ $record?->iv_cannulation ?? '-' }}
                                        </td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">SWAB C&S</td>
                                    @for($day = 1; $day <= $daysInMonth; $day++)
                                        @php
                                            $dateStr = sprintf('%04d-%02d-%02d', $selectedYear, $selectedMonth, $day);
                                            $record = $daysInMonthArray[$dateStr] ?? null;
                                        @endphp
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center {{ !$record ? 'bg-red-100' : '' }}">
                                            {{ $record?->swab_cs_nose_oral ?? '-' }}
                                        </td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">DRESSING</td>
                                    @for($day = 1; $day <= $daysInMonth; $day++)
                                        @php
                                            $dateStr = sprintf('%04d-%02d-%02d', $selectedYear, $selectedMonth, $day);
                                            $record = $daysInMonthArray[$dateStr] ?? null;
                                        @endphp
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center {{ !$record ? 'bg-red-100' : '' }}">
                                            {{ $record?->dressing ?? '-' }}
                                        </td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">12 LED ECG</td>
                                    @for($day = 1; $day <= $daysInMonth; $day++)
                                        @php
                                            $dateStr = sprintf('%04d-%02d-%02d', $selectedYear, $selectedMonth, $day);
                                            $record = $daysInMonthArray[$dateStr] ?? null;
                                        @endphp
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center {{ !$record ? 'bg-red-100' : '' }}">
                                            {{ $record?->ecg_12_led ?? '-' }}
                                        </td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">URINARY CATH</td>
                                    @for($day = 1; $day <= $daysInMonth; $day++)
                                        @php
                                            $dateStr = sprintf('%04d-%02d-%02d', $selectedYear, $selectedMonth, $day);
                                            $record = $daysInMonthArray[$dateStr] ?? null;
                                        @endphp
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center {{ !$record ? 'bg-red-100' : '' }}">
                                            {{ $record?->urinary_catheterization ?? '-' }}
                                        </td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">NG TUBE</td>
                                    @for($day = 1; $day <= $daysInMonth; $day++)
                                        @php
                                            $dateStr = sprintf('%04d-%02d-%02d', $selectedYear, $selectedMonth, $day);
                                            $record = $daysInMonthArray[$dateStr] ?? null;
                                        @endphp
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center {{ !$record ? 'bg-red-100' : '' }}">
                                            {{ $record?->ng_tube_insertion ?? '-' }}
                                        </td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">NEBULIZATION</td>
                                    @for($day = 1; $day <= $daysInMonth; $day++)
                                        @php
                                            $dateStr = sprintf('%04d-%02d-%02d', $selectedYear, $selectedMonth, $day);
                                            $record = $daysInMonthArray[$dateStr] ?? null;
                                        @endphp
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center {{ !$record ? 'bg-red-100' : '' }}">
                                            {{ $record?->nebulization ?? '-' }}
                                        </td>
                                    @endfor
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Monthly Statistics -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Monthly Total Statistics</h3>
                        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Total Procedures for {{ date('F Y', mktime(0, 0, 0, $selectedMonth, 1, $selectedYear)) }}
                                </h3>
                            </div>
                            <div class="border-t border-gray-200">
                                <dl>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Total Procedures</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-semibold">
                                            @php
                                                $totalProcedures = $totals->total_injection_vaccine +
                                                                $totals->total_iv_medication +
                                                                $totals->total_urea_blood_test +
                                                                $totals->total_venepuncture +
                                                                $totals->total_iv_cannulation +
                                                                $totals->total_swab_cs_nose_oral +
                                                                $totals->total_dressing +
                                                                $totals->total_ecg_12_led +
                                                                $totals->total_urinary_catheterization +
                                                                $totals->total_ng_tube_insertion +
                                                                $totals->total_nebulization;
                                            @endphp
                                            {{ $totalProcedures }}
                                        </dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:px-6">
                                        <h4 class="text-md font-medium text-gray-900 mb-3">Breakdown by Procedure</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                            <div class="bg-gray-50 p-4 rounded-lg">
                                                <h5 class="font-medium text-gray-700 mb-2">Injection & Medication</h5>
                                                <div class="space-y-1">
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Injection Vaccine:</span>
                                                        <span class="font-medium">{{ $totals->total_injection_vaccine }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">IV Medication:</span>
                                                        <span class="font-medium">{{ $totals->total_iv_medication }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Nebulization:</span>
                                                        <span class="font-medium">{{ $totals->total_nebulization }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="bg-gray-50 p-4 rounded-lg">
                                                <h5 class="font-medium text-gray-700 mb-2">Tests & Monitoring</h5>
                                                <div class="space-y-1">
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Urea Blood Test:</span>
                                                        <span class="font-medium">{{ $totals->total_urea_blood_test }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Venepuncture:</span>
                                                        <span class="font-medium">{{ $totals->total_venepuncture }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">12 LED ECG:</span>
                                                        <span class="font-medium">{{ $totals->total_ecg_12_led }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="bg-gray-50 p-4 rounded-lg">
                                                <h5 class="font-medium text-gray-700 mb-2">Procedures & Care</h5>
                                                <div class="space-y-1">
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">IV Cannulation:</span>
                                                        <span class="font-medium">{{ $totals->total_iv_cannulation }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">SWAB C&S:</span>
                                                        <span class="font-medium">{{ $totals->total_swab_cs_nose_oral }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Dressing:</span>
                                                        <span class="font-medium">{{ $totals->total_dressing }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="bg-gray-50 p-4 rounded-lg">
                                                <h5 class="font-medium text-gray-700 mb-2">Tube Insertions</h5>
                                                <div class="space-y-1">
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Urinary Cath:</span>
                                                        <span class="font-medium">{{ $totals->total_urinary_catheterization }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">NG Tube:</span>
                                                        <span class="font-medium">{{ $totals->total_ng_tube_insertion }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
