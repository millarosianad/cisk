<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style type="text/css">
        .chartBox{
            width: 700px;
            height: 700px;
        }
    </style>

</head>
<body>

<?php 
    // var_dump($get_dashboard_monitor->result_array());
    // die;
    $bulan = array();
    $d1 = array();

    foreach ($get_dashboard_monitor->result_array() as $key ) {
        // var_dump($key['D1']);
        $bulan[] = $key['bulan'];
        $d1[] = $key['D1'];
    }

    // die;
    // print_r($d1);
    // echo json_encode($d1);
    // die;
?>

<div class="chartBox">
  <canvas id="myChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

    const d1 = <?php echo json_encode($d1); ?>;
    const bulan = <?php echo json_encode($bulan); ?>;

// setup block
const data = {
labels: bulan,
    datasets: [{
    label: 'omzet',
    data: d1,
    borderWidth: 1,
    backgroundColor: [
      'rgba(255, 99, 132, 0.2)',
      'rgba(255, 159, 64, 0.2)',
      'rgba(255, 205, 86, 0.2)',
      'rgba(75, 192, 192, 0.2)',
      'rgba(54, 162, 235, 0.2)',
      'rgba(153, 102, 255, 0.2)',
      'rgba(201, 203, 207, 0.2)'
    ],
    borderColor: [
      'rgb(255, 99, 132)',
      'rgb(255, 159, 64)',
      'rgb(255, 205, 86)',
      'rgb(75, 192, 192)',
      'rgb(54, 162, 235)',
      'rgb(153, 102, 255)',
      'rgb(201, 203, 207)'
    ],
}]};

// config block

const config = {
    type: 'bar',
    data,
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
};

// render block
const myChart = new Chart(
    document.getElementById('myChart'),
    config
);
 
</script>

    
</body>
</html>