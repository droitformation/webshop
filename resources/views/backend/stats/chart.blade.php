<canvas id="myChart" width="400" height="100"></canvas>
<script>
    var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($data->keys()); ?>,
            datasets: [{
                label: <?php echo json_encode(stat_search($search)); ?>,
                data: <?php echo json_encode($data->values()); ?>,
                backgroundColor: 'rgba(20, 120, 170, 0.2)',
                borderColor: 'rgba(20, 120, 170,1)',
                borderWidth: 1
            }]
        },
        options: {scales: {yAxes: [{ticks: {beginAtZero:true}}]}}
    });
</script>