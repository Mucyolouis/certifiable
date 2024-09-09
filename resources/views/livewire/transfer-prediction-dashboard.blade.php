<div>
    <div class="grid grid-cols-3 gap-4">
        <div class="p-4 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4">Approval Status Distribution</h2>
            <div id="approvalStatusChart" style="height: 300px;"></div>
        </div>
        <div class="p-4 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4">Role Distribution</h2>
            <div id="roleDistributionChart" style="height: 300px;"></div>
        </div>
        <div class="p-4 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4">Top Church Transfers</h2>
            <div id="churchTransfersChart" style="height: 300px;"></div>
        </div>
        <div class="p-4 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4">Feature Importance</h2>
            <div id="featureImportanceChart" style="height: 300px;"></div>
        </div>
        <div class="p-4 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4">Transfer Reasons</h2>
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

            var roleDistributionChart = new ApexCharts(document.querySelector("#roleDistributionChart"), {
                series: [{
                    data: @json(array_column($roleDistribution, 'value'))
                }],
                chart: {
                    type: 'bar',
                },
                xaxis: {
                    categories: @json(array_column($roleDistribution, 'name')),
                },
            });
            roleDistributionChart.render();

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
                    data: @json(array_column($transferReasons, 'value'))
                }],
                chart: {
                    type: 'pie',
                },
                labels: @json(array_column($transferReasons, 'name')),
            });
            transferReasonsChart.render();
        });
    </script>
</div>