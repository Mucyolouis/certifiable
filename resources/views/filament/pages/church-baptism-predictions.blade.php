<x-filament::page>
    <div class="mb-6">
        <h2 class="mb-2 text-2xl font-bold">Church with Highest Baptism Rate</h2>
        @if($this->getChurchWithHighestBaptismRate)
            <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        {{ $this->getChurchWithHighestBaptismRate->name }}
                    </h3>
                    <p class="max-w-2xl mt-1 text-sm text-gray-500">
                        Baptism Rate: 
                        @if($this->getChurchWithHighestBaptismRate->users_count > 0)
                            {{ number_format(($this->getChurchWithHighestBaptismRate->baptized_users_count / $this->getChurchWithHighestBaptismRate->users_count) * 100, 2) }}%
                        @else
                            0%
                        @endif
                    </p>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="px-4 py-5 bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                Total Members
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $this->getChurchWithHighestBaptismRate->users_count }}
                            </dd>
                        </div>
                        <div class="px-4 py-5 bg-white sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                Baptized Members
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $this->getChurchWithHighestBaptismRate->baptized_users_count }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        @else
            <p>No data available.</p>
        @endif
    </div>

    <div class="mb-6">
        <h2 class="mb-2 text-2xl font-bold">Churches with Highest Predicted Baptism Growth</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase border-b-2 border-gray-300">
                            Church
                        </th>
                        <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase border-b-2 border-gray-300">
                            Current Rate
                        </th>
                        <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase border-b-2 border-gray-300">
                            Predicted Rate
                        </th>
                        <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase border-b-2 border-gray-300">
                            Trend
                        </th>
                        <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase border-b-2 border-gray-300">
                            Total Christians
                        </th>
                        <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase border-b-2 border-gray-300">
                            Baptized Christians
                        </th>
                        <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase border-b-2 border-gray-300">
                            Total Pastors
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($this->getTop5FuturePredictions() as $prediction)
                        <tr>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
                                {{ $prediction['name'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
                                {{ number_format($prediction['current_rate'], 2) }}%
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
                                {{ number_format($prediction['predicted_rate'], 2) }}%
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $prediction['trend'] === 'Increasing' ? 'bg-green-100 text-green-800' : 
                                       ($prediction['trend'] === 'Decreasing' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ $prediction['trend'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
                                {{ $prediction['total_christians'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
                                {{ $prediction['baptized_christians'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
                                {{ $prediction['total_pastors'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mb-6">
        <h2 class="mb-2 text-2xl font-bold">Baptism Rate Trends</h2>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="p-4 overflow-hidden bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-semibold">Average</h3>
                <p class="text-2xl font-bold text-blue-600">{{ $this->getTrendData()['average'] }}%</p>
            </div>
            <div class="p-4 overflow-hidden bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-semibold">Median</h3>
                <p class="text-2xl font-bold text-green-600">{{ $this->getTrendData()['median'] }}%</p>
            </div>
            <div class="p-4 overflow-hidden bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-semibold">Growth Rate</h3>
                <p class="text-2xl font-bold {{ $this->getTrendData()['growthRate'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $this->getTrendData()['growthRate'] }}%
                </p>
            </div>
            <div class="p-4 overflow-hidden bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-semibold">Highest Baptism Rate</h3>
                <p class="text-2xl font-bold text-red-600">{{ $this->getTrendData()['max']['value'] }}%</p>
                <p class="text-sm text-gray-600">{{ $this->getTrendData()['max']['name'] }}</p>
            </div>
            <div class="p-4 overflow-hidden bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-semibold">Lowest Baptism Rate</h3>
                <p class="text-2xl font-bold text-yellow-600">{{ $this->getTrendData()['min']['value'] }}%</p>
                <p class="text-sm text-gray-600">{{ $this->getTrendData()['min']['name'] }}</p>
            </div>
        </div>
    </div>

    <div class="mb-6">
        <h2 class="mb-2 text-2xl font-bold">Baptism Rates Across Churches</h2>
        <div id="baptism-rates-chart" style="height: 400px;"></div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        var options = {
            chart: {
                type: 'line',
                height: 400
            },
            series: [{
                name: 'Baptism Rate',
                data: @json($this->getChartData()['data'])
            }],
            xaxis: {
                categories: @json($this->getChartData()['labels']),
                labels: {
                    rotate: -45,
                    trim: true,
                    maxHeight: 120
                }
            },
            yaxis: {
                title: {
                    text: 'Baptism Rate (%)'
                },
                max: 100
            },
            title: {
                text: 'Baptism Rates Across Churches with Trend',
                align: 'center'
            },
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return val.toFixed(2) + "%";
                },
                offsetY: -10,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                }
            },
            stroke: {
                curve: 'smooth'
            },
            markers: {
                size: 6,
                hover: {
                    size: 8
                }
            },
            colors: ['#4F46E5'],
            annotations: {
                yaxis: [{
                    y: {{ $this->getTrendData()['average'] }},
                    borderColor: '#00E396',
                    label: {
                        borderColor: '#00E396',
                        style: {
                            color: '#fff',
                            background: '#00E396'
                        },
                        text: 'Average: {{ $this->getTrendData()['average'] }}%'
                    }
                }]
            },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function (y) {
                        if(typeof y !== "undefined") {
                            return y.toFixed(2) + "%";
                        }
                        return y;
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#baptism-rates-chart"), options);
        chart.render();
    </script>
    @endpush
</x-filament::page>
