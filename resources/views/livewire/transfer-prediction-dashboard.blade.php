<div>
    <div class="grid grid-cols-3 gap-4">
        <div class="p-4 rounded-lg shadow">
            <h2 class="mb-4 text-lg font-semibold">Approval Status Distribution</h2>
            <div id="approvalStatusChart" style="height: 300px;"></div>
        </div>
        {{-- <div class="p-4 rounded-lg shadow">
            <h2 class="mb-4 text-lg font-semibold">Role Distribution</h2>
            <div id="roleDistributionChart" style="height: 300px;"></div>
        </div> --}}
        <div class="p-4 rounded-lg shadow">
            <h2 class="mb-4 text-lg font-semibold">Top Church Transfers</h2>
            <div id="churchTransfersChart" style="height: 300px;"></div>
        </div>
        <div class="p-4 rounded-lg shadow">
            <h2 class="mb-4 text-lg font-semibold">Feature Importance</h2>
            <div id="featureImportanceChart" style="height: 300px;"></div>
        </div>
        <div class="p-4 rounded-lg shadow">
            <h2 class="mb-4 text-lg font-semibold">Transfer Reasons</h2>
            <div id="transferReasonsChart" style="height: 300px;"></div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.52.0/apexcharts.min.js" integrity="sha512-piY4QAXPoG2xLdUZZbcc5klXzMxckrQKY9A2o6nKDRt9inolvvLbvGPC+z9IZ29b28UJlD05B7CjxxPaxh4bjQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        window.addEventListener('load', function (event) {
            var approvalStatusChart = new ApexCharts(document.querySelector("#approvalStatusChart"), {
                series: [{
                    data: @json(array_column($approvalStatus, 'value'))
                }],
                chart: {
                    type: 'bar',
                },
                xaxis: {
                    categories: @json(array_column($approvalStatus, 'name')),
                },
            });
            approvalStatusChart.render();

            // var roleDistributionChart = new ApexCharts(document.querySelector("#roleDistributionChart"), {
            //     series: [{
            //         data: @json(array_column($roleDistribution, 'value'))
            //     }],
            //     chart: {
            //         type: 'bar',
            //     },
            //     xaxis: {
            //         categories: @json(array_column($roleDistribution, 'name')),
            //     },
            // });
            // roleDistributionChart.render();

            var churchTransfersChart = new ApexCharts(document.querySelector("#churchTransfersChart"), {
                series: @json(array_column($churchTransfers, 'value')),
                labels: @json(array_column($churchTransfers, 'name')),
                chart: {
                    type: 'pie',
                },
            });
            churchTransfersChart.render();

            var featureImportanceChart = new ApexCharts(document.querySelector("#featureImportanceChart"), {
                series: [{
                    data: @json(array_column($featureImportance, 'value'))
                }],
                chart: {
                    type: 'bar',
                },
                xaxis: {
                    categories: @json(array_column($featureImportance, 'name')),
                },
            });
            featureImportanceChart.render();

            var transferReasonsChart = new ApexCharts(document.querySelector("#transferReasonsChart"), {
    series: [{
        name: 'Total Requests',
        data: @json($transferReasons->pluck('total')->toArray())
    }, {
        name: 'Approved Requests',
        data: @json($transferReasons->pluck('approved')->toArray())
    }],
    chart: {
        type: 'bar',
        height: 350,
        stacked: false,
    },
    plotOptions: {
        bar: {
            horizontal: true,
            dataLabels: {
                position: 'top',
            },
        }
    },
    dataLabels: {
        enabled: true,
        offsetX: -6,
        style: {
            fontSize: '12px',
            colors: ['#fff']
        }
    },
    stroke: {
        show: true,
        width: 1,
        colors: ['#fff']
    },
    xaxis: {
        categories: @json($transferReasons->pluck('name')->toArray()),
        title: {
            text: 'Number of Requests'
        }
    },
    yaxis: {
        title: {
            text: 'Transfer Reasons'
        },
    },
    tooltip: {
        shared: false,
        y: {
            formatter: function (val) {
                return val + " requests"
            }
        }
    },
    colors: ['#008FFB', '#00E396'],
    legend: {
        position: 'top',
        horizontalAlign: 'left',
        offsetX: 40
    }
});
transferReasonsChart.render();
        });
    </script>
</div>