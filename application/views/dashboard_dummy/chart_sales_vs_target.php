<!DOCTYPE html>
<html>
<head>

    <script type="text/javascript" src="<?php echo base_url() ?>assets_new/js/charts.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      <?php 
      $thn = date('Y');
      $sub = $jmlh_branch['sub'];
      $subx = explode(" ",$sub);
      $jmlh = count($subx);
      ?>

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Dp', 'Sales','Target',],
          <?php 
            foreach ($get_chart_sales_vs_target as $a) {
                echo "['$a->branch_name', $a->sales,$a->target],";
            }
            ?>
        ]);

        var options = {
          chart: {
            title: '<?php echo $thn ;?>',
            subtitle: 'Sales VS Target',
          },
          bars: 'horizontal', // Required for Material Bar Charts.
          bar: { groupWidth: "85%" },
          hAxis: {format: 'decimal'},
        };

        var chart = new google.charts.Bar(document.getElementById('barchart_material_4'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>
</head>
<body>
<div class="row">
  <div class="col-xl-12 col-md-12">
    <div class="card latest-update-card">
      <div class="card-header">
        <h5>Sales Vs Target</h5>
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
  <div id="barchart_material_4" style="width: 100%; height: <?php if ($sub == '') {
        $a = 1000*$jmlh;
        echo $a;
      }else{
        $a = 70*$jmlh;
        echo $a;
      } ;?>px;"></div>
  </div>
  </div>

</body>
</html>