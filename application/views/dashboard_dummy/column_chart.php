<!DOCTYPE html>
<html>
<head>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Dp', 'omzet','stok','piutang'],
          <?php 
            foreach ($get_data_chart as $a) {
                echo "['$a->branch_name', $a->tot_omzet,$a->tot_harga_stok,$a->tot_piutang],";
            }
            ?>
        ]);

        var options = {
          chart: {
            title: 'Company Performance',
            subtitle: 'Omzet, Stok, and Piutang: 2021',
          },
          bars: 'horizontal', // Required for Material Bar Charts.
          bar: { groupWidth: "90%" }
        };

        var chart = new google.charts.Bar(document.getElementById('barchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>
</head>
<body>

  <div class="col-xl-12 col-md-12">
    <div class="card latest-update-card">
      <div class="card-header">
        <h5>Performance</h5>
        <div class="card-header-right">
            <ul class="list-unstyled card-option">
                <li class="first-opt"><i class="feather icon-chevron-left open-card-option"></i>
                </li>
                <li><i class="feather icon-maximize full-card"></i></li>
                <li><i class="feather icon-minus minimize-card"></i>
                </li>
                <li><i class="feather icon-refresh-cw reload-card"></i>
                </li>
                <li><i class="feather icon-trash close-card"></i></li>
                <li><i class="feather icon-chevron-left open-card-option"></i>
                </li>
            </ul>
        </div>
    </div>
  <div class="card-block">

  <div id="barchart_material" style="width: 1000px; height: 800px;"></div>
            


</body>
</html>