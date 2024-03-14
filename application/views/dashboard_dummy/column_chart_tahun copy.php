<!DOCTYPE html>
<html>
<head>

  <script type="text/javascript" src="<?php echo base_url() ?>assets_new/js/charts.js"></script>

  <script>
      // google.charts.load('current', {'packages':['bar']});
      // google.charts.setOnLoadCallback(drawChart);

      // function drawChart() {
      //   var data = google.visualization.arrayToDataTable([
      //     ['Dp', '2021','2020'],
      //     <?php 
      //   foreach ($get_data_chart as $a) {
      //       echo "['$a->nama_comp', $a->tot1,$a->tot2],";
      //   }
      //   ?>
      //   ]);

      //   var options = {
      //     chart: {
      //       title: 'Omzet',
      //       subtitle: '2021 - 2020',
      //     },
      //     bars: 'horizontal', // Required for Material Bar Charts.
      //     hAxis: {format: 'short'},
      //     height: 2500,
      //     colors: ['#0000FF', '#FF0000', '#7570b3'],
      //     bar: { groupWidth: "90%" }
      //   };

      //   var chart = new google.charts.Bar(document.getElementById('chart_div'));

      //   chart.draw(data, google.charts.Bar.convertOptions(options));

      //   var btns = document.getElementById('btn-group');

      //   btns.onclick = function (e) {

      //     if (e.target.tagName === 'BUTTON') {
      //       options.hAxis.format = e.target.id === 'none' ? '' : e.target.id;
      //       chart.draw(data, google.charts.Bar.convertOptions(options));
      //     }
      //   }
      // }
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Dp', '2021','2020'],
          <?php 
            $jumlah_branch = 2;
            foreach ($get_data_chart_tahun as $a) {
                echo "['$a->branch_name', $a->tot1,$a->tot2],";
            }
            ?>
            ]);

        var options = {
          chart: {
            title: 'Company Performance',
            subtitle: 'Omzet: 2021-2020',
          },
          bars: 'horizontal', // Required for Material Bar Charts.
          hAxis: {format: 'decimal'},
          height: 200 * <?php echo $jumlah_branch; ?>,
          width: 1000,
          colors: ['#1b9e77', '#d95f02', '#7570b3'],
          bar: { groupWidth: "30%" }
        };

        var chart = new google.charts.Bar(document.getElementById('chart_div'));

        chart.draw(data, google.charts.Bar.convertOptions(options));

        var btns = document.getElementById('btn-group');

        btns.onclick = function (e) {

          if (e.target.tagName === 'BUTTON') {
            options.hAxis.format = e.target.id === 'none' ? '' : e.target.id;
            chart.draw(data, google.charts.Bar.convertOptions(options));
          }
        }
      }
  </script>
</head>
<body>

  <div class="col-xl-12 col-md-12">
    <div class="card latest-update-card">
      <div class="card-header">
        <h5>Omzet 2021 - 2020</h5>
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

  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
       <div id="chart_div"></div>
  <br/>
    <div id="btn-group">
      <button class="button button-blue" id="none">No Format</button>
      <button class="button button-blue" id="scientific">Scientific Notation</button>
      <button class="button button-blue" id="decimal">Decimal</button>
      <button class="button button-blue" id="short">Short</button>
    </div>
  </div>
  </div>
  </div>

</body>
</html>