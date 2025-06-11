<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('CTG Daily Records') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Action Buttons -->
                    <div class="flex justify-end mb-6">
                        <div class="flex space-x-4">
                            <a href="{{  route('annual.ctg.monthly') }}"
                               class="inline-flex items-center px-4 py-2 bg-[#87cefa] border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-[#ffd3dd] focus:bg-[#ffd3dd] active:bg-[#ffd3dd] focus:outline-none focus:ring-2 focus:ring-[#87cefa] focus:ring-offset-2 transition ease-in-out duration-150">
                                Monthly Records
                            </a>
                            <a href="{{ route('annual.ctg.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-[#87cefa] border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-[#ffd3dd] focus:bg-[#ffd3dd] active:bg-[#ffd3dd] focus:outline-none focus:ring-2 focus:ring-[#87cefa] focus:ring-offset-2 transition ease-in-out duration-150">
                                Back to Form
                            </a>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('annual.ctg.daily') }}" class="mb-6">
                        <div class="flex gap-4 items-end">
                            <div>
                                <x-input-label for="date" :value="__('Select Date')" />
                                <x-text-input id="date" name="date" type="date" class="mt-1 block w-full"
                                    :value="request('date', $date)" required />
                            </div>
                            <x-primary-button>{{ __('View') }}</x-primary-button>
                        </div>
                    </form>
                </div>

                    @if($record)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Dr. Geetha</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Dr. Joseph</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Dr. Sutha</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Dr. Ramesh</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Updated By</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                            {{ Carbon\Carbon::parse($record->date)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                            {{ $record->dr_geetha_count }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                            {{ $record->dr_joseph_count }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                            {{ $record->dr_sutha_count }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                            {{ $record->dr_ramesh_count }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">
                                            {{ $record->dr_geetha_count + $record->dr_joseph_count + $record->dr_sutha_count + $record->dr_ramesh_count }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                            {{ $record->user_name }}
                                            <div class="text-xs text-gray-500">
                                                {{ Carbon\Carbon::parse($record->updated_at)->format('d/m/Y H:i') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                            <a href="{{ route('annual.ctg.edit', $record) }}"
                                               style="display: inline-flex; align-items: center; padding: 0.25rem 0.75rem; background-color: #ff69b4; border: 1px solid transparent; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 600; color: white; text-transform: uppercase; letter-spacing: 0.05em; transition: all 0.15s ease-in-out;"
                                               onmouseover="this.style.backgroundColor='#ff1493'"
                                               onmouseout="this.style.backgroundColor='#ff69b4'">
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No records found for this date.</p>
                    @endif

            </div>
        </div>
    </div>
</x-app-layout>
