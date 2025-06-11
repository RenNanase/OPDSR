<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('CTG Monthly Records') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

             <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="mb-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <form method="GET" action="{{ route('annual.ctg.monthly') }}" class="mb-6">
                        <div class="flex gap-4 items-end">
                            <div>
                                <x-input-label for="month" :value="__('Month')" />
                                <select id="month" name="month" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="year" :value="__('Year')" />
                                <select id="year" name="year" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    @foreach(range(date('Y')-2, date('Y')+2) as $y)
                                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-6">
                                <x-primary-button>{{ __('View Records') }}</x-primary-button>
                            </div>

                            <div class="mt-6">
                                <a href="{{ route('annual.ctg.export', ['month' => $month, 'year' => $year]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500">
                                    {{ __('Export to Excel') }}
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="flex space-x-4">
                        <a href="{{ route('annual.ctg.daily') }}"
                           class="inline-flex items-center px-4 py-2 bg-[#87cefa] border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-[#ffd3dd] focus:bg-[#ffd3dd] active:bg-[#ffd3dd] focus:outline-none focus:ring-2 focus:ring-[#87cefa] focus:ring-offset-2 transition ease-in-out duration-150">
                            Daily Records
                        </a>
                    </div>

                </div>
            </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Dr. Geetha</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Dr. Joseph</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Dr. Sutha</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Dr. Ramesh</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php
                                    $daysInMonth = Carbon\Carbon::create($year, $month, 1)->daysInMonth;
                                    $totalGeetha = 0;
                                    $totalJoseph = 0;
                                    $totalSutha = 0;
                                    $totalRamesh = 0;
                                @endphp

                                @for($day = 1; $day <= $daysInMonth; $day++)
                                    @php
                                        $date = Carbon\Carbon::create($year, $month, $day);
                                        $record = $records->first(function($record) use ($date) {
                                            return Carbon\Carbon::parse($record->date)->format('Y-m-d') === $date->format('Y-m-d');
                                        });
                                        $geethaCount = $record ? $record->dr_geetha_count : 0;
                                        $josephCount = $record ? $record->dr_joseph_count : 0;
                                        $suthaCount = $record ? $record->dr_sutha_count : 0;
                                        $rameshCount = $record ? $record->dr_ramesh_count : 0;
                                        $dayTotal = $geethaCount + $josephCount + $suthaCount + $rameshCount;

                                        $totalGeetha += $geethaCount;
                                        $totalJoseph += $josephCount;
                                        $totalSutha += $suthaCount;
                                        $totalRamesh += $rameshCount;
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                            {{ $day }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center {{ !$record ? 'bg-red-100' : '' }}">
                                            {{ $geethaCount }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center {{ !$record ? 'bg-red-100' : '' }}">
                                            {{ $josephCount }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center {{ !$record ? 'bg-red-100' : '' }}">
                                            {{ $suthaCount }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center {{ !$record ? 'bg-red-100' : '' }}">
                                            {{ $rameshCount }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center {{ !$record ? 'bg-red-100' : '' }}">
                                            {{ $dayTotal }}
                                        </td>
                                    </tr>
                                @endfor
                                <tr class="bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Monthly Total</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">{{ $totalGeetha }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">{{ $totalJoseph }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">{{ $totalSutha }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">{{ $totalRamesh }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">
                                        {{ $totalGeetha + $totalJoseph + $totalSutha + $totalRamesh }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
        </div>
    </div>
</x-app-layout>
