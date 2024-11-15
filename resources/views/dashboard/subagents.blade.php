<x-app-layout>
    <!-- Page Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- Monthly Sales Chart and Product Sales Chart Section -->
    <div class="py-12">
        <div class="flex flex-wrap">
            <!-- Agent Monthly Sales Chart -->
            @if($usersWithSales->isNotEmpty())
            <div class="w-full sm:w-3/4 px-6">
                <div class="bg-white border border-gray-200 rounded-lg shadow p-4"> 
                    {!! $agentMonthlySales->container() !!}
                </div>
            </div>
            <!-- Agent Product Sales Chart -->
            <div class="w-full sm:w-1/4 px-6">
                <div class="bg-white border border-gray-200 rounded-lg shadow p-4">
                    {!! $agentProductSales->container() !!}
                </div>

                <!-- Sales Progress Bar -->
                <div class="bg-white border border-gray-200 rounded-lg shadow p-4 mt-4">
                    <!-- Sales Progress Bar Label and Percentage -->
                    <div class="flex mb-2 items-center justify-between">
                        <div>
                            <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-blue-600 bg-blue-200">
                                Progress
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-semibold inline-block text-blue-600">
                                {{ $progressPercentage }}%
                            </span>
                        </div>
                    </div>
                    <!-- Sales Progress Bar itself with blue and red segments -->
                    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded">
                        <div style="width: {{ $progressPercentage }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center {{ $progressPercentage >= 100 ? 'bg-green-500' : 'bg-green-500' }}"></div>
                        <div style="width: {{ 100 - $progressPercentage }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-red-500"></div>
                    </div>
                </div>

                <!-- Total Sales Section -->
                <div class="bg-white border border-gray-200 rounded-lg shadow p-4 mt-4">
                    <!-- Total sales label -->
                    <div class="text-sm font-semibold text-gray-800">Total sales reach the goal</div>
                    <!-- Total sales amount -->
                    <div class="text-2xl font-bold text-blue-600">${{ number_format($totalSales) }} USD</div>
                </div>

                <!-- Remaining Sales Section -->
                <div class="bg-white border border-gray-200 rounded-lg shadow p-4 mt-4">
                    <!-- Remaining sales label -->
                    <div class="text-sm font-semibold text-gray-800">Remaining sales reach the goal</div>
                    <!-- Remaining sales amount -->
                    <div class="text-2xl font-bold text-red-600">${{ number_format($remainingSales) }} USD</div>
                </div>

                <!-- Last Update Section -->
                <div class="bg-white border border-gray-200 rounded-lg shadow p-4 mt-4">
                    <!-- Last Update label -->
                    <div class="text-sm font-semibold text-gray-800">Last Update</div>
                    <!-- Last Update value -->
                    <div class="text-2xl font-bold text-gray-800">{{ $lastUpdate }}</div>
                </div>
            </div>
            @else
                <!-- No data found message -->
                <div class="w-full px-6 flex items-center justify-center">
                    <span class="text-red-600 text-2xl font-bold">No sales data available</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Load the necessary chart scripts -->
    @if($agentProductSales)
    <script src="{{ $agentProductSales->cdn() }}"></script>
    {{ $agentProductSales->script() }}
    @endif

    @if($agentMonthlySales)
    <script src="{{ $agentMonthlySales->cdn() }}"></script>
    {{ $agentMonthlySales->script() }}
    @endif
</x-app-layout>
