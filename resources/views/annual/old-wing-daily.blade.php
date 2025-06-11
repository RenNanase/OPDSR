<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Old Wing - Daily Medical Procedures') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                  <div class="mb-6">
                     <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <form method="GET" action="{{ route('annual.old-wing.daily') }}" class="flex items-center space-x-4">
                            <x-input-label for="date" value="Select Date" />
                            <x-text-input id="date" name="date" type="date" class="mt-1"
                                :value="old('date', $date)" />
                            <x-primary-button>
                                {{ __('View') }}
                            </x-primary-button>
                            @if($procedures)
                                <a href="{{ route('annual.old-wing.edit', ['date' => $date]) }}"
                                    <x-primary-button>
                                        {{ __('Edit') }}
                                    </x-primary-button>
                                </a>
                            @endif
                        </form>
                  <!-- Action Buttons -->
                        <div class="flex space-x-4">
                            <a href="{{  route('annual.old-wing.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-[#87cefa] border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-[#ffd3dd] focus:bg-[#ffd3dd] active:bg-[#ffd3dd] focus:outline-none focus:ring-2 focus:ring-[#87cefa] focus:ring-offset-2 transition ease-in-out duration-150">
                               Back to Log
                            </a>

                        </div>
                    </div>
                    </div>

                    @if($procedures)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Procedure</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Count</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">INJECTION VACCINE</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ $procedures->injection_vaccine }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">IV MEDICATION</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ $procedures->iv_medication }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">UREA BLOOD TEST (UBT)</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ $procedures->urea_blood_test }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">VENEPUNCTURE</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ $procedures->venepuncture }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">IV CANNULATION</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ $procedures->iv_cannulation }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">SWAB C&S / NOSE / ORAL</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ $procedures->swab_cs_nose_oral }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">DRESSING</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ $procedures->dressing }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">12 LED ECG</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ $procedures->ecg_12_led }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">URINARY CATHETERIZATION</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ $procedures->urinary_catheterization }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">NG TUBE INSERTION</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ $procedures->ng_tube_insertion }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">NEBULIZATION</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ $procedures->nebulization }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 p-4 bg-[#f0e130] rounded-lg">
                            <p class="text-sm text-gray-600">
                                Last updated by: {{ $procedures->user_name }} on {{ $procedures->updated_at->format('d M Y, h:i A') }}
                            </p>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-gray-500">No records found for this date.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
