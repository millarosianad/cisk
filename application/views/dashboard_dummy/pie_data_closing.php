<html>
  <head>
    <script type="text/javascript" src="<?php echo base_url() ?>assets_new/js/charts.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Informasi Closing DP'],
        <?php 
        foreach ($get_closing as $a) {
            echo "['$a->status', $a->jumlah_dp],";
        }
        ?>
        ]);

        var options = {
          title: '',
          pieHole: 0.3,
        //   width: 700,
        //   width_units: '%',
          chartArea: {'width': '60%', 'height': '100%'}, 
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="donutchart" style="width: 80%; height: 200%;"></div>
  </body>
</html>
