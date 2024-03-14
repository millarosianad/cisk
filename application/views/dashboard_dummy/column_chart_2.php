<!DOCTYPE html>
<html>
<head>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    google.charts.load('current', {'packages':['corechart', 'bar']});
      google.charts.setOnLoadCallback(drawStuff);

      function drawStuff() {

        var button = document.getElementById('change-chart');
        var chartDiv = document.getElementById('chart_div');

        var data = google.visualization.arrayToDataTable([
          ['Month', '2021', '2020'],
          ['Jan', 8000, 8000],
          ['Feb', 24000, 70000],
          ['Mar', 30000, 1400.3],
          ['Apr', 50000, 1000.9],
          ['Mei', 100000, 13000.1],
          ['Jun', 60000, 1300.1],
          ['Jul', 8000, 23787.3],
          ['Ags', 24000, 4000.5],
          ['Sep', 30000, 14000.3],
          ['Okt', 50000, 1000.9],
          ['Nov', 60000, 13000.1],
          ['Des', 60000, 13000.1],
        ]);

        var materialOptions = {
          width: 1200,
          height: 400,

          chart: {
            title: '',
            subtitle: ''
          },
         
          axes: {
            y: {
              distance: {label: 'parsecs'}, // Left y-axis.
              brightness: {side: 'right', label: ''} // Right y-axis.
            }
          }
        };

        var classicOptions = {
          width: 900,
          series: {
            0: {targetAxisIndex: 0},
            0: {targetAxisIndex: 0}
          },
          title: 'Omzet',
          vAxes: {
            // Adds titles to each axis.
            0: {title: 'parsecs'},
            0: {title: 'apparent magnitude'}
          }
        };

        function drawMaterialChart() {
          var materialChart = new google.charts.Bar(chartDiv);
          materialChart.draw(data, google.charts.Bar.convertOptions(materialOptions));
          button.innerText = 'Change to Classic';
          button.onclick = drawClassicChart;
        }

        function drawClassicChart() {
          var classicChart = new google.visualization.ColumnChart(chartDiv);
          classicChart.draw(data, classicOptions);
          button.innerText = 'Change to Material';
          button.onclick = drawMaterialChart;
        }

        drawMaterialChart();
    };
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
          
          <div id="chart_div" style="width: 100%; height: 200%;"></div>


          </div>
      </div>
  </div>

                    
            

</body>
</html>