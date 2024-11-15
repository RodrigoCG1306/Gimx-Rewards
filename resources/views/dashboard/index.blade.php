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
            <!-- Season Filter Form -->
            <div class="w-full mb-4 px-6">
                <!-- Formulario para seleccionar la temporada -->
                <form action="{{ route('dashboard.index') }}" method="GET">
                    <select name="season_id" id="season_id">
                        <option value="">All Seasons</option>
                        @foreach($seasons as $season)
                            <option value="{{ $season->id }}" @if($selectedSeasonId == $season->id) selected @endif>{{ $season->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit">Filter</button>
                </form>
            </div>

            <!-- Monthly Sales Chart -->
            @if ($usersWithSales->isNotEmpty())
                @if ($monthlyChart)
                    <div class="w-full sm:w-3/4 px-6">
                        <div class="bg-white border border-gray-200 rounded-lg shadow p-4">
                            {!! $monthlyChart->container() !!}
                        </div>
                    </div>
                @endif
                
                <!-- Product Sales Chart -->
                <div class="w-full sm:w-1/4 px-6">
                    <div class="bg-white border border-gray-200 rounded-lg shadow p-4">
                        {!! $productChart->container() !!}
                    </div>
                    <!-- List of Users with Sales -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow p-4 mt-4">
                        <ul class="space-y-2">
                            @php $count = 1; @endphp
                            @foreach($usersWithSales as $userData)
                                @php
                                    $user = App\Models\User::find($userData->user_id);
                                    $faltante = max(0, 80000 - $userData->total_sales);
                                    $topUser = $count <= 3 ? true : false;
                                    $count++;
                                @endphp
                                <li class="flex justify-between @if($topUser && $userData->total_sales >= 80000) bg-purple-100 @endif">
                                    <span class="text-gray-800 @if($topUser && $userData->total_sales >= 80000) text-purple-600 @endif">{{ $user->name }}</span>
                                    <span class="text-blue-600">${{ number_format($userData->total_sales) }} USD</span>
                                </li>
                                <li class="flex justify-between">
                                    <span class="text-sm @if($topUser && $userData->total_sales >= 80000) text-purple-600 @else text-red-600 font-bold @endif">Remaining: ${{ number_format($faltante) }} USD</span>
                                </li>
                            @endforeach
                        </ul>
                        {{ $usersWithSales->links() }}
                    </div>

                    <!-- Last Update Section -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow p-4 mt-4">
                        <!-- Last Update label -->
                        <div class="text-sm font-semibold text-gray-800">Last Update</div>
                        <!-- Last Update amount -->
                        <div class="text-2xl font-bold text-gray-800">{{ $lastUpdate }}</div>
                    </div>
                </div>
            @else
                <!-- No data found message -->
                <div class="w-full px-6 flex items-center justify-center">
                    <span class="text-red-600 text-2xl font-bold">No data found</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Load the necessary chart scripts -->
    @if ($productChart)
        <script src="{{ $productChart->cdn() }}"></script>
        {{ $productChart->script() }}
    @endif

    @if ($monthlyChart)
        <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.54.1"></script>
        {{ $monthlyChart->script() }}
    @endif

    <script>
        // JavaScript code to submit the form when a different season is selected
        const seasonSelect = document.getElementById('season');
        seasonSelect.addEventListener('change', function() {
            document.getElementById('seasonFilterForm').submit();
        });
    </script>
</x-app-layout>
