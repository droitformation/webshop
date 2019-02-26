<div>
    <div class="chartjs-tooltip" id="tooltip-0"></div>
    <div class="chartjs-tooltip" id="tooltip-1"></div>
    <canvas id="myChart" width="400" height="100"></canvas>
</div>
<script>
    var customTooltips = function(tooltip) {
        $(this._chart.canvas).css('cursor', 'pointer');

        var positionY = this._chart.canvas.offsetTop;
        var positionX = this._chart.canvas.offsetLeft;

        $('.chartjs-tooltip').css({
            opacity: 0,
        });

        if (!tooltip || !tooltip.opacity) {
            return;
        }

        if (tooltip.dataPoints.length > 0) {
            tooltip.dataPoints.forEach(function(dataPoint) {
                var content = [dataPoint.xLabel, dataPoint.yLabel].join(': ');
                var $tooltip = $('#tooltip-' + dataPoint.datasetIndex);

                $tooltip.html(content);
                $tooltip.css({
                    opacity: 1,
                    top: positionY + dataPoint.y + 'px',
                    left: positionX + dataPoint.x + 'px',
                });
            });
        }
    };
    var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($datapoints->keys()); ?>,
            datasets: [{
                label: <?php echo json_encode(stat_search($search)); ?>,
                data: <?php echo json_encode($datapoints->values()); ?>,
                backgroundColor: 'rgba(20, 120, 170, 0.2)',
                borderColor: 'rgba(20, 120, 170,1)',
                borderWidth: 1
            },
            {
                label: <?php echo json_encode(stat_search($search)); ?>,
                data: <?php echo json_encode($datapoints->reverse()->values()); ?>,
                backgroundColor: 'rgba(20, 160, 170, 0.2)',
                borderColor: 'rgba(20, 160, 170,1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {yAxes: [{ticks: {beginAtZero:true}}]},
            tooltips: {
                enabled: false,
                mode: 'index',
                intersect: false,
                custom: customTooltips
            }
        }
    });
</script>